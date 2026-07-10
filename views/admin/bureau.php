<?php
// views/admin/bureau.php
$adminLayout = true;
include __DIR__ . '/../../views/admin/../../views/layout_header.php';
$pageTitle = $title ?? 'Mon Bureau';
?>
<div class="admin-container">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="admin-content">
        <header class="admin-header">
            <div class="header-flex" style="display:flex; justify-content:space-between; align-items:center; width:100%;">
                <h1><?php echo $pageTitle; ?></h1>
                <a href="<?php echo BASE_URL; ?>/bureaux" target="_blank" class="btn-outline-sm">
                    <i class="fas fa-eye"></i> Voir la page publique
                </a>
            </div>
        </header>

        <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> Bureau mis à jour avec succès !</div>
        <?php endif; ?>

        <div class="bureau-grid">
            <!-- Formulaire -->
            <div class="bureau-form-card">
                <div class="card-header-section">
                    <i class="fas fa-building"></i>
                    <h2>Informations du Bureau</h2>
                    <p>Ces informations seront visibles par les clients sur la page publique des bureaux.</p>
                </div>
                <form action="<?php echo BASE_URL; ?>/admin/bureau/save" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Nom du Bureau</label>
                        <input type="text" name="nom" value="<?php echo htmlspecialchars($bureau['nom'] ?? ''); ?>" placeholder="Ex: Bureau Plateau, Bureau Cocody...">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-map-marker-alt"></i> Adresse complète</label>
                        <textarea name="adresse" rows="2" placeholder="Ex: Rue des Jardiniers, Plateau, Abidjan"><?php echo htmlspecialchars($bureau['adresse'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-row-2">
                        <div class="form-group">
                            <label><i class="fab fa-whatsapp" style="color:#25d366"></i> WhatsApp</label>
                            <input type="text" name="phone_whatsapp" value="<?php echo htmlspecialchars($bureau['phone_whatsapp'] ?? ''); ?>" placeholder="+225 07 XX XX XX XX">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-phone"></i> Téléphone</label>
                            <input type="text" name="phone_tel" value="<?php echo htmlspecialchars($bureau['phone_tel'] ?? ''); ?>" placeholder="+225 27 XX XX XX XX">
                        </div>
                    </div>
                    <div class="form-row-2">
                        <div class="form-group">
                            <label><i class="fas fa-phone-volume"></i> Fixe</label>
                            <input type="text" name="phone_fixe" value="<?php echo htmlspecialchars($bureau['phone_fixe'] ?? ''); ?>" placeholder="Numéro fixe (optionnel)">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-envelope"></i> Email (optionnel)</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($bureau['email'] ?? ''); ?>" placeholder="bureau@example.com">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> Description du bureau</label>
                        <textarea name="description" rows="3" placeholder="Décrivez votre bureau, spécialités, zone de couverture..."><?php echo htmlspecialchars($bureau['description'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-image"></i> Logo du bureau</label>
                        <?php if (!empty($bureau['logo'])): ?>
                        <div class="current-logo">
                            <img src="<?php echo BASE_URL . $bureau['logo']; ?>" alt="Logo actuel">
                            <span>Logo actuel</span>
                        </div>
                        <?php endif; ?>
                        <div class="file-drop-zone" onclick="document.getElementById('logoInput').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Cliquez pour choisir un logo</p>
                            <small>JPG, PNG, SVG • Max 2Mo</small>
                        </div>
                        <input type="file" id="logoInput" name="logo" accept="image/*" style="display:none" onchange="previewLogo(this)">
                        <img id="logoPreview" src="" alt="" style="display:none; max-height:80px; border-radius:8px; margin-top:10px;">
                    </div>
                    <button type="submit" class="btn-primary-full">
                        <i class="fas fa-save"></i> Enregistrer le Bureau
                    </button>
                </form>
            </div>

            <!-- Aperçu -->
            <div class="bureau-preview-card">
                <div class="card-header-section">
                    <i class="fas fa-eye"></i>
                    <h2>Aperçu Public</h2>
                    <p>Voici comment votre bureau apparaîtra aux clients.</p>
                </div>
                <div class="bureau-preview-box">
                    <div class="preview-header">
                        <?php if (!empty($bureau['logo'])): ?>
                        <img src="<?php echo BASE_URL . $bureau['logo']; ?>" alt="Logo" class="preview-logo">
                        <?php else: ?>
                        <div class="preview-avatar"><?php echo strtoupper(substr($bureau['admin_name'] ?? $_SESSION['username'] ?? 'A', 0, 1)); ?></div>
                        <?php endif; ?>
                        <div>
                            <h3><?php echo htmlspecialchars($bureau['nom'] ?? 'Votre Bureau'); ?></h3>
                            <span class="preview-badge">Bureau Officiel</span>
                        </div>
                    </div>
                    <?php if (!empty($bureau['adresse'])): ?>
                    <p class="preview-addr"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($bureau['adresse']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($bureau['description'])): ?>
                    <p class="preview-desc"><?php echo htmlspecialchars($bureau['description']); ?></p>
                    <?php endif; ?>
                    <div class="preview-contacts">
                        <?php if (!empty($bureau['phone_whatsapp'])): ?>
                        <span class="prev-contact wa"><i class="fab fa-whatsapp"></i> <?php echo htmlspecialchars($bureau['phone_whatsapp']); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($bureau['phone_tel'])): ?>
                        <span class="prev-contact tel"><i class="fas fa-phone"></i> <?php echo htmlspecialchars($bureau['phone_tel']); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($bureau['email'])): ?>
                        <span class="prev-contact email"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($bureau['email']); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if (empty($bureau['nom']) && empty($bureau['phone_whatsapp'])): ?>
                    <div class="preview-empty">
                        <i class="fas fa-info-circle"></i>
                        <p>Remplissez le formulaire pour voir l'aperçu de votre bureau.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
.bureau-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 30px; }
.bureau-form-card, .bureau-preview-card { background: white; border-radius: 16px; box-shadow: var(--shadow); overflow: hidden; }
.card-header-section { background: linear-gradient(135deg, #0f172a, #1e293b); color: white; padding: 24px 28px; display: flex; flex-direction: column; gap: 4px; }
.card-header-section i { font-size: 1.5rem; color: var(--secondary); margin-bottom: 8px; }
.card-header-section h2 { font-size: 1.2rem; font-weight: 700; margin: 0; }
.card-header-section p { font-size: 0.82rem; opacity: 0.6; margin: 0; }
.bureau-form-card form { padding: 28px; display: flex; flex-direction: column; gap: 18px; }
.form-group label { display: flex; align-items: center; gap: 8px; font-size: 0.82rem; font-weight: 700; color: var(--primary); margin-bottom: 6px; }
.form-group input, .form-group textarea, .form-group select { width: 100%; padding: 11px 14px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-family: inherit; font-size: 0.9rem; transition: border 0.2s; }
.form-group input:focus, .form-group textarea:focus { outline: none; border-color: var(--secondary); }
.form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.file-drop-zone { border: 2px dashed #e2e8f0; border-radius: 10px; padding: 24px; text-align: center; cursor: pointer; transition: all 0.2s; }
.file-drop-zone:hover { border-color: var(--secondary); background: #fff7ed; }
.file-drop-zone i { font-size: 2rem; color: var(--secondary); margin-bottom: 8px; }
.file-drop-zone p { font-weight: 600; font-size: 0.9rem; margin: 0; }
.file-drop-zone small { color: var(--gray); }
.current-logo { display: flex; align-items: center; gap: 12px; padding: 10px; background: #f8fafc; border-radius: 8px; margin-bottom: 10px; }
.current-logo img { height: 50px; border-radius: 6px; object-fit: contain; }
.current-logo span { font-size: 0.82rem; color: var(--gray); }
.btn-primary-full { width: 100%; background: var(--primary); color: white; border: none; padding: 14px; border-radius: 10px; font-weight: 700; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; transition: all 0.2s; }
.btn-primary-full:hover { background: var(--secondary); color: var(--primary); }
.btn-outline-sm { background: transparent; border: 1.5px solid var(--border); color: var(--primary); padding: 8px 16px; border-radius: 8px; font-size: 0.82rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }
.btn-outline-sm:hover { background: var(--primary); color: white; border-color: var(--primary); }
/* Preview */
.bureau-preview-card .card-header-section { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
.bureau-preview-box { padding: 24px; }
.preview-header { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
.preview-logo { width: 60px; height: 60px; object-fit: contain; border-radius: 10px; border: 2px solid #e2e8f0; }
.preview-avatar { width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b, #ef4444); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 800; color: white; flex-shrink: 0; }
.preview-header h3 { font-size: 1.1rem; font-weight: 700; margin: 0 0 4px; }
.preview-badge { background: #fef3c7; color: #92400e; font-size: 0.68rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; text-transform: uppercase; }
.preview-addr { font-size: 0.85rem; color: var(--gray); margin-bottom: 12px; }
.preview-desc { font-size: 0.88rem; color: #475569; line-height: 1.5; margin-bottom: 16px; padding: 12px; background: #f8fafc; border-radius: 8px; border-left: 3px solid #e2e8f0; }
.preview-contacts { display: flex; flex-direction: column; gap: 8px; }
.prev-contact { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; padding: 8px 12px; border-radius: 8px; }
.prev-contact.wa { background: #f0fdf4; color: #15803d; }
.prev-contact.tel { background: #eff6ff; color: #1d4ed8; }
.prev-contact.email { background: #fef3c7; color: #92400e; }
.preview-empty { text-align: center; padding: 40px 20px; color: var(--gray); }
.preview-empty i { font-size: 2rem; color: #e2e8f0; display: block; margin-bottom: 12px; }
@media (max-width: 900px) { .bureau-grid { grid-template-columns: 1fr; } .form-row-2 { grid-template-columns: 1fr; } }
</style>
<script>
function previewLogo(input) {
    const preview = document.getElementById('logoPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php include __DIR__ . '/../../views/layout_footer.php'; ?>
