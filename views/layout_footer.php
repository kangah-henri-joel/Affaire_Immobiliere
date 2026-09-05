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
    /* ── Footer styles ─────────────────────────────────── */
    footer {
        background: #0f172a !important;
        color: #94a3b8 !important;
        padding: 80px 0 30px !important;
        margin-top: 80px !important;
        display: block !important;
        width: 100% !important;
    }
    .footer-content {
        display: grid !important;
        grid-template-columns: 2fr 1fr 1fr !important;
        gap: 60px !important;
    }
    .footer-section h3 { color: #ffffff !important; margin-bottom: 25px !important; }
    .footer-section p { color: #94a3b8; display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
    .footer-section ul { list-style: none; padding: 0; }
    .footer-section ul li { margin-bottom: 10px; }
    .footer-section ul li a { color: #94a3b8 !important; text-decoration: none; }
    .footer-section ul li a:hover { color: #f59e0b !important; }
    .footer-bottom {
        border-top: 1px solid rgba(255,255,255,0.05) !important;
        padding-top: 30px !important;
        text-align: center !important;
        margin-top: 50px !important;
        color: #64748b !important;
    }
    .social-links-footer { display: flex; gap: 15px; margin-top: 15px; }
    .social-links-footer a { color: #64748b !important; font-size: 1.2rem; }
    .social-links-footer a:hover { color: #f59e0b !important; }

    /* ── WhatsApp floating button ───────────────────────── */
    .whatsapp-float {
        position: fixed !important;
        bottom: 30px !important;
        right: 30px !important;
        background-color: #25d366 !important;
        color: white !important;
        padding: 12px 25px !important;
        border-radius: 50px !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        box-shadow: 0 10px 25px rgba(37,211,102,0.4) !important;
        z-index: 9999 !important;
        font-weight: 700 !important;
        text-decoration: none !important;
    }
    .whatsapp-float i { font-size: 1.8rem; }

    @media (max-width: 768px) {
        footer { padding: 50px 0 20px !important; }
        .footer-content { grid-template-columns: 1fr !important; gap: 30px !important; }
        .whatsapp-float span { display: none !important; }
        .whatsapp-float { padding: 15px !important; bottom: 20px !important; right: 20px !important; border-radius: 50% !important; }
    }
    </style>

    <script src="<?php echo BASE_URL; ?>/assets/js/africa-locations.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/ux-helpers.js?v=<?php echo @filemtime(__DIR__ . '/../assets/js/ux-helpers.js') ?: time(); ?>"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
