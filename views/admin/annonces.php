<?php include __DIR__ . '/../layout_header.php'; ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="admin-container">
    <aside class="admin-sidebar">
        <ul>
            <li><a href="<?php echo BASE_URL; ?>/admin"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/annonces" class="active"><i class="fas fa-home"></i> Annonces</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/publications"><i class="fas fa-bullhorn"></i> Publications</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/leads"><i class="fas fa-envelope"></i> Leads</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/consultants"><i class="fas fa-users"></i> Consultants</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/settings"><i class="fas fa-cogs"></i> Entreprise</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/profil"><i class="fas fa-user-circle"></i> Profil</a></li>
            <li><a href="<?php echo BASE_URL; ?>/logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </aside>
    
    <main class="admin-content">
        <header class="admin-header">
            <div class="header-flex">
                <h1>Gestion des Annonces</h1>
                <button class="btn-primary" onclick="toggleModal('addAnnonceModal')"><i class="fas fa-plus"></i> Nouvelle Annonce</button>
            </div>
        </header>

        <div class="annonce-table-card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="admin-annonce-list">
                        <?php if(!empty($annonces)): ?>
                            <?php foreach($annonces as $a): ?>
                                <tr>
                                    <td><?php echo $a['title']; ?></td>
                                    <td><?php echo $a['category_name']; ?></td>
                                    <td><?php echo number_format($a['price'], 0, ',', ' '); ?> FCFA</td>
                                    <td><?php echo ucfirst($a['type']); ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/annonce/<?php echo $a['id']; ?>" class="btn-sm"><i class="fas fa-eye"></i></a>
                                        <button class="btn-sm btn-delete"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align:center">Aucune annonce trouvée.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal Ajout Annonce -->
<div id="addAnnonceModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="toggleModal('addAnnonceModal')">&times;</span>
        <h2>Ajouter une Annonce</h2>
        <form id="addAnnonceForm" action="<?php echo BASE_URL; ?>/admin/annonces/save" method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-group">
                    <label>Titre</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Catégorie</label>
                    <select name="category_id">
                        <option value="1">Terrain</option>
                        <option value="2">Maison</option>
                        <option value="3">Studio</option>
                        <option value="4">Magasin</option>
                        <option value="5">Véhicule</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Prix (FCFA)</label>
                    <input type="number" name="price" required>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="type">
                        <option value="vente">Vente</option>
                        <option value="location">Location</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="desc-input" rows="5"></textarea>
                <button type="button" class="btn-ai" id="btn-ai-trigger" onclick="generateAIMarketing()">
                    <i class="fas fa-robot"></i> Optimiser avec l'IA Marketing
                </button>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Localisation (Nom)</label>
                    <input type="text" name="location_name" placeholder="Ex: Cocody Angré">
                </div>
                <div class="form-group">
                    <label>Contact WhatsApp</label>
                    <input type="text" name="whatsapp_contact" placeholder="+225...">
                </div>
                <div class="form-group">
                    <label>Média (Image ou Vidéo)</label>
                    <input type="file" name="media" accept="image/*,video/*">
                    <p style="font-size: 0.8rem; color: var(--gray); margin-top: 5px;">Images (.jpg, .png) ou Vidéos (.mp4, .mov)</p>
                </div>
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <label>Localisation (Cliquez sur la carte pour placer le marqueur)</label>
                <div id="map-picker" style="height: 300px; border-radius: 10px; margin-bottom: 15px;"></div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <input type="text" name="latitude" id="lat-input" placeholder="Latitude" readonly required>
                    <input type="text" name="longitude" id="lng-input" placeholder="Longitude" readonly required>
                </div>
            </div>

            <button type="submit" class="btn-submit">Publier l'annonce</button>
        </form>
    </div>
</div>

<style>
.header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.annonce-table-card { background: var(--white); border-radius: 12px; box-shadow: var(--shadow); overflow: hidden; }
.modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto; }
.modal-content { background: var(--white); margin: 2% auto; padding: 30px; width: 70%; border-radius: 15px; }
.close { float: right; font-size: 1.5rem; cursor: pointer; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
.btn-ai { background: #6366f1; color: white; border: none; padding: 10px 20px; border-radius: 8px; margin-top: 10px; cursor: pointer; font-weight: 600; }
.btn-submit { background: var(--primary); color: white; border: none; padding: 15px; border-radius: 8px; width: 100%; font-weight: 700; cursor: pointer; margin-top: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark); }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit; }
</style>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map, marker;
    
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
        if (modal.style.display === 'block' && id === 'addAnnonceModal') {
            setTimeout(initMap, 300);
        }
    }

    function initMap() {
        if (map) {
            map.invalidateSize();
            return;
        }

        const defaultLat = 5.3484;
        const defaultLng = -4.0305;

        map = L.map('map-picker').setView([defaultLat, defaultLng], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);

        function updateCoords(lat, lng) {
            document.getElementById('lat-input').value = lat.toFixed(6);
            document.getElementById('lng-input').value = lng.toFixed(6);
        }

        updateCoords(defaultLat, defaultLng);

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoords(e.latlng.lat, e.latlng.lng);
        });

        marker.on('dragend', function(e) {
            updateCoords(e.target.getLatLng().lat, e.target.getLatLng().lng);
        });
    }

    async function generateAIMarketing() {
        const descInput = document.getElementById('desc-input');
        const desc = descInput.value;
        if(!desc) return alert('Veuillez entrer une description de base.');
        
        const btn = document.getElementById('btn-ai-trigger');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération en cours...';
        btn.disabled = true;
        
        try {
            const response = await fetch('<?php echo BASE_URL; ?>/api/ai/generate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'description=' + encodeURIComponent(desc)
            });
            const data = await response.json();
            
            if (data.generated_text) {
                if (confirm('L\'IA a généré un texte optimisé. Voulez-vous l\'utiliser ?')) {
                    descInput.value = data.generated_text;
                }
            }
        } catch (e) {
            alert('Erreur: Le service IA est injoignable.');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }
</script>

<?php include __DIR__ . '/../layout_footer.php'; ?>
