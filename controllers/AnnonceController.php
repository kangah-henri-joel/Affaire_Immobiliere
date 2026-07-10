<?php
// controllers/AnnonceController.php

require_once __DIR__ . '/Controller.php';

class AnnonceController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new AnnonceModel();
        // Initialiser le token client persistant (cookie 30 jours)
        $this->initClientToken();
    }

    /** Initialise un token unique persistant pour le visiteur (cookie 30j) */
    private function initClientToken() {
        if (empty($_SESSION['client_token'])) {
            // Vérifier si un cookie existe
            if (!empty($_COOKIE['client_token'])) {
                $_SESSION['client_token'] = $_COOKIE['client_token'];
            } else {
                $_SESSION['client_token'] = bin2hex(random_bytes(16));
            }
        }
        // Rafraîchir le cookie (30 jours)
        setcookie('client_token', $_SESSION['client_token'], time() + 30 * 24 * 3600, '/');
    }

    public function index() {
        $filters = [
            'category' => $_GET['category'] ?? '',
            'query'    => $_GET['query'] ?? ''
        ];
        $annonces = $this->model->getAll($filters);
        $this->render('annonces/index', ['annonces' => $annonces, 'title' => 'Toutes les Annonces']);
    }

    public function map() {
        $annonces = $this->model->getAll();
        $this->render('annonces/map', ['annonces' => $annonces, 'title' => 'Carte des Biens']);
    }

    public function view($id) {
        $annonce = $this->model->getById($id);
        if (!$annonce) {
            die("Annonce non trouvée");
        }
        
        // Sécurité pour les brouillons
        if ($annonce['status'] === 'brouillon') {
            $isAdmin     = isset($_SESSION['user_id']);
            $isOwner     = $isAdmin && ((int)$annonce['user_id'] === (int)$_SESSION['user_id']);
            $isSuperAdmin = $isAdmin && (($_SESSION['user_role'] ?? '') === 'super_admin');
            if (!$isOwner && !$isSuperAdmin) {
                die("Cette annonce est en cours de rédaction (brouillon) et n'est pas encore publique.");
            }
        }

        // Incrémenter les vues
        $db = $this->model->getDb();
        $db->prepare("UPDATE annonces SET views_count = views_count + 1 WHERE id = ?")->execute([$id]);

        $media = $this->model->getMedia($id);
        
        $commentModel = new CommentModel();
        $comments     = $commentModel->getByAnnonceId($id);

        // Récupérer le nom connu de l'auteur (si déjà commenté)
        $knownAuthorName = $commentModel->getAuthorName($_SESSION['client_token'] ?? '');

        // Récupérer la conversation client (si elle existe) avec l'admin de l'annonce
        $clientConvId = null;
        $clientConvMessages = [];
        if (!empty($_SESSION['client_token']) && !empty($annonce['user_id'])) {
            $clientMsgModel = new ClientMessageModel();
            $convId = hash('sha256', $annonce['user_id'] . '_' . $_SESSION['client_token']);
            $conv = $clientMsgModel->getConversation($convId);
            if ($conv) {
                $clientConvId       = $convId;
                $clientConvMessages = $clientMsgModel->getMessages($convId);
                $clientMsgModel->markAdminMessagesRead($convId);
            }
        }

        $this->render('annonces/view', [
            'annonce'            => $annonce, 
            'media'              => $media,
            'comments'           => $comments,
            'knownAuthorName'    => $knownAuthorName,
            'clientConvId'       => $clientConvId,
            'clientConvMessages' => $clientConvMessages,
            'title'              => $annonce['title']
        ]);
    }

    public function saveComment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }

        $annonceId  = (int)($_POST['annonce_id'] ?? 0);
        $parentId   = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
        $content    = trim($_POST['content'] ?? '');

        if (!$annonceId || empty($content)) {
            echo "<script>alert('Veuillez remplir le message.'); window.history.back();</script>";
            return;
        }

        // Récupérer le nom de l'auteur : priorité au nom déjà connu (session)
        $commentModel    = new CommentModel();
        $knownAuthorName = $commentModel->getAuthorName($_SESSION['client_token'] ?? '');
        $authorName      = $knownAuthorName ?: trim($_POST['author_name'] ?? '');

        if (empty($authorName)) {
            echo "<script>alert('Veuillez entrer votre nom ou pseudo.'); window.history.back();</script>";
            return;
        }

        $commentModel->create($annonceId, $authorName, $content, $_SESSION['client_token'], $parentId);
        $this->redirect('/annonce/' . $annonceId . '#comments-section');
    }

    public function deleteComment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }

        $commentId = (int)($_POST['comment_id'] ?? 0);
        $annonceId = (int)($_POST['annonce_id'] ?? 0);

        if (!$commentId || !$annonceId) {
            $this->redirect('/');
        }

        $commentModel = new CommentModel();
        $comment      = $commentModel->getById($commentId);

        if (!$comment) {
            echo "<script>alert('Commentaire non trouvé.'); window.history.back();</script>";
            return;
        }

        // SEULEMENT admin ou super_admin peuvent supprimer
        $role    = $_SESSION['user_role'] ?? '';
        $isAdmin = !empty($_SESSION['user_id']) && in_array($role, ['admin', 'super_admin']);

        if ($isAdmin) {
            $commentModel->delete($commentId);
            $this->redirect('/annonce/' . $annonceId . '#comments-section');
        } else {
            echo "<script>alert('Seuls les administrateurs peuvent supprimer les commentaires.'); window.history.back();</script>";
        }
    }

    public function contact() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }

        $data = $_POST;
        $data['annonce_id'] = (!empty($data['annonce_id']) && $data['annonce_id'] != 0) ? $data['annonce_id'] : null;

        $leadModel = new LeadModel();
        $leadModel->create($data);

        require_once __DIR__ . '/../config/MailHelper.php';
        $adminEmail = 'admin@immoaffaire.ci';
        $subject    = ($data['annonce_id']) ? "Nouveau Lead (Annonce)" : "Nouveau Contact Général";
        $subject   .= " - " . $data['client_name'];
        
        $message = "<p>Vous avez reçu une nouvelle demande.</p><ul>
            <li><strong>Nom :</strong> {$data['client_name']}</li>
            <li><strong>Email :</strong> {$data['client_email']}</li>
            <li><strong>Tel :</strong> {$data['client_phone']}</li>
            <li><strong>Message :</strong> {$data['message']}</li>
            </ul>";
        
        MailHelper::send($adminEmail, $subject, $message);

        $redirectUrl = ($data['annonce_id']) ? BASE_URL . "/annonce/" . $data['annonce_id'] : BASE_URL . "/contact";
        echo "<script>alert('Votre demande a été envoyée avec succès !'); window.location.href='" . $redirectUrl . "';</script>";
    }

    /** Envoyer un message client à l'admin d'une annonce */
    public function sendClientMessage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }

        $annonceId  = (int)($_POST['annonce_id'] ?? 0);
        $adminId    = (int)($_POST['admin_id'] ?? 0);
        $clientName = trim($_POST['client_name'] ?? '');
        $clientEmail = trim($_POST['client_email'] ?? '');
        $content    = trim($_POST['content'] ?? '');

        if (!$annonceId || !$adminId || empty($content) || empty($clientName)) {
            echo "<script>alert('Veuillez remplir tous les champs.'); window.history.back();</script>";
            return;
        }

        $clientToken = $_SESSION['client_token'] ?? bin2hex(random_bytes(16));
        $_SESSION['client_token'] = $clientToken;
        setcookie('client_token', $clientToken, time() + 30 * 24 * 3600, '/');

        $model  = new ClientMessageModel();
        $convId = $model->getOrCreateConversation($adminId, $clientToken, $clientName, $clientEmail, $annonceId);
        $model->send($convId, 'client', $clientName, $content);

        // Stocker le convId en session pour accès rapide
        if (!isset($_SESSION['my_conversations'])) {
            $_SESSION['my_conversations'] = [];
        }
        if (!in_array($convId, $_SESSION['my_conversations'])) {
            $_SESSION['my_conversations'][] = $convId;
        }

        $this->redirect('/annonce/' . $annonceId . '?msg=sent#client-chat');
    }

    /** Récupérer la conversation client (lien par email) */
    public function findConversation() {
        $email = trim($_POST['email'] ?? $_GET['email'] ?? '');
        if (empty($email)) {
            $this->redirect('/');
        }
        $model = new ClientMessageModel();
        $convs = $model->findByEmail($email);
        $this->render('annonces/conversations', [
            'title'         => 'Mes Conversations',
            'conversations' => $convs,
            'email'         => $email,
        ]);
    }

    /** Définir un média comme image/vidéo principale */
    public function setMainMedia() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/annonces');
        }
        $annonceId = (int)($_POST['annonce_id'] ?? 0);
        $mediaId   = (int)($_POST['media_id'] ?? 0);
        if (!$annonceId || !$mediaId) {
            $this->redirect('/admin/annonces');
        }
        // Vérifier les droits
        $role = $_SESSION['user_role'] ?? '';
        $annonce = $this->model->getById($annonceId);
        if (!$annonce || ($role !== 'super_admin' && (int)$annonce['user_id'] !== (int)$_SESSION['user_id'])) {
            $this->redirect('/admin/annonces?error=unauthorized');
            return;
        }
        $db = $this->model->getDb();
        $db->prepare("UPDATE images SET is_main = 0 WHERE annonce_id = ?")->execute([$annonceId]);
        $db->prepare("UPDATE images SET is_main = 1 WHERE id = ? AND annonce_id = ?")->execute([$mediaId, $annonceId]);
        $this->redirect('/admin/annonces?success=main_set');
    }
}
