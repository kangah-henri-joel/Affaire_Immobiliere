<?php include __DIR__ . '/../layout_header.php'; ?>

<section class="annonces-list">
    <div class="container">
        <h1 class="section-title"><?php echo $title; ?></h1>
        
        <div class="filter-bar">
            <form action="/Projet_Affaire/annonces" method="GET">
                <div class="filter-group">
                    <label>Catégorie</label>
                    <select name="category">
                        <option value="">Toutes</option>
                        <option value="terrain">Terrains</option>
                        <option value="maison">Maisons</option>
                        <option value="vehicule">Véhicules</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Prix Max</label>
                    <input type="number" name="price_max" placeholder="Ex: 10000000">
                </div>
                <button type="submit" class="btn-filter">Filtrer</button>
            </form>
        </div>

        <div class="annonce-grid">
            <?php if (empty($annonces)): ?>
                <p>Aucune annonce trouvée.</p>
            <?php else: ?>
                <?php foreach ($annonces as $annonce): ?>
                    <div class="annonce-card">
                        <div class="annonce-img">
                            <?php if (($annonce['media_type'] ?? 'image') === 'video'): ?>
                                <div class="video-preview-small">
                                    <i class="fas fa-play-circle"></i>
                                    <span>Vidéo</span>
                                </div>
                            <?php else: ?>
                                <img src="<?php echo BASE_URL . ($annonce['image_path'] ?? '/assets/images/placeholder.jpg'); ?>" alt="<?php echo $annonce['title']; ?>">
                            <?php endif; ?>
                            <span class="badge <?php echo $annonce['type']; ?>"><?php echo ucfirst($annonce['type']); ?></span>
                        </div>
                        <div class="annonce-info">
                            <h3><a href="/Projet_Affaire/annonce/<?php echo $annonce['id']; ?>"><?php echo $annonce['title']; ?></a></h3>
                            <p class="location"><i class="fas fa-map-marker-alt"></i> <?php echo $annonce['location_name']; ?></p>
                            <p class="price"><?php echo number_format($annonce['price'], 0, ',', ' '); ?> FCFA</p>
                            <div class="card-footer">
                                <span><?php echo $annonce['category_name']; ?></span>
                                <a href="https://wa.me/<?php echo $annonce['whatsapp_contact']; ?>" class="wa-btn"><i class="fab fa-whatsapp"></i> Contact</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.annonces-list { padding: 60px 0; }
.filter-bar {
    background: var(--white);
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 40px;
    box-shadow: var(--shadow);
}
.filter-bar form { display: flex; gap: 20px; align-items: flex-end; }
.filter-group { flex: 1; display: flex; flex-direction: column; }
.filter-group label { margin-bottom: 5px; font-weight: 600; font-size: 0.9rem; }
.filter-group select, .filter-group input { padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; }
.btn-filter { background: var(--primary); color: var(--white); border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer; }

.annonce-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
}
.annonce-card {
    background: var(--white);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
}
.annonce-card:hover { transform: translateY(-5px); }
.annonce-img { position: relative; height: 200px; overflow: hidden; }
.annonce-img img { width: 100%; height: 100%; object-fit: cover; }
.video-preview-small {
    width: 100%; height: 100%; background: #0f172a;
    display: flex; flex-direction: column; justify-content: center; align-items: center;
    color: white; gap: 5px;
}
.video-preview-small i { font-size: 2.5rem; color: var(--secondary); }
.badge {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 5px 15px;
    border-radius: 50px;
    color: var(--white);
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}
.badge.vente { background: var(--accent); }
.badge.location { background: var(--primary); }
.annonce-info { padding: 20px; }
.annonce-info h3 { margin-bottom: 10px; font-size: 1.2rem; }
.location { color: var(--gray); font-size: 0.9rem; margin-bottom: 10px; }
.price { color: var(--secondary); font-size: 1.4rem; font-weight: 700; margin-bottom: 20px; }
.card-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0; pt: 15px; }
.wa-btn { color: #25d366; font-weight: 600; }
</style>

<?php include __DIR__ . '/../layout_footer.php'; ?>
