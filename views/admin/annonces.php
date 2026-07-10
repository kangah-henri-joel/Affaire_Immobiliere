<?php include __DIR__ . '/../layout_header.php'; ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="admin-container">
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <main class="admin-content">
        <header class="admin-header">
            <div class="header-flex">
                <h1>Gestion des Annonces</h1>
                <button class="btn-primary" onclick="toggleModal('addAnnonceModal')"><i class="fas fa-plus"></i> Nouvelle Annonce</button>
            </div>
        </header>

        <?php if (!empty($_GET['success']) && $_GET['success'] === 'main_set'): ?>
        <div class="alert alert-success" style="margin-bottom: 20px;"><i class="fas fa-check-circle"></i> Image principale mise à jour avec succès !</div>
        <?php endif; ?>

        <div class="annonce-table-card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Médias / Aperçu</th>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="admin-annonce-list">
                        <?php if(!empty($annonces)): ?>
                            <?php foreach($annonces as $a): ?>
                                <tr>
                                    <td>
                                        <div class="admin-annonce-medias">
                                            <?php 
                                            $annMedias = $mediaByAnnonce[$a['id']] ?? [];
                                            if (!empty($annMedias)): 
                                                foreach ($annMedias as $med): 
                                                    $isMain = $med['is_main'] == 1;
                                            ?>
                                                <div class="admin-media-thumb <?php echo $isMain ? 'is-main' : ''; ?>" title="<?php echo $isMain ? 'Média Principal' : 'Définir comme principal'; ?>">
                                                    <?php if ($med['media_type'] === 'video'): ?>
                                                        <video src="<?php echo BASE_URL . $med['file_path']; ?>" muted style="width:50px; height:50px; object-fit:cover; border-radius:6px;"></video>
                                                        <span class="video-indicator"><i class="fas fa-play"></i></span>
                                                    <?php else: ?>
                                                        <img src="<?php echo BASE_URL . $med['file_path']; ?>" alt="media" style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!$isMain): ?>
                                                        <form action="<?php echo BASE_URL; ?>/annonce/media/set-main" method="POST" class="set-main-form">
                                                            <input type="hidden" name="annonce_id" value="<?php echo $a['id']; ?>">
                                                            <input type="hidden" name="media_id" value="<?php echo $med['id']; ?>">
                                                            <button type="submit" class="btn-set-main" onclick="return confirm('Définir cette image comme miniature principale ?');">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="main-badge-star"><i class="fas fa-star"></i></span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php 
                                                endforeach; 
                                            else:
                                            ?>
                                                <span class="no-media-txt"><i class="fas fa-image"></i> Aucun</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($a['title']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($a['category_name']); ?></td>
                                    <td><strong><?php echo number_format($a['price'], 0, ',', ' '); ?></strong> <span style="font-size:0.8rem">FCFA</span></td>
                                    <td><span class="badge-type <?php echo $a['type']; ?>"><?php echo ucfirst($a['type']); ?></span></td>
                                    <td>
                                        <?php if ($a['status'] === 'brouillon'): ?>
                                            <span class="status-badge status-draft">Brouillon</span>
                                        <?php else: ?>
                                            <span class="status-badge status-published">Publié</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="actions-flex">
                                            <a href="<?php echo BASE_URL; ?>/annonce/<?php echo $a['id']; ?>" class="btn-action-icon btn-view" title="Voir l'annonce publique"><i class="fas fa-eye"></i></a>
                                            <a href="<?php echo BASE_URL; ?>/admin/affiche?annonce_id=<?php echo $a['id']; ?>" class="btn-action-icon btn-affiche" title="Générer une affiche"><i class="fas fa-bullhorn"></i></a>
                                            <a href="<?php echo BASE_URL; ?>/admin/annonces/delete?id=<?php echo $a['id']; ?>" 
                                               class="btn-action-icon btn-delete" 
                                               onclick="return confirm('Êtes-vous sûr de vouloir mettre cette annonce à la corbeille ?');"
                                               title="Supprimer">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" style="text-align:center; padding:40px 0; color:var(--gray);"><i class="fas fa-folder-open" style="font-size:2rem; display:block; margin-bottom:10px;"></i> Aucune annonce trouvée.</td></tr>
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
            <input type="hidden" name="primary_media_index" id="primary_media_index" value="0">
            <div class="form-grid">
                <div class="form-group">
                    <label>Titre</label>
                    <input type="text" name="title" required placeholder="Ex: Splendide Villa 5 pièces">
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
                    <input type="number" name="price" required placeholder="Ex: 45000000">
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
                <textarea name="description" id="desc-input" rows="5" placeholder="Décrivez le bien en détails..."></textarea>
                <button type="button" class="btn-ai" id="btn-ai-trigger" onclick="generateAIMarketing()">
                    <i class="fas fa-robot"></i> Optimiser avec l'IA Marketing
                </button>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Nom du quartier / lieu <span style="color:var(--gray);font-weight:400;">(affiché publiquement)</span></label>
                    <input type="text" name="location_name" id="location_name" placeholder="Ex: Cocody Angré, Plateau, Yopougon...">
                </div>
                <div class="form-group">
                    <label>Médias (Images ou Vidéo - Max 5)</label>
                    <input type="file" name="media[]" accept="image/*,video/*" multiple id="annonce-media-input">
                    <p style="font-size: 0.8rem; color: var(--gray); margin-top: 5px;" id="media-helper-text">
                        Sélectionnez jusqu'à 5 fichiers.
                    </p>
                    <!-- Zone de prévisualisation avec choix du média principal -->
                    <div id="media-previews-container" class="media-previews-container"></div>
                </div>
            </div>

            <!-- Localisation géographique précise -->
            <div class="form-group geo-section" style="margin-top: 10px;">
                <label><i class="fas fa-map-marker-alt" style="color:var(--danger);"></i> Localisation géographique précise</label>

                <!-- Barre de recherche d'adresse -->
                <div class="geo-search-bar">
                    <div class="geo-search-input-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text" id="geoSearchInput" placeholder="Tapez une adresse ou un quartier (ex: Cocody, Abidjan)..." />
                    </div>
                    <button type="button" class="btn-geo-search" onclick="geocodeAddress()">
                        <i class="fas fa-location-arrow"></i> Localiser
                    </button>
                    <button type="button" class="btn-geo-me" onclick="geolocateMe()" title="Utiliser ma position GPS">
                        <i class="fas fa-crosshairs"></i>
                    </button>
                </div>
                <p id="geoStatus" class="geo-status"></p>

                <!-- Carte interactive -->
                <div id="map-picker" style="height: 350px; border-radius: 12px; margin: 10px 0; border: 2px solid var(--border);"></div>
                <p style="font-size:0.78rem; color:var(--gray); margin-bottom:10px;"><i class="fas fa-info-circle"></i> Vous pouvez aussi cliquer directement sur la carte ou déplacer le marqueur.</p>

                <!-- Coordonnées (auto-remplies) -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div>
                        <label style="font-size:0.8rem;">Latitude (auto)</label>
                        <input type="text" name="latitude" id="lat-input" placeholder="Ex: 5.348400" readonly required style="background:#f8fafc;">
                    </div>
                    <div>
                        <label style="font-size:0.8rem;">Longitude (auto)</label>
                        <input type="text" name="longitude" id="lng-input" placeholder="Ex: -4.030500" readonly required style="background:#f8fafc;">
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 20px;">
                <button type="submit" name="status" value="disponible" class="btn-submit" style="margin-top: 0; width: 100%;">🚀 Publier Directement</button>
                <button type="submit" name="status" value="brouillon" class="btn-submit" style="margin-top: 0; width: 100%; background: #64748b;">💾 Stocker (Brouillon)</button>
            </div>
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
/* Géolocalisation */
.geo-search-bar { display: flex; gap: 8px; margin-bottom: 8px; }
.geo-search-input-wrap { flex: 1; display: flex; align-items: center; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0 12px; gap: 8px; background: white; }
.geo-search-input-wrap i { color: #64748b; }
.geo-search-input-wrap input { border: none; outline: none; padding: 11px 0; font-family: inherit; font-size: 0.9rem; width: 100%; }
.btn-geo-search { background: var(--primary); color: white; border: none; border-radius: 8px; padding: 0 16px; cursor: pointer; font-weight: 600; white-space: nowrap; font-size: 0.88rem; display: flex; align-items: center; gap: 6px; }
.btn-geo-me { background: #6366f1; color: white; border: none; border-radius: 8px; padding: 0 14px; cursor: pointer; font-size: 1rem; }
.geo-status { font-size: 0.8rem; margin: 4px 0 8px; min-height: 18px; }
.geo-status.ok { color: #059669; } .geo-status.err { color: #dc2626; } .geo-status.loading { color: #6366f1; }

/* Custom badges and visual highlights */
.badge-type { padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
.badge-type.vente { background: #fee2e2; color: #dc2626; }
.badge-type.location { background: #e0f2fe; color: #0369a1; }
.status-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; }
.status-draft { background: #e2e8f0; color: #475569; }
.status-published { background: #dcfce7; color: #15803d; }
.actions-flex { display: flex; gap: 6px; }
.btn-action-icon { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: 1.5px solid #e2e8f0; color: var(--primary); transition: all 0.2s; text-decoration: none; }
.btn-action-icon:hover { background: #f1f5f9; color: var(--secondary); border-color: var(--secondary); }
.btn-action-icon.btn-delete:hover { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }

/* Media thumbnails display list */
.admin-annonce-medias { display: flex; gap: 6px; overflow-x: auto; max-width: 180px; padding: 4px 0; }
.admin-media-thumb { position: relative; width: 50px; height: 50px; border-radius: 6px; cursor: pointer; flex-shrink: 0; }
.admin-media-thumb.is-main { border: 2.5px solid var(--secondary); box-shadow: 0 0 8px rgba(245,158,11,0.5); }
.video-indicator { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0,0,0,0.6); color: white; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.6rem; }
.main-badge-star { position: absolute; top: -5px; right: -5px; background: var(--secondary); color: var(--primary); width: 16px; height: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.6rem; }
.btn-set-main { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.7); color: white; border: none; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; opacity: 0; transition: opacity 0.2s; cursor: pointer; }
.admin-media-thumb:hover .btn-set-main { opacity: 1; }
.no-media-txt { font-size: 0.75rem; color: var(--gray); display: inline-flex; align-items: center; gap: 4px; }

/* Upload preview styling */
.media-previews-container { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px; }
.media-preview-item { position: relative; width: 70px; height: 70px; border-radius: 8px; border: 2px solid #e2e8f0; overflow: hidden; cursor: pointer; }
.media-preview-item.selected-primary { border-color: var(--secondary); box-shadow: 0 0 6px rgba(245,158,11,0.5); }
.media-preview-item img, .media-preview-item video { width: 100%; height: 100%; object-fit: cover; }
.primary-indicator-label { position: absolute; bottom: 0; left: 0; width: 100%; background: rgba(245,158,11,0.85); color: var(--primary); font-size: 0.58rem; font-weight: 800; text-align: center; text-transform: uppercase; padding: 2px 0; }
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
        if (map) { map.invalidateSize(); return; }

        const defaultLat = 5.3484, defaultLng = -4.0305;
        map = L.map('map-picker').setView([defaultLat, defaultLng], 12);

        // Deux fonds de carte
        const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap', maxZoom: 19 });
        const sat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '© Esri', maxZoom: 19 });
        osm.addTo(map);
        L.control.layers({ 'Plan': osm, 'Satellite': sat }, {}, { position: 'topright' }).addTo(map);

        marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
        marker.bindPopup('Déplacez ce marqueur pour ajuster la position').openPopup();

        function updateCoords(lat, lng) {
            document.getElementById('lat-input').value = lat.toFixed(6);
            document.getElementById('lng-input').value = lng.toFixed(6);
        }

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoords(e.latlng.lat, e.latlng.lng);
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });

        marker.on('dragend', function(e) {
            const ll = e.target.getLatLng();
            updateCoords(ll.lat, ll.lng);
            reverseGeocode(ll.lat, ll.lng);
        });
    }

    // Geocoding : adresse → coordonnées
    function geocodeAddress() {
        const query = document.getElementById('geoSearchInput').value.trim();
        if (!query) return;
        const status = document.getElementById('geoStatus');
        status.className = 'geo-status loading';
        status.textContent = '🔍 Recherche en cours...';

        fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query + ', Côte d\'Ivoire')}&format=json&limit=1`, {
            headers: { 'Accept-Language': 'fr', 'User-Agent': 'ImmoAffaire/1.0' }
        })
        .then(r => r.json())
        .then(results => {
            if (results.length > 0) {
                const lat = parseFloat(results[0].lat);
                const lon = parseFloat(results[0].lon);
                const name = results[0].display_name;
                map.flyTo([lat, lon], 16, { duration: 1.2 });
                marker.setLatLng([lat, lon]);
                document.getElementById('lat-input').value = lat.toFixed(6);
                document.getElementById('lng-input').value = lon.toFixed(6);
                // Auto-remplir le nom du lieu si vide
                const locField = document.getElementById('location_name');
                if (!locField.value) locField.value = results[0].display_name.split(',')[0];
                status.className = 'geo-status ok';
                status.textContent = '✅ Lieu trouvé : ' + name.split(',').slice(0,2).join(',');
            } else {
                status.className = 'geo-status err';
                status.textContent = '❌ Aucun résultat. Essayez un nom de quartier ou de ville.';
            }
        })
        .catch(() => { status.className = 'geo-status err'; status.textContent = '❌ Erreur réseau.'; });
    }

    // Geocoding inverse : coordonnées → nom
    function reverseGeocode(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`, {
            headers: { 'Accept-Language': 'fr', 'User-Agent': 'ImmoAffaire/1.0' }
        })
        .then(r => r.json())
        .then(data => {
            const status = document.getElementById('geoStatus');
            status.className = 'geo-status ok';
            status.textContent = '📍 ' + (data.display_name || 'Position sélectionnée');
        })
        .catch(() => {});
    }

    // Géolocalisation GPS du navigateur
    function geolocateMe() {
        if (!navigator.geolocation) return alert('Géolocalisation non supportée.');
        const status = document.getElementById('geoStatus');
        status.className = 'geo-status loading';
        status.textContent = '📡 Obtention de votre position GPS...';
        navigator.geolocation.getCurrentPosition(pos => {
            const lat = pos.coords.latitude, lng = pos.coords.longitude;
            map.flyTo([lat, lng], 16, { duration: 1.2 });
            marker.setLatLng([lat, lng]);
            document.getElementById('lat-input').value = lat.toFixed(6);
            document.getElementById('lng-input').value = lng.toFixed(6);
            reverseGeocode(lat, lng);
        }, () => { status.className = 'geo-status err'; status.textContent = '❌ Impossible d\'obtenir votre position.'; });
    }

    // Recherche via Entrée
    document.addEventListener('DOMContentLoaded', () => {
        const geoInput = document.getElementById('geoSearchInput');
        if (geoInput) geoInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); geocodeAddress(); } });
        
        const mediaInput = document.getElementById('annonce-media-input');
        const previewsContainer = document.getElementById('media-previews-container');
        const primaryMediaIndexInput = document.getElementById('primary_media_index');

        if (mediaInput) {
            mediaInput.addEventListener('change', function() {
                if (this.files.length > 5) {
                    alert('Vous pouvez sélectionner au maximum 5 photos/médias.');
                    this.value = '';
                    previewsContainer.innerHTML = '';
                    primaryMediaIndexInput.value = '0';
                    return;
                }

                previewsContainer.innerHTML = '';
                primaryMediaIndexInput.value = '0'; // Par défaut le premier

                Array.from(this.files).forEach((file, index) => {
                    const reader = new FileReader();
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'media-preview-item' + (index === 0 ? ' selected-primary' : '');
                    itemDiv.dataset.index = index;

                    itemDiv.addEventListener('click', function() {
                        document.querySelectorAll('.media-preview-item').forEach(el => el.classList.remove('selected-primary'));
                        this.classList.add('selected-primary');
                        primaryMediaIndexInput.value = this.dataset.index;
                    });

                    reader.onload = function(e) {
                        if (file.type.startsWith('video/')) {
                            const videoEl = document.createElement('video');
                            videoEl.src = e.target.result;
                            videoEl.muted = true;
                            itemDiv.appendChild(videoEl);
                        } else {
                            const imgEl = document.createElement('img');
                            imgEl.src = e.target.result;
                            itemDiv.appendChild(imgEl);
                        }

                        if (index === 0) {
                            const label = document.createElement('span');
                            label.className = 'primary-indicator-label';
                            label.innerText = 'Principal';
                            itemDiv.appendChild(label);
                        }
                    };

                    reader.readAsDataURL(file);
                    previewsContainer.appendChild(itemDiv);
                });
            });
        }
    });

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
