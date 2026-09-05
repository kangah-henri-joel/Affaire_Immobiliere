<?php include __DIR__ . '/../layout_header.php'; ?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/lib/leaflet/leaflet.css" />
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/lib/leaflet-markercluster/MarkerCluster.css" />
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/lib/leaflet-markercluster/MarkerCluster.Default.css" />

<section class="map-view-section">
    <div class="map-container-full">
        <div id="global-map"></div>

        <!-- Panneau latéral -->
        <div class="map-side-panel">
            <div class="map-panel-header">
                <h3>Biens sur la carte <span class="highlight" id="countBadge"><?php echo count($annonces); ?></span></h3>
                <p>Cliquez sur un marqueur pour voir les détails.</p>
            </div>

            <!-- Recherche par lieu -->
            <div class="map-search-box">
                <div class="map-search-input-wrap">
                    <i class="fas fa-search"></i>
                    <input type="text" id="mapSearchInput" placeholder="Rechercher un lieu, quartier..." />
                </div>
                <button onclick="searchLocation()" class="btn-map-search">
                    <i class="fas fa-location-arrow"></i>
                </button>
            </div>

            <!-- Filtres -->
            <div class="map-filters">
                <button class="filter-chip active" data-type="all" onclick="filterMarkers('all', this)">Tous</button>
                <button class="filter-chip" data-type="vente" onclick="filterMarkers('vente', this)">Vente</button>
                <button class="filter-chip" data-type="location" onclick="filterMarkers('location', this)">Location</button>
            </div>

            <!-- Liste des biens -->
            <div class="map-annonce-list" id="annonceList">
                <?php foreach($annonces as $a): if(!$a['latitude'] || !$a['longitude']) continue;
                    $imgUrl = SiteUrl::media($a['image_path'] ?? null);
                    $isVideo = ($a['media_type'] ?? 'image') === 'video';
                ?>
                <div class="map-annonce-item" data-id="<?php echo $a['id']; ?>" data-type="<?php echo $a['type']; ?>" onclick="flyToAnnonce(<?php echo $a['id']; ?>)">
                    <div class="map-item-img">
                        <?php if ($isVideo): ?>
                            <div class="map-item-video-thumb">
                                <video src="<?php echo $imgUrl; ?>" autoplay muted loop playsinline></video>
                                <i class="fas fa-play-circle video-play-icon"></i>
                            </div>
                        <?php else: ?>
                            <img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($a['title']); ?>" loading="lazy" onerror="this.src='<?php echo SiteUrl::media(null); ?>'">
                        <?php endif; ?>
                    </div>
                    <div class="map-item-info">
                        <strong><?php echo htmlspecialchars($a['title']); ?></strong>
                        <span class="map-item-price"><?php echo number_format($a['price'], 0, ',', ' '); ?> FCFA</span>
                        <span class="map-item-loc"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($a['location_name']); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Bouton ma position -->
            <button class="btn-my-location" onclick="goToMyLocation()" title="Ma position">
                <i class="fas fa-crosshairs"></i> Ma position
            </button>
        </div>
    </div>
</section>

<script src="<?php echo BASE_URL; ?>/assets/lib/leaflet/leaflet.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/lib/leaflet-markercluster/leaflet.markercluster.js"></script>
<script>
<?php
// Préparer les données de la carte avec les URLs d'image complètes via SiteUrl
$annoncesMapData = array_values(array_filter($annonces, fn($a) => $a['latitude'] && $a['longitude']));
foreach ($annoncesMapData as &$a) {
    $a['image_url'] = SiteUrl::media($a['image_path'] ?? null);
    $a['is_video'] = ($a['media_type'] ?? 'image') === 'video';
}
unset($a);
?>
const annoncesData = <?php echo json_encode($annoncesMapData); ?>;
const BASE_URL = '<?php echo BASE_URL; ?>';
const PLACEHOLDER_URL = '<?php echo SiteUrl::media(null); ?>';

// Map init — centré sur Abidjan
const map = L.map('global-map', { zoomControl: false }).setView([5.3484, -4.0305], 12);

// Tuiles avec deux options (bascule)
const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://www.openstreetmap.org">OpenStreetMap</a>',
    maxZoom: 19
});

const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: '© Esri — Source: Esri, Maxar, Earthstar Geographics',
    maxZoom: 19
});

osmLayer.addTo(map);

// Contrôles
L.control.zoom({ position: 'bottomright' }).addTo(map);
L.control.layers(
    { 'Plan (OSM)': osmLayer, 'Satellite': satelliteLayer },
    {},
    { position: 'bottomright' }
).addTo(map);

// Icône personnalisée
function makeIcon(type) {
    const color = type === 'vente' ? '#f59e0b' : '#6366f1';
    const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="36" height="44" viewBox="0 0 36 44">
        <path d="M18 0C8.06 0 0 8.06 0 18c0 13.5 18 26 18 26S36 31.5 36 18C36 8.06 27.94 0 18 0z" fill="${color}"/>
        <circle cx="18" cy="18" r="10" fill="white"/>
        <text x="18" y="23" text-anchor="middle" font-size="12" fill="${color}" font-family="Arial" font-weight="bold">${type === 'vente' ? 'V' : 'L'}</text>
    </svg>`;
    return L.divIcon({
        html: svg,
        iconSize: [36, 44],
        iconAnchor: [18, 44],
        popupAnchor: [0, -44],
        className: ''
    });
}

// Cluster group
const markers = L.markerClusterGroup({ showCoverageOnHover: false, maxClusterRadius: 60 });
const markerMap = {}; // id → marker

annoncesData.forEach(a => {
    const marker = L.marker([parseFloat(a.latitude), parseFloat(a.longitude)], { icon: makeIcon(a.type) });

    const mediaThumbnail = a.is_video
        ? `<div style="position:relative;width:100%;height:130px;background:#0f172a;border-radius:8px 8px 0 0;overflow:hidden;display:flex;align-items:center;justify-content:center;">
               <video src="${a.image_url}" autoplay muted loop playsinline style="width:100%;height:130px;object-fit:cover;display:block;"></video>
               <i class="fas fa-play-circle" style="position:absolute;font-size:2rem;color:#f59e0b;pointer-events:none;text-shadow:0 2px 4px rgba(0,0,0,0.6);"></i>
           </div>`
        : `<img src="${a.image_url}" alt="${a.title}" onerror="this.src='${PLACEHOLDER_URL}'" style="width:100%;height:130px;object-fit:cover;border-radius:8px 8px 0 0;display:block;">`;

    const popup = `
        <div class="leaflet-popup-custom">
            ${mediaThumbnail}
            <div class="popup-body">
                <span class="popup-type ${a.type}">${a.type === 'vente' ? 'Vente' : 'Location'}</span>
                <h4>${a.title}</h4>
                <p class="popup-price">${new Intl.NumberFormat('fr-FR').format(a.price)} FCFA</p>
                <p class="popup-loc"><i class="fas fa-map-marker-alt"></i> ${a.location_name || ''}</p>
                <a href="${BASE_URL}/annonce/${a.id}" class="popup-btn">Voir les détails →</a>
            </div>
        </div>`;
    marker.bindPopup(popup, { maxWidth: 240, minWidth: 220 });
    marker._annonceData = a;
    markers.addLayer(marker);
    markerMap[a.id] = marker;
});

map.addLayer(markers);

// Voler vers une annonce depuis la liste latérale
function flyToAnnonce(id) {
    const marker = markerMap[id];
    if (!marker) return;
    // Ouvrir le cluster si nécessaire
    markers.zoomToShowLayer(marker, () => {
        map.flyTo(marker.getLatLng(), 16, { duration: 1.2 });
        setTimeout(() => marker.openPopup(), 1300);
    });
    // Highlight item in list
    document.querySelectorAll('.map-annonce-item').forEach(el => el.classList.remove('active'));
    const el = document.querySelector(`.map-annonce-item[data-id="${id}"]`);
    if (el) { el.classList.add('active'); el.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); }
}

// Mettre à jour la liste latérale quand on clique sur un marqueur
map.on('popupopen', e => {
    const data = e.popup._source?._annonceData;
    if (!data) return;
    document.querySelectorAll('.map-annonce-item').forEach(el => el.classList.remove('active'));
    const el = document.querySelector(`.map-annonce-item[data-id="${data.id}"]`);
    if (el) { el.classList.add('active'); el.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); }
});

// Filtre par type
function filterMarkers(type, btn) {
    document.querySelectorAll('.filter-chip').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    markers.clearLayers();
    let count = 0;
    annoncesData.forEach(a => {
        if (type === 'all' || a.type === type) {
            markers.addLayer(markerMap[a.id]);
            count++;
        }
    });

    // Filtre la liste latérale
    document.querySelectorAll('.map-annonce-item').forEach(el => {
        el.style.display = (type === 'all' || el.dataset.type === type) ? 'flex' : 'none';
    });
    document.getElementById('countBadge').textContent = count;
}

// Recherche de lieu via Nominatim (OpenStreetMap geocoding)
function searchLocation() {
    const query = document.getElementById('mapSearchInput').value.trim();
    if (!query) return;

    fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&limit=1`, {
        headers: { 'Accept-Language': 'fr', 'User-Agent': 'ImmoAffaire/1.0' }
    })
    .then(r => r.json())
    .then(results => {
        if (results.length > 0) {
            const { lat, lon, display_name } = results[0];
            map.flyTo([lat, lon], 14, { duration: 1.5 });
            L.popup({ closeButton: true })
                .setLatLng([lat, lon])
                .setContent(`<b>${display_name.split(',')[0]}</b><br><small>${display_name}</small>`)
                .openOn(map);
        } else {
            alert('Aucun résultat trouvé. Essayez un nom de quartier ou de ville.');
        }
    })
    .catch(() => alert('Erreur de recherche. Vérifiez votre connexion.'));
}

// Recherche via touche Entrée
document.getElementById('mapSearchInput').addEventListener('keydown', e => {
    if (e.key === 'Enter') searchLocation();
});

// Géolocalisation
function goToMyLocation() {
    if (!navigator.geolocation) { alert('Géolocalisation non supportée.'); return; }
    navigator.geolocation.getCurrentPosition(pos => {
        const { latitude, longitude } = pos.coords;
        map.flyTo([latitude, longitude], 15, { duration: 1.5 });
        L.circle([latitude, longitude], { radius: 50, color: '#6366f1', fillOpacity: 0.3 }).addTo(map);
        L.marker([latitude, longitude], {
            icon: L.divIcon({ html: '<div style="background:#6366f1;width:16px;height:16px;border-radius:50%;border:3px solid white;box-shadow:0 0 10px rgba(99,102,241,0.8);"></div>', iconAnchor:[8,8], className:'' })
        }).addTo(map).bindPopup('Vous êtes ici').openPopup();
    }, () => alert('Impossible d\'obtenir votre position.'));
}
</script>

<style>
.map-view-section { height: calc(100vh - 80px); overflow: hidden; }
.map-container-full { display: flex; height: 100%; position: relative; }

#global-map {
    flex: 1;
    height: 100%;
    z-index: 1;
}

/* Panneau latéral */
.map-side-panel {
    width: 320px;
    min-width: 320px;
    background: white;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: -4px 0 20px rgba(0,0,0,0.1);
    z-index: 10;
}

.map-panel-header {
    padding: 20px 20px 10px;
    border-bottom: 1px solid var(--border);
}
.map-panel-header h3 { font-size: 1.1rem; font-weight: 800; color: var(--primary); }
.map-panel-header .highlight { background: var(--secondary); color: var(--primary); padding: 2px 8px; border-radius: 20px; font-size: 0.9rem; margin-left: 5px; }
.map-panel-header p { font-size: 0.8rem; color: var(--gray); margin-top: 4px; }

.map-search-box {
    display: flex;
    gap: 8px;
    padding: 12px 15px;
    border-bottom: 1px solid var(--border);
}
.map-search-input-wrap {
    flex: 1;
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 0 12px;
    gap: 8px;
}
.map-search-input-wrap i { color: var(--gray); font-size: 0.85rem; }
.map-search-input-wrap input { border: none; background: none; outline: none; padding: 10px 0; font-family: inherit; font-size: 0.88rem; width: 100%; }
.btn-map-search { background: var(--primary); color: white; border: none; border-radius: 10px; padding: 0 14px; cursor: pointer; font-size: 1rem; transition: var(--transition); }
.btn-map-search:hover { background: var(--secondary); color: var(--primary); }

.map-filters {
    display: flex;
    gap: 8px;
    padding: 12px 15px;
    border-bottom: 1px solid var(--border);
}
.filter-chip {
    border: 2px solid var(--border);
    background: white;
    border-radius: 20px;
    padding: 5px 14px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    color: var(--gray);
}
.filter-chip.active, .filter-chip:hover { background: var(--primary); border-color: var(--primary); color: white; }

/* Liste des biens */
.map-annonce-list {
    flex: 1;
    overflow-y: auto;
    padding: 6px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.map-annonce-item {
    display: flex;
    gap: 8px;
    align-items: center;
    padding: 6px 8px;
    border-radius: 10px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: var(--transition);
    background: #f8fafc;
}
.map-annonce-item:hover, .map-annonce-item.active {
    border-color: var(--secondary);
    background: #fffbeb;
}
.map-item-img { width: 56px; height: 56px; border-radius: 8px; overflow: hidden; flex-shrink: 0; position: relative; background: #0f172a; }
.map-item-img img, .map-item-img video { width: 100%; height: 100%; object-fit: cover; }
.map-item-video-thumb { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; position: relative; background: #0f172a; }
.map-item-video-thumb .video-play-icon { position: absolute; font-size: 1rem; color: #f59e0b; pointer-events: none; z-index: 2; text-shadow: 0 2px 4px rgba(0,0,0,0.6); }
.map-item-info { display: flex; flex-direction: column; gap: 3px; min-width: 0; }
.map-item-info strong { font-size: 0.85rem; font-weight: 700; color: var(--primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.map-item-price { font-size: 0.82rem; font-weight: 700; color: var(--secondary); }
.map-item-loc { font-size: 0.75rem; color: var(--gray); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.map-item-loc i { color: var(--danger); }

.btn-my-location {
    margin: 10px;
    background: #6366f1;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 12px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 0.9rem;
    transition: var(--transition);
}
.btn-my-location:hover { background: #4f46e5; }

/* Popup Leaflet personnalisé */
.leaflet-popup-custom { width: 230px; font-family: 'Outfit', sans-serif; }
.leaflet-popup-custom img { width: 100%; height: 130px; object-fit: cover; border-radius: 8px 8px 0 0; display: block; }
.popup-body { padding: 12px; }
.popup-type { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; padding: 3px 10px; border-radius: 20px; }
.popup-type.vente { background: #fef3c7; color: #92400e; }
.popup-type.location { background: #ede9fe; color: #4c1d95; }
.popup-body h4 { font-size: 0.9rem; font-weight: 700; margin: 8px 0 4px; color: var(--primary); }
.popup-price { font-size: 1rem; font-weight: 800; color: var(--secondary); margin: 4px 0; }
.popup-loc { font-size: 0.78rem; color: var(--gray); margin-bottom: 10px; }
.popup-btn { display: block; text-align: center; background: var(--primary); color: white; padding: 8px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; text-decoration: none; transition: var(--transition); }
.popup-btn:hover { background: var(--secondary); color: var(--primary); }

.leaflet-popup-content-wrapper { border-radius: 12px !important; padding: 0 !important; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.2) !important; }
.leaflet-popup-content { margin: 0 !important; }

/* Responsive */
@media (max-width: 768px) {
    .map-view-section { height: calc(100vh - 80px); flex-direction: column; }
    .map-container-full { flex-direction: column; }
    #global-map { height: 55vh; }
    .map-side-panel { width: 100%; min-width: unset; height: 45vh; flex-direction: column; box-shadow: 0 -4px 20px rgba(0,0,0,0.1); }
    .map-annonce-list { flex-direction: row; flex-wrap: nowrap; overflow-x: auto; overflow-y: hidden; padding: 10px; }
    .map-annonce-item { min-width: 200px; flex-direction: column; align-items: flex-start; }
    .map-item-img { width: 100%; height: 80px; }
    .btn-my-location { display: none; }
}
</style>

<?php include __DIR__ . '/../layout_footer.php'; ?>
