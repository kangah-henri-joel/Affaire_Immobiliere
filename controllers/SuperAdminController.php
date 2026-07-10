<?php
// controllers/SuperAdminController.php

require_once __DIR__ . '/Controller.php';

class SuperAdminController extends Controller {

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        if (($_SESSION['user_role'] ?? '') !== 'super_admin') {
            $this->redirect('/admin');
        }
    }

    // ─── Dashboard ───────────────────────────────────────────────────────────
    public function index() {
        $userModel    = new UserModel();
        $annonceModel = new AnnonceModel();
        $db = $annonceModel->getDb();

        $stats = [
            'total_super_admins'  => $userModel->countByRole('super_admin'),
            'total_admins'        => $userModel->countByRole('admin'),
            'total_agents'        => $userModel->countByRole('agent'),
            'total_annonces'      => $db->query("SELECT COUNT(*) FROM annonces")->fetchColumn(),
            'total_leads'         => $db->query("SELECT COUNT(*) FROM leads")->fetchColumn(),
            'total_publications'  => $db->query("SELECT COUNT(*) FROM publications")->fetchColumn(),
            'total_views'         => $db->query("SELECT COALESCE(SUM(views_count),0) FROM annonces")->fetchColumn(),
            'leads_new'           => $db->query("SELECT COUNT(*) FROM leads WHERE status='new'")->fetchColumn(),
            'leads_contacted'     => $db->query("SELECT COUNT(*) FROM leads WHERE status='contacted'")->fetchColumn(),
            'leads_closed'        => $db->query("SELECT COUNT(*) FROM leads WHERE status='closed'")->fetchColumn(),
            'annonces_disponible' => $db->query("SELECT COUNT(*) FROM annonces WHERE status='disponible'")->fetchColumn(),
            'annonces_vendu'      => $db->query("SELECT COUNT(*) FROM annonces WHERE status='vendu'")->fetchColumn(),
        ];

        $recentLeads    = $db->query("SELECT l.*, a.title as annonce_title FROM leads l LEFT JOIN annonces a ON l.annonce_id = a.id ORDER BY l.created_at DESC LIMIT 5")->fetchAll(\PDO::FETCH_ASSOC);
        $recentAnnonces = $db->query("SELECT a.*, c.name as category_name FROM annonces a LEFT JOIN categories c ON a.category_id = c.id ORDER BY a.created_at DESC LIMIT 5")->fetchAll(\PDO::FETCH_ASSOC);
        $allUsers       = $userModel->getAllUsers();

        // Messages non lus
        $msgModel    = new InternalMessageModel();
        $unreadCount = $msgModel->countUnread($_SESSION['user_id']);

        $this->render('super_admin/dashboard', [
            'title'          => 'Super Admin – Tableau de Bord',
            'stats'          => $stats,
            'recentLeads'    => $recentLeads,
            'recentAnnonces' => $recentAnnonces,
            'allUsers'       => $allUsers,
            'unreadCount'    => $unreadCount,
        ]);
    }

    // ─── Gestion des Admins ───────────────────────────────────────────────────
    public function admins() {
        $model  = new UserModel();
        $admins = $model->getAllAdmins();
        $this->render('super_admin/admins', ['title' => 'Gestion des Administrateurs', 'admins' => $admins]);
    }

    public function saveAdmin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/super-admin/admins');
        }
        $model = new UserModel();
        $id    = $_POST['id'] ?? null;
        if ($id) {
            $model->update($id, $_POST);
            if (!empty($_POST['role'])) $model->updateRole($id, $_POST['role']);
        } else {
            $data = $_POST;
            $data['role'] = 'admin';
            $model->create($data);
        }
        $this->redirect('/super-admin/admins?success=1');
    }

    public function deleteAdmin() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            if ($id === (int)$_SESSION['user_id']) {
                $this->redirect('/super-admin/admins?error=self_delete');
            }
            $model  = new UserModel();
            $target = $model->getById($id);
            if ($target && $target['role'] !== 'super_admin') {
                $model->delete($id);
            }
        }
        $this->redirect('/super-admin/admins?success=deleted');
    }

    public function changeRole() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id   = (int)$_POST['user_id'];
            $role = $_POST['role'];
            if ($id !== (int)$_SESSION['user_id']) {
                $model = new UserModel();
                $model->updateRole($id, $role);
            }
        }
        $this->redirect('/super-admin/users?success=role_updated');
    }

    // ─── Gestion de tous les utilisateurs ────────────────────────────────────
    public function users() {
        $model = new UserModel();
        $users = $model->getAllUsers();
        $this->render('super_admin/users', ['title' => 'Gestion des Utilisateurs', 'users' => $users]);
    }

    public function saveUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/super-admin/users');
        }
        $model = new UserModel();
        $id    = $_POST['id'] ?? null;
        if ($id) {
            $model->update($id, $_POST);
            if (!empty($_POST['role'])) $model->updateRole($id, $_POST['role']);
        } else {
            $model->create($_POST);
        }
        $this->redirect('/super-admin/users?success=1');
    }

    public function deleteUser() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            if ($id !== (int)$_SESSION['user_id']) {
                $model  = new UserModel();
                $target = $model->getById($id);
                if ($target && $target['role'] !== 'super_admin') {
                    $model->delete($id);
                }
            }
        }
        $this->redirect('/super-admin/users?success=deleted');
    }

    // ─── Gestion des Leads ────────────────────────────────────────────────────
    public function leads() {
        $model        = new LeadModel();
        $leads        = $model->getAll();
        $annonceModel = new AnnonceModel();
        $db           = $annonceModel->getDb();
        $stats = [
            'new'       => $db->query("SELECT COUNT(*) FROM leads WHERE status='new'")->fetchColumn(),
            'contacted' => $db->query("SELECT COUNT(*) FROM leads WHERE status='contacted'")->fetchColumn(),
            'closed'    => $db->query("SELECT COUNT(*) FROM leads WHERE status='closed'")->fetchColumn(),
        ];
        $this->render('super_admin/leads', ['title' => 'Gestion des Clients / Leads', 'leads' => $leads, 'stats' => $stats]);
    }

    public function updateLeadStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id     = (int)$_POST['lead_id'];
            $status = $_POST['status'];
            $model  = new LeadModel();
            if (in_array($status, ['new','contacted','closed'])) {
                $model->getDb()->prepare("UPDATE leads SET status = ? WHERE id = ?")->execute([$status, $id]);
            }
        }
        $this->redirect('/super-admin/leads?success=updated');
    }

    public function deleteLead() {
        if (isset($_GET['id'])) {
            $model = new LeadModel();
            $model->getDb()->prepare("DELETE FROM leads WHERE id = ?")->execute([(int)$_GET['id']]);
        }
        $this->redirect('/super-admin/leads?success=deleted');
    }

    // ─── Annonces ────────────────────────────────────────────────────────────
    public function annonces() {
        $model      = new AnnonceModel();
        $annonces   = $model->getAll();
        $db         = $model->getDb();
        $categories = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll(\PDO::FETCH_ASSOC);
        $this->render('super_admin/annonces', ['title' => 'Gestion Globale des Annonces', 'annonces' => $annonces, 'categories' => $categories]);
    }

    public function deleteAnnonce() {
        if (isset($_GET['id'])) {
            $id    = (int)$_GET['id'];
            $model = new AnnonceModel();
            $db    = $model->getDb();
            // Suppression des médias, leads, et publication liés
            $db->prepare("DELETE FROM images       WHERE annonce_id = ?")->execute([$id]);
            $db->prepare("DELETE FROM leads        WHERE annonce_id = ?")->execute([$id]);
            $db->prepare("DELETE FROM publications WHERE annonce_id = ?")->execute([$id]);
            $model->hardDelete($id);
            $actionLog = new ActionLogModel();
            $actionLog->log($_SESSION['user_id'], "Suppression d'annonce (super admin)", "L'annonce ID {$id} a été supprimée définitivement.");
        }
        $this->redirect('/super-admin/annonces?success=deleted');
    }

    public function deletePublication() {
        if (isset($_GET['id'])) {
            $model = new PublicationModel();
            $model->delete((int)$_GET['id']);
            $actionLog = new ActionLogModel();
            $actionLog->log($_SESSION['user_id'], "Suppression de publication (super admin)", "Publication ID {$_GET['id']} supprimée.");
        }
        $this->redirect('/super-admin/annonces?success=pub_deleted');
    }

    // ─── Paramètres ──────────────────────────────────────────────────────────
    public function settings() {
        $model    = new SettingsModel();
        $settings = $model->getAll();
        $this->render('super_admin/settings', ['title' => 'Paramètres Globaux', 'settings' => $settings]);
    }

    public function saveSettings() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new SettingsModel();
            $model->updateMultiple($_POST);
        }
        $this->redirect('/super-admin/settings?success=1');
    }

    // ─── Journal d'activité ───────────────────────────────────────────────────
    public function logs() {
        $logModel       = new ActionLogModel();
        $auditLogs      = $logModel->getAll();
        $annonceModel   = new AnnonceModel();
        $db             = $annonceModel->getDb();
        $recentLeads    = $db->query("SELECT l.*, a.title as annonce_title FROM leads l LEFT JOIN annonces a ON l.annonce_id = a.id ORDER BY l.created_at DESC LIMIT 20")->fetchAll(\PDO::FETCH_ASSOC);
        $recentAnnonces = $db->query("SELECT a.*, c.name as cat FROM annonces a LEFT JOIN categories c ON a.category_id=c.id ORDER BY a.created_at DESC LIMIT 20")->fetchAll(\PDO::FETCH_ASSOC);
        $recentPubs     = $db->query("SELECT p.*, a.title as annonce_title FROM publications p LEFT JOIN annonces a ON p.annonce_id=a.id ORDER BY p.scheduled_at DESC LIMIT 20")->fetchAll(\PDO::FETCH_ASSOC);
        $this->render('super_admin/logs', [
            'title'          => 'Journal d\'Activité',
            'auditLogs'      => $auditLogs,
            'recentLeads'    => $recentLeads,
            'recentAnnonces' => $recentAnnonces,
            'recentPubs'     => $recentPubs,
        ]);
    }

    // ─── Messagerie avec les Admins ───────────────────────────────────────────
    public function adminMessages() {
        $msgModel = new InternalMessageModel();
        $threads  = $msgModel->getAdminThreads($_SESSION['user_id']);
        $this->render('super_admin/admin_messages', [
            'title'   => 'Messages des Admins',
            'threads' => $threads,
        ]);
    }

    public function adminConversation() {
        $adminId = (int)($_GET['admin_id'] ?? 0);
        if (!$adminId) $this->redirect('/super-admin/messages');

        $userModel = new UserModel();
        $admin     = $userModel->getById($adminId);
        if (!$admin) $this->redirect('/super-admin/messages');

        $msgModel = new InternalMessageModel();
        $messages = $msgModel->getConversation($_SESSION['user_id'], $adminId);
        $msgModel->markAsRead($_SESSION['user_id'], $adminId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = trim($_POST['content'] ?? '');
            if (!empty($content)) {
                $msgModel->send($_SESSION['user_id'], $adminId, $content);
                $this->redirect('/super-admin/messages/conversation?admin_id=' . $adminId);
                return;
            }
        }

        $this->render('super_admin/admin_conversation', [
            'title'    => 'Conversation avec ' . ($admin['full_name'] ?? $admin['username']),
            'admin'    => $admin,
            'messages' => $messages,
        ]);
    }

    // ─── Statistiques visiteurs ───────────────────────────────────────────────
    public function visitors() {
        $model    = new VisitorModel();
        $stats    = $model->getStats();
        $byDay    = $model->getByDay(30);
        $topPages = $model->getTopPages(10);
        $recent   = $model->getUniqueVisitors(100);
        $this->render('super_admin/visitors', [
            'title'    => 'Statistiques des Visiteurs',
            'stats'    => $stats,
            'byDay'    => $byDay,
            'topPages' => $topPages,
            'recent'   => $recent,
        ]);
    }
}
