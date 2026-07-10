<?php include __DIR__ . '/../layout_header.php'; ?>

<div class="admin-container">
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <main class="admin-content">
        <header class="admin-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1>Programmation des <span class="highlight">Publications</span></h1>
                    <p style="color: var(--gray);">Automatisez votre marketing sur les réseaux sociaux.</p>
                </div>
                <button class="btn-primary" onclick="openAddModal()"><i class="fas fa-plus"></i> Programmer un Post</button>
            </div>
        </header>

        <div class="stats-grid">
            <div class="stat-card">
                <i class="fab fa-facebook" style="color: #1877f2; background: rgba(24, 119, 242, 0.1);"></i>
                <div>
                    <h4>Facebook</h4>
                    <p><?php echo count(array_filter($publications, fn($p) => $p['platform'] == 'facebook' && $p['status'] == 'pending')); ?> en attente</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fab fa-whatsapp" style="color: #25d366; background: rgba(37, 211, 102, 0.1);"></i>
                <div>
                    <h4>WhatsApp</h4>
                    <p><?php echo count(array_filter($publications, fn($p) => $p['platform'] == 'whatsapp' && $p['status'] == 'pending')); ?> en attente</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fab fa-tiktok" style="color: #000; background: rgba(0, 0, 0, 0.05);"></i>
                <div>
                    <h4>TikTok</h4>
                    <p><?php echo count(array_filter($publications, fn($p) => $p['platform'] == 'tiktok' && $p['status'] == 'pending')); ?> en attente</p>
                </div>
            </div>
        </div>

        <div class="annonce-table-card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Annonce</th>
                            <th>Plateforme</th>
                            <th>Date Prévue</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($publications)): ?>
                            <?php foreach($publications as $p): ?>
                                <tr>
                                    <td style="font-weight: 600;"><?php echo $p['annonce_title']; ?></td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <i class="fab fa-<?php echo $p['platform']; ?>" style="font-size: 1.2rem;"></i>
                                            <?php echo ucfirst($p['platform']); ?>
                                        </div>
                                    </td>
                                    <td style="color: var(--gray);"><?php echo date('d/m/Y H:i', strtotime($p['scheduled_at'])); ?></td>
                                    <td>
                                        <span class="badge-status <?php echo $p['status']; ?>">
                                            <?php echo $p['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 5px; flex-wrap:wrap;">
                                            <?php
                                                require_once __DIR__ . '/../../config/SiteUrl.php';
                                                $annUrl  = SiteUrl::annonce($p['annonce_id']);
                                                $imgUrl  = SiteUrl::media($p['image_path'] ?? null);

                                                // Message texte complet
                                                $richMsg = "\u{1F3E0} *" . $p['annonce_title'] . "*\n"
                                                         . "\u{1F4B0} Prix : " . number_format($p['annonce_price'] ?? 0, 0, ',', ' ') . " FCFA\n"
                                                         . "\u{1F4CD} " . ($p['location_name'] ?? '') . "\n"
                                                         . "\u{1F5C2} " . ($p['category_name'] ?? '') . ' — ' . ucfirst($p['annonce_type'] ?? '') . "\n\n"
                                                         . ($p['generated_text'] ?? '') . "\n\n"
                                                         . "\u{1F449} Voir le bien : " . $annUrl;
                                            ?>

                                            <?php if($p['platform'] == 'whatsapp'): ?>
                                                <a href="https://api.whatsapp.com/send?text=<?php echo rawurlencode($richMsg); ?>"
                                                   target="_blank" class="btn-share btn-share-wa" title="Publier sur WhatsApp">
                                                    <i class="fab fa-whatsapp"></i> Publier
                                                </a>

                                            <?php elseif($p['platform'] == 'facebook'): ?>
                                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode($annUrl); ?>&quote=<?php echo rawurlencode(($p['generated_text'] ?? '') . "\n\n\u{1F449} " . $annUrl); ?>"
                                                   target="_blank" class="btn-share btn-share-fb" title="Partager sur Facebook">
                                                    <i class="fab fa-facebook"></i> Partager
                                                </a>

                                            <?php elseif($p['platform'] == 'tiktok'): ?>
                                                <button class="btn-share btn-share-tt"
                                                        onclick="publishToTikTok(this, `<?php echo addslashes(str_replace(['`','\\'], ['\\`','\\\\'], $richMsg)); ?>`)"
                                                        title="Copier le texte et ouvrir TikTok">
                                                    <i class="fab fa-tiktok"></i> Publier
                                                </button>
                                            <?php endif; ?>

                                            <!-- Copier le texte -->
                                            <button class="btn-share btn-share-copy"
                                                    onclick="copyToClipboard(this, `<?php echo addslashes(str_replace(['`','\\'], ['\\`','\\\\'], $richMsg)); ?>`)"
                                                    title="Copier le texte">
                                                <i class="fas fa-copy"></i>
                                            </button>

                                            <button class="btn-sm" onclick="editPublication(<?php echo $p['id']; ?>)"><i class="fas fa-edit"></i></button>
                                            <a href="<?php echo BASE_URL; ?>/admin/publications/delete?id=<?php echo $p['id']; ?>" class="btn-sm" style="color: var(--danger);" onclick="return confirm('Supprimer cette publication ?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align:center; padding: 40px; color: var(--gray);">Aucune publication programmée.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Toast notification TikTok -->
<div id="tiktokToast" style="
    display:none; position:fixed; bottom:30px; left:50%; transform:translateX(-50%);
    background:#0f172a; color:white; padding:16px 28px; border-radius:14px;
    box-shadow:0 10px 30px rgba(0,0,0,0.3); z-index:9999; font-size:0.92rem;
    align-items:center; gap:12px; max-width:420px; text-align:center;
    border:1.5px solid rgba(255,255,255,0.1);
">
    <i class="fab fa-tiktok" style="font-size:1.5rem; color:#fe2c55;"></i>
    <div>
        <strong style="display:block;margin-bottom:3px;">Texte copié !</strong>
        <span style="color:rgba(255,255,255,0.7);font-size:0.82rem;">
            TikTok s'ouvre… Collez le texte dans votre description de vidéo.
        </span>
    </div>
</div>

<!-- Modal Publication (Unique pour Ajout/Edit) -->
<div id="publishModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="toggleModal('publishModal')">&times;</span>
        <h2 id="modalTitle">Programmer une <span class="highlight">Publication</span></h2>
        <form action="<?php echo BASE_URL; ?>/admin/publications/save" method="POST" id="publishForm" onsubmit="return validatePlatforms()">
            <input type="hidden" name="id" id="pubId">
            
            <div class="form-group" id="annonceSelectGroup">
                <label>Choisir l'Annonce</label>
                <select name="annonce_id" id="pubAnnonceId">
                    <?php foreach($annonces as $a): ?>
                        <option value="<?php echo $a['id']; ?>"><?php echo $a['title']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Sélection des plateformes par checkboxes -->
            <div class="form-group" id="platformsGroup">
                <label style="display:block;margin-bottom:10px;font-weight:700;">Plateformes <span style="color:var(--danger);font-size:0.8rem;">(sélectionnez au moins une)</span></label>
                <div style="display:flex;gap:16px;flex-wrap:wrap;">
                    <label class="platform-checkbox-label" id="lbl-facebook">
                        <input type="checkbox" name="platforms[]" value="facebook" id="chk-facebook" class="platform-chk" onchange="syncSinglePlatform()">
                        <i class="fab fa-facebook" style="color:#1877f2;font-size:1.4rem;"></i>
                        <span>Facebook</span>
                    </label>
                    <label class="platform-checkbox-label" id="lbl-whatsapp">
                        <input type="checkbox" name="platforms[]" value="whatsapp" id="chk-whatsapp" class="platform-chk" onchange="syncSinglePlatform()">
                        <i class="fab fa-whatsapp" style="color:#25d366;font-size:1.4rem;"></i>
                        <span>WhatsApp</span>
                    </label>
                    <label class="platform-checkbox-label" id="lbl-tiktok">
                        <input type="checkbox" name="platforms[]" value="tiktok" id="chk-tiktok" class="platform-chk" onchange="syncSinglePlatform()">
                        <i class="fab fa-tiktok" style="color:#000;font-size:1.4rem;"></i>
                        <span>TikTok</span>
                    </label>
                </div>
                <!-- Champ caché pour l'édition d'une publication existante (1 seule plateforme) -->
                <input type="hidden" name="platform" id="pubPlatform">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Date et Heure</label>
                    <input type="datetime-local" name="scheduled_at" id="pubDate" required>
                </div>
                <div class="form-group">
                    <label>Répéter (Jours)</label>
                    <input type="number" name="repeat_days" id="pubRepeat" value="0" min="0" placeholder="0 = une fois">
                </div>
            </div>

            <div class="form-group">
                <label>Texte Publicitaire</label>
                <textarea name="generated_text" id="pubText" rows="6" placeholder="Le texte généré apparaîtra ici..."></textarea>
            </div>

            <button type="submit" class="btn-submit" id="btnSubmit">Valider la programmation</button>
        </form>
    </div>
</div>

<style>
.platform-checkbox-label {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 18px; border: 2px solid #e2e8f0; border-radius: 10px;
    cursor: pointer; transition: all 0.2s; user-select: none; background: #f8fafc;
    font-weight: 600; font-size: 0.9rem;
}
.platform-checkbox-label input[type="checkbox"] { display: none; }
.platform-checkbox-label.checked { border-color: var(--primary); background: #eff6ff; color: var(--primary); }
.platform-checkbox-label.checked i { filter: drop-shadow(0 0 4px currentColor); }
</style>

<script>
// Toggle visuel des checkboxes plateforme
document.querySelectorAll('.platform-chk').forEach(chk => {
    chk.addEventListener('change', () => {
        chk.closest('.platform-checkbox-label').classList.toggle('checked', chk.checked);
    });
});

function syncSinglePlatform() {
    // rien de spécial ici, géré par la boucle ci-dessus
}

function validatePlatforms() {
    const isEdit = document.getElementById('pubId').value !== '';
    if (isEdit) return true; // en édition, la plateforme est dans le champ hidden
    const checked = document.querySelectorAll('.platform-chk:checked');
    if (checked.length === 0) {
        alert('Veuillez sélectionner au moins une plateforme.');
        return false;
    }
    return true;
}

function toggleModal(id) {
    const modal = document.getElementById(id);
    modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
}

function openAddModal() {
    document.getElementById('modalTitle').innerHTML = 'Programmer une <span class="highlight">Publication</span>';
    document.getElementById('pubId').value = '';
    document.getElementById('publishForm').reset();
    document.getElementById('annonceSelectGroup').style.display = 'block';
    document.getElementById('platformsGroup').style.display = 'block';
    // Réinitialiser les checkboxes visuellement
    document.querySelectorAll('.platform-checkbox-label').forEach(l => l.classList.remove('checked'));
    document.querySelectorAll('.platform-chk').forEach(c => c.checked = false);
    document.getElementById('pubPlatform').value = '';
    document.getElementById('btnSubmit').innerText = 'Valider la programmation';
    toggleModal('publishModal');
}

async function editPublication(id) {
    try {
        const response = await fetch('<?php echo BASE_URL; ?>/admin/publications/get?id=' + id);
        const data = await response.json();
        
        document.getElementById('modalTitle').innerHTML = 'Modifier la <span class="highlight">Publication</span>';
        document.getElementById('pubId').value = data.id;

        // En mode édition, on utilise le champ hidden platform et on cache les checkboxes
        document.getElementById('pubPlatform').value = data.platform;
        document.getElementById('platformsGroup').style.display = 'none';
        
        // Format date for input datetime-local
        const date = new Date(data.scheduled_at);
        const formattedDate = date.toISOString().slice(0, 16);
        document.getElementById('pubDate').value = formattedDate;
        
        document.getElementById('pubText').value = data.generated_text;
        document.getElementById('pubRepeat').value = data.repeat_days || 0;
        
        document.getElementById('pubAnnonceId').value = data.annonce_id;
        document.getElementById('annonceSelectGroup').style.display = 'none';
        
        document.getElementById('btnSubmit').innerText = 'Enregistrer les modifications';
        toggleModal('publishModal');
    } catch (error) {
        alert('Erreur lors du chargement des données');
    }
}

function copyToClipboard(btn, text) {
    navigator.clipboard.writeText(text).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => btn.innerHTML = orig, 2000);
    });
}

function publishToTikTok(btn, text) {
    // 1. Copier le texte
    navigator.clipboard.writeText(text).then(() => {
        // 2. Afficher le toast
        const toast = document.getElementById('tiktokToast');
        toast.style.display = 'flex';

        // 3. Ouvrir TikTok après un court délai
        setTimeout(() => {
            window.open('https://www.tiktok.com/upload', '_blank');
        }, 600);

        // 4. Masquer le toast après 5s
        setTimeout(() => { toast.style.display = 'none'; }, 5000);

        // 5. Feedback visuel sur le bouton
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copié !';
        setTimeout(() => btn.innerHTML = orig, 3000);
    }).catch(() => {
        // Fallback si clipboard API non disponible
        window.open('https://www.tiktok.com/upload', '_blank');
        alert('Veuillez copier manuellement le texte de l\'annonce pour le coller sur TikTok.');
    });
}
</script>

<?php include __DIR__ . '/../layout_footer.php'; ?>
