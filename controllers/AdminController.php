<?php
// controllers/AdminController.php

require_once __DIR__ . '/Controller.php';

class AdminController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    public function index() {
        $model = new AnnonceModel();
        $db = $model->getDb();
        $stats = [
            'total_annonces' => $db->query("SELECT COUNT(*) FROM annonces")->fetchColumn(),
            'total_views' => $db->query("SELECT SUM(views_count) FROM annonces")->fetchColumn() ?? 0,
            'total_leads' => $db->query("SELECT COUNT(*) FROM leads")->fetchColumn(),
            'total_publications' => $db->query("SELECT COUNT(*) FROM publications")->fetchColumn()
        ];
        $this->render('admin/dashboard', [
            'title' => 'Tableau de Bord - Admin',
            'stats' => $stats
        ]);
    }

    public function annonces() {
        $model = new AnnonceModel();
        $annonces = $model->getAll();
        $this->render('admin/annonces', [
            'title' => 'Gestion des Annonces',
            'annonces' => $annonces
        ]);
    }

    public function saveAnnonce() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/annonces');
        }

        $model = new AnnonceModel();
        $data = $_POST;
        
        // Handle media upload (Image or Video)
        $imagePath = '/assets/images/placeholder.jpg';
        $mediaType = 'image';

        if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['media']['tmp_name'];
            $fileName = $_FILES['media']['name'];
            $fileType = $_FILES['media']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = __DIR__ . '/../assets/uploads/annonces/';
            
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }
            
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $imagePath = '/assets/uploads/annonces/' . $newFileName;
                // Determine if it's a video
                if (strpos($fileType, 'video') !== false) {
                    $mediaType = 'video';
                }
            }
        }

        $annonceId = $model->create($data);
        
        // Save media to images table
        $db = $model->getDb();
        $stmt = $db->prepare("INSERT INTO images (annonce_id, file_path, is_main, media_type) VALUES (?, ?, ?, ?)");
        $stmt->execute([$annonceId, $imagePath, 1, $mediaType]);

        $this->redirect('/admin/annonces');
    }

    public function leads() {
        $model = new LeadModel();
        $leads = $model->getAll();
        $this->render('admin/leads', [
            'title' => 'Gestion des Leads',
            'leads' => $leads
        ]);
    }

    public function replyLead() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['lead_id'];
            $reply = $_POST['reply_text'];
            
            $model = new LeadModel();
            $lead = $model->getById($id);
            
            if ($lead) {
                $model->saveReply($id, $reply);
                
                // Send email to client
                require_once __DIR__ . '/../config/MailHelper.php';
                $subject = "Réponse à votre demande sur ImmoAffaire";
                $message = "
                    <h2>Bonjour {$lead['client_name']},</h2>
                    <p>Nous avons bien reçu votre message concernant votre demande sur ImmoAffaire.</p>
                    <p><strong>Notre réponse :</strong></p>
                    <div style='padding: 20px; background: #f8fafc; border-radius: 10px; border-left: 5px solid #0f172a;'>
                        " . nl2br($reply) . "
                    </div>
                    <p>Nous restons à votre entière disposition pour tout complément d'information.</p>
                    <p>Cordialement,<br>L'équipe ImmoAffaire</p>
                ";
                
                MailHelper::send($lead['client_email'], $subject, $message);
            }
            
            $this->redirect('/admin/leads?success=replied');
        }
    }

    public function publications() {
        $model = new PublicationModel();
        $publications = $model->getAll();
        
        $annonceModel = new AnnonceModel();
        $annonces = $annonceModel->getAll();

        $this->render('admin/publications', [
            'title' => 'Programmation des Publications',
            'publications' => $publications,
            'annonces' => $annonces
        ]);
    }

    public function savePublication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/publications');
        }

        $model = new PublicationModel();
        $data = $_POST;
        $id = $data['id'] ?? null;
        
        // Get generated text from AI if not provided or just use the current description
        if (empty($data['generated_text'])) {
            $annonceModel = new AnnonceModel();
            $annonce = $annonceModel->getById($data['annonce_id']);
            $data['generated_text'] = $annonce['description'];
        }

        if ($id) {
            $model->update($id, $data);
        } else {
            $model->create($data);
        }
        
        $this->redirect('/admin/publications');
    }

    public function getPublicationJson() {
        if (isset($_GET['id'])) {
            $model = new PublicationModel();
            $pub = $model->getById($_GET['id']);
            header('Content-Type: application/json');
            echo json_encode($pub);
            exit;
        }
    }

    public function deletePublication() {
        if (isset($_GET['id'])) {
            $model = new PublicationModel();
            $model->delete($_GET['id']);
        }
        $this->redirect('/admin/publications');
    }

    public function profile() {
        $model = new UserModel();
        $user = $model->getById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model->update($_SESSION['user_id'], $_POST);
            $_SESSION['username'] = $_POST['username'];
            $this->redirect('/admin/profil?success=1');
        }

        $this->render('admin/profile', ['title' => 'Mon Profil', 'user' => $user]);
    }

    public function consultants() {
        $model = new UserModel();
        $agents = $model->getAllAgents();
        $this->render('admin/users', ['title' => 'Gestion des Consultants', 'agents' => $agents]);
    }

    public function saveConsultant() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new UserModel();
            $model->create($_POST);
            $this->redirect('/admin/consultants');
        }
    }

    public function deleteConsultant() {
        if (isset($_GET['id'])) {
            $model = new UserModel();
            $model->delete($_GET['id']);
            $this->redirect('/admin/consultants');
        }
    }

    public function settings() {
        $model = new SettingsModel();
        $settings = $model->getAll();
        $this->render('admin/settings', ['title' => 'Paramètres de l\'entreprise', 'settings' => $settings]);
    }

    public function saveSettings() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new SettingsModel();
            $model->updateMultiple($_POST);
            $this->redirect('/admin/settings?success=1');
        }
    }
}
