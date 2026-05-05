<?php
// controllers/HomeController.php

require_once __DIR__ . '/Controller.php';

class HomeController extends Controller {
    public function index() {
        $annonceModel = new AnnonceModel();
        $latestAnnonces = $annonceModel->getAll(['limit' => 6]);
        $this->render('home', [
            'title' => 'Immo Affaire - Bienvenue',
            'annonces' => $latestAnnonces
        ]);
    }

    public function contact() {
        $this->render('contact', ['title' => 'Contactez-nous - Immo Affaire']);
    }
}
