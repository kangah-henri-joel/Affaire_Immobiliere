<?php
// Fetch company settings for the header
require_once __DIR__ . '/../models/SettingsModel.php';
$settingsModel = new SettingsModel();
$siteSettings = $settingsModel->getAll();
$companyName = $siteSettings['company_name'] ?? 'ImmoAffaire';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $companyName; ?> - Votre partenaire immobilier de confiance en Côte d'Ivoire.">
    <title><?php echo $title ?? $companyName; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="<?php echo BASE_URL; ?>/" class="logo">
                    <span class="logo-text"><?php 
                        // Split name for styling if it matches ImmoSomething
                        if(strpos($companyName, 'Immo') === 0) {
                            echo 'Immo<span class="highlight">' . substr($companyName, 4) . '</span>';
                        } else {
                            echo $companyName;
                        }
                    ?></span>
                </a>
                
                <ul class="nav-links" id="nav-links">
                    <li><a href="<?php echo BASE_URL; ?>/">Accueil</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/annonces">Annonces</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/annonces/map">Carte</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/contact">Contact</a></li>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?php echo BASE_URL; ?>/admin" class="btn-admin-nav"><i class="fas fa-lock"></i> Admin</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_URL; ?>/login" class="btn-login-nav">Connexion</a></li>
                    <?php endif; ?>
                </ul>

                <button class="mobile-toggle" onclick="toggleMenu()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>
    </header>

    <script>
    function toggleMenu() {
        const nav = document.getElementById('nav-links');
        nav.classList.toggle('active');
    }
    </script>

    <main>
