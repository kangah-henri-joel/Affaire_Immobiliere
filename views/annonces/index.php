<?php include __DIR__ . '/../layout_header.php'; ?>

<section class="annonces-list">
    <div class="container">
        <h1 class="section-title"><?php echo $title; ?></h1>
        
        <!-- Smart Filter 2 niveaux -->
        <div class="smart-filter-wrap">
            <div class="smart-filter-row">
                <span class="filter-label">Type :</span>
                <div class="smart-filter-group" id="typeFilterList">
                    <button class="smart-btn type-btn active" data-type="all" onclick="applyFilter('list')">Tous</button>
                    <button class="smart-btn type-btn type-vente" data-type="vente" onclick="applyFilter('list')">
                        <i class="fas fa-tag"></i> Vente
                    </button>
                    <button class="smart-btn type-btn type-location" data-type="location" onclick="applyFilter('list')">
                        <i class="fas fa-key"></i> Location
                    </button>
                </div>
            </div>
            <div class="smart-filter-row">
                <span class="filter-label">Catégorie :</span>
                <div class="smart-filter-group" id="catFilterList">
                    <?php $currentCat = $_GET['category'] ?? ''; ?>
                    <button class="smart-btn cat-btn <?php echo empty($currentCat) ? 'active' : ''; ?>" data-cat="all" onclick="applyFilter('list')">
                        <i class="fas fa-th-large"></i> Tout
                    </button>
                    <button class="smart-btn cat-btn <?php echo ($currentCat === 'terrain') ? 'active' : ''; ?>" data-cat="terrain" onclick="applyFilter('list')">
                        <i class="fas fa-mountain"></i> Terrain
                    </button>
                    <button class="smart-btn cat-btn <?php echo ($currentCat === 'maison') ? 'active' : ''; ?>" data-cat="maison" onclick="applyFilter('list')">
                        <i class="fas fa-home"></i> Maison
                    </button>
                    <button class="smart-btn cat-btn <?php echo (in_array($currentCat, ['vehicule', 'engins'])) ? 'active' : ''; ?>" data-cat="engins" onclick="applyFilter('list')">
                        <i class="fas fa-car"></i> Engins (Voiture, Moto)
                    </button>
                </div>
            </div>
            <div class="filter-result-count" id="listResultCount">
                <?php echo count($annonces); ?> bien<?php echo count($annonces) > 1 ? 's' : ''; ?> disponible<?php echo count($annonces) > 1 ? 's' : ''; ?>
            </div>
        </div>

        <div class="filter-bar">
            <form action="<?php echo BASE_URL; ?>/annonces" method="GET" id="filterForm">
                <div class="filter-row-top">
                    <div class="filter-group">
                        <label><i class="fas fa-th-large"></i> Catégorie</label>
                        <select name="category" onchange="this.form.submit()">
                            <option value="">Toutes</option>
                            <option value="terrain" <?php echo (($_GET['category'] ?? '') === 'terrain') ? 'selected' : ''; ?>>Terrains</option>
                            <option value="maison" <?php echo (($_GET['category'] ?? '') === 'maison') ? 'selected' : ''; ?>>Maisons</option>
                            <option value="vehicule" <?php echo (in_array(($_GET['category'] ?? ''), ['vehicule', 'engins'])) ? 'selected' : ''; ?>>Véhicules</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="fas fa-tag"></i> Type</label>
                        <select name="type" onchange="this.form.submit()">
                            <option value="">Tous</option>
                            <option value="vente" <?php echo (($_GET['type'] ?? '') === 'vente') ? 'selected' : ''; ?>>Vente</option>
                            <option value="location" <?php echo (($_GET['type'] ?? '') === 'location') ? 'selected' : ''; ?>>Location</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="fas fa-money-bill-wave"></i> Prix Max (FCFA)</label>
                        <input type="number" name="price_max" id="filterPriceMax" placeholder="Ex: 10 000 000" value="<?php echo htmlspecialchars($_GET['price_max'] ?? ''); ?>">
                    </div>
                </div>

                <!-- Filtres géographiques -->
                <div class="filter-geo-section">
                    <div class="filter-geo-title">
                        <i class="fas fa-map-marker-alt"></i> Filtrer par localisation
                    </div>
                    <div class="filter-row-geo">
                        <div class="filter-group">
                            <label><i class="fas fa-globe-africa"></i> Pays</label>
                            <input type="text" name="pays" id="filterPays" list="list-pays"
                                   placeholder="Ex: Côte d'Ivoire"
                                   value="<?php echo htmlspecialchars($_GET['pays'] ?? ''); ?>">
                            <datalist id="list-pays"></datalist>
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-city"></i> Ville</label>
                            <input type="text" name="ville" id="filterVille" list="list-villes"
                                   placeholder="Ex: Abidjan"
                                   value="<?php echo htmlspecialchars($_GET['ville'] ?? ''); ?>">
                            <datalist id="list-villes"></datalist>
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-map"></i> Commune</label>
                            <input type="text" name="commune" id="filterCommune" list="list-communes"
                                   placeholder="Ex: Cocody"
                                   value="<?php echo htmlspecialchars($_GET['commune'] ?? ''); ?>">
                            <datalist id="list-communes"></datalist>
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-map-pin"></i> Quartier</label>
                            <input type="text" name="quartier" id="filterQuartier" list="list-quartiers"
                                   placeholder="Ex: Angré, Riviera..."
                                   value="<?php echo htmlspecialchars($_GET['quartier'] ?? ''); ?>">
                            <datalist id="list-quartiers"></datalist>
                        </div>
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Rechercher</button>
                    <?php if (!empty(array_filter([
                        $_GET['category'] ?? '', $_GET['type'] ?? '', $_GET['price_max'] ?? '',
                        $_GET['pays'] ?? '', $_GET['ville'] ?? '', $_GET['commune'] ?? '', $_GET['quartier'] ?? ''
                    ]))): ?>
                    <a href="<?php echo BASE_URL; ?>/annonces" class="btn-filter-reset"><i class="fas fa-times"></i> Réinitialiser</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="annonce-grid" id="annoncesGrid">
            <?php if (empty($annonces)): ?>
                <p style="grid-column:1/-1;text-align:center;color:var(--gray);padding:60px 0;">Aucune annonce trouvée.</p>
            <?php else: ?>
                <?php require_once __DIR__ . '/../../config/SiteUrl.php'; ?>
                <?php foreach ($annonces as $annonce): ?>
                    <div class="annonce-card"
                         data-type="<?php echo $annonce['type']; ?>"
                         data-price="<?php echo (float)$annonce['price']; ?>"
                         data-cat="<?php
                            $slug = strtolower($annonce['category_name'] ?? '');
                            if (str_contains($slug, 'terrain')) echo 'terrain';
                            elseif (str_contains($slug, 'maison') || str_contains($slug, 'studio') || str_contains($slug, 'magasin') || str_contains($slug, 'appartement')) echo 'maison';
                            elseif (str_contains($slug, 'v') || str_contains($slug, 'engin') || str_contains($slug, 'auto') || str_contains($slug, 'car') || str_contains($slug, 'moto')) echo 'engins';
                            else echo 'autre';
                         ?>">
                        <div class="annonce-img">
                            <a href="<?php echo BASE_URL; ?>/annonce/<?php echo $annonce['id']; ?>" style="display:block;width:100%;height:100%;">
                                <?php if (($annonce['media_type'] ?? 'image') === 'video'): ?>
                                    <div class="video-preview-small">
                                        <i class="fas fa-play-circle"></i>
                                        <span>Vidéo</span>
                                    </div>
                                <?php else: ?>
                                    <img src="<?php echo BASE_URL . ($annonce['image_path'] ?? '/assets/images/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($annonce['title']); ?>" loading="lazy">
                                <?php endif; ?>
                            </a>
                            <span class="badge <?php echo $annonce['type']; ?>"><?php echo ucfirst($annonce['type']); ?></span>
                        </div>
                        <div class="annonce-info">
                            <h3><a href="<?php echo BASE_URL; ?>/annonce/<?php echo $annonce['id']; ?>"><?php echo $annonce['title']; ?></a></h3>
                            <p class="location"><i class="fas fa-map-marker-alt"></i> <?php echo $annonce['location_name']; ?></p>
                            <p class="price"><?php echo number_format($annonce['price'], 0, ',', ' '); ?> FCFA</p>
                            <div class="card-footer">
                                <span><?php echo $annonce['category_name']; ?></span>
                                <a href="https://wa.me/<?php echo $annonce['whatsapp_contact']; ?>" class="wa-btn"><i class="fab fa-whatsapp"></i> Contact</a>
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
                                        $waNum   = preg_replace('/[^0-9]/', '', $annonce['author_whatsapp']);
                                        $annLink = SiteUrl::annonce($annonce['id']);
                                        $imgLink = SiteUrl::media($annonce['image_path'] ?? null);
                                        $waMsg   = "\u{1F3E0} *" . $annonce['title'] . "*\n"
                                                 . "\u{1F4B0} Prix : " . number_format($annonce['price'], 0, ',', ' ') . " FCFA\n"
                                                 . "\u{1F4CD} " . ($annonce['location_name'] ?? '') . "\n\n"
                                                 . "\u{1F449} Voir le bien : " . $annLink . "\n"
                                                 . "\u{1F4F7} Photo : " . $imgLink . "\n\n"
                                                 . "Bonjour, je suis intéressé par cette annonce. Merci !";
                                    ?>
                                    <a href="https://wa.me/<?php echo $waNum; ?>?text=<?php echo rawurlencode($waMsg); ?>" class="cta-wa" target="_blank">
                                        <i class="fab fa-whatsapp"></i> <?php echo htmlspecialchars($annonce['author_whatsapp']); ?>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (!empty($annonce['author_phone'])): ?>
                                    <a href="tel:<?php echo $annonce['author_phone']; ?>" class="cta-tel">
                                        <i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($annonce['author_phone']); ?>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.annonces-list { padding: 30px 0 60px; }
.filter-bar {
    background: var(--white);
    padding: 16px 20px;
    border-radius: 16px;
    margin-bottom: 24px;
    box-shadow: var(--shadow);
    border: 1px solid #f1f5f9;
}

/* Ligne haute : catégorie, type, prix */
.filter-row-top {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: flex-end;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1.5px dashed #e2e8f0;
}
.filter-row-top .filter-group { flex: 1; min-width: 130px; }

/* Section géo */
.filter-geo-section {
    background: linear-gradient(135deg, #f8faff 0%, #f1f5ff 100%);
    border: 1.5px solid #e0e7ff;
    border-radius: 12px;
    padding: 10px 14px;
    margin-bottom: 12px;
}
.filter-geo-title {
    font-size: 0.78rem;
    font-weight: 800;
    color: #4f46e5;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 7px;
}
.filter-row-geo {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

/* Groupes de filtres */
.filter-group { display: flex; flex-direction: column; }
.filter-group label {
    margin-bottom: 4px;
    font-weight: 700;
    font-size: 0.78rem;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 5px;
}
.filter-group label i { color: #6366f1; font-size: 0.74rem; }
.filter-group select,
.filter-group input {
    padding: 8px 12px;
    border: 1.5px solid #e2e8f0;
    border-radius: 9px;
    font-family: inherit;
    font-size: 0.88rem;
    color: var(--primary);
    background: white;
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
}
.filter-group select:focus,
.filter-group input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
}
.filter-group input::placeholder { color: #a8b3cc; }

/* Boutons d'action */
.filter-actions {
    display: flex;
    gap: 12px;
    align-items: center;
}
.btn-filter {
    background: linear-gradient(135deg, var(--primary), #1e40af);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.92rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.25);
}
.btn-filter:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(15, 23, 42, 0.35); }
.btn-filter-reset {
    background: #fee2e2;
    color: #dc2626;
    border: 1.5px solid #fca5a5;
    padding: 11px 20px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.88rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-filter-reset:hover { background: #dc2626; color: white; border-color: #dc2626; }

.annonce-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
}
.annonce-card {
    background: var(--white);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
    display: flex;
    flex-direction: column;
    height: 100%;
}
.annonce-card:hover { transform: translateY(-3px); }
.annonce-img { position: relative; height: 135px; overflow: hidden; flex-shrink: 0; }
.annonce-img img { width: 100%; height: 100%; object-fit: cover; }
.video-preview-small {
    width: 100%; height: 100%; background: #0f172a;
    display: flex; flex-direction: column; justify-content: center; align-items: center;
    color: white; gap: 5px;
}
.video-preview-small i { font-size: 2rem; color: var(--secondary); }
.badge {
    position: absolute;
    top: 8px;
    left: 8px;
    padding: 3px 8px;
    border-radius: 50px;
    color: var(--white);
    font-size: 0.68rem;
    font-weight: 600;
    text-transform: uppercase;
}
.badge.vente { background: var(--accent); }
.badge.location { background: var(--primary); }
.annonce-info {
    padding: 10px 12px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}
.annonce-info h3 {
    margin-bottom: 4px;
    font-size: 0.92rem;
    line-height: 1.25;
    height: 2.5em;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    word-break: break-word;
}
.annonce-info h3 a { color: inherit; text-decoration: none; }
.location {
    color: var(--gray);
    font-size: 0.78rem;
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.price { color: var(--secondary); font-size: 1.05rem; font-weight: 700; margin-bottom: 6px; white-space: nowrap; }
.card-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0; padding-top: 8px; margin-top: auto; }
.wa-btn { color: #25d366; font-weight: 600; }

/* ── AGENT STRIP SUR CARTES LISTE ──────── */
.card-agent-strip {
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1.5px dashed #e2e8f0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.card-agent-identity {
    display: flex;
    align-items: center;
    gap: 10px;
}
.card-agent-avatar {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800;
    flex-shrink: 0;
}
.card-agent-role {
    display: block;
    font-size: 0.6rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--secondary);
}
.card-agent-name {
    display: block;
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 160px;
}
.card-agent-actions {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.cta-wa, .cta-tel {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 10px;
    border-radius: 7px;
    font-size: 0.8rem;
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
.cta-wa:hover { background: #25d366; color: white; border-color: #25d366; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,211,102,0.3); }
.cta-tel {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1.5px solid #bfdbfe;
}
.cta-tel:hover { background: #1d4ed8; color: white; border-color: #1d4ed8; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(29,78,216,0.3); }

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
.annonce-card.hidden { display: none; }
.section-title { font-size: 2rem; font-weight: 800; color: var(--primary); margin-bottom: 30px; }

/* ===== RESPONSIVE TABLETTE ===== */
@media (max-width: 1200px) {
    .annonce-grid { grid-template-columns: repeat(4, 1fr) !important; }
}
@media (max-width: 900px) {
    .annonce-grid { grid-template-columns: repeat(3, 1fr) !important; }
}

/* ===== RESPONSIVE MOBILE ===== */
@media (max-width: 768px) {
    .annonces-list { padding: 16px 0 40px; }
    .section-title { font-size: 1.3rem; margin-bottom: 12px; }

    /* Smart filter : compact */
    .smart-filter-wrap { padding: 12px; border-radius: 12px; margin-bottom: 12px; gap: 8px; }
    .smart-filter-row { flex-direction: column; align-items: flex-start; gap: 6px; }
    .filter-label { min-width: unset; font-size: 0.75rem; }
    .smart-filter-group { gap: 5px; flex-wrap: wrap; }
    .smart-btn { padding: 6px 11px; font-size: 0.78rem; gap: 4px; }
    .filter-result-count { font-size: 0.75rem; }

    /* Filter bar compact */
    .filter-bar { padding: 12px 10px; margin-bottom: 14px; border-radius: 12px; }
    .filter-row-top { flex-direction: column; gap: 8px; margin-bottom: 10px; padding-bottom: 10px; }
    .filter-row-top .filter-group { width: 100%; flex: unset; }
    .filter-group label { font-size: 0.75rem; margin-bottom: 3px; }
    .filter-group select, .filter-group input {
        padding: 8px 10px;
        font-size: 0.85rem;
        width: 100%;
        box-sizing: border-box;
    }

    /* Géo : 2 colonnes sur mobile */
    .filter-geo-section { padding: 10px 12px; margin-bottom: 10px; }
    .filter-geo-title { font-size: 0.72rem; margin-bottom: 8px; }
    .filter-row-geo { grid-template-columns: 1fr 1fr !important; gap: 8px; }

    .filter-actions { flex-direction: row; gap: 8px; }
    .btn-filter { flex: 1; justify-content: center; padding: 10px; font-size: 0.85rem; }
    .btn-filter-reset { flex: 0 0 auto; padding: 10px 14px; font-size: 0.82rem; }

    /* Grille 2 colonnes sur mobile */
    .annonce-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 6px !important; }
    .annonce-card { border-radius: 10px; height: 100%; display: flex; flex-direction: column; }
    .annonce-img { height: 95px !important; flex-shrink: 0; }
    .annonce-info { padding: 6px 6px !important; display: flex; flex-direction: column; flex-grow: 1; }
    .annonce-info h3 {
        font-size: 0.75rem !important;
        line-height: 1.2 !important;
        height: 2.4em !important;
        margin-bottom: 2px !important;
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        word-break: break-word !important;
    }
    .annonce-info h3 a { color: inherit; text-decoration: none; }
    .location { font-size: 0.68rem !important; margin-bottom: 2px !important; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .location i { font-size: 0.62rem; }
    .price { font-size: 0.82rem !important; margin-bottom: 4px !important; font-weight: 800; white-space: nowrap; }
    .card-footer { flex-wrap: wrap; gap: 2px !important; padding-top: 4px !important; margin-top: auto; }
    .card-footer span { font-size: 0.62rem; padding: 2px 4px; }
    .wa-btn { font-size: 0.68rem; padding: 3px 5px; }
    .badge { font-size: 0.58rem; padding: 2px 5px; top: 4px; left: 4px; }
    .card-agent-strip { display: none !important; }
}

@media (max-width: 400px) {
    .annonce-img { height: 85px !important; }
    .annonce-grid { gap: 5px !important; }
    .annonce-info { padding: 5px 4px !important; }
    .annonce-info h3 { font-size: 0.72rem !important; }
    .price { font-size: 0.78rem !important; }
}
</style>

<script>
function applyFilter(gridId) {
    const catGroup   = document.getElementById('catFilter'  + (gridId === 'home' ? 'Home' : 'List'));
    const typeGroup  = document.getElementById('typeFilter' + (gridId === 'home' ? 'Home' : 'List'));
    const grid       = document.getElementById(gridId === 'home' ? 'homeAnnonceGrid' : 'annoncesGrid');
    const countEl    = document.getElementById(gridId === 'home' ? 'homeResultCount' : 'listResultCount');
    const maxPriceEl = document.getElementById('filterPriceMax');

    // Gérer l'activation du bouton cliqué (toggle dans le groupe)
    if (window.event && window.event.currentTarget) {
        const clicked = window.event.currentTarget;
        if (clicked.classList && clicked.classList.contains('cat-btn')) {
            if (catGroup) catGroup.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
            clicked.classList.add('active');
        } else if (clicked.classList && clicked.classList.contains('type-btn')) {
            if (typeGroup) typeGroup.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
            clicked.classList.add('active');
        }
    }

    const selCat   = catGroup ? (catGroup.querySelector('.smart-btn.active')?.dataset.cat || 'all') : 'all';
    const selType  = typeGroup ? (typeGroup.querySelector('.smart-btn.active')?.dataset.type || 'all') : 'all';
    const maxPrice = (maxPriceEl && maxPriceEl.value !== '') ? parseFloat(maxPriceEl.value) : null;

    const cardSel = gridId === 'home' ? '#homeAnnonceGrid .annonce-card-pro' : '#annoncesGrid .annonce-card';
    const cards = document.querySelectorAll(cardSel);

    let visible = 0;
    cards.forEach(card => {
        const matchCat   = selCat  === 'all' || card.dataset.cat  === selCat;
        const matchType  = selType === 'all' || card.dataset.type === selType;
        const cardPrice  = card.dataset.price ? parseFloat(card.dataset.price) : 0;
        const matchPrice = (maxPrice === null || isNaN(maxPrice)) ? true : (cardPrice <= maxPrice);

        if (matchCat && matchType && matchPrice) {
            card.classList.remove('hidden');
            visible++;
        } else {
            card.classList.add('hidden');
        }
    });

    if (countEl) {
        countEl.textContent = visible === 0
            ? 'Aucun bien ne correspond à cette sélection.'
            : `${visible} bien${visible > 1 ? 's' : ''} disponible${visible > 1 ? 's' : ''}`;
    }

    // Afficher/masquer message vide
    let emptyId = gridId === 'home' ? 'noResultsHome' : 'noResultsList';
    let msg = document.getElementById(emptyId);
    if (!msg && grid) {
        msg = document.createElement('div');
        msg.id = emptyId;
        msg.style.cssText = 'grid-column:1/-1;text-align:center;padding:60px 0;color:#64748b;';
        msg.innerHTML = '<i class="fas fa-search" style="font-size:2.5rem;display:block;margin-bottom:15px;color:#e2e8f0;"></i>Aucun bien dans cette catégorie pour le moment.';
        grid.appendChild(msg);
    }
    if (msg) {
        msg.style.display = visible === 0 ? 'block' : 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const maxPriceEl = document.getElementById('filterPriceMax');
    if (maxPriceEl) {
        maxPriceEl.addEventListener('input', function() {
            applyFilter('list');
        });
    }
});
</script>


<?php include __DIR__ . '/../layout_footer.php'; ?>

