<?php
// views/admin/affiche.php
include __DIR__ . '/../../views/layout_header.php';
?>
<div class="admin-container">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="admin-content">
        <header class="admin-header">
            <a href="<?php echo BASE_URL; ?>/admin/annonces" class="btn-back"><i class="fas fa-arrow-left"></i> Retour aux annonces</a>
            <h1>Générateur d'Affiche Publicitaire</h1>
        </header>

        <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> Affiche enregistrée dans le journal d'activité.</div>
        <?php endif; ?>

        <div class="affiche-workspace">
            <!-- Contrôles et Choix des modèles -->
            <div class="affiche-controls">
                <div class="control-section-card">
                    <h3>1. Choisissez un Modèle</h3>
                    <div class="models-selector-grid">
                        <div class="model-select-item active" data-model="classique">
                            <div class="model-preview-badge classique">Classique</div>
                            <span>Corporate Bleu & Or</span>
                        </div>
                        <div class="model-select-item" data-model="moderne">
                            <div class="model-preview-badge moderne">Moderne</div>
                            <span>Vibrant & Énergique</span>
                        </div>
                        <div class="model-select-item" data-model="luxury">
                            <div class="model-preview-badge luxury">Luxury</div>
                            <span>Élégant Dark & Or</span>
                        </div>
                    </div>
                </div>

                <div class="control-section-card" style="margin-top:20px;">
                    <h3>2. Personnalisez l'Image</h3>
                    <p class="section-help-text">Sélectionnez le média à afficher sur le poster :</p>
                    <div class="media-selector-grid">
                        <?php if (!empty($media)): ?>
                            <?php foreach ($media as $idx => $med): ?>
                                <?php if ($med['media_type'] === 'image'): ?>
                                    <div class="media-select-thumb <?php echo ($idx === 0) ? 'selected' : ''; ?>" data-src="<?php echo BASE_URL . $med['file_path']; ?>">
                                        <img src="<?php echo BASE_URL . $med['file_path']; ?>" alt="thumb">
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucune image disponible pour cette annonce.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="control-section-card" style="margin-top:20px;">
                    <h3>3. Coordonnées de Contact</h3>
                    <div class="form-group">
                        <label>Nom à afficher</label>
                        <input type="text" id="contactName" value="<?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'ImmoAffaire'); ?>">
                    </div>
                    <div class="form-group" style="margin-top:10px;">
                        <label>Téléphone WhatsApp</label>
                        <input type="text" id="contactPhone" value="<?php echo htmlspecialchars($annonce['whatsapp_contact'] ?? '+225 07 00 00 00 00'); ?>">
                    </div>
                    <div class="form-group" style="margin-top:10px;">
                        <label>Adresse / Secteur</label>
                        <input type="text" id="contactLocation" value="<?php echo htmlspecialchars($annonce['location_name'] ?? 'Abidjan, Côte d\'Ivoire'); ?>">
                    </div>
                </div>

                <div class="control-actions-card" style="margin-top:20px;">
                    <!-- Sauvegarder dans la DB pour l'historique et télécharger -->
                    <form action="<?php echo BASE_URL; ?>/admin/affiche/save" method="POST" id="saveAfficheForm">
                        <input type="hidden" name="annonce_id" value="<?php echo $annonce['id']; ?>">
                        <input type="hidden" name="model_name" id="modelNameInput" value="classique">
                        
                        <button type="button" class="btn-download-affiche" onclick="downloadPoster()">
                            <i class="fas fa-download"></i> Télécharger l'Affiche (PNG)
                        </button>
                    </form>
                </div>
            </div>

            <!-- Espace de Prévisualisation et Canvas -->
            <div class="affiche-preview-panel">
                <div class="preview-panel-header">
                    <h3><i class="fas fa-image"></i> Aperçu en temps réel</h3>
                    <span class="dimensions-badge">Format Carré (1080 x 1080 px)</span>
                </div>
                <div class="canvas-wrapper">
                    <!-- Canvas réel caché/interne pour rendu haute qualité -->
                    <canvas id="posterCanvas" width="1080" height="1080" style="display:none;"></canvas>
                    
                    <!-- Rendu simulé en CSS dans le navigateur (pour le design dynamique premium) -->
                    <div class="poster-css-preview theme-classique" id="cssPoster">
                        <div class="poster-badge-top">À VENDRE</div>
                        
                        <div class="poster-main-image-container">
                            <img src="<?php echo !empty($media[0]['file_path']) ? BASE_URL . $media[0]['file_path'] : '/assets/images/placeholder.jpg'; ?>" id="previewImg" alt="poster main image">
                        </div>

                        <div class="poster-content-box">
                            <div class="poster-cat-badge"><?php echo htmlspecialchars($annonce['category_name']); ?></div>
                            <h2 class="poster-title"><?php echo htmlspecialchars($annonce['title']); ?></h2>
                            <p class="poster-loc"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($annonce['location_name']); ?></p>
                            <div class="poster-price-badge"><?php echo number_format($annonce['price'], 0, ',', ' '); ?> FCFA</div>
                            
                            <hr class="poster-divider">

                            <div class="poster-footer-row">
                                <div class="poster-contact-info">
                                    <span class="p-agent-name" id="prevAgentName"><?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']); ?></span>
                                    <span class="p-agent-tel" id="prevAgentPhone"><i class="fab fa-whatsapp"></i> <?php echo htmlspecialchars($annonce['whatsapp_contact'] ?? '+225 07 00 00 00 00'); ?></span>
                                </div>
                                <div class="poster-brand">
                                    <strong>ImmoAffaire</strong>
                                    <span>L'immobilier d'exception</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des affiches -->
        <div class="affiche-history-panel" style="margin-top:30px;">
            <div class="panel-header">
                <h3><i class="fas fa-history"></i> Historique des affiches générées</h3>
            </div>
            <div class="panel-body">
                <?php if (empty($affiches)): ?>
                    <p style="color:var(--gray); text-align:center; padding:20px 0;">Aucune affiche enregistrée récemment pour ce produit.</p>
                <?php else: ?>
                    <div class="history-list-grid">
                        <?php foreach ($affiches as $af): ?>
                            <div class="history-item-row">
                                <div class="history-icon"><i class="fas fa-file-image"></i></div>
                                <div class="history-meta">
                                    <strong>Modèle : <?php echo ucfirst(htmlspecialchars($af['model_name'])); ?></strong>
                                    <span>Générée par <?php echo htmlspecialchars($af['created_by']); ?> le <?php echo date('d/m/Y H:i', strtotime($af['created_at'])); ?></span>
                                </div>
                                <div class="history-status-badge"><i class="fas fa-check-circle"></i> Prêt</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<style>
.affiche-workspace { display: grid; grid-template-columns: 1fr 1.2fr; gap: 30px; }
.control-section-card, .control-actions-card { background: white; border-radius: 16px; padding: 24px; box-shadow: var(--shadow); border: 1.5px solid #f1f5f9; }
.control-section-card h3 { font-size: 1rem; font-weight: 700; color: var(--primary); margin: 0 0 12px; }
.section-help-text { font-size: 0.8rem; color: var(--gray); margin-bottom: 12px; }

/* Modèles selector */
.models-selector-grid { display: flex; flex-direction: column; gap: 10px; }
.model-select-item { display: flex; align-items: center; gap: 14px; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 12px; cursor: pointer; transition: all 0.2s; }
.model-select-item:hover { border-color: var(--secondary); background: #f8fafc; }
.model-select-item.active { border-color: var(--secondary); background: #fff7ed; }
.model-preview-badge { width: 70px; height: 36px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: white; }
.model-preview-badge.classique { background: #0f172a; border: 1.5px solid #f59e0b; color: #f59e0b; }
.model-preview-badge.moderne { background: linear-gradient(135deg, #6366f1, #ec4899); }
.model-preview-badge.luxury { background: #111; border: 1.5px solid #d97706; color: #d97706; }
.model-select-item span { font-size: 0.88rem; font-weight: 700; color: var(--primary); }

/* Média selector */
.media-selector-grid { display: flex; gap: 8px; flex-wrap: wrap; }
.media-select-thumb { width: 56px; height: 56px; border-radius: 8px; overflow: hidden; border: 2.5px solid transparent; cursor: pointer; transition: all 0.2s; }
.media-select-thumb.selected { border-color: var(--secondary); transform: scale(1.05); }
.media-select-thumb img { width: 100%; height: 100%; object-fit: cover; }

/* Form inputs */
.form-group label { display: block; font-size: 0.78rem; font-weight: 700; color: var(--primary); margin-bottom: 5px; }
.form-group input { width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-family: inherit; font-size: 0.88rem; }
.form-group input:focus { outline: none; border-color: var(--secondary); }

/* Download button */
.btn-download-affiche { width: 100%; background: linear-gradient(135deg, #f59e0b, #d97706); color: var(--primary); border: none; padding: 14px; border-radius: 12px; font-weight: 800; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; transition: all 0.2s; }
.btn-download-affiche:hover { background: var(--primary); color: white; }

/* Preview panel */
.affiche-preview-panel { background: white; border-radius: 16px; box-shadow: var(--shadow); border: 1.5px solid #f1f5f9; overflow: hidden; display: flex; flex-direction: column; }
.preview-panel-header { padding: 16px 24px; border-bottom: 1.5px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
.preview-panel-header h3 { font-size: 0.95rem; font-weight: 700; color: var(--primary); margin: 0; display: flex; align-items: center; gap: 8px; }
.dimensions-badge { background: #f1f5f9; color: var(--gray); font-size: 0.7rem; font-weight: 700; padding: 4px 8px; border-radius: 6px; }
.canvas-wrapper { padding: 24px; display: flex; justify-content: center; background: #f8fafc; flex: 1; }

/* CSS simulated poster */
.poster-css-preview { width: 340px; height: 340px; border-radius: 12px; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; box-shadow: 0 10px 25px rgba(0,0,0,0.15); transition: all 0.2s; }
.poster-badge-top { position: absolute; top: 12px; left: 12px; background: red; color: white; padding: 4px 10px; font-size: 0.65rem; font-weight: 800; border-radius: 4px; z-index: 5; text-transform: uppercase; }

/* Themes styling */
/* theme-classique */
.theme-classique { background: #0f172a; border: 4px solid #f59e0b; }
.theme-classique .poster-main-image-container { position: absolute; top: 0; left: 0; width: 100%; height: 60%; overflow: hidden; }
.theme-classique .poster-main-image-container img { width: 100%; height: 100%; object-fit: cover; }
.theme-classique .poster-content-box { background: #0f172a; padding: 12px; z-index: 2; border-top: 3px solid #f59e0b; }
.theme-classique .poster-cat-badge { background: rgba(245,158,11,0.15); color: #f59e0b; font-size: 0.58rem; font-weight: 800; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-bottom: 4px; text-transform: uppercase; }
.theme-classique .poster-title { font-size: 0.88rem; color: white; font-weight: 800; margin: 0 0 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.theme-classique .poster-loc { font-size: 0.65rem; color: #94a3b8; margin: 0 0 6px; }
.theme-classique .poster-price-badge { background: #f59e0b; color: #0f172a; font-size: 0.95rem; font-weight: 900; padding: 4px 8px; border-radius: 6px; display: inline-block; }
.theme-classique .poster-divider { border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 8px 0; }
.theme-classique .poster-footer-row { display: flex; justify-content: space-between; align-items: center; }
.theme-classique .p-agent-name { font-size: 0.72rem; font-weight: 700; color: white; display: block; }
.theme-classique .p-agent-tel { font-size: 0.65rem; color: #f59e0b; font-weight: 700; }
.theme-classique .poster-brand { text-align: right; line-height: 1.1; }
.theme-classique .poster-brand strong { font-size: 0.72rem; color: white; display: block; }
.theme-classique .poster-brand span { font-size: 0.55rem; color: #94a3b8; }

/* theme-moderne */
.theme-moderne { background: linear-gradient(135deg, #4f46e5, #ec4899); border: none; }
.theme-moderne .poster-main-image-container { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; }
.theme-moderne .poster-main-image-container img { width: 100%; height: 100%; object-fit: cover; opacity: 0.85; filter: contrast(1.1); }
.theme-moderne .poster-main-image-container::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: linear-gradient(0deg, rgba(79,70,229,0.95) 0%, transparent 100%); z-index: 1; }
.theme-moderne .poster-content-box { z-index: 2; padding: 14px; position: relative; }
.theme-moderne .poster-cat-badge { background: white; color: #4f46e5; font-size: 0.58rem; font-weight: 800; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-bottom: 4px; }
.theme-moderne .poster-title { font-size: 0.95rem; color: white; font-weight: 800; margin: 0 0 2px; }
.theme-moderne .poster-loc { font-size: 0.65rem; color: rgba(255,255,255,0.8); margin: 0 0 8px; }
.theme-moderne .poster-price-badge { background: #ec4899; color: white; font-size: 0.95rem; font-weight: 900; padding: 4px 8px; border-radius: 6px; display: inline-block; box-shadow: 0 4px 10px rgba(236,72,153,0.3); }
.theme-moderne .poster-divider { border: 0; border-top: 1px solid rgba(255,255,255,0.2); margin: 8px 0; }
.theme-moderne .poster-footer-row { display: flex; justify-content: space-between; align-items: center; }
.theme-moderne .p-agent-name { font-size: 0.72rem; font-weight: 700; color: white; display: block; }
.theme-moderne .p-agent-tel { font-size: 0.65rem; color: #f59e0b; font-weight: 700; }
.theme-moderne .poster-brand { text-align: right; }
.theme-moderne .poster-brand strong { font-size: 0.72rem; color: white; display: block; }
.theme-moderne .poster-brand span { font-size: 0.55rem; color: rgba(255,255,255,0.7); }

/* theme-luxury */
.theme-luxury { background: #000; border: 1.5px solid #d97706; }
.theme-luxury .poster-main-image-container { position: absolute; top: 0; left: 0; width: 100%; height: 50%; overflow: hidden; }
.theme-luxury .poster-main-image-container img { width: 100%; height: 100%; object-fit: cover; filter: grayscale(1); }
.theme-luxury .poster-content-box { background: #111111; padding: 12px; z-index: 2; border-top: 1px solid #d97706; text-align: center; }
.theme-luxury .poster-cat-badge { border: 1px solid #d97706; color: #d97706; font-size: 0.52rem; font-weight: 800; padding: 1px 6px; border-radius: 2px; display: inline-block; margin-bottom: 4px; }
.theme-luxury .poster-title { font-size: 0.82rem; color: white; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 2px; font-family: serif; }
.theme-luxury .poster-loc { font-size: 0.62rem; color: #a1a1aa; margin: 0 0 6px; }
.theme-luxury .poster-price-badge { background: transparent; color: #d97706; border: 1.5px solid #d97706; font-size: 0.88rem; font-weight: 700; padding: 4px 10px; border-radius: 0; display: inline-block; font-family: serif; }
.theme-luxury .poster-divider { border: 0; border-top: 1px dashed rgba(217,119,6,0.3); margin: 8px 0; }
.theme-luxury .poster-footer-row { display: flex; justify-content: space-between; align-items: center; text-align: left; }
.theme-luxury .p-agent-name { font-size: 0.68rem; font-weight: 700; color: white; display: block; font-family: serif; }
.theme-luxury .p-agent-tel { font-size: 0.62rem; color: #d97706; font-weight: 700; }
.theme-luxury .poster-brand { text-align: right; }
.theme-luxury .poster-brand strong { font-size: 0.68rem; color: white; display: block; font-family: serif; }
.theme-luxury .poster-brand span { font-size: 0.52rem; color: #71717a; }

/* History list */
.history-list-grid { display: flex; flex-direction: column; gap: 10px; }
.history-item-row { display: flex; align-items: center; gap: 12px; padding: 12px 16px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; }
.history-icon { width: 36px; height: 36px; border-radius: 50%; background: #e0f2fe; color: #0369a1; display: flex; align-items: center; justify-content: center; font-size: 1rem; }
.history-meta { flex: 1; display: flex; flex-direction: column; }
.history-meta strong { font-size: 0.85rem; color: var(--primary); }
.history-meta span { font-size: 0.75rem; color: var(--gray); }
.history-status-badge { color: #15803d; font-size: 0.85rem; display: flex; align-items: center; gap: 4px; font-weight: 700; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Modele selection handler
    const items = document.querySelectorAll('.model-select-item');
    const cssPoster = document.getElementById('cssPoster');
    const modelInput = document.getElementById('modelNameInput');

    items.forEach(item => {
        item.addEventListener('click', function() {
            items.forEach(el => el.classList.remove('active'));
            this.classList.add('active');
            const model = this.dataset.model;
            
            // Modifier theme css
            cssPoster.className = 'poster-css-preview theme-' + model;
            modelInput.value = model;

            // Mettre à jour le badge de vente/location si nécessaire
            const topBadge = cssPoster.querySelector('.poster-badge-top');
            if (model === 'luxury') {
                topBadge.style.background = 'transparent';
                topBadge.style.color = '#d97706';
                topBadge.style.border = '1px solid #d97706';
            } else if (model === 'moderne') {
                topBadge.style.background = '#ec4899';
                topBadge.style.color = 'white';
                topBadge.style.border = 'none';
            } else {
                topBadge.style.background = 'red';
                topBadge.style.color = 'white';
                topBadge.style.border = 'none';
            }
        });
    });

    // Image selector handler
    const thumbItems = document.querySelectorAll('.media-select-thumb');
    const previewImg = document.getElementById('previewImg');

    thumbItems.forEach(thumb => {
        thumb.addEventListener('click', function() {
            thumbItems.forEach(el => el.classList.remove('selected'));
            this.classList.add('selected');
            previewImg.src = this.dataset.src;
        });
    });

    // Inputs dynamic listeners
    const contactNameInput = document.getElementById('contactName');
    const contactPhoneInput = document.getElementById('contactPhone');
    const prevAgentName = document.getElementById('prevAgentName');
    const prevAgentPhone = document.getElementById('prevAgentPhone');

    contactNameInput.addEventListener('input', function() {
        prevAgentName.textContent = this.value || 'Agent';
    });

    contactPhoneInput.addEventListener('input', function() {
        prevAgentPhone.innerHTML = '<i class="fab fa-whatsapp"></i> ' + (this.value || '');
    });
});

/** Fonction de dessin et de téléchargement de l'affiche publicitaire en HTML Canvas */
function downloadPoster() {
    const canvas = document.getElementById('posterCanvas');
    const ctx = canvas.getContext('2d');
    const model = document.getElementById('modelNameInput').value;

    const imgSource = document.getElementById('previewImg');
    const titleText = "<?php echo addslashes($annonce['title']); ?>";
    const priceText = "<?php echo number_format($annonce['price'], 0, ',', ' '); ?> FCFA";
    const categoryText = "<?php echo htmlspecialchars($annonce['category_name']); ?>";
    const locationText = document.getElementById('contactLocation').value;
    const contactName = document.getElementById('contactName').value;
    const contactPhone = document.getElementById('contactPhone').value;

    // Charger l'image dans le canvas
    const imgObj = new Image();
    imgObj.crossOrigin = 'anonymous'; // éviter les problèmes de canvas souillé
    imgObj.src = imgSource.src;

    imgObj.onload = function() {
        // Nettoyer canvas
        ctx.clearRect(0, 0, 1080, 1080);

        if (model === 'classique') {
            // Background principal (bleu très sombre)
            ctx.fillStyle = '#0f172a';
            ctx.fillRect(0, 0, 1080, 1080);

            // Dessiner l'image (prend les 60% supérieurs de l'affiche)
            ctx.drawImage(imgObj, 0, 0, 1080, 650);

            // Bordure Or entre image et contenu
            ctx.fillStyle = '#f59e0b';
            ctx.fillRect(0, 650, 1080, 8);

            // Badge à Vendre
            ctx.fillStyle = '#ef4444';
            ctx.fillRect(50, 50, 200, 60);
            ctx.fillStyle = 'white';
            ctx.font = 'bold 28px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('À VENDRE', 150, 90);

            // Contenu en bas
            ctx.textAlign = 'left';

            // Catégorie
            ctx.fillStyle = 'rgba(245, 158, 11, 0.15)';
            ctx.fillRect(50, 690, 160, 40);
            ctx.fillStyle = '#f59e0b';
            ctx.font = 'bold 20px sans-serif';
            ctx.fillText(categoryText.toUpperCase(), 70, 718);

            // Titre du bien
            ctx.fillStyle = 'white';
            ctx.font = 'bold 44px sans-serif';
            ctx.fillText(titleText, 50, 785);

            // Localisation
            ctx.fillStyle = '#94a3b8';
            ctx.font = '30px sans-serif';
            ctx.fillText('📍 ' + locationText, 50, 835);

            // Prix (Ruban)
            ctx.fillStyle = '#f59e0b';
            ctx.fillRect(50, 875, 480, 75);
            ctx.fillStyle = '#0f172a';
            ctx.font = 'black 40px sans-serif';
            ctx.fillText(priceText, 80, 928);

            // Divider
            ctx.strokeStyle = 'rgba(255,255,255,0.1)';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(50, 985);
            ctx.lineTo(1030, 985);
            ctx.stroke();

            // Contacts
            ctx.fillStyle = 'white';
            ctx.font = 'bold 28px sans-serif';
            ctx.fillText(contactName, 50, 1035);

            ctx.fillStyle = '#f59e0b';
            ctx.font = 'bold 26px sans-serif';
            ctx.fillText('💬 ' + contactPhone, 50, 1065);

            // Marque
            ctx.textAlign = 'right';
            ctx.fillStyle = 'white';
            ctx.font = 'bold 32px sans-serif';
            ctx.fillText('ImmoAffaire', 1030, 1035);
            ctx.fillStyle = '#94a3b8';
            ctx.font = '22px sans-serif';
            ctx.fillText("L'immobilier d'exception", 1030, 1065);

        } else if (model === 'moderne') {
            // Image en arrière-plan complet
            ctx.drawImage(imgObj, 0, 0, 1080, 1080);

            // Dégradé sombre
            const gradient = ctx.createLinearGradient(0, 400, 0, 1080);
            gradient.addColorStop(0, 'rgba(79, 70, 229, 0)');
            gradient.addColorStop(0.5, 'rgba(79, 70, 229, 0.7)');
            gradient.addColorStop(1, 'rgba(79, 70, 229, 0.98)');
            ctx.fillStyle = gradient;
            ctx.fillRect(0, 0, 1080, 1080);

            // Badge à Vendre
            ctx.fillStyle = '#ec4899';
            ctx.fillRect(50, 50, 200, 60);
            ctx.fillStyle = 'white';
            ctx.font = 'bold 28px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('À VENDRE', 150, 90);

            // Contenu
            ctx.textAlign = 'left';

            // Catégorie
            ctx.fillStyle = 'white';
            ctx.fillRect(50, 690, 160, 40);
            ctx.fillStyle = '#4f46e5';
            ctx.font = 'bold 20px sans-serif';
            ctx.fillText(categoryText.toUpperCase(), 70, 718);

            // Titre du bien
            ctx.fillStyle = 'white';
            ctx.font = 'bold 50px sans-serif';
            ctx.fillText(titleText, 50, 785);

            // Localisation
            ctx.fillStyle = 'rgba(255,255,255,0.9)';
            ctx.font = '30px sans-serif';
            ctx.fillText('📍 ' + locationText, 50, 835);

            // Prix (Ruban Rose Moderne)
            ctx.fillStyle = '#ec4899';
            ctx.fillRect(50, 875, 480, 75);
            ctx.fillStyle = 'white';
            ctx.font = 'black 40px sans-serif';
            ctx.fillText(priceText, 80, 928);

            // Divider
            ctx.strokeStyle = 'rgba(255,255,255,0.2)';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(50, 985);
            ctx.lineTo(1030, 985);
            ctx.stroke();

            // Contacts
            ctx.fillStyle = 'white';
            ctx.font = 'bold 28px sans-serif';
            ctx.fillText(contactName, 50, 1035);

            ctx.fillStyle = '#f59e0b';
            ctx.font = 'bold 26px sans-serif';
            ctx.fillText('💬 ' + contactPhone, 50, 1065);

            // Marque
            ctx.textAlign = 'right';
            ctx.fillStyle = 'white';
            ctx.font = 'bold 32px sans-serif';
            ctx.fillText('ImmoAffaire', 1030, 1035);
            ctx.fillStyle = 'rgba(255,255,255,0.8)';
            ctx.font = '22px sans-serif';
            ctx.fillText("L'immobilier d'exception", 1030, 1065);

        } else if (model === 'luxury') {
            // Background principal (noir profond)
            ctx.fillStyle = '#0a0a0a';
            ctx.fillRect(0, 0, 1080, 1080);

            // Cadre Or luxury
            ctx.strokeStyle = '#d97706';
            ctx.lineWidth = 6;
            ctx.strokeRect(30, 30, 1020, 1020);

            // Image centrée (grisaille simulée)
            ctx.drawImage(imgObj, 60, 60, 960, 500);

            // Contenu
            ctx.textAlign = 'center';

            // Catégorie
            ctx.strokeStyle = '#d97706';
            ctx.lineWidth = 2;
            ctx.strokeRect(460, 600, 160, 40);
            ctx.fillStyle = '#d97706';
            ctx.font = 'bold 18px Georgia, serif';
            ctx.fillText(categoryText.toUpperCase(), 540, 627);

            // Titre du bien
            ctx.fillStyle = 'white';
            ctx.font = 'bold 44px Georgia, serif';
            ctx.fillText(titleText.toUpperCase(), 540, 695);

            // Localisation
            ctx.fillStyle = '#a1a1aa';
            ctx.font = '26px Georgia, serif';
            ctx.fillText('📍 ' + locationText, 540, 745);

            // Prix (Ruban Luxury)
            ctx.strokeStyle = '#d97706';
            ctx.lineWidth = 3;
            ctx.strokeRect(340, 785, 400, 75);
            ctx.fillStyle = '#d97706';
            ctx.font = 'bold 36px Georgia, serif';
            ctx.fillText(priceText, 540, 836);

            // Contacts
            ctx.textAlign = 'left';
            ctx.fillStyle = 'white';
            ctx.font = 'bold 24px Georgia, serif';
            ctx.fillText(contactName, 80, 935);

            ctx.fillStyle = '#d97706';
            ctx.font = 'bold 24px Georgia, serif';
            ctx.fillText('💬 ' + contactPhone, 80, 975);

            // Marque
            ctx.textAlign = 'right';
            ctx.fillStyle = 'white';
            ctx.font = 'bold 28px Georgia, serif';
            ctx.fillText('ImmoAffaire', 1000, 935);
            ctx.fillStyle = '#71717a';
            ctx.font = '18px Georgia, serif';
            ctx.fillText("L'immobilier d'exception", 1000, 975);
        }

        // Télécharger l'image PNG résultante
        const link = document.createElement('a');
        link.download = 'affiche_' + model + '_' + titleText.toLowerCase().replace(/[^a-z0-9]/g, '_') + '.png';
        link.href = canvas.toDataURL('image/png');
        link.click();

        // Envoyer la soumission du formulaire d'enregistrement de l'affiche en arrière-plan
        document.getElementById('saveAfficheForm').submit();
    };

    // Si erreur de chargement
    imgObj.onerror = function() {
        alert('Erreur lors du chargement de l\'image pour le rendu.');
    };
}
</script>
<?php include __DIR__ . '/../../views/layout_footer.php'; ?>
