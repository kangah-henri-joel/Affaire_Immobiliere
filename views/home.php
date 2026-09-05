<?php include 'layout_header.php'; ?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>L'immobilier d'exception en <span class="highlight">Côte d'Ivoire</span></h1>
            <p>Découvrez une sélection exclusive de terrains, villas et véhicules de prestige. Simple, rapide et sécurisé.</p>
            
            <div class="search-wrapper">
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
                        <div class="search-group search-geo">
                            <i class="fas fa-globe-africa"></i>
                            <input type="text" name="pays" id="heroPays" list="hero-pays" placeholder="Pays...">
                            <datalist id="hero-pays"></datalist>
                        </div>
                        <div class="search-group search-geo">
                            <i class="fas fa-city"></i>
                            <input type="text" name="ville" id="heroVille" list="hero-villes" placeholder="Ville...">
                            <datalist id="hero-villes"></datalist>
                        </div>
                        <div class="search-group search-geo">
                            <i class="fas fa-map"></i>
                            <input type="text" name="commune" id="heroCommune" list="hero-communes" placeholder="Commune...">
                            <datalist id="hero-communes"></datalist>
                        </div>
                        <div class="search-group flex-grow">
                            <i class="fas fa-search"></i>
                            <input type="text" name="quartier" id="heroQuartier" list="hero-quartiers" placeholder="Quartier ou mot-clé...">
                            <datalist id="hero-quartiers"></datalist>
                        </div>
                        <button type="submit" class="btn-search-pro">Rechercher</button>
                    </form>
                </div>
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

        <!-- Système de filtres 2 niveaux -->
        <div class="smart-filter-wrap">
            <!-- Niveau 1 : Type -->
            <div class="smart-filter-row">
                <span class="filter-label">Type :</span>
                <div class="smart-filter-group" id="typeFilterHome">
                    <button class="smart-btn type-btn active" data-type="all" onclick="applyFilter('home')">
                        Tous
                    </button>
                    <button class="smart-btn type-btn type-vente" data-type="vente" onclick="applyFilter('home')">
                        <i class="fas fa-tag"></i> Vente
                    </button>
                    <button class="smart-btn type-btn type-location" data-type="location" onclick="applyFilter('home')">
                        <i class="fas fa-key"></i> Location
                    </button>
                </div>
            </div>
            <!-- Niveau 2 : Catégorie -->
            <div class="smart-filter-row">
                <span class="filter-label">Catégorie :</span>
                <div class="smart-filter-group" id="catFilterHome">
                    <button class="smart-btn cat-btn active" data-cat="all" onclick="applyFilter('home')">
                        <i class="fas fa-th-large"></i> Tout
                    </button>
                    <button class="smart-btn cat-btn" data-cat="terrain" onclick="applyFilter('home')">
                        <i class="fas fa-mountain"></i> Terrain
                    </button>
                    <button class="smart-btn cat-btn" data-cat="maison" onclick="applyFilter('home')">
                        <i class="fas fa-home"></i> Maison
                    </button>
                    <button class="smart-btn cat-btn" data-cat="engins" onclick="applyFilter('home')">
                        <i class="fas fa-car"></i> Engins (Voiture, Moto)
                    </button>
                </div>
            </div>
            <div class="filter-result-count" id="homeResultCount"></div>
        </div>

        <div class="annonce-grid" id="homeAnnonceGrid">
            <?php if (!empty($annonces)): ?>
                <?php foreach ($annonces as $annonce): ?>
                    <div class="annonce-card-pro"
                         data-type="<?php echo $annonce['type']; ?>"
                         data-cat="<?php
                            $slug = strtolower($annonce['category_name'] ?? '');
                            if (str_contains($slug, 'terrain')) echo 'terrain';
                            elseif (str_contains($slug, 'maison') || str_contains($slug, 'studio') || str_contains($slug, 'magasin')) echo 'maison';
                            elseif (str_contains($slug, 'v') || str_contains($slug, 'engin')) echo 'engins';
                            else echo strtolower($annonce['category_name']);
                         ?>">
                        <div class="card-img-pro">
                            <a href="<?php echo BASE_URL; ?>/annonce/<?php echo $annonce['id']; ?>" style="display:block;width:100%;height:100%;">
                                <?php if (($annonce['media_type'] ?? 'image') === 'video'): ?>
                                    <div class="video-preview-placeholder">
                                        <i class="fas fa-play-circle"></i>
                                        <span>Vidéo</span>
                                    </div>
                                <?php else: ?>
                                    <img src="<?php echo BASE_URL . ($annonce['image_path'] ?? '/assets/images/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($annonce['title']); ?>">
                                <?php endif; ?>
                            </a>
                            <div class="badge-price-pro"><?php echo number_format($annonce['price'], 0, ',', ' '); ?> FCFA</div>
                        </div>
                        <div class="card-body-pro">
                            <span class="category-label"><?php echo $annonce['category_name']; ?></span>
                            <h3><a href="<?php echo BASE_URL; ?>/annonce/<?php echo $annonce['id']; ?>" style="color:inherit;text-decoration:none;"><?php echo htmlspecialchars($annonce['title']); ?></a></h3>
                            <p class="location-pro"><i class="fas fa-map-marker-alt"></i> <?php echo $annonce['location_name']; ?></p>
                            <div class="card-footer-pro">
                                <span class="status-tag"><?php echo ucfirst($annonce['type']); ?></span>
                                <a href="<?php echo BASE_URL; ?>/annonce/<?php echo $annonce['id']; ?>" class="btn-link-pro">Détails <i class="fas fa-arrow-right"></i></a>
                            </div>
                            <?php if (!empty($annonce['author_name']) || !empty($annonce['author_whatsapp']) || !empty($annonce['author_phone'])): ?>
                            <div class="card-agent-strip">
                                <div class="card-agent-identity">
                                    <div class="card-agent-avatar"><?php echo strtoupper(substr($annonce['author_name'] ?? 'A', 0, 1)); ?></div>
                                    <div>
                                        <span class="card-agent-role">Agent</span>
                                        <span class="card-agent-name"><?php echo htmlspecialchars($annonce['author_name'] ?? 'Notre équipe'); ?></span>
                                    </div>
                                </div>
                                <div class="card-agent-actions">
                                    <?php if (!empty($annonce['author_whatsapp'])): ?>
                                    <?php
                                        require_once __DIR__ . '/../config/SiteUrl.php';
                                        $waNum   = preg_replace('/[^0-9]/', '', $annonce['author_whatsapp']);
                                        $annLink = SiteUrl::annonce($annonce['id']);
                                        $imgLink = SiteUrl::media($annonce['image_path'] ?? null);
                                        $waMsg   = "\u{1F3E0} *" . $annonce['title'] . "*\n"
                                                 . "\u{1F4B0} Prix : " . number_format($annonce['price'], 0, ',', ' ') . " FCFA\n"
                                                 . "\u{1F4CD} " . ($annonce['location_name'] ?? '') . "\n\n"
                                                 . "\u{1F449} Voir le bien : " . $annLink . "\n"
                                                 . "\u{1F4F7} Photo : " . $imgLink . "\n\n"
                                                 . "Bonjour, je suis intéressé par ce bien. Merci !";
                                    ?>
                                    <a href="https://wa.me/<?php echo $waNum; ?>?text=<?php echo rawurlencode($waMsg); ?>" class="cta-wa" target="_blank" title="WhatsApp : <?php echo htmlspecialchars($annonce['author_whatsapp']); ?>">
                                        <i class="fab fa-whatsapp"></i> <?php echo htmlspecialchars($annonce['author_whatsapp']); ?>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (!empty($annonce['author_phone'])): ?>
                                    <a href="tel:<?php echo $annonce['author_phone']; ?>" class="cta-tel" title="Appeler : <?php echo htmlspecialchars($annonce['author_phone']); ?>">
                                        <i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($annonce['author_phone']); ?>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
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
.search-wrapper {
    margin: 0 auto;
    max-width: 900px;
    width: 100%;
}

.search-container-glass {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px);
    padding: 10px;
    border-radius: 20px;
    max-width: 900px;
    margin: 0 auto;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: background 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
}

.search-container-glass.is-sticky {
    position: fixed;
    left: 50%;
    transform: translateX(-50%);
    z-index: 999;
    background: rgba(255, 255, 255, 0.85) !important;
    backdrop-filter: blur(20px);
    border: 1px solid rgba(15, 23, 42, 0.1);
    box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.3);
    padding: 12px;
    border-radius: 24px;
    animation: slideDown 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes slideDown {
    from {
        transform: translate(-50%, -20px);
        opacity: 0;
    }
    to {
        transform: translate(-50%, 0);
        opacity: 1;
    }
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

.search-group.search-geo {
    min-width: 130px;
    max-width: 160px;
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

.pro-features { padding: 30px 0; background: white; border-bottom: 1px solid var(--border); }
.features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.feature-item { text-align: center; }
.feat-icon { font-size: 2rem; color: var(--secondary); margin-bottom: 10px; }
.feature-item h3 { margin-bottom: 5px; font-weight: 700; font-size: 1.05rem; }
.feature-item p { color: var(--gray); font-size: 0.9rem; }

.section-header-pro { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 25px; padding-top: 30px; }
.section-title-pro { font-size: 2.5rem; font-weight: 800; margin-bottom: 10px; }

.annonce-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 10px; }

.annonce-card-pro {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
    display: flex;
    flex-direction: column;
    height: 100%;
}

.annonce-card-pro:hover { transform: translateY(-3px); }

.card-img-pro { position: relative; height: 135px; flex-shrink: 0; }
.card-img-pro img { width: 100%; height: 100%; object-fit: cover; }
.video-preview-placeholder {
    width: 100%; height: 100%; background: #0f172a;
    display: flex; flex-direction: column; justify-content: center; align-items: center;
    color: white; gap: 5px;
}
.video-preview-placeholder i { font-size: 2rem; color: var(--secondary); }
.badge-price-pro {
    position: absolute;
    bottom: 8px;
    left: 8px;
    background: var(--primary);
    color: white;
    padding: 4px 10px;
    border-radius: 8px;
    font-weight: 800;
    font-size: 0.78rem;
    white-space: nowrap;
}

.card-body-pro { padding: 10px 12px; display: flex; flex-direction: column; flex-grow: 1; }
.category-label { color: var(--secondary); font-weight: 700; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.8px; }
.card-body-pro h3 {
    font-size: 0.92rem;
    margin: 4px 0;
    font-weight: 700;
    line-height: 1.25;
    height: 2.5em;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    word-break: break-word;
}
.location-pro { color: var(--gray); margin-bottom: 6px; font-size: 0.78rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

.card-footer-pro {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #f1f5f9;
    padding-top: 8px;
    margin-top: auto;
}

.status-tag { background: #f8fafc; padding: 5px 15px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; color: var(--primary); }
.btn-link-pro { color: var(--primary); font-weight: 700; }
.btn-link-pro:hover { color: var(--secondary); }

/* ── AGENT STRIP SUR CARTES ACCUEIL ─────── */
.card-agent-strip {
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1.5px dashed #e2e8f0;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.card-agent-identity {
    display: flex;
    align-items: center;
    gap: 10px;
}
.card-agent-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 800;
    flex-shrink: 0;
}
.card-agent-role {
    display: block;
    font-size: 0.62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--secondary);
}
.card-agent-name {
    display: block;
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 170px;
}
.card-agent-actions {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.cta-wa, .cta-tel {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 12px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s;
    width: 100%;
}
.cta-wa {
    background: #f0fdf4;
    color: #15803d;
    border: 1.5px solid #bbf7d0;
}
.cta-wa:hover {
    background: #25d366;
    color: white;
    border-color: #25d366;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(37,211,102,0.3);
}
.cta-tel {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1.5px solid #bfdbfe;
}
.cta-tel:hover {
    background: #1d4ed8;
    color: white;
    border-color: #1d4ed8;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(29,78,216,0.3);
}

@media (max-width: 1024px) {
    .annonce-grid { grid-template-columns: repeat(4, 1fr) !important; }
}

@media (max-width: 768px) {
    /* Hero */
    .hero { padding: 60px 0 40px !important; min-height: unset !important; }
    .hero-content { text-align: center; }
    .hero-content h1 { font-size: 1.6rem !important; line-height: 1.3; margin-bottom: 12px; }
    .hero-content > p { font-size: 0.95rem !important; margin-bottom: 20px; }

    /* Barre de recherche : tout afficher en colonne sur mobile */
    .search-wrapper { padding: 0 12px; }
    .search-container-glass { padding: 8px; border-radius: 16px; }
    .search-form-pro {
        flex-direction: column;
        gap: 0;
        border-radius: 12px;
    }
    .search-group {
        border-right: none;
        border-bottom: 1px solid #f1f5f9;
        padding: 10px 14px;
    }
    .search-group:last-of-type { border-bottom: none; }
    /* Afficher les champs géo sur mobile (pas les cacher) */
    .search-group.search-geo {
        display: flex !important;
        min-width: unset;
        max-width: unset;
    }
    .search-group select,
    .search-group input { font-size: 0.95rem; padding: 10px 0; }
    .btn-search-pro { padding: 14px; width: 100%; border-radius: 10px; font-size: 1rem; }

    /* Section avantages */
    .pro-features { padding: 20px 0 !important; }
    .features-grid { grid-template-columns: 1fr; gap: 16px !important; }
    .feat-icon { font-size: 1.75rem !important; margin-bottom: 6px !important; }
    .feature-item h3 { font-size: 0.95rem !important; margin-bottom: 3px !important; }
    .feature-item p { font-size: 0.82rem !important; }

    /* Section header opportunités */
    .section-header-pro {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        padding-top: 20px !important;
        margin-bottom: 15px !important;
    }
    .section-title-pro { font-size: 1.5rem !important; margin-bottom: 4px; }
    .section-header-pro > p { font-size: 0.85rem; }
    .btn-outline-pro { align-self: flex-start; }

    /* Filtres */
    .smart-filter-wrap { padding: 14px; gap: 10px; margin-bottom: 20px; border-radius: 14px; }
    .smart-filter-row { flex-direction: column; align-items: flex-start; gap: 8px; }
    .smart-btn { padding: 7px 13px; font-size: 0.8rem; }

    /* Grille annonces */
    .annonce-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 6px !important; }
    .annonce-card-pro { border-radius: 10px; height: 100%; display: flex; flex-direction: column; }
    .card-img-pro { height: 95px !important; flex-shrink: 0; }
    .card-body-pro { padding: 6px 6px !important; display: flex; flex-direction: column; flex-grow: 1; }
    .card-body-pro h3 {
        font-size: 0.75rem !important;
        line-height: 1.2 !important;
        height: 2.4em !important;
        margin: 2px 0 !important;
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        word-break: break-word !important;
    }
    .location-pro { font-size: 0.68rem !important; margin-bottom: 2px !important; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .badge-price-pro { font-size: 0.65rem !important; padding: 3px 6px !important; bottom: 6px; left: 6px; }
    .card-footer-pro { padding-top: 4px !important; margin-top: auto; }
    .status-tag { font-size: 0.68rem; padding: 3px 8px; }
    .btn-link-pro { font-size: 0.72rem; }
    .card-agent-strip { display: none !important; }
}

@media (max-width: 400px) {
    .hero-content h1 { font-size: 1.35rem !important; }
    .annonce-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 5px !important; }
    .card-img-pro { height: 85px !important; }
}
</style>

<style>
/* ===== SMART FILTER 2 NIVEAUX ===== */
.smart-filter-wrap {
    background: white;
    border-radius: 20px;
    padding: 20px 25px;
    margin-bottom: 35px;
    box-shadow: var(--shadow);
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.smart-filter-row {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}

.filter-label {
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--gray);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
    min-width: 80px;
}

.smart-filter-group {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.smart-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 20px;
    border-radius: 50px;
    border: 2px solid var(--border);
    background: var(--light);
    font-family: 'Outfit', sans-serif;
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--gray);
    cursor: pointer;
    transition: all 0.22s ease;
}

.smart-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: white;
    transform: translateY(-1px);
}

/* Catégories actives */
.cat-btn.active { background: var(--primary); border-color: var(--primary); color: white; box-shadow: 0 4px 14px rgba(15,23,42,0.25); transform: translateY(-1px); }
.cat-btn[data-cat="terrain"].active { background: #92400e; border-color: #92400e; box-shadow: 0 4px 14px rgba(146,64,14,0.3); }
.cat-btn[data-cat="maison"].active  { background: var(--accent); border-color: var(--accent); box-shadow: 0 4px 14px rgba(99,102,241,0.3); }
.cat-btn[data-cat="engins"].active  { background: #0369a1; border-color: #0369a1; box-shadow: 0 4px 14px rgba(3,105,161,0.3); }

/* Types actifs */
.type-btn.active        { background: var(--primary); border-color: var(--primary); color: white; box-shadow: 0 4px 14px rgba(15,23,42,0.25); transform: translateY(-1px); }
.type-vente.active      { background: #f59e0b; border-color: #f59e0b; color: var(--primary); box-shadow: 0 4px 14px rgba(245,158,11,0.35); }
.type-location.active   { background: #10b981; border-color: #10b981; color: white; box-shadow: 0 4px 14px rgba(16,185,129,0.3); }

.filter-result-count {
    font-size: 0.8rem;
    color: var(--gray);
    font-style: italic;
    min-height: 18px;
}

/* Cartes cachées */
.annonce-card-pro.hidden { display: none; }
</style>

<script>
function applyFilter(gridId) {
    const catGroup  = document.getElementById('catFilter'  + (gridId === 'home' ? 'Home' : 'List'));
    const typeGroup = document.getElementById('typeFilter' + (gridId === 'home' ? 'Home' : 'List'));
    const grid      = document.getElementById(gridId === 'home' ? 'homeAnnonceGrid' : 'annoncesGrid');
    const countEl   = document.getElementById(gridId === 'home' ? 'homeResultCount' : 'listResultCount');

    // Lire le bouton actif
    const activeCatBtn = catGroup.querySelector('.smart-btn.active');
    const activeTypeBtn = typeGroup.querySelector('.smart-btn.active');

    // Gérer l'activation du bouton cliqué (toggle dans le groupe)
    const clicked = event.currentTarget;
    if (clicked.classList.contains('cat-btn')) {
        catGroup.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
        clicked.classList.add('active');
    } else if (clicked.classList.contains('type-btn')) {
        typeGroup.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
        clicked.classList.add('active');
    }

    const selCat  = catGroup.querySelector('.smart-btn.active')?.dataset.cat  || 'all';
    const selType = typeGroup.querySelector('.smart-btn.active')?.dataset.type || 'all';

    const cardSel = gridId === 'home' ? '#homeAnnonceGrid .annonce-card-pro' : '#annoncesGrid .annonce-card';
    const cards = document.querySelectorAll(cardSel);

    let visible = 0;
    cards.forEach(card => {
        const matchCat  = selCat  === 'all' || card.dataset.cat  === selCat;
        const matchType = selType === 'all' || card.dataset.type === selType;
        if (matchCat && matchType) {
            card.classList.remove('hidden');
            visible++;
        } else {
            card.classList.add('hidden');
        }
    });

    if (countEl) {
        countEl.textContent = visible === 0
            ? 'Aucun bien correspond à cette sélection.'
            : `${visible} bien${visible > 1 ? 's' : ''} affiché${visible > 1 ? 's' : ''}`;
    }

    // Afficher/masquer message vide
    let emptyId = gridId === 'home' ? 'noResultsHome' : 'noResultsList';
    let msg = document.getElementById(emptyId);
    if (!msg) {
        msg = document.createElement('div');
        msg.id = emptyId;
        msg.style.cssText = 'grid-column:1/-1;text-align:center;padding:60px 0;color:#64748b;';
        msg.innerHTML = '<i class="fas fa-search" style="font-size:2.5rem;display:block;margin-bottom:15px;color:#e2e8f0;"></i>Aucun bien dans cette catégorie pour le moment.';
        grid.appendChild(msg);
    }
    msg.style.display = visible === 0 ? 'block' : 'none';
}

// Sticky Search Bar Logic
document.addEventListener('DOMContentLoaded', function() {
    const wrapper = document.querySelector('.search-wrapper');
    const container = document.querySelector('.search-container-glass');
    if (!wrapper || !container) return;

    function handleScroll() {
        if (window.innerWidth <= 768) {
            container.classList.remove('is-sticky');
            container.style.top = '';
            container.style.width = '';
            return;
        }

        const header = document.querySelector('header');
        const headerHeight = header ? header.offsetHeight : 80;
        const wrapperRect = wrapper.getBoundingClientRect();
        
        if (wrapperRect.top <= headerHeight) {
            container.classList.add('is-sticky');
            container.style.top = headerHeight + 'px';
            container.style.width = wrapperRect.width + 'px';
        } else {
            container.classList.remove('is-sticky');
            container.style.top = '';
            container.style.width = '';
        }
    }

    window.addEventListener('scroll', handleScroll);
    window.addEventListener('resize', handleScroll);
    handleScroll();
});
</script>

<?php include 'layout_footer.php'; ?>
