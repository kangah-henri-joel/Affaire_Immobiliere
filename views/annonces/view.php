<?php include __DIR__ . '/../layout_header.php'; ?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<section class="annonce-detail">
    <div class="container">
        <div class="detail-grid">
            <div class="main-content">
                <div class="media-carousel-container">
                    <div class="media-gallery" id="mediaGallery">
                        <?php if (!empty($media)): ?>
                            <?php foreach ($media as $index => $item): ?>
                                <div class="media-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <?php if ($item['media_type'] === 'video'): ?>
                                        <video controls class="main-media">
                                            <source src="<?php echo BASE_URL . $item['file_path']; ?>" type="video/mp4">
                                        </video>
                                    <?php else: ?>
                                        <img src="<?php echo BASE_URL . $item['file_path']; ?>" alt="<?php echo $annonce['title']; ?>" class="main-media">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <img src="<?php echo BASE_URL; ?>/assets/images/placeholder.jpg" class="main-media">
                        <?php endif; ?>
                    </div>
                    <?php if (count($media) > 1): ?>
                        <button class="carousel-btn prev" onclick="moveSlide(-1)"><i class="fas fa-chevron-left"></i></button>
                        <button class="carousel-btn next" onclick="moveSlide(1)"><i class="fas fa-chevron-right"></i></button>
                        <div class="carousel-dots" id="carouselDots">
                            <?php foreach ($media as $index => $item): ?>
                                <span class="dot <?php echo $index === 0 ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $index; ?>)"></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="description-box">
                    <h1><?php echo $annonce['title']; ?></h1>
                    <p class="price-big"><?php echo number_format($annonce['price'], 0, ',', ' '); ?> FCFA</p>
                    <div class="meta-info">
                        <span><i class="fas fa-tag"></i> <?php echo $annonce['category_name']; ?></span>
                        <span><i class="fas fa-map-marker-alt"></i> <?php echo $annonce['location_name']; ?></span>
                        <span><i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($annonce['created_at'])); ?></span>
                    </div>
                    <hr>
                    <h3>Description</h3>
                    <div class="text-content">
                        <?php echo nl2br($annonce['description']); ?>
                    </div>
                </div>

                <div class="map-box">
                    <h3>Localisation</h3>
                    <p style="margin-bottom: 15px; color: var(--gray);"><i class="fas fa-map-marker-alt"></i> <?php echo $annonce['location_name']; ?></p>
                    <div id="map" style="height: 400px; border-radius: 12px;"></div>
                </div>
            </div>

            <aside class="sidebar">
                <div class="contact-card">
                    <h3>Intéressé par ce bien ?</h3>
                    <form action="<?php echo BASE_URL; ?>/annonce/contact" method="POST">
                        <input type="hidden" name="annonce_id" value="<?php echo $annonce['id']; ?>">
                        <div class="form-group">
                            <input type="text" name="client_name" placeholder="Votre nom complet" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="client_email" placeholder="Votre email" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="client_phone" placeholder="Votre téléphone" required>
                        </div>
                        <div class="form-group">
                            <textarea name="message" placeholder="Votre message..." rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn-submit-contact">Envoyer ma demande</button>
                    </form>
                </div>

                <div class="contact-card" style="margin-top: 20px;">
                    <h3>Contact Rapide</h3>
                    <a href="https://wa.me/<?php echo $annonce['whatsapp_contact']; ?>" class="btn-whatsapp">
                        <i class="fab fa-whatsapp"></i> WhatsApp Direct
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const lat = <?php echo $annonce['latitude'] ?? '5.3484'; ?>;
        const lng = <?php echo $annonce['longitude'] ?? '-4.0305'; ?>;
        
        const map = L.map('map').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup('<b><?php echo addslashes($annonce['title']); ?></b><br><?php echo addslashes($annonce['location_name']); ?>')
            .openPopup();
    });

    let currentSlide = 0;
    const slides = document.querySelectorAll('.media-item');
    
    function showSlide(index) {
        if (index >= slides.length) currentSlide = 0;
        else if (index < 0) currentSlide = slides.length - 1;
        else currentSlide = index;
        
        slides.forEach(s => s.classList.remove('active'));
        slides[currentSlide].classList.add('active');
        
        // Update dots
        const dots = document.querySelectorAll('.dot');
        dots.forEach(d => d.classList.remove('active'));
        if (dots[currentSlide]) dots[currentSlide].classList.add('active');
        
        // Pause all videos when switching
        document.querySelectorAll('video').forEach(v => v.pause());
    }

    function currentSlide(index) {
        showSlide(index);
    }

    function moveSlide(step) {
        showSlide(currentSlide + step);
    }
</script>

<style>
.annonce-detail { padding: 60px 0; }
.detail-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 40px; }
.media-carousel-container { position: relative; border-radius: 20px; overflow: hidden; box-shadow: var(--shadow); margin-bottom: 30px; background: #000; }
.media-gallery { display: flex; transition: transform 0.5s ease-in-out; }
.media-item { min-width: 100%; height: 500px; display: none; }
.media-item.active { display: block; }
.main-media { width: 100%; height: 100%; object-fit: cover; }
video.main-media { object-fit: contain; }

.carousel-btn { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.2); color: white; border: none; padding: 20px; cursor: pointer; border-radius: 50%; backdrop-filter: blur(5px); transition: var(--transition); }
.carousel-btn:hover { background: var(--secondary); color: var(--primary); }
.prev { left: 20px; }
.next { right: 20px; }
.carousel-dots { position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); display: flex; gap: 10px; }
.dot { width: 10px; height: 10px; border-radius: 50%; background: rgba(255,255,255,0.3); cursor: pointer; }
.dot.active { background: var(--secondary); width: 25px; border-radius: 10px; }
.description-box { background: var(--white); padding: 40px; border-radius: 15px; box-shadow: var(--shadow); margin-bottom: 30px; }
.price-big { font-size: 2.5rem; font-weight: 700; color: var(--secondary); margin: 10px 0 20px; }
.meta-info { display: flex; gap: 20px; color: var(--gray); font-size: 0.95rem; margin-bottom: 20px; }
.text-content { font-size: 1.1rem; line-height: 1.8; color: #475569; }
.map-box { background: var(--white); padding: 40px; border-radius: 15px; box-shadow: var(--shadow); }

.contact-card { background: var(--white); padding: 30px; border-radius: 15px; box-shadow: var(--shadow); text-align: center; }
.btn-whatsapp { display: block; background: #25d366; color: var(--white); padding: 15px; border-radius: 10px; font-weight: 600; margin-top: 15px; }
.btn-submit-contact { width: 100%; background: var(--primary); color: var(--white); border: none; padding: 15px; border-radius: 10px; font-weight: 600; cursor: pointer; }
.form-group { margin-bottom: 15px; }
.form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; }

@media (max-width: 768px) {
    .detail-grid { grid-template-columns: 1fr; }
    .main-img { height: 300px; }
}
</style>

<?php include __DIR__ . '/../layout_footer.php'; ?>
