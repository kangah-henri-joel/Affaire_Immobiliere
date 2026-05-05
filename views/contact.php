<?php 
include 'layout_header.php'; 
// Fetch settings if not already available
if (!isset($siteSettings)) {
    require_once __DIR__ . '/../models/SettingsModel.php';
    $settingsModel = new SettingsModel();
    $siteSettings = $settingsModel->getAll();
}
$companyName = $siteSettings['company_name'] ?? 'ImmoAffaire';
$whatsapp = $siteSettings['company_whatsapp'] ?? '';
$email = $siteSettings['company_email'] ?? '';
$address = $siteSettings['company_address'] ?? '';
?>

<section class="contact-hero">
    <div class="container">
        <h1>Contactez <span class="highlight"><?php echo $companyName; ?></span></h1>
        <p>Une question ? Un projet immobilier ? Notre équipe est à votre écoute.</p>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-info">
                <?php if(!empty($address)): ?>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h3>Notre Bureau</h3>
                        <p><?php echo $address; ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(!empty($whatsapp)): ?>
                <div class="info-item">
                    <i class="fab fa-whatsapp"></i>
                    <div>
                        <h3>WhatsApp</h3>
                        <p><?php echo $whatsapp; ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(!empty($email)): ?>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>Email</h3>
                        <p><?php echo $email; ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="social-box-contact" style="margin-top: 40px;">
                    <h3>Suivez-nous</h3>
                    <div class="social-links-footer">
                        <?php if(!empty($siteSettings['social_facebook'])): ?>
                            <a href="<?php echo $siteSettings['social_facebook']; ?>"><i class="fab fa-facebook"></i> Facebook</a>
                        <?php endif; ?>
                        <?php if(!empty($siteSettings['social_tiktok'])): ?>
                            <a href="<?php echo $siteSettings['social_tiktok']; ?>"><i class="fab fa-tiktok"></i> TikTok</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="contact-form-container">
                <form action="<?php echo BASE_URL; ?>/annonce/contact" method="POST">
                    <input type="hidden" name="annonce_id" value="0">
                    <div class="form-grid-contact">
                        <div class="form-group">
                            <label>Nom Complet</label>
                            <input type="text" name="client_name" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="client_email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" name="client_phone" required>
                    </div>
                    <div class="form-group">
                        <label>Sujet / Message</label>
                        <textarea name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">Envoyer le message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
.contact-hero { padding: 100px 0; background: var(--primary); color: white; text-align: center; }
.contact-hero h1 { font-size: 3rem; font-weight: 800; margin-bottom: 20px; }
.contact-section { padding: 100px 0; background: #f8fafc; }
.contact-grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 80px; }
.info-item { display: flex; gap: 20px; margin-bottom: 40px; align-items: flex-start; }
.info-item i { font-size: 1.5rem; color: var(--primary); background: white; padding: 20px; border-radius: 20px; box-shadow: var(--shadow); }
.info-item h3 { margin-bottom: 5px; font-weight: 700; color: var(--primary); }
.contact-form-container { background: white; padding: 50px; border-radius: 25px; box-shadow: var(--shadow); }
.form-grid-contact { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

.social-links-footer a { display: block; margin-bottom: 10px; font-weight: 600; color: var(--gray); transition: var(--transition); }
.social-links-footer a:hover { color: var(--primary); transform: translateX(5px); }

@media (max-width: 768px) {
    .contact-grid { grid-template-columns: 1fr; }
    .form-grid-contact { grid-template-columns: 1fr; }
}
</style>

<?php include 'layout_footer.php'; ?>
