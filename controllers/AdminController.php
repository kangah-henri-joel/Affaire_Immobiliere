<?php
// controllers/AdminController.php

require_once __DIR__ . '/Controller.php';

class AdminController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        $role = $_SESSION['user_role'] ?? 'client';
        if (!in_array($role, ['agent', 'admin', 'super_admin'])) {
            $this->redirect('/login');
        }
    }

    public function index() {
        $model  = new AnnonceModel();
        $db     = $model->getDb();
        $role   = $_SESSION['user_role'] ?? 'agent';
        $userId = $_SESSION['user_id'];
        
        if ($role === 'super_admin') {
            $stats = [
                'total_annonces'     => $db->query("SELECT COUNT(*) FROM annonces")->fetchColumn(),
                'total_views'        => $db->query("SELECT SUM(views_count) FROM annonces")->fetchColumn() ?? 0,
                'total_leads'        => $db->query("SELECT COUNT(*) FROM leads")->fetchColumn(),
                'total_publications' => $db->query("SELECT COUNT(*) FROM publications")->fetchColumn()
            ];
        } else {
            $stmt1 = $db->prepare("SELECT COUNT(*) FROM annonces WHERE user_id = ?");
            $stmt1->execute([$userId]);
            $stmt2 = $db->prepare("SELECT SUM(views_count) FROM annonces WHERE user_id = ?");
            $stmt2->execute([$userId]);
            $stmt3 = $db->prepare("SELECT COUNT(*) FROM leads l JOIN annonces a ON l.annonce_id = a.id WHERE a.user_id = ?");
            $stmt3->execute([$userId]);
            $stmt4 = $db->prepare("SELECT COUNT(*) FROM publications p JOIN annonces a ON p.annonce_id = a.id WHERE a.user_id = ?");
            $stmt4->execute([$userId]);
            $stats = [
                'total_annonces'     => $stmt1->fetchColumn(),
                'total_views'        => $stmt2->fetchColumn() ?? 0,
                'total_leads'        => $stmt3->fetchColumn(),
                'total_publications' => $stmt4->fetchColumn()
            ];
        }

        // Comptage messages non lus
        $clientMsgModel   = new ClientMessageModel();
        $internalMsgModel = new InternalMessageModel();
        $unreadClient   = $clientMsgModel->countAdminUnread($userId);
        $unreadInternal = $internalMsgModel->countUnread($userId);

        $this->render('admin/dashboard', [
            'title'          => 'Tableau de Bord - Admin',
            'stats'          => $stats,
            'unreadClient'   => $unreadClient,
            'unreadInternal' => $unreadInternal,
        ]);
    }

    public function annonces() {
        $model   = new AnnonceModel();
        $filters = ['include_drafts' => true];
        $role    = $_SESSION['user_role'] ?? 'agent';
        if ($role !== 'super_admin') {
            $filters['user_id'] = $_SESSION['user_id'];
        }
        $annonces = $model->getAll($filters);

        // Récupérer les médias de chaque annonce
        $db = $model->getDb();
        $mediaByAnnonce = [];
        foreach ($annonces as $ann) {
            $stmt = $db->prepare("SELECT * FROM images WHERE annonce_id = ? ORDER BY is_main DESC");
            $stmt->execute([$ann['id']]);
            $mediaByAnnonce[$ann['id']] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        $this->render('admin/annonces', [
            'title'          => 'Gestion des Annonces',
            'annonces'       => $annonces,
            'mediaByAnnonce' => $mediaByAnnonce,
        ]);
    }

    public function saveAnnonce() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/annonces');
        }

        $model  = new AnnonceModel();
        $data   = $_POST;
        $data['status'] = $_POST['status'] ?? 'disponible';

        // Handle media upload (multiple files)
        $uploadedMedia   = [];
        $primaryMediaIdx = isset($_POST['primary_media_index']) ? (int)$_POST['primary_media_index'] : 0;

        if (isset($_FILES['media']) && is_array($_FILES['media']['name'])) {
            $fileCount = count($_FILES['media']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['media']['error'][$i] === UPLOAD_ERR_OK) {
                    $fileName      = $_FILES['media']['name'][$i];
                    $fileType      = $_FILES['media']['type'][$i];
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $newFileName   = md5(time() . $fileName . $i) . '.' . $fileExtension;
                    $uploadFileDir = __DIR__ . '/../assets/uploads/annonces/';
                    if (!is_dir($uploadFileDir)) mkdir($uploadFileDir, 0777, true);
                    if (move_uploaded_file($_FILES['media']['tmp_name'][$i], $uploadFileDir . $newFileName)) {
                        $uploadedMedia[] = [
                            'path' => '/assets/uploads/annonces/' . $newFileName,
                            'type' => (strpos($fileType, 'video') !== false) ? 'video' : 'image'
                        ];
                    }
                }
            }
        }

        if (empty($uploadedMedia)) {
            $uploadedMedia[] = ['path' => '/assets/images/placeholder.jpg', 'type' => 'image'];
        }

        $userModel   = new UserModel();
        $currentUser = $userModel->getById($_SESSION['user_id']);
        $data['user_id']          = $_SESSION['user_id'];
        $data['published_by']     = $_SESSION['full_name'] ?? $_SESSION['username'];
        $data['whatsapp_contact'] = $currentUser['phone_whatsapp'] ?? null;

        $annonceId = $model->create($data);

        $db   = $model->getDb();
        $stmt = $db->prepare("INSERT INTO images (annonce_id, file_path, is_main, media_type) VALUES (?, ?, ?, ?)");
        foreach ($uploadedMedia as $index => $media) {
            $isMain = ($index === $primaryMediaIdx) ? 1 : 0;
            $stmt->execute([$annonceId, $media['path'], $isMain, $media['type']]);
        }
        // S'assurer qu'au moins une image est principale
        $stmt2 = $db->prepare("SELECT COUNT(*) FROM images WHERE annonce_id = ? AND is_main = 1");
        $stmt2->execute([$annonceId]);
        if ($stmt2->fetchColumn() == 0) {
            $db->prepare("UPDATE images SET is_main = 1 WHERE annonce_id = ? LIMIT 1")->execute([$annonceId]);
        }

        $actionLog = new ActionLogModel();
        $actionLog->log($_SESSION['user_id'], "Création d'annonce", "L'annonce '{$data['title']}' a été créée avec le statut: " . ($data['status'] === 'brouillon' ? 'Brouillon' : 'Publiée'));

        if ($data['status'] !== 'brouillon') {
            $clients = $userModel->getAllActiveClientsEmails();
            if (!empty($clients)) {
                require_once __DIR__ . '/../config/MailHelper.php';
                $subject = "Nouvelle annonce disponible : " . $data['title'];
                $message = "<h2>Bonjour,</h2><p>Une nouvelle annonce qui pourrait vous intéresser vient d'être publiée sur ImmoAffaire : <strong>{$data['title']}</strong>.</p><p>Connectez-vous pour la découvrir !</p><p>L'équipe ImmoAffaire</p>";
                foreach ($clients as $client) {
                    if (!empty($client['email'])) {
                        MailHelper::send($client['email'], $subject, $message);
                    }
                }
            }
        }

        $this->redirect('/admin/annonces');
    }

    public function leads() {
        $model  = new LeadModel();
        $role   = $_SESSION['user_role'] ?? 'agent';
        $userId = $_SESSION['user_id'];

        if ($role === 'super_admin') {
            $leads = $model->getAll();
        } else {
            $db   = $model->getDb();
            $sql  = "SELECT l.*, a.title as annonce_title FROM leads l JOIN annonces a ON l.annonce_id = a.id WHERE a.user_id = ? ORDER BY l.created_at DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute([$userId]);
            $leads = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        $this->render('admin/leads', ['title' => 'Gestion des Leads', 'leads' => $leads]);
    }

    public function replyLead() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id    = $_POST['lead_id'];
            $reply = $_POST['reply_text'];
            $model = new LeadModel();
            $lead  = $model->getById($id);
            if ($lead) {
                $model->saveReply($id, $reply);
                $actionLog = new ActionLogModel();
                $actionLog->log($_SESSION['user_id'], "Réponse au lead", "Réponse envoyée à {$lead['client_name']}");
                require_once __DIR__ . '/../config/MailHelper.php';
                $subject = "Réponse à votre demande sur ImmoAffaire";
                $message = "<h2>Bonjour {$lead['client_name']},</h2><p>Notre réponse :</p><div style='padding:20px;background:#f8fafc;border-radius:10px;border-left:5px solid #0f172a;'>" . nl2br($reply) . "</div><p>Cordialement,<br>L'équipe ImmoAffaire</p>";
                MailHelper::send($lead['client_email'], $subject, $message);
            }
            $this->redirect('/admin/leads?success=replied');
        }
    }

    public function publications() {
        $model  = new PublicationModel();
        $role   = $_SESSION['user_role'] ?? 'agent';
        $userId = $_SESSION['user_id'];

        if ($role === 'super_admin') {
            $publications = $model->getAll();
            $annonceModel = new AnnonceModel();
            $annonces     = $annonceModel->getAll(['include_drafts' => true]);
        } else {
            $db   = $model->getDb();
            $sql  = "SELECT p.*, a.title as annonce_title, a.price as annonce_price,
                            a.location_name, a.type as annonce_type, a.whatsapp_contact,
                            c.name as category_name,
                            i.file_path as image_path
                     FROM publications p
                     JOIN annonces a ON p.annonce_id = a.id
                     JOIN categories c ON a.category_id = c.id
                     LEFT JOIN images i ON i.annonce_id = a.id AND i.is_main = 1
                     WHERE a.user_id = ?
                     ORDER BY p.scheduled_at DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute([$userId]);
            $publications = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $annonceModel = new AnnonceModel();
            $annonces     = $annonceModel->getAll(['user_id' => $userId, 'include_drafts' => true]);
        }

        $this->render('admin/publications', [
            'title'        => 'Programmation des Publications',
            'publications' => $publications,
            'annonces'     => $annonces
        ]);
    }

    public function savePublication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/publications');
        }
        $model = new PublicationModel();
        $data  = $_POST;
        $id    = $data['id'] ?? null;

        if (empty($data['generated_text'])) {
            $annonceModel       = new AnnonceModel();
            $annonce            = $annonceModel->getById($data['annonce_id']);
            $data['generated_text'] = $annonce['description'] ?? '';
        }

        if ($id) {
            // ── Édition : une seule plateforme (champ hidden `platform`)
            $model->update($id, $data);
            $actionLog = new ActionLogModel();
            $actionLog->log($_SESSION['user_id'], "Modification de publication", "La publication ID {$id} a été modifiée.");
        } else {
            // ── Création : on lit le tableau de plateformes cochées
            $platforms = $data['platforms'] ?? [];
            // Fallback au champ platform si le tableau est vide (compatibilité)
            if (empty($platforms) && !empty($data['platform'])) {
                $platforms = [$data['platform']];
            }
            foreach ($platforms as $platform) {
                $row = $data;
                $row['platform'] = $platform;
                $model->create($row);
            }
            $platformStr = implode(', ', array_map('ucfirst', $platforms));
            $actionLog = new ActionLogModel();
            $actionLog->log($_SESSION['user_id'], "Programmation de publication", "Nouvelle publication sur {$platformStr} programmée.");
        }
        $this->redirect('/admin/publications');
    }

    public function getPublicationJson() {
        if (isset($_GET['id'])) {
            $model = new PublicationModel();
            $pub   = $model->getById($_GET['id']);
            header('Content-Type: application/json');
            echo json_encode($pub);
            exit;
        }
    }

    public function publishAnnonce() {
        if (isset($_GET['id'])) {
            $model = new AnnonceModel();
            $id = (int)$_GET['id'];
            $annonce = $model->getById($id);
            $role = $_SESSION['user_role'] ?? 'agent';
            
            // Check ownership/authorization
            if ($annonce && ($role === 'super_admin' || (int)$annonce['user_id'] === (int)$_SESSION['user_id'])) {
                if ($annonce['status'] === 'brouillon') {
                    $db = $model->getDb();
                    $db->prepare("UPDATE annonces SET status = 'disponible' WHERE id = ?")->execute([$id]);
                    
                    $actionLog = new ActionLogModel();
                    $actionLog->log($_SESSION['user_id'], "Publication d'annonce", "L'annonce '{$annonce['title']}' est passée de brouillon à publiée.");

                    // Notifier les clients actifs
                    $userModel = new UserModel();
                    $clients = $userModel->getAllActiveClientsEmails();
                    if (!empty($clients)) {
                        require_once __DIR__ . '/../config/MailHelper.php';
                        $subject = "Nouvelle annonce disponible : " . $annonce['title'];
                        $message = "<h2>Bonjour,</h2><p>Une nouvelle annonce qui pourrait vous intéresser vient d'être publiée sur ImmoAffaire : <strong>{$annonce['title']}</strong>.</p><p>Connectez-vous pour la découvrir !</p><p>L'équipe ImmoAffaire</p>";
                        foreach ($clients as $client) {
                            if (!empty($client['email'])) {
                                MailHelper::send($client['email'], $subject, $message);
                            }
                        }
                    }
                }
            }
        }
        $this->redirect('/admin/annonces?success=published');
    }

    public function deleteAnnonce() {
        if (isset($_GET['id'])) {
            $model = new AnnonceModel();
            $id    = (int)$_GET['id'];
            $annonce = $model->getById($id);
            if (!$annonce) {
                $db   = $model->getDb();
                $stmt = $db->prepare("SELECT * FROM annonces WHERE id = ?");
                $stmt->execute([$id]);
                $annonce = $stmt->fetch(\PDO::FETCH_ASSOC);
            }
            if (!$annonce) { $this->redirect('/admin/annonces'); return; }
            $role = $_SESSION['user_role'] ?? 'agent';
            if ($role !== 'super_admin' && (int)$annonce['user_id'] !== (int)$_SESSION['user_id']) {
                $this->redirect('/admin/annonces?error=unauthorized'); return;
            }
            $model->softDelete($id);
            $actionLog = new ActionLogModel();
            $actionLog->log($_SESSION['user_id'], "Mise à la corbeille", "L'annonce '{$annonce['title']}' a été mise à la corbeille.");
        }
        $this->redirect('/admin/annonces');
    }

    public function corbeille() {
        $model    = new AnnonceModel();
        $role     = $_SESSION['user_role'] ?? 'agent';
        $userId   = ($role === 'super_admin') ? null : (int)$_SESSION['user_id'];
        $annonces = $model->getTrashed($userId);
        $this->render('admin/corbeille', ['title' => 'Corbeille', 'annonces' => $annonces]);
    }

    public function restoreAnnonce() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
            $model = new AnnonceModel();
            $id    = (int)$_POST['id'];
            $db    = $model->getDb();
            $stmt  = $db->prepare("SELECT * FROM annonces WHERE id = ?");
            $stmt->execute([$id]);
            $annonce = $stmt->fetch(\PDO::FETCH_ASSOC);
            $role = $_SESSION['user_role'] ?? 'agent';
            if (!$annonce || ($role !== 'super_admin' && (int)$annonce['user_id'] !== (int)$_SESSION['user_id'])) {
                $this->redirect('/admin/annonces/corbeille'); return;
            }
            $model->restore($id);
            $actionLog = new ActionLogModel();
            $actionLog->log($_SESSION['user_id'], "Restauration d'annonce", "L'annonce '{$annonce['title']}' a été restaurée.");
        }
        $this->redirect('/admin/annonces/corbeille?success=restored');
    }

    public function hardDeleteAnnonce() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
            $model = new AnnonceModel();
            $id    = (int)$_POST['id'];
            $db    = $model->getDb();
            $stmt  = $db->prepare("SELECT * FROM annonces WHERE id = ?");
            $stmt->execute([$id]);
            $annonce = $stmt->fetch(\PDO::FETCH_ASSOC);
            $role = $_SESSION['user_role'] ?? 'agent';
            if (!$annonce || ($role !== 'super_admin' && (int)$annonce['user_id'] !== (int)$_SESSION['user_id'])) {
                $this->redirect('/admin/annonces/corbeille'); return;
            }
            $db->prepare("DELETE FROM images WHERE annonce_id = ?")->execute([$id]);
            $db->prepare("DELETE FROM leads  WHERE annonce_id = ?")->execute([$id]);
            $model->hardDelete($id);
            $actionLog = new ActionLogModel();
            $actionLog->log($_SESSION['user_id'], "Suppression définitive", "L'annonce '{$annonce['title']}' a été supprimée définitivement.");
        }
        $this->redirect('/admin/annonces/corbeille?success=deleted');
    }

    public function emptyTrash() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model  = new AnnonceModel();
            $db     = $model->getDb();
            $role   = $_SESSION['user_role'] ?? 'agent';
            $userId = (int)$_SESSION['user_id'];
            $trashed = ($role === 'super_admin') ? $model->getTrashed(null) : $model->getTrashed($userId);
            foreach ($trashed as $a) {
                $db->prepare("DELETE FROM images WHERE annonce_id = ?")->execute([$a['id']]);
                $db->prepare("DELETE FROM leads  WHERE annonce_id = ?")->execute([$a['id']]);
                $model->hardDelete($a['id']);
            }
            $actionLog = new ActionLogModel();
            $actionLog->log($_SESSION['user_id'], "Corbeille vidée", count($trashed) . " annonce(s) supprimée(s) définitivement.");
        }
        $this->redirect('/admin/annonces/corbeille?success=emptied');
    }

    public function deletePublication() {
        if (isset($_GET['id'])) {
            $model = new PublicationModel();
            $model->delete($_GET['id']);
            $actionLog = new ActionLogModel();
            $actionLog->log($_SESSION['user_id'], "Suppression de publication", "La publication ID {$_GET['id']} a été supprimée.");
        }
        $this->redirect('/admin/publications');
    }

    public function profile() {
        $model = new UserModel();
        $user  = $model->getById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $ext     = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','gif','webp'];
                if (in_array($ext, $allowed)) {
                    $uploadDir = __DIR__ . '/../assets/uploads/avatars/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    $filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $filename)) {
                        $data['avatar'] = '/assets/uploads/avatars/' . $filename;
                    }
                }
            }
            $model->update($_SESSION['user_id'], $data);
            $_SESSION['username'] = $_POST['username'];
            $actionLog = new ActionLogModel();
            $actionLog->log($_SESSION['user_id'], "Mise à jour du profil", "Informations du profil mises à jour.");
            $this->redirect('/admin/profil?success=1');
        }

        $this->render('admin/profile', ['title' => 'Mon Profil', 'user' => $user]);
    }

    public function consultants() {
        $model  = new UserModel();
        $agents = $model->getAllAgents();
        $this->render('admin/users', ['title' => 'Gestion des Consultants', 'agents' => $agents]);
    }

    public function saveConsultant() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new UserModel();
            $model->create($_POST);
            $actionLog = new ActionLogModel();
            $actionLog->log($_SESSION['user_id'], "Ajout de consultant", "Consultant '{$_POST['username']}' ajouté.");
            $this->redirect('/admin/consultants');
        }
    }

    public function deleteConsultant() {
        if (isset($_GET['id'])) {
            $model = new UserModel();
            $model->delete($_GET['id']);
            $actionLog = new ActionLogModel();
            $actionLog->log($_SESSION['user_id'], "Suppression de consultant", "Le consultant ID {$_GET['id']} a été supprimé.");
            $this->redirect('/admin/consultants');
        }
    }

    public function settings() {
        $model    = new SettingsModel();
        $settings = $model->getAll();
        $this->render('admin/settings', ['title' => 'Paramètres de l\'entreprise', 'settings' => $settings]);
    }

    public function saveSettings() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new SettingsModel();
            $model->updateMultiple($_POST);
            $actionLog = new ActionLogModel();
            $actionLog->log($_SESSION['user_id'], "Mise à jour des paramètres", "Les paramètres de l'entreprise ont été modifiés.");
            $this->redirect('/admin/settings?success=1');
        }
    }

    public function journal() {
        $logModel = new ActionLogModel();
        $logs     = $logModel->getByUser($_SESSION['user_id']);
        $this->render('admin/journal', ['title' => 'Mon Journal d\'Activité', 'logs' => $logs]);
    }

    // ─── BUREAU ──────────────────────────────────────────────────────────────
    public function bureau() {
        $model  = new BureauModel();
        $userId = $_SESSION['user_id'];
        $bureau = $model->getByUserId($userId);
        $this->render('admin/bureau', ['title' => 'Mon Bureau', 'bureau' => $bureau]);
    }

    public function saveBureau() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/bureau');
        }
        $model  = new BureauModel();
        $userId = $_SESSION['user_id'];
        $data   = $_POST;

        // Upload logo
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $ext     = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp','svg'];
            if (in_array($ext, $allowed)) {
                $uploadDir = __DIR__ . '/../assets/uploads/bureaux/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $filename = 'bureau_' . $userId . '_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $filename)) {
                    $data['logo'] = '/assets/uploads/bureaux/' . $filename;
                }
            }
        }

        $model->upsert($userId, $data);
        $actionLog = new ActionLogModel();
        $actionLog->log($userId, "Mise à jour du bureau", "Les informations du bureau ont été mises à jour.");
        $this->redirect('/admin/bureau?success=1');
    }

    // ─── MESSAGERIE INTERNE (admin ↔ super-admin) ────────────────────────────
    public function messages() {
        // Trouver le super-admin
        $userModel   = new UserModel();
        $db          = $userModel->getDb();
        $superAdmin  = $db->query("SELECT * FROM users WHERE role = 'super_admin' LIMIT 1")->fetch(\PDO::FETCH_ASSOC);

        if (!$superAdmin) {
            $this->render('admin/messages', ['title' => 'Messages', 'messages' => [], 'superAdmin' => null]);
            return;
        }

        $msgModel = new InternalMessageModel();
        $messages = $msgModel->getConversation($_SESSION['user_id'], $superAdmin['id']);
        $msgModel->markAsRead($_SESSION['user_id'], $superAdmin['id']);

        $this->render('admin/messages', [
            'title'      => 'Messages avec le Super Admin',
            'messages'   => $messages,
            'superAdmin' => $superAdmin,
        ]);
    }

    public function sendMessage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/messages');
        }
        $content     = trim($_POST['content'] ?? '');
        $receiverId  = (int)($_POST['receiver_id'] ?? 0);
        if (empty($content) || !$receiverId) {
            $this->redirect('/admin/messages');
        }
        // Vérifier que le récepteur est bien un super_admin
        $userModel = new UserModel();
        $receiver  = $userModel->getById($receiverId);
        if (!$receiver || $receiver['role'] !== 'super_admin') {
            $this->redirect('/admin/messages?error=invalid');
            return;
        }
        $msgModel = new InternalMessageModel();
        $msgModel->send($_SESSION['user_id'], $receiverId, $content);
        $this->redirect('/admin/messages');
    }

    // ─── MESSAGERIE CLIENT (admin ↔ client) ──────────────────────────────────
    public function clientMessages() {
        $model    = new ClientMessageModel();
        $userId   = $_SESSION['user_id'];
        $convList = $model->getAdminConversations($userId);
        $this->render('admin/client_messages', [
            'title'      => 'Messages Clients',
            'convList'   => $convList,
        ]);
    }

    public function clientConversation() {
        $convId = $_GET['id'] ?? '';
        if (empty($convId)) {
            $this->redirect('/admin/client-messages');
        }
        $model = new ClientMessageModel();
        $conv  = $model->getConversation($convId);
        if (!$conv || (int)$conv['admin_id'] !== (int)$_SESSION['user_id']) {
            $this->redirect('/admin/client-messages?error=not_found');
            return;
        }
        $messages = $model->getMessages($convId);
        $model->markClientMessagesRead($convId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = trim($_POST['content'] ?? '');
            if (!empty($content)) {
                $adminName = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Admin';
                $model->send($convId, 'admin', $adminName, $content);
                $this->redirect('/admin/client-messages/conversation?id=' . $convId);
                return;
            }
        }

        $this->render('admin/client_conversation', [
            'title'    => 'Conversation – ' . ($conv['client_name'] ?? 'Client'),
            'conv'     => $conv,
            'messages' => $messages,
        ]);
    }

    // ─── STATISTIQUES VISITEURS ───────────────────────────────────────────────
    public function visitors() {
        $model = new VisitorModel();
        $stats    = $model->getStats();
        $byDay    = $model->getByDay(30);
        $topPages = $model->getTopPages(10);
        $recent   = $model->getUniqueVisitors(50);
        $this->render('admin/visitors', [
            'title'    => 'Statistiques des Visiteurs',
            'stats'    => $stats,
            'byDay'    => $byDay,
            'topPages' => $topPages,
            'recent'   => $recent,
        ]);
    }

    // ─── AFFICHE PUBLICITAIRE ─────────────────────────────────────────────────
    public function affiche() {
        $annonceId = (int)($_GET['annonce_id'] ?? 0);
        if (!$annonceId) {
            $this->redirect('/admin/annonces');
        }
        $annonceModel = new AnnonceModel();
        $annonce      = $annonceModel->getById($annonceId);
        $media        = $annonceModel->getMedia($annonceId);

        if (!$annonce) {
            $this->redirect('/admin/annonces');
        }
        // Vérifier les droits
        $role = $_SESSION['user_role'] ?? 'agent';
        if ($role !== 'super_admin' && (int)$annonce['user_id'] !== (int)$_SESSION['user_id']) {
            $this->redirect('/admin/annonces?error=unauthorized');
            return;
        }

        $afficheModel = new AfficheModel();
        $affiches     = $afficheModel->getByAnnonce($annonceId);

        $this->render('admin/affiche', [
            'title'    => 'Affiche Publicitaire – ' . $annonce['title'],
            'annonce'  => $annonce,
            'media'    => $media,
            'affiches' => $affiches,
        ]);
    }

    public function saveAfficheRecord() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/annonces');
        }
        $annonceId = (int)($_POST['annonce_id'] ?? 0);
        $modelName = $_POST['model_name'] ?? 'classique';
        if (!$annonceId) {
            $this->redirect('/admin/annonces');
        }
        $afficheModel = new AfficheModel();
        $afficheModel->save($annonceId, $_SESSION['user_id'], $modelName);
        $actionLog = new ActionLogModel();
        $actionLog->log($_SESSION['user_id'], "Affiche générée", "Affiche '{$modelName}' générée pour l'annonce ID {$annonceId}.");
        $this->redirect('/admin/affiche?annonce_id=' . $annonceId . '&success=1');
    }
}
