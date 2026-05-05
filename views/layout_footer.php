    </main>

    <?php
    // Fetch company settings for the footer
    if (!isset($siteSettings)) {
        require_once __DIR__ . '/../models/SettingsModel.php';
        $settingsModel = new SettingsModel();
        $siteSettings = $settingsModel->getAll();
    }
    
    // Clean WhatsApp number for the link (digits only)
    $rawWhatsapp = $siteSettings['company_whatsapp'] ?? '';
    $cleanWhatsapp = preg_replace('/[^0-9]/', '', $rawWhatsapp);
    
    $email = $siteSettings['company_email'] ?? 'contact@immoaffaire.com';
    $companyName = $siteSettings['company_name'] ?? 'ImmoAffaire';
    ?>

    <!-- Bouton WhatsApp Flottant -->
    <?php if(!empty($cleanWhatsapp)): ?>
    <a href="https://wa.me/<?php echo $cleanWhatsapp; ?>" class="whatsapp-float" target="_blank" title="Contactez-nous sur WhatsApp">
        <i class="fab fa-whatsapp"></i>
        <span>Contactez-nous</span>
    </a>
    <?php endif; ?>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><?php echo $companyName; ?></h3>
                    <p>Votre partenaire immobilier de confiance en Côte d'Ivoire.</p>
                    <div class="social-links-footer">
                        <?php if(!empty($siteSettings['social_facebook'])): ?>
                            <a href="<?php echo $siteSettings['social_facebook']; ?>" target="_blank"><i class="fab fa-facebook"></i></a>
                        <?php endif; ?>
                        <?php if(!empty($siteSettings['social_tiktok'])): ?>
                            <a href="<?php echo $siteSettings['social_tiktok']; ?>" target="_blank"><i class="fab fa-tiktok"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Liens Rapides</h3>
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>/annonces">Toutes les annonces</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/annonces/map">Carte interactive</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/contact">Nous contacter</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <?php if(!empty($rawWhatsapp)): ?>
                        <p><i class="fab fa-whatsapp"></i> <?php echo $rawWhatsapp; ?></p>
                    <?php endif; ?>
                    <p><i class="fas fa-envelope"></i> <?php echo $email; ?></p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo $companyName; ?>. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <style>
    .whatsapp-float {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background-color: #25d366;
        color: white;
        padding: 12px 25px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 10px 25px rgba(37, 211, 102, 0.4);
        z-index: 9999; /* Assurer qu'il est au-dessus de tout */
        font-weight: 700;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .whatsapp-float:hover {
        transform: translateY(-5px) scale(1.05);
        background-color: #128c7e;
        color: white;
        box-shadow: 0 15px 30px rgba(37, 211, 102, 0.5);
    }
    .whatsapp-float i { font-size: 1.8rem; }
    
    footer { background: var(--primary); color: #94a3b8; padding: 80px 0 30px; margin-top: 80px; }
    .footer-content { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 60px; }
    .footer-section h3 { color: white; margin-bottom: 25px; }
    .footer-bottom { border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 30px; text-align: center; margin-top: 50px; }
    
    @media (max-width: 768px) {
        .footer-content { grid-template-columns: 1fr; gap: 40px; }
        .whatsapp-float span { display: none; }
        .whatsapp-float { padding: 15px; bottom: 20px; right: 20px; }
    }
    </style>

    <script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
