<?php include __DIR__ . '/../layout_header.php'; ?>

<div class="admin-container">
    <aside class="admin-sidebar">
        <ul>
            <li><a href="<?php echo BASE_URL; ?>/admin"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/annonces"><i class="fas fa-home"></i> Annonces</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/publications"><i class="fas fa-bullhorn"></i> Publications</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/leads"><i class="fas fa-envelope"></i> Leads</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/consultants"><i class="fas fa-users"></i> Consultants</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/settings" class="active"><i class="fas fa-cogs"></i> Paramètres Entreprise</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/profil"><i class="fas fa-user-circle"></i> Mon Profil</a></li>
            <li><a href="<?php echo BASE_URL; ?>/logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </aside>
    
    <main class="admin-content">
        <header class="admin-header">
            <h1>Paramètres de l'<span class="highlight">Entreprise</span></h1>
            <p style="color: var(--gray);">Ces informations s'afficheront sur tout le site public.</p>
        </header>

        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success" style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i> Paramètres enregistrés avec succès !
            </div>
        <?php endif; ?>

        <div class="annonce-table-card" style="padding: 40px; max-width: 800px;">
            <form action="<?php echo BASE_URL; ?>/admin/settings/save" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nom de l'entreprise</label>
                        <input type="text" name="company_name" value="<?php echo $settings['company_name'] ?? 'ImmoAffaire'; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email de contact</label>
                        <input type="email" name="company_email" value="<?php echo $settings['company_email'] ?? ''; ?>" required>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" name="company_phone" value="<?php echo $settings['company_phone'] ?? ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Adresse physique</label>
                        <input type="text" name="company_address" value="<?php echo $settings['company_address'] ?? ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Lien Facebook</label>
                    <input type="url" name="social_facebook" value="<?php echo $settings['social_facebook'] ?? ''; ?>" placeholder="https://facebook.com/...">
                </div>

                <div class="form-group">
                    <label>Lien TikTok</label>
                    <input type="url" name="social_tiktok" value="<?php echo $settings['social_tiktok'] ?? ''; ?>" placeholder="https://tiktok.com/@...">
                </div>

                <div class="form-group">
                    <label>Numéro WhatsApp Entreprise (format international)</label>
                    <input type="text" name="company_whatsapp" value="<?php echo $settings['company_whatsapp'] ?? ''; ?>" placeholder="+2250102030405">
                </div>
                
                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">
                    <i class="fas fa-save"></i> Mettre à jour les informations
                </button>
            </form>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>
