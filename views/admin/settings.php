<?php include __DIR__ . '/../layout_header.php'; ?>

<div class="admin-container">
    <?php include __DIR__ . '/sidebar.php'; ?>
    
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

                <div class="form-group" style="background:#f0fdf4; padding:20px; border-radius:12px; border:1.5px solid #bbf7d0;">
                    <label style="display:flex; align-items:center; gap:8px;">
                        <i class="fas fa-globe" style="color:#059669;"></i> URL publique du site
                        <span style="font-size:0.72rem; font-weight:400; color:#64748b;">(obligatoire pour les liens WhatsApp & réseaux sociaux)</span>
                    </label>
                    <input type="url" name="site_url" value="<?php echo $settings['site_url'] ?? ''; ?>" 
                           placeholder="https://votre-domaine.com/Projet_Affaire" 
                           style="border-color:#86efac;">
                    <p style="font-size:0.78rem; color:#64748b; margin-top:6px;">
                        <i class="fas fa-info-circle"></i> Entrez l'adresse complète de votre site accessible depuis Internet.<br>
                        Exemple : <code>https://immoaffaire.ci</code> ou <code>http://192.168.1.10/Projet_Affaire</code><br>
                        Si vide, les liens utiliseront l'adresse locale (localhost) qui ne fonctionne pas depuis un téléphone externe.
                    </p>
                </div>
                
                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">
                    <i class="fas fa-save"></i> Mettre à jour les informations
                </button>
            </form>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>
