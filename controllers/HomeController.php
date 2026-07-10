<?php
// controllers/HomeController.php

require_once __DIR__ . '/Controller.php';

class HomeController extends Controller {

    public function index() {
        $model   = new AnnonceModel();
        $annonces = $model->getAll(['limit' => 12]);
        $this->render('home', ['title' => 'ImmoAffaire – L\'immobilier d\'exception en Côte d\'Ivoire', 'annonces' => $annonces]);
    }

    public function contact() {
        $this->render('contact', ['title' => 'Contactez-Nous']);
    }

    /** Page publique listant tous les bureaux */
    public function bureaux() {
        $model   = new BureauModel();
        $bureaux = $model->getAll();
        $this->render('bureaux', ['title' => 'Nos Bureaux', 'bureaux' => $bureaux]);
    }

    /** Formulaire de contact pour un bureau spécifique */
    public function bureauContact() {
        $bureauId = (int)($_GET['id'] ?? 0);
        $model    = new BureauModel();
        $bureau   = $model->getById($bureauId);

        if (!$bureau) {
            $this->redirect('/bureaux');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name    = trim($_POST['name'] ?? '');
            $message = trim($_POST['message'] ?? '');
            $phone   = trim($_POST['phone'] ?? '');
            $email   = trim($_POST['email'] ?? '');

            if (!$name || !$message) {
                $error = 'Veuillez remplir votre nom et votre message.';
            } else {
                // Créer un lead générique associé au bureau
                $db = $model->getDb();
                $db->prepare("INSERT INTO leads (client_name, client_phone, client_email, message, status) VALUES (?, ?, ?, ?, 'new')")
                   ->execute([$name, $phone, $email, "[Bureau: {$bureau['nom']}]\n" . $message]);

                // Notification email si bureau a un email
                if (!empty($bureau['email'])) {
                    require_once __DIR__ . '/../config/MailHelper.php';
                    $subject = "Nouveau message pour le bureau : " . $bureau['nom'];
                    $html    = "<h2>Nouveau message reçu</h2><ul>
                        <li><strong>Nom :</strong> {$name}</li>
                        <li><strong>Téléphone :</strong> {$phone}</li>
                        <li><strong>Email :</strong> {$email}</li>
                        <li><strong>Message :</strong><br>" . nl2br($message) . "</li></ul>";
                    MailHelper::send($bureau['email'], $subject, $html);
                }

                $success = true;
            }
        }

        $this->render('bureau_contact', [
            'title'   => 'Contacter ' . ($bureau['nom'] ?? 'le bureau'),
            'bureau'  => $bureau,
            'error'   => $error ?? null,
            'success' => $success ?? false,
        ]);
    }
}
