<?php include __DIR__ . '/../layout_header.php'; ?>

<div class="admin-container">
    <aside class="admin-sidebar">
        <ul>
            <li><a href="<?php echo BASE_URL; ?>/admin"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/annonces"><i class="fas fa-home"></i> Annonces</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/publications" class="active"><i class="fas fa-bullhorn"></i> Publications</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/leads"><i class="fas fa-envelope"></i> Leads</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/consultants"><i class="fas fa-users"></i> Consultants</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/settings"><i class="fas fa-cogs"></i> Entreprise</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/profil"><i class="fas fa-user-circle"></i> Profil</a></li>
            <li><a href="<?php echo BASE_URL; ?>/logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </aside>
    
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
                                        <div style="display: flex; gap: 5px;">
                                            <?php if($p['platform'] == 'whatsapp'): ?>
                                                <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($p['generated_text']); ?>" target="_blank" class="btn-sm" style="color: #25d366;" title="Publier sur WhatsApp">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                            <?php elseif($p['platform'] == 'facebook'): ?>
                                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(BASE_URL . '/annonce/' . $p['annonce_id']); ?>&quote=<?php echo urlencode($p['generated_text']); ?>" target="_blank" class="btn-sm" style="color: #1877f2;" title="Partager sur Facebook">
                                                    <i class="fab fa-facebook"></i>
                                                </a>
                                            <?php endif; ?>
                                            
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

<!-- Modal Publication (Unique pour Ajout/Edit) -->
<div id="publishModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="toggleModal('publishModal')">&times;</span>
        <h2 id="modalTitle">Programmer une <span class="highlight">Publication</span></h2>
        <form action="<?php echo BASE_URL; ?>/admin/publications/save" method="POST" id="publishForm">
            <input type="hidden" name="id" id="pubId">
            
            <div class="form-group" id="annonceSelectGroup">
                <label>Choisir l'Annonce</label>
                <select name="annonce_id" id="pubAnnonceId">
                    <?php foreach($annonces as $a): ?>
                        <option value="<?php echo $a['id']; ?>"><?php echo $a['title']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Plateforme</label>
                    <select name="platform" id="pubPlatform" required>
                        <option value="facebook">Facebook</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="tiktok">TikTok</option>
                    </select>
                </div>
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

<script>
function toggleModal(id) {
    const modal = document.getElementById(id);
    modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
}

function openAddModal() {
    document.getElementById('modalTitle').innerHTML = 'Programmer une <span class="highlight">Publication</span>';
    document.getElementById('pubId').value = '';
    document.getElementById('publishForm').reset();
    document.getElementById('annonceSelectGroup').style.display = 'block';
    document.getElementById('btnSubmit').innerText = 'Valider la programmation';
    toggleModal('publishModal');
}

async function editPublication(id) {
    try {
        const response = await fetch('<?php echo BASE_URL; ?>/admin/publications/get?id=' + id);
        const data = await response.json();
        
        document.getElementById('modalTitle').innerHTML = 'Modifier la <span class="highlight">Publication</span>';
        document.getElementById('pubId').value = data.id;
        document.getElementById('pubPlatform').value = data.platform;
        
        // Format date for input datetime-local
        const date = new Date(data.scheduled_at);
        const formattedDate = date.toISOString().slice(0, 16);
        document.getElementById('pubDate').value = formattedDate;
        
        document.getElementById('pubText').value = data.generated_text;
        document.getElementById('pubRepeat').value = data.repeat_days || 0;
        
        // Hide annonce select on edit to avoid complexity, but keep value
        document.getElementById('pubAnnonceId').value = data.annonce_id;
        document.getElementById('annonceSelectGroup').style.display = 'none';
        
        document.getElementById('btnSubmit').innerText = 'Enregistrer les modifications';
        toggleModal('publishModal');
    } catch (error) {
        alert('Erreur lors du chargement des données');
    }
}
</script>

<?php include __DIR__ . '/../layout_footer.php'; ?>
