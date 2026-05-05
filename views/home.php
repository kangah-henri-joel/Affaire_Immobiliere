<?php include 'layout_header.php'; ?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>L'immobilier d'exception en <span class="highlight">Côte d'Ivoire</span></h1>
            <p>Découvrez une sélection exclusive de terrains, villas et véhicules de prestige. Simple, rapide et sécurisé.</p>
            
            <div class="search-container-glass">
                <form action="<?php echo BASE_URL; ?>/annonces" method="GET" class="search-form-pro">
                    <div class="search-group">
                        <i class="fas fa-th-large"></i>
                        <select name="category">
                            <option value="">Toutes catégories</option>
                            <option value="terrain">Terrains</option>
                            <option value="maison">Maisons</option>
                            <option value="vehicule">Véhicules</option>
                        </select>
                    </div>
                    <div class="search-group flex-grow">
                        <i class="fas fa-search"></i>
                        <input type="text" name="query" placeholder="Ville, quartier ou mot-clé...">
                    </div>
                    <button type="submit" class="btn-search-pro">Rechercher</button>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="pro-features">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item">
                <div class="feat-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>Annonces Vérifiées</h3>
                <p>Tous nos biens sont rigoureusement contrôlés pour votre sécurité.</p>
            </div>
            <div class="feature-item">
                <div class="feat-icon"><i class="fas fa-bolt"></i></div>
                <h3>Rapidité</h3>
                <p>Mise en relation instantanée via WhatsApp ou appel direct.</p>
            </div>
            <div class="feature-item">
                <div class="feat-icon"><i class="fas fa-chart-line"></i></div>
                <h3>Meilleur Prix</h3>
                <p>Nous négocions pour vous les meilleures opportunités du marché.</p>
            </div>
        </div>
    </div>
</section>

<section class="featured">
    <div class="container">
        <div class="section-header-pro">
            <div>
                <h2 class="section-title-pro">Dernières <span class="highlight">Opportunités</span></h2>
                <p>Les nouveautés immobilières et véhicules fraîchement publiés.</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/annonces" class="btn-outline-pro">Voir tout</a>
        </div>

        <div class="annonce-grid">
            <?php if (!empty($annonces)): ?>
                <?php foreach ($annonces as $annonce): ?>
                    <div class="annonce-card-pro">
                        <div class="card-img-pro">
                            <?php if (($annonce['media_type'] ?? 'image') === 'video'): ?>
                                <div class="video-preview-placeholder">
                                    <i class="fas fa-play-circle"></i>
                                    <span>Vidéo</span>
                                </div>
                            <?php else: ?>
                                <img src="<?php echo BASE_URL . ($annonce['image_path'] ?? '/assets/images/placeholder.jpg'); ?>" alt="<?php echo $annonce['title']; ?>">
                            <?php endif; ?>
                            <div class="badge-price-pro"><?php echo number_format($annonce['price'], 0, ',', ' '); ?> FCFA</div>
                        </div>
                        <div class="card-body-pro">
                            <span class="category-label"><?php echo $annonce['category_name']; ?></span>
                            <h3><?php echo $annonce['title']; ?></h3>
                            <p class="location-pro"><i class="fas fa-map-marker-alt"></i> <?php echo $annonce['location_name']; ?></p>
                            <div class="card-footer-pro">
                                <span class="status-tag"><?php echo ucfirst($annonce['type']); ?></span>
                                <a href="<?php echo BASE_URL; ?>/annonce/<?php echo $annonce['id']; ?>" class="btn-link-pro">Détails <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <p>Aucune annonce ne correspond à votre recherche pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
/* Styles spécifiques pour l'accueil Pro */
.search-container-glass {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px);
    padding: 10px;
    border-radius: 20px;
    max-width: 900px;
    margin: 0 auto;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.search-form-pro {
    display: flex;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    padding: 5px;
}

.search-group {
    display: flex;
    align-items: center;
    padding: 0 20px;
    border-right: 1px solid #f1f5f9;
}

.search-group i { color: var(--secondary); margin-right: 10px; }
.search-group select, .search-group input { border: none; padding: 15px 0; outline: none; width: 100%; font-family: inherit; font-size: 1rem; }
.flex-grow { flex-grow: 1; }

.btn-search-pro {
    background: var(--primary);
    color: white;
    border: none;
    padding: 0 40px;
    border-radius: 12px;
    font-weight: 700;
    cursor: pointer;
}

.pro-features { padding: 80px 0; background: white; border-bottom: 1px solid var(--border); }
.features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; }
.feature-item { text-align: center; }
.feat-icon { font-size: 2.5rem; color: var(--secondary); margin-bottom: 20px; }
.feature-item h3 { margin-bottom: 10px; font-weight: 700; }
.feature-item p { color: var(--gray); }

.section-header-pro { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; padding-top: 80px; }
.section-title-pro { font-size: 2.5rem; font-weight: 800; margin-bottom: 10px; }

.annonce-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px; }

.annonce-card-pro {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.annonce-card-pro:hover { transform: translateY(-10px); }

.card-img-pro { position: relative; height: 250px; }
.card-img-pro img { width: 100%; height: 100%; object-fit: cover; }
.video-preview-placeholder {
    width: 100%; height: 100%; background: #0f172a;
    display: flex; flex-direction: column; justify-content: center; align-items: center;
    color: white; gap: 10px;
}
.video-preview-placeholder i { font-size: 3rem; color: var(--secondary); }
.badge-price-pro {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: var(--primary);
    color: white;
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: 800;
    font-size: 1.1rem;
}

.card-body-pro { padding: 25px; }
.category-label { color: var(--secondary); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }
.card-body-pro h3 { font-size: 1.4rem; margin: 10px 0; font-weight: 700; }
.location-pro { color: var(--gray); margin-bottom: 20px; font-size: 0.9rem; }

.card-footer-pro {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #f1f5f9;
    padding-top: 20px;
}

.status-tag { background: #f8fafc; padding: 5px 15px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; color: var(--primary); }
.btn-link-pro { color: var(--primary); font-weight: 700; }
.btn-link-pro:hover { color: var(--secondary); }

@media (max-width: 768px) {
    .features-grid { grid-template-columns: 1fr; }
    .search-form-pro { flex-direction: column; }
    .search-group { border-right: none; border-bottom: 1px solid #f1f5f9; }
    .btn-search-pro { padding: 15px; width: 100%; }
}
</style>

<?php include 'layout_footer.php'; ?>
