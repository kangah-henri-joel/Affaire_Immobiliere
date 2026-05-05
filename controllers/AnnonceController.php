<?php
// controllers/AnnonceController.php

require_once __DIR__ . '/Controller.php';

class AnnonceController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new AnnonceModel();
    }

    public function index() {
        $filters = [
            'category' => $_GET['category'] ?? '',
            'query' => $_GET['query'] ?? ''
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
        $media = $this->model->getMedia($id);
        $this->render('annonces/view', [
            'annonce' => $annonce, 
            'media' => $media,
            'title' => $annonce['title']
        ]);
    }

    public function contact() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }

        $data = $_POST;
        // Convert 0 or empty to null for the database
        $data['annonce_id'] = (!empty($data['annonce_id']) && $data['annonce_id'] != 0) ? $data['annonce_id'] : null;

        $leadModel = new LeadModel();
        $leadModel->create($data);

        // Notify admin
        $adminEmail = 'admin@immoaffaire.ci';
        $subject = ($data['annonce_id']) ? "Nouveau Lead (Annonce)" : "Nouveau Contact Général";
        $subject .= " - " . $data['client_name'];
        
        $message = "
            <p>Vous avez reçu une nouvelle demande.</p>
            <ul>
                <li><strong>Nom :</strong> {$data['client_name']}</li>
                <li><strong>Email :</strong> {$data['client_email']}</li>
                <li><strong>Tel :</strong> {$data['client_phone']}</li>
                <li><strong>Type :</strong> " . ($data['annonce_id'] ? "Intérêt pour un bien" : "Contact Général") . "</li>
                <li><strong>Message :</strong> {$data['message']}</li>
            </ul>
        ";
        
        require_once __DIR__ . '/../config/MailHelper.php';
        MailHelper::send($adminEmail, $subject, $message);

        // Redirect with success message
        $redirectUrl = ($data['annonce_id']) ? BASE_URL . "/annonce/" . $data['annonce_id'] : BASE_URL . "/contact";
        echo "<script>alert('Votre demande a été envoyée avec succès !'); window.location.href='" . $redirectUrl . "';</script>";
    }
}
