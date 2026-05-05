<?php include __DIR__ . '/../layout_header.php'; ?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<section class="map-view-section">
    <div class="map-container-full">
        <div id="global-map" style="height: calc(100vh - 80px); width: 100%;"></div>
        
        <div class="map-overlay-search">
            <div class="container">
                <div class="search-compact">
                    <h3>Explorez les biens <span class="highlight">sur la carte</span></h3>
                    <p><?php echo count($annonces); ?> biens trouvés à Abidjan et alentours.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Center on Abidjan
        const map = L.map('global-map').setView([5.3484, -4.0305], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        const annonces = <?php echo json_encode($annonces); ?>;
        
        annonces.forEach(a => {
            if (a.latitude && a.longitude) {
                const marker = L.marker([a.latitude, a.longitude]).addTo(map);
                const popupContent = `
                    <div class="map-popup">
                        <img src="<?php echo BASE_URL; ?>${a.image_path || '/assets/images/placeholder.jpg'}" style="width:100%; border-radius:8px; height:100px; object-fit:cover;">
                        <h4 style="margin:10px 0 5px;">${a.title}</h4>
                        <p style="color:var(--secondary); font-weight:bold; margin-bottom:10px;">${new Intl.NumberFormat().format(a.price)} FCFA</p>
                        <a href="<?php echo BASE_URL; ?>/annonce/${a.id}" class="btn-sm" style="display:block; text-align:center; background:var(--primary); color:white; border-radius:5px; padding:5px;">Voir détails</a>
                    </div>
                `;
                marker.bindPopup(popupContent);
            }
        });
    });
</script>

<style>
.map-view-section { position: relative; margin-top: -20px; }
.map-container-full { position: relative; }
.map-overlay-search { position: absolute; top: 20px; left: 20px; z-index: 1000; background: rgba(255,255,255,0.9); padding: 20px; border-radius: 15px; box-shadow: var(--shadow); width: 300px; }
.map-popup { width: 200px; font-family: 'Outfit', sans-serif; }
.leaflet-popup-content-wrapper { border-radius: 12px; padding: 5px; }
</style>

<?php include __DIR__ . '/../layout_footer.php'; ?>
