<?php 
include 'layout_header.php'; 
$rawWhatsapp = $siteSettings['company_whatsapp'] ?? '+225 0000000000';
$cleanWhatsapp = preg_replace('/[^0-9]/', '', $rawWhatsapp);
if (empty($cleanWhatsapp)) {
    $cleanWhatsapp = '2250700000000';
}
$geoPresets = AnnonceModel::getPresetLocations();
?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>L'immobilier d'exception en <span class="highlight">Côte d'Ivoire</span></h1>
            <p>Découvrez une sélection exclusive de terrains certifiés, villas de prestige, appartements et véhicules avec accompagnement notarié.</p>
            
            <div class="search-wrapper">
                <div class="hero-search-tabs">
                    <button type="button" class="hero-tab-btn active" data-type="" onclick="setHeroType(this, '')">
                        <i class="fas fa-th-large"></i> Tout
                    </button>
                    <button type="button" class="hero-tab-btn" data-type="vente" onclick="setHeroType(this, 'vente')">
                        <i class="fas fa-tag"></i> Acheter
                    </button>
                    <button type="button" class="hero-tab-btn" data-type="location" onclick="setHeroType(this, 'location')">
                        <i class="fas fa-key"></i> Louer
                    </button>
                </div>

                <div class="search-container-glass">
                    <form action="<?php echo BASE_URL; ?>/annonces" method="GET" class="search-form-pro" id="heroSearchForm">
                        <input type="hidden" name="type" id="heroType" value="">

                        <!-- Barre de recherche libre principale -->
                        <div class="search-group flex-grow search-main-query">
                            <i class="fas fa-search"></i>
                            <input type="text" name="query" id="heroQuery" placeholder="Que recherchez-vous ? (ex : Villa duplex Cocody, Terrain avec ACD, Mercedes...)" autocomplete="off">
                            <button type="button" class="btn-clear-query" id="clearHeroQuery" onclick="clearHeroQueryInput()" style="display:none;" title="Effacer"><i class="fas fa-times"></i></button>
                        </div>

                        <!-- Filtre catégorie -->
                        <div class="search-group search-cat-select">
                            <i class="fas fa-layer-group"></i>
                            <select name="category" id="heroCategory">
                                <option value="">Toutes catégories</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat['slug']); ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="terrain">Terrains</option>
                                    <option value="maison">Maisons & Villas</option>
                                    <option value="studio">Studios & Appartements</option>
                                    <option value="magasin">Magasins & Bureaux</option>
                                    <option value="vehicule">Véhicules & Engins</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Bouton Déroulant Filtres Complémentaires -->
                        <button type="button" class="btn-toggle-hero-filters" id="btnToggleHeroFilters" onclick="toggleHeroFilters()" title="Localisation et budget">
                            <i class="fas fa-sliders-h"></i>
                            <span>Filtres</span>
                            <span class="hero-filter-badge" id="heroFilterBadge" style="display:none;">0</span>
                        </button>

                        <!-- Bouton Rechercher -->
                        <button type="submit" class="btn-search-pro">
                            <i class="fas fa-search"></i>
                            <span>Rechercher</span>
                        </button>
                    </form>

                    <!-- Volet filtres géographiques & budget -->
                    <div class="hero-advanced-filters" id="heroAdvancedFilters" style="display:none;">
                        <div class="advanced-filters-header">
                            <span><i class="fas fa-map-marker-alt"></i> Filtrer par localisation et budget</span>
                            <button type="button" class="btn-close-adv" onclick="toggleHeroFilters()"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="advanced-filters-grid">
                            <div class="adv-filter-item">
                                <label><i class="fas fa-globe-africa"></i> Pays</label>
                                <select name="pays" id="heroPays" form="heroSearchForm" onchange="updateHeroFilterBadge()">
                                    <option value="">Tous les pays</option>
                                    <?php foreach ($geoPresets['pays'] as $p): ?>
                                        <option value="<?php echo htmlspecialchars($p); ?>"><?php echo htmlspecialchars($p); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="adv-filter-item">
                                <label><i class="fas fa-city"></i> Ville</label>
                                <select name="ville" id="heroVille" form="heroSearchForm" onchange="updateHeroFilterBadge()">
                                    <option value="">Toutes les villes</option>
                                    <?php foreach ($geoPresets['villes'] as $v): ?>
                                        <option value="<?php echo htmlspecialchars($v); ?>"><?php echo htmlspecialchars($v); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="adv-filter-item">
                                <label><i class="fas fa-map"></i> Commune</label>
                                <select name="commune" id="heroCommune" form="heroSearchForm" onchange="updateHeroFilterBadge()">
                                    <option value="">Toutes les communes</option>
                                    <?php foreach ($geoPresets['communes'] as $com): ?>
                                        <option value="<?php echo htmlspecialchars($com); ?>"><?php echo htmlspecialchars($com); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="adv-filter-item">
                                <label><i class="fas fa-map-pin"></i> Quartier</label>
                                <select name="quartier" id="heroQuartier" form="heroSearchForm" onchange="updateHeroFilterBadge()">
                                    <option value="">Tous les quartiers</option>
                                    <?php foreach ($geoPresets['quartiers'] as $q): ?>
                                        <option value="<?php echo htmlspecialchars($q); ?>"><?php echo htmlspecialchars($q); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="adv-filter-item">
                                <label><i class="fas fa-money-bill-wave"></i> Budget Max (FCFA)</label>
                                <input type="number" name="price_max" id="heroPriceMax" placeholder="Ex: 50 000 000" form="heroSearchForm" oninput="updateHeroFilterBadge()">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Suggestions rapides sous la barre de recherche (Chips cliquables) -->
                <div class="hero-quick-chips">
                    <span class="chips-label"><i class="fas fa-bolt"></i> Suggestions :</span>
                    <button type="button" class="hero-chip" onclick="quickHeroSearch('Cocody')">Cocody</button>
                    <button type="button" class="hero-chip" onclick="quickHeroSearch('Bingerville')">Bingerville</button>
                    <button type="button" class="hero-chip" onclick="quickHeroSearch('Terrain avec ACD')">Terrain ACD</button>
                    <button type="button" class="hero-chip" onclick="quickHeroSearch('Villa duplex')">Villa duplex</button>
                    <button type="button" class="hero-chip" onclick="quickHeroSearch('Marcory')">Marcory</button>
                    <button type="button" class="hero-chip" onclick="quickHeroSearch('Appartement meublé')">Appartement meublé</button>
                    <button type="button" class="hero-chip" onclick="quickHeroSearch('Véhicule')">Véhicules</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bandeau de confiance & chiffres clés -->
<div class="hero-trust-bar">
    <div class="container">
        <div class="trust-grid">
            <div class="trust-item">
                <div class="trust-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="trust-text">
                    <strong>+<?php echo max(30, $totalAnnonces ?? 0); ?> Biens Vérifiés</strong>
                    <span>Titres contrôlés & sécurisés</span>
                </div>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><i class="fab fa-whatsapp"></i></div>
                <div class="trust-text">
                    <strong>Contact Direct WhatsApp</strong>
                    <span>Échange instantané avec l'agent</span>
                </div>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><i class="fas fa-hand-holding-usd"></i></div>
                <div class="trust-text">
                    <strong>0% Frais Cachés</strong>
                    <span>Transparence absolue des prix</span>
                </div>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><i class="fas fa-headset"></i></div>
                <div class="trust-text">
                    <strong>Accompagnement 7j/7</strong>
                    <span>Assistance visites & démarches</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section : Explorer par Catégorie -->
<section class="section-categories">
    <div class="container">
        <div class="section-header-center">
            <span class="section-badge"><i class="fas fa-layer-group"></i> Typologies de biens</span>
            <h2 class="section-title-pro">Explorer par <span class="highlight">Catégorie</span></h2>
            <p>Accédez instantanément à nos opportunités soigneusement répertoriées.</p>
        </div>

        <div class="categories-grid">
            <a href="<?php echo BASE_URL; ?>/annonces?category=maison" class="category-card-item">
                <div class="cat-icon-wrap cat-maison">
                    <i class="fas fa-home"></i>
                </div>
                <div class="cat-info">
                    <h3>Maisons & Villas</h3>
                    <p>Duplex, villas de standing, résidences sécurisées</p>
                    <span class="cat-count"><?php echo isset($categoryCounts['maison']) ? $categoryCounts['maison'] . ' annonces' : 'Explorer'; ?> <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>

            <a href="<?php echo BASE_URL; ?>/annonces?category=terrain" class="category-card-item">
                <div class="cat-icon-wrap cat-terrain">
                    <i class="fas fa-mountain"></i>
                </div>
                <div class="cat-info">
                    <h3>Terrains & Parcelles</h3>
                    <p>Lots avec ACD, lotissements approuvés, agricoles</p>
                    <span class="cat-count"><?php echo isset($categoryCounts['terrain']) ? $categoryCounts['terrain'] . ' annonces' : 'Explorer'; ?> <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>

            <a href="<?php echo BASE_URL; ?>/annonces?query=appartement" class="category-card-item">
                <div class="cat-icon-wrap cat-appart">
                    <i class="fas fa-building"></i>
                </div>
                <div class="cat-info">
                    <h3>Appartements & Studios</h3>
                    <p>Locations meublées, appartements modernes</p>
                    <span class="cat-count"><?php echo isset($categoryCounts['studio']) ? $categoryCounts['studio'] . ' annonces' : 'Explorer'; ?> <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>

            <a href="<?php echo BASE_URL; ?>/annonces?category=vehicule" class="category-card-item">
                <div class="cat-icon-wrap cat-vehicule">
                    <i class="fas fa-car-side"></i>
                </div>
                <div class="cat-info">
                    <h3>Véhicules & Engins</h3>
                    <p>Berlines, 4x4, pick-up, engins pro & motos</p>
                    <span class="cat-count"><?php echo isset($categoryCounts['vehicule']) ? $categoryCounts['vehicule'] . ' annonces' : 'Explorer'; ?> <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>

            <a href="<?php echo BASE_URL; ?>/annonces?query=magasin" class="category-card-item">
                <div class="cat-icon-wrap cat-commerce">
                    <i class="fas fa-store"></i>
                </div>
                <div class="cat-info">
                    <h3>Bureaux & Magasins</h3>
                    <p>Espaces commerciaux, bureaux & locaux pro</p>
                    <span class="cat-count"><?php echo isset($categoryCounts['magasin']) ? $categoryCounts['magasin'] . ' annonces' : 'Explorer'; ?> <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
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

        <!-- Système de filtres 2 niveaux avec recherche libre -->
        <div class="smart-filter-wrap">
            <!-- Barre de recherche instantanée libre -->
            <div class="smart-filter-search-row">
                <div class="smart-filter-search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="homeLiveSearch" placeholder="Rechercher librement parmi ces annonces (ex : Cocody, duplex, ACD, 25 000 000, Mercedes...)" oninput="applyFilter('home')" autocomplete="off">
                    <button type="button" class="btn-clear-search" id="clearHomeSearch" onclick="clearLiveSearch('home')" style="display:none;" title="Effacer"><i class="fas fa-times"></i></button>
                </div>
            </div>

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
                                <span class="status-tag status-tag-<?php echo strtolower($annonce['type']); ?>"><?php echo ucfirst($annonce['type']); ?></span>
                                <a href="<?php echo BASE_URL; ?>/annonce/<?php echo $annonce['id']; ?>" class="btn-link-pro">Détails <i class="fas fa-arrow-right"></i></a>
                            </div>

                            <!-- Actions compactes mobiles (visibles uniquement sur petit écran) -->
                            <?php if (!empty($annonce['author_whatsapp']) || !empty($annonce['author_phone'])): ?>
                            <div class="card-mobile-actions">
                                <?php if (!empty($annonce['author_whatsapp'])): ?>
                                <?php 
                                    $waNumMob = preg_replace('/[^0-9]/', '', $annonce['author_whatsapp']);
                                    $annLinkMob = SiteUrl::annonce($annonce['id']);
                                    $waMsgMob = "\u{1F3E0} *" . $annonce['title'] . "*\n\u{1F4B0} Prix : " . number_format($annonce['price'], 0, ',', ' ') . " FCFA\n\u{1F449} " . $annLinkMob . "\nBonjour, je suis intéressé par ce bien.";
                                ?>
                                <a href="https://wa.me/<?php echo $waNumMob; ?>?text=<?php echo rawurlencode($waMsgMob); ?>" class="btn-card-mob-wa" target="_blank" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($annonce['author_phone'])): ?>
                                <a href="tel:<?php echo $annonce['author_phone']; ?>" class="btn-card-mob-tel" title="Appeler">
                                    <i class="fas fa-phone-alt"></i> Appeler
                                </a>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

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
                    <button type="button" class="btn-reset-filters" onclick="clearLiveSearch('home')"><i class="fas fa-undo"></i> Réinitialiser les filtres</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Section : Communes & Zones Phares -->
<section class="section-locations">
    <div class="container">
        <div class="section-header-pro">
            <div>
                <span class="section-badge"><i class="fas fa-map-marked-alt"></i> Où investir ?</span>
                <h2 class="section-title-pro">Communes & Zones <span class="highlight">Phares</span></h2>
                <p>Découvrez les secteurs les plus attractifs et dynamiques pour votre achat ou location.</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/annonces" class="btn-outline-pro">Explorer toute la Côte d'Ivoire</a>
        </div>

        <div class="locations-grid">
            <a href="<?php echo BASE_URL; ?>/annonces?commune=Cocody" class="location-card loc-cocody">
                <div class="loc-overlay"></div>
                <div class="loc-content">
                    <span class="loc-tag">Résidentiel & Prestige</span>
                    <h3>Cocody</h3>
                    <p>Riviera, Angré, Deux-Plateaux, Palmeraie</p>
                    <span class="loc-link">Découvrir les opportunités <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>

            <a href="<?php echo BASE_URL; ?>/annonces?commune=Bingerville" class="location-card loc-bingerville">
                <div class="loc-overlay"></div>
                <div class="loc-content">
                    <span class="loc-tag">En plein essor</span>
                    <h3>Bingerville</h3>
                    <p>Cités calmes, terrains viabilisés & cadre vert</p>
                    <span class="loc-link">Découvrir les opportunités <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>

            <a href="<?php echo BASE_URL; ?>/annonces?commune=Marcory" class="location-card loc-marcory">
                <div class="loc-overlay"></div>
                <div class="loc-content">
                    <span class="loc-tag">Affaires & Centralité</span>
                    <h3>Marcory & Zone 4</h3>
                    <p>Appartements modernes & commerces florissants</p>
                    <span class="loc-link">Découvrir les opportunités <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>

            <a href="<?php echo BASE_URL; ?>/annonces?ville=Bassam" class="location-card loc-bassam">
                <div class="loc-overlay"></div>
                <div class="loc-content">
                    <span class="loc-tag">Bord de mer & Détente</span>
                    <h3>Grand-Bassam & Assinie</h3>
                    <p>Résidences de vacances, bord de lagune & plages</p>
                    <span class="loc-link">Découvrir les opportunités <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Section : Comment ça fonctionne ? -->
<section class="section-how-it-works">
    <div class="container">
        <div class="section-header-center">
            <span class="section-badge"><i class="fas fa-compass"></i> Processus Simplifié</span>
            <h2 class="section-title-pro">Comment ça <span class="highlight">fonctionne ?</span></h2>
            <p>Trouver ou acquérir un bien n'a jamais été aussi rapide, limpide et sécurisé.</p>
        </div>

        <div class="how-grid">
            <div class="how-step">
                <div class="how-step-badge">01</div>
                <div class="how-step-icon"><i class="fas fa-search-location"></i></div>
                <h3>Explorez & Filtrez</h3>
                <p>Consultez des dizaines de terrains, villas et véhicules vérifiés selon vos critères et budget.</p>
            </div>

            <div class="how-step">
                <div class="how-step-badge">02</div>
                <div class="how-step-icon"><i class="fab fa-whatsapp"></i></div>
                <h3>Échangez Directement</h3>
                <p>Contactez le conseiller attitré en 1 clic par WhatsApp ou téléphone pour poser vos questions.</p>
            </div>

            <div class="how-step">
                <div class="how-step-badge">03</div>
                <div class="how-step-icon"><i class="fas fa-calendar-check"></i></div>
                <h3>Visitez sur Place</h3>
                <p>Programmez une visite physique guidée pour apprécier l'environnement et l'état réel du bien.</p>
            </div>

            <div class="how-step">
                <div class="how-step-badge">04</div>
                <div class="how-step-icon"><i class="fas fa-file-signature"></i></div>
                <h3>Concluez en Sécurité</h3>
                <p>Bénéficiez du contrôle des documents (ACD, CMP) et finalisez votre acquisition en toute sérénité.</p>
            </div>
        </div>
    </div>
</section>

<!-- Section : Double Bannière d'Action (Demandes & Propriétaires) -->
<section class="section-dual-cta">
    <div class="container">
        <div class="dual-cta-grid">
            <!-- Carte 1 : Déposer une Demande -->
            <div class="cta-card cta-buyer">
                <div class="cta-badge"><i class="fas fa-bullhorn"></i> Service Gratuit pour Acheteurs</div>
                <h3>Vous cherchez un bien spécifique ?</h3>
                <p>Ne perdez plus de temps ! Décrivez précisément votre recherche (zone, budget, critères) et nos agents locaux s'activent pour vous dénicher l'opportunité idéale.</p>
                <div class="cta-actions">
                    <a href="<?php echo BASE_URL; ?>/demandes" class="btn-cta-primary">
                        <i class="fas fa-plus-circle"></i> Déposer ma demande
                    </a>
                    <a href="<?php echo BASE_URL; ?>/demandes" class="btn-cta-sub">
                        Consulter les demandes en cours <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Carte 2 : Propriétaires & Agents -->
            <div class="cta-card cta-seller">
                <div class="cta-badge"><i class="fas fa-home"></i> Propriétaires & Promoteurs</div>
                <h3>Vous souhaitez vendre ou louer un bien ?</h3>
                <p>Profitez de notre large audience d'acheteurs qualifiés et d'une multidiffusion puissante (Web, WhatsApp, Facebook, TikTok) pour conclure rapidement et au juste prix.</p>
                <div class="cta-actions">
                    <a href="<?php echo BASE_URL; ?>/contact" class="btn-cta-gold">
                        <i class="fas fa-handshake"></i> Confier mon bien
                    </a>
                    <?php if (!empty($cleanWhatsapp)): ?>
                    <a href="https://wa.me/<?php echo $cleanWhatsapp; ?>?text=<?php echo rawurlencode("Bonjour ImmoAffaire, je souhaite vous confier un bien à la vente ou en location."); ?>" target="_blank" class="btn-cta-wa">
                        <i class="fab fa-whatsapp"></i> WhatsApp direct
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section : Témoignages & Avis Clients -->
<section class="section-testimonials">
    <div class="container">
        <div class="section-header-center">
            <span class="section-badge"><i class="fas fa-heart"></i> Retours d'expérience</span>
            <h2 class="section-title-pro">Ce que disent nos <span class="highlight">Clients</span></h2>
            <p>La satisfaction et la sécurité de nos acquéreurs et locataires sont notre plus belle fierté.</p>
        </div>

        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testi-stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="testi-text">"J'ai pu acquérir une parcelle avec ACD à Bingerville sans aucune mauvaise surprise. L'équipe a vérifié chaque document avant la signature chez le notaire. Un professionnalisme exemplaire !"</p>
                <div class="testi-user">
                    <div class="testi-avatar">KA</div>
                    <div class="testi-meta">
                        <strong>Kouamé A.</strong>
                        <span>Acquéreur Terrain • Bingerville</span>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <div class="testi-stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="testi-text">"La mise en relation directe par WhatsApp est ultra pratique. J'ai visité une superbe villa duplex à Cocody Angré 24h après avoir vu l'annonce. Bail signé en 3 jours !"</p>
                <div class="testi-user">
                    <div class="testi-avatar">MD</div>
                    <div class="testi-meta">
                        <strong>Mireille D.</strong>
                        <span>Locataire Villa • Cocody Angré</span>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <div class="testi-stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                </div>
                <p class="testi-text">"En tant que propriétaire, j'ai confié la vente de deux véhicules et d'un terrain commercial. Les deux ont trouvé preneur en moins d'un mois grâce à leur réseau réactif. Merci !"</p>
                <div class="testi-user">
                    <div class="testi-avatar">YB</div>
                    <div class="testi-meta">
                        <strong>Yacouba B.</strong>
                        <span>Propriétaire & Investisseur • Abidjan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section : Foire Aux Questions (FAQ) -->
<section class="section-faq">
    <div class="container">
        <div class="section-header-center">
            <span class="section-badge"><i class="fas fa-question-circle"></i> Réponses Claires</span>
            <h2 class="section-title-pro">Foire Aux <span class="highlight">Questions</span></h2>
            <p>Tout ce que vous devez savoir pour réussir vos démarches en toute tranquillité.</p>
        </div>

        <div class="faq-accordion">
            <div class="faq-item active">
                <button type="button" class="faq-question" onclick="toggleFaq(this)">
                    <span><i class="fas fa-file-contract"></i> Comment s'assurer de l'authenticité juridique d'un terrain ou d'un bien ?</span>
                    <i class="fas fa-chevron-down faq-chevron"></i>
                </button>
                <div class="faq-answer">
                    <p>Chaque bien répertorié sur notre plateforme est soumis à une vérification rigoureuse : consultation du titre foncier officiel (ACD – Arrêté de Concession Définitive, CMP, attestation villageoise approuvée avec guide), identité des propriétaires et conformité cadastrale.</p>
                </div>
            </div>

            <div class="faq-item">
                <button type="button" class="faq-question" onclick="toggleFaq(this)">
                    <span><i class="fas fa-tag"></i> Y a-t-il des frais pour consulter ou déposer une demande de recherche ?</span>
                    <i class="fas fa-chevron-down faq-chevron"></i>
                </button>
                <div class="faq-answer">
                    <p>Non, la consultation des annonces et le dépôt d'une demande de recherche sont entièrement gratuits pour les visiteurs et acheteurs. Vous êtes directement mis en relation avec le conseiller responsable sans intermédiaire superflu.</p>
                </div>
            </div>

            <div class="faq-item">
                <button type="button" class="faq-question" onclick="toggleFaq(this)">
                    <span><i class="fas fa-map-marked-alt"></i> Puis-je planifier une visite sur place avant de m'engager ?</span>
                    <i class="fas fa-chevron-down faq-chevron"></i>
                </button>
                <div class="faq-answer">
                    <p>Absolument ! La visite des lieux est une étape essentielle. Il vous suffit de cliquer sur le bouton WhatsApp ou Appel de l'annonce pour convenir d'un rendez-vous sur place selon vos disponibilités.</p>
                </div>
            </div>

            <div class="faq-item">
                <button type="button" class="faq-question" onclick="toggleFaq(this)">
                    <span><i class="fas fa-bullhorn"></i> Comment proposer mon bien à la vente ou à la location ?</span>
                    <i class="fas fa-chevron-down faq-chevron"></i>
                </button>
                <div class="faq-answer">
                    <p>Vous pouvez nous contacter directement par WhatsApp ou via le formulaire de contact. Notre équipe se chargera de vérifier les documents, d'effectuer les prises de vue et de diffuser votre annonce sur nos canaux à fort impact.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section : Bandeau Contact Express / Assistance Rapide -->
<section class="section-quick-contact">
    <div class="container">
        <div class="quick-contact-card">
            <div class="qc-content">
                <h2>Besoin d'un accompagnement personnalisé pour votre projet ?</h2>
                <p>Nos conseillers sont disponibles pour vous orienter, répondre à vos questions et organiser vos visites 7j/7.</p>
            </div>
            <div class="qc-actions">
                <?php if (!empty($cleanWhatsapp)): ?>
                <a href="https://wa.me/<?php echo $cleanWhatsapp; ?>?text=<?php echo rawurlencode("Bonjour ImmoAffaire, j'ai besoin d'un accompagnement pour mon projet immobilier."); ?>" target="_blank" class="btn-qc-wa">
                    <i class="fab fa-whatsapp"></i> Échanger sur WhatsApp
                </a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>/contact" class="btn-qc-contact">
                    <i class="fas fa-envelope"></i> Formulaire de Contact
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* Styles spécifiques pour l'accueil Pro */
.search-wrapper {
    margin: 0 auto;
    max-width: 960px;
    width: 100%;
}

/* Onglets de type Hero (Acheter / Louer / Tout) */
.hero-search-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
    justify-content: flex-start;
}
.hero-tab-btn {
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #e2e8f0;
    padding: 8px 20px;
    border-radius: 30px;
    font-weight: 700;
    font-size: 0.9rem;
    font-family: inherit;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 7px;
}
.hero-tab-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}
.hero-tab-btn.active {
    background: white;
    color: var(--primary);
    border-color: white;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
}

.search-container-glass {
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    padding: 10px;
    border-radius: 22px;
    max-width: 960px;
    margin: 0 auto;
    border: 1px solid rgba(255, 255, 255, 0.35);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1) inset;
    transition: background 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
}

.search-container-glass.is-sticky {
    position: fixed;
    left: 50%;
    transform: translateX(-50%);
    z-index: 999;
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(15, 23, 42, 0.12);
    box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.35);
    padding: 10px;
    border-radius: 22px;
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
    border-radius: 16px;
    overflow: hidden;
    padding: 6px;
    align-items: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
}

.search-group {
    display: flex;
    align-items: center;
    padding: 0 16px;
    border-right: 1px solid #f1f5f9;
}

.search-main-query {
    position: relative;
    padding: 0 16px;
}
.search-main-query input {
    font-size: 1.02rem;
    font-weight: 500;
    color: #1e293b;
    padding: 12px 0;
}
.search-main-query input::placeholder {
    color: #94a3b8;
    font-weight: 400;
}
.btn-clear-query {
    background: #f1f5f9;
    border: none;
    color: #64748b;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.75rem;
    margin-right: 6px;
    transition: all 0.2s ease;
}
.btn-clear-query:hover { background: #fee2e2; color: #ef4444; }

.search-cat-select {
    min-width: 170px;
    max-width: 200px;
}
.search-cat-select select {
    cursor: pointer;
    font-weight: 600;
    color: #334155;
    padding: 12px 0;
}

.search-group i { color: var(--secondary); margin-right: 10px; font-size: 1.05rem; }
.search-group select, .search-group input { border: none; outline: none; width: 100%; font-family: inherit; font-size: 0.98rem; }
.flex-grow { flex-grow: 1; }

.btn-toggle-hero-filters {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    color: #475569;
    padding: 11px 18px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.88rem;
    font-family: inherit;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    white-space: nowrap;
    margin: 0 6px;
}
.btn-toggle-hero-filters:hover,
.btn-toggle-hero-filters.active {
    background: #e2e8f0;
    color: var(--primary);
    border-color: #cbd5e1;
}
.hero-filter-badge {
    background: var(--secondary);
    color: white;
    font-size: 0.7rem;
    font-weight: 800;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-search-pro {
    background: linear-gradient(135deg, var(--primary) 0%, #1e293b 100%);
    color: white;
    border: none;
    padding: 13px 32px;
    border-radius: 12px;
    font-weight: 800;
    font-size: 0.98rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.25);
}
.btn-search-pro:hover {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.35);
}

/* Volet filtres complémentaires déroulable */
.hero-advanced-filters {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(15px);
    border-radius: 16px;
    margin-top: 10px;
    padding: 18px 22px;
    border: 1px solid rgba(255, 255, 255, 0.6);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.16);
    animation: fadeInDown 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    text-align: left;
}
@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}
.advanced-filters-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--gray);
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.btn-close-adv {
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    font-size: 1rem;
    padding: 4px;
}
.btn-close-adv:hover { color: #ef4444; }

.advanced-filters-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
}
.adv-filter-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.adv-filter-item label {
    font-size: 0.76rem;
    font-weight: 700;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 6px;
}
.adv-filter-item label i { color: var(--secondary); font-size: 0.85rem; }
.adv-filter-item input,
.adv-filter-item select {
    background: white;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 0.88rem;
    font-family: inherit;
    color: #1e293b;
    outline: none;
    transition: all 0.2s ease;
    width: 100%;
    box-sizing: border-box;
}
.adv-filter-item select {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 16px 16px;
    padding-right: 32px;
    appearance: none;
    -webkit-appearance: none;
}
.adv-filter-item input:focus,
.adv-filter-item select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.08);
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

/* ── HERO TOP BADGE & QUICK CHIPS ─────── */
.hero-top-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(245, 158, 11, 0.15);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(245, 158, 11, 0.4);
    color: #fef08a;
    font-size: 0.8rem;
    font-weight: 700;
    padding: 7px 18px;
    border-radius: 50px;
    margin-bottom: 20px;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.15);
}
.hero-top-badge i {
    color: #facc15;
    font-size: 0.9rem;
}

.hero-quick-chips {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 14px;
    justify-content: center;
}
.chips-label {
    font-size: 0.8rem;
    color: #cbd5e1;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.hero-chip {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #f8fafc;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 600;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.2s ease;
}
.hero-chip:hover {
    background: white;
    color: var(--primary);
    border-color: white;
    transform: translateY(-1px);
}

/* ── HERO TRUST BAR ─────── */
.hero-trust-bar {
    background: white;
    border-bottom: 1px solid #e2e8f0;
    padding: 22px 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
}
.trust-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
.trust-item {
    display: flex;
    align-items: center;
    gap: 14px;
}
.trust-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: #fffbeb;
    color: #f59e0b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}
.trust-text {
    display: flex;
    flex-direction: column;
}
.trust-text strong {
    font-size: 0.95rem;
    color: #0f172a;
    font-weight: 700;
}
.trust-text span {
    font-size: 0.78rem;
    color: #64748b;
}

/* ── SECTION EXPLORER PAR CATEGORIE ─────── */
.section-categories {
    padding: 60px 0 30px;
}
.section-header-center {
    text-align: center;
    max-width: 680px;
    margin: 0 auto 40px;
}
.section-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f1f5f9;
    color: var(--primary);
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 6px 14px;
    border-radius: 30px;
    margin-bottom: 10px;
}
.section-header-center h2 {
    font-size: 2.2rem;
    font-weight: 800;
    margin-bottom: 10px;
}
.section-header-center p {
    color: #64748b;
    font-size: 1rem;
}
.categories-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
}
.category-card-item {
    background: white;
    border-radius: 16px;
    padding: 22px 18px;
    border: 1.5px solid #e2e8f0;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    transition: all 0.25s ease;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.04);
}
.category-card-item:hover {
    transform: translateY(-4px);
    border-color: #cbd5e1;
    box-shadow: 0 12px 25px -4px rgba(15,23,42,0.12);
}
.cat-icon-wrap {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 16px;
}
.cat-maison { background: #e0e7ff; color: #4338ca; }
.cat-terrain { background: #fef3c7; color: #b45309; }
.cat-appart { background: #ede9fe; color: #7c3aed; }
.cat-vehicule { background: #e0f2fe; color: #0369a1; }
.cat-commerce { background: #dcfce7; color: #15803d; }
.cat-info h3 {
    font-size: 1.05rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 6px;
}
.cat-info p {
    font-size: 0.8rem;
    color: #64748b;
    line-height: 1.4;
    margin-bottom: 14px;
    min-height: 2.8em;
}
.cat-count {
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--secondary);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: auto;
}

/* ── STATUS TAGS & CARTE AMELIOREE ─────── */
.status-tag-vente {
    background: #fef3c7 !important;
    color: #b45309 !important;
    border: 1px solid #fde68a;
}
.status-tag-location {
    background: #d1fae5 !important;
    color: #065f46 !important;
    border: 1px solid #a7f3d0;
}
.card-mobile-actions {
    display: none;
    padding: 6px 8px 8px;
    border-top: 1px solid #f1f5f9;
    gap: 6px;
}
.btn-card-mob-wa, .btn-card-mob-tel {
    flex: 1;
    padding: 6px 8px;
    border-radius: 6px;
    font-size: 0.72rem;
    font-weight: 700;
    text-align: center;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    text-decoration: none;
}
.btn-card-mob-wa {
    background: #dcfce7;
    color: #15803d;
}
.btn-card-mob-tel {
    background: #dbeafe;
    color: #1d4ed8;
}
.btn-reset-filters {
    margin-top: 15px;
    background: var(--primary);
    color: white;
    border: none;
    padding: 9px 18px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
}
.btn-reset-filters:hover {
    background: var(--secondary);
    color: var(--primary);
}

/* ── SECTION COMMUNES & LOCALISATIONS ─────── */
.section-locations {
    padding: 60px 0;
    background: #f8fafc;
}
.locations-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
}
.location-card {
    position: relative;
    border-radius: 18px;
    overflow: hidden;
    height: 250px;
    display: flex;
    align-items: flex-end;
    padding: 24px 20px;
    text-decoration: none;
    color: white;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}
.location-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 30px rgba(0,0,0,0.2);
}
.loc-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(15,23,42,0.15) 0%, rgba(15,23,42,0.85) 100%);
    transition: opacity 0.3s ease;
}
.loc-cocody { background: linear-gradient(135deg, #1e3a8a, #0f172a); }
.loc-bingerville { background: linear-gradient(135deg, #065f46, #022c22); }
.loc-marcory { background: linear-gradient(135deg, #701a75, #3b0764); }
.loc-bassam { background: linear-gradient(135deg, #0369a1, #082f49); }
.loc-content {
    position: relative;
    z-index: 2;
}
.loc-tag {
    display: inline-block;
    background: rgba(255,255,255,0.22);
    backdrop-filter: blur(8px);
    font-size: 0.68rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin-bottom: 8px;
}
.loc-content h3 {
    font-size: 1.35rem;
    font-weight: 800;
    margin-bottom: 4px;
}
.loc-content p {
    font-size: 0.8rem;
    color: #cbd5e1;
    margin-bottom: 12px;
}
.loc-link {
    font-size: 0.8rem;
    font-weight: 700;
    color: #facc15;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* ── SECTION COMMENT CA FONCTIONNE ─────── */
.section-how-it-works {
    padding: 70px 0;
    background: white;
}
.how-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 22px;
    position: relative;
}
.how-step {
    background: #f8fafc;
    border-radius: 18px;
    padding: 28px 20px;
    border: 1px solid #e2e8f0;
    position: relative;
    transition: all 0.25s ease;
}
.how-step:hover {
    transform: translateY(-3px);
    background: white;
    border-color: #cbd5e1;
    box-shadow: 0 10px 25px -4px rgba(0,0,0,0.06);
}
.how-step-badge {
    position: absolute;
    top: 16px;
    right: 18px;
    font-size: 1.3rem;
    font-weight: 900;
    color: #cbd5e1;
}
.how-step-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary) 0%, #334155 100%);
    color: #facc15;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 16px;
}
.how-step h3 {
    font-size: 1.1rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 8px;
}
.how-step p {
    font-size: 0.84rem;
    color: #64748b;
    line-height: 1.5;
}

/* ── SECTION DUAL CTA ─────── */
.section-dual-cta {
    padding: 60px 0;
    background: #f1f5f9;
}
.dual-cta-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px;
}
.cta-card {
    border-radius: 20px;
    padding: 35px 30px;
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08);
}
.cta-buyer {
    background: white;
    border: 1.5px solid #cbd5e1;
}
.cta-seller {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: white;
}
.cta-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 5px 12px;
    border-radius: 30px;
    margin-bottom: 16px;
    align-self: flex-start;
}
.cta-buyer .cta-badge { background: #dbeafe; color: #1e40af; }
.cta-seller .cta-badge { background: rgba(245,158,11,0.25); color: #fef08a; border: 1px solid rgba(245,158,11,0.4); }
.cta-card h3 {
    font-size: 1.4rem;
    font-weight: 800;
    margin-bottom: 10px;
    line-height: 1.25;
}
.cta-buyer p {
    color: #64748b;
    font-size: 0.9rem;
    margin-bottom: 24px;
    line-height: 1.5;
}
.cta-seller p {
    color: #94a3b8;
    font-size: 0.9rem;
    margin-bottom: 24px;
    line-height: 1.5;
}
.cta-actions {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
    margin-top: auto;
}
.btn-cta-primary {
    background: var(--primary);
    color: white;
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-cta-primary:hover {
    background: #334155;
    transform: translateY(-1px);
}
.btn-cta-sub {
    color: var(--primary);
    font-size: 0.88rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
}
.btn-cta-gold {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #0f172a;
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 800;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-cta-gold:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(245,158,11,0.4);
}
.btn-cta-wa {
    background: rgba(37, 211, 102, 0.2);
    border: 1px solid rgba(37, 211, 102, 0.5);
    color: #4ade80;
    padding: 11px 18px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-cta-wa:hover {
    background: #25d366;
    color: white;
    transform: translateY(-1px);
}

/* ── SECTION TESTIMONIALS ─────── */
.section-testimonials {
    padding: 70px 0;
    background: white;
}
.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 22px;
}
.testimonial-card {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 18px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    transition: all 0.25s ease;
}
.testimonial-card:hover {
    transform: translateY(-3px);
    background: white;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.07);
}
.testi-stars {
    color: #f59e0b;
    font-size: 0.88rem;
    margin-bottom: 12px;
    display: flex;
    gap: 3px;
}
.testi-text {
    font-size: 0.9rem;
    color: #334155;
    font-style: italic;
    line-height: 1.55;
    margin-bottom: 18px;
    flex-grow: 1;
}
.testi-user {
    display: flex;
    align-items: center;
    gap: 12px;
    border-top: 1px solid #e2e8f0;
    padding-top: 14px;
}
.testi-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: white;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.88rem;
}
.testi-meta strong {
    display: block;
    font-size: 0.9rem;
    color: #0f172a;
}
.testi-meta span {
    font-size: 0.76rem;
    color: #64748b;
}

/* ── SECTION FAQ ACCORDION ─────── */
.section-faq {
    padding: 65px 0;
    background: #f8fafc;
}
.faq-accordion {
    max-width: 820px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.faq-item {
    background: white;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    transition: all 0.2s ease;
}
.faq-item.active {
    border-color: var(--primary);
    box-shadow: 0 4px 14px rgba(15,23,42,0.06);
}
.faq-question {
    width: 100%;
    padding: 18px 22px;
    background: none;
    border: none;
    text-align: left;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    font-family: inherit;
    font-size: 0.98rem;
    font-weight: 700;
    color: #0f172a;
    gap: 14px;
}
.faq-chevron {
    color: #94a3b8;
    font-size: 0.85rem;
    transition: transform 0.25s ease;
    flex-shrink: 0;
}
.faq-item.active .faq-chevron {
    transform: rotate(180deg);
    color: var(--primary);
}
.faq-answer {
    display: none;
    padding: 0 22px 20px;
    font-size: 0.9rem;
    color: #475569;
    line-height: 1.6;
}
.faq-item.active .faq-answer {
    display: block;
}

/* ── SECTION QUICK CONTACT BANNER ─────── */
.section-quick-contact {
    padding: 40px 0 60px;
    background: white;
}
.quick-contact-card {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0f172a 100%);
    border-radius: 22px;
    padding: 38px 42px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 30px;
    color: white;
    box-shadow: 0 20px 40px -10px rgba(15,23,42,0.3);
}
.qc-content h2 {
    font-size: 1.7rem;
    font-weight: 800;
    margin-bottom: 8px;
    color: white;
}
.qc-content p {
    color: #94a3b8;
    font-size: 0.98rem;
    max-width: 580px;
}
.qc-actions {
    display: flex;
    gap: 14px;
    flex-shrink: 0;
}
.btn-qc-wa {
    background: #25d366;
    color: white;
    padding: 13px 22px;
    border-radius: 12px;
    font-weight: 800;
    font-size: 0.92rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(37,211,102,0.35);
}
.btn-qc-wa:hover {
    background: #1eb954;
    transform: translateY(-2px);
}
.btn-qc-contact {
    background: rgba(255,255,255,0.12);
    border: 1.5px solid rgba(255,255,255,0.3);
    color: white;
    padding: 13px 22px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.92rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
}
.btn-qc-contact:hover {
    background: white;
    color: var(--primary);
}

@media (max-width: 1024px) {
    .annonce-grid { grid-template-columns: repeat(4, 1fr) !important; }
    .trust-grid { grid-template-columns: repeat(2, 1fr); }
    .categories-grid { grid-template-columns: repeat(3, 1fr); }
    .locations-grid { grid-template-columns: repeat(2, 1fr); }
    .how-grid { grid-template-columns: repeat(2, 1fr); }
    .testimonials-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
    /* Hero */
    .hero { padding: 60px 0 40px !important; min-height: unset !important; }
    .hero-content { text-align: center; }
    .hero-content h1 { font-size: 1.6rem !important; line-height: 1.3; margin-bottom: 12px; }
    .hero-content > p { font-size: 0.95rem !important; margin-bottom: 20px; }

    /* Barre de recherche Hero : responsive */
    .hero-search-tabs { justify-content: center; gap: 6px; }
    .hero-tab-btn { padding: 7px 14px; font-size: 0.82rem; }
    .search-wrapper { padding: 0 8px; }
    .search-container-glass { padding: 8px; border-radius: 16px; }
    .search-form-pro {
        flex-direction: column;
        gap: 0;
        border-radius: 14px;
    }
    .search-group {
        border-right: none;
        border-bottom: 1px solid #f1f5f9;
        padding: 10px 14px;
        width: 100%;
    }
    .search-cat-select {
        min-width: 100%;
        max-width: 100%;
    }
    .search-group select,
    .search-group input { font-size: 0.95rem; padding: 10px 0; }
    .btn-toggle-hero-filters {
        width: calc(100% - 16px);
        margin: 8px 8px 4px;
        justify-content: center;
        padding: 10px;
    }
    .btn-search-pro {
        padding: 13px;
        width: calc(100% - 16px);
        margin: 4px 8px 8px;
        border-radius: 10px;
        font-size: 1rem;
        justify-content: center;
    }
    .advanced-filters-grid {
        grid-template-columns: 1fr !important;
        gap: 10px !important;
    }
    .hero-advanced-filters { padding: 14px; }

    /* Section avantages & trust bar */
    .trust-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
    .trust-icon { width: 38px; height: 38px; font-size: 1.1rem; }
    .trust-text strong { font-size: 0.82rem; }
    .trust-text span { font-size: 0.7rem; }

    /* Categories responsive */
    .section-categories { padding: 40px 0 20px; }
    .section-header-center { margin-bottom: 25px; }
    .section-header-center h2 { font-size: 1.6rem; }
    .categories-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .category-card-item { padding: 14px 12px; border-radius: 12px; }
    .cat-icon-wrap { width: 42px; height: 42px; font-size: 1.2rem; margin-bottom: 10px; }
    .cat-info h3 { font-size: 0.88rem; }
    .cat-info p { display: none; }
    .cat-count { font-size: 0.75rem; }

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
    .card-mobile-actions { display: flex !important; }

    /* Localisations responsive */
    .section-locations { padding: 40px 0; }
    .locations-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .location-card { height: 170px; padding: 14px; }
    .loc-content h3 { font-size: 1.1rem; }
    .loc-content p { display: none; }

    /* How it works */
    .section-how-it-works { padding: 40px 0; }
    .how-grid { grid-template-columns: 1fr; gap: 12px; }
    .how-step { padding: 20px 16px; }

    /* Dual CTA */
    .section-dual-cta { padding: 40px 0; }
    .dual-cta-grid { grid-template-columns: 1fr; gap: 16px; }
    .cta-card { padding: 24px 18px; }

    /* Testimonials */
    .section-testimonials { padding: 40px 0; }
    .testimonials-grid { grid-template-columns: 1fr; gap: 14px; }

    /* FAQ */
    .section-faq { padding: 40px 0; }
    .faq-question { font-size: 0.9rem; padding: 14px 16px; }
    .faq-answer { padding: 0 16px 16px; font-size: 0.85rem; }

    /* Quick Contact */
    .section-quick-contact { padding: 20px 0 40px; }
    .quick-contact-card { flex-direction: column; text-align: center; padding: 25px 18px; border-radius: 16px; gap: 18px; }
    .qc-content h2 { font-size: 1.35rem; }
    .qc-actions { width: 100%; flex-direction: column; gap: 10px; }
    .btn-qc-wa, .btn-qc-contact { width: 100%; justify-content: center; }

    .hero-top-badge { font-size: 0.72rem; padding: 5px 12px; }
    .hero-quick-chips { gap: 6px; }
    .hero-chip { font-size: 0.72rem; padding: 4px 9px; }
}

@media (max-width: 400px) {
    .hero-content h1 { font-size: 1.35rem !important; }
    .annonce-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 5px !important; }
    .card-img-pro { height: 85px !important; }
    .categories-grid { grid-template-columns: 1fr 1fr !important; gap: 8px !important; }
    .locations-grid { grid-template-columns: 1fr !important; }
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
    font-size: 0.85rem;
    color: var(--gray);
    font-weight: 600;
    min-height: 18px;
}

/* Barre de recherche instantanée directe */
.smart-filter-search-row {
    width: 100%;
}
.smart-filter-search-box {
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    padding: 10px 18px;
    gap: 12px;
    transition: all 0.25s ease;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
}
.smart-filter-search-box:focus-within {
    background: white;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.08);
}
.smart-filter-search-box i {
    color: var(--secondary);
    font-size: 1.1rem;
}
.smart-filter-search-box input {
    border: none;
    background: transparent;
    outline: none;
    width: 100%;
    font-family: inherit;
    font-size: 0.98rem;
    color: #0f172a;
    font-weight: 500;
}
.smart-filter-search-box input::placeholder {
    color: #94a3b8;
}
.btn-clear-search {
    background: #e2e8f0;
    border: none;
    color: #64748b;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.75rem;
    transition: all 0.2s ease;
}
.btn-clear-search:hover {
    background: #ef4444;
    color: white;
}

/* Cartes cachées */
.annonce-card-pro.hidden { display: none !important; }
</style>

<script>
// Gestion des onglets Type dans le Hero
function setHeroType(btn, type) {
    document.querySelectorAll('.hero-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const heroType = document.getElementById('heroType');
    if (heroType) heroType.value = type;
}

// Ouvrir / Fermer le volet des filtres avancés du Hero
function toggleHeroFilters() {
    const pane = document.getElementById('heroAdvancedFilters');
    const toggleBtn = document.getElementById('btnToggleHeroFilters');
    if (!pane) return;
    
    const isVisible = pane.style.display !== 'none';
    pane.style.display = isVisible ? 'none' : 'block';
    if (toggleBtn) {
        toggleBtn.classList.toggle('active', !isVisible);
    }
}

// Mise à jour du badge indiquant le nombre de filtres avancés renseignés
function updateHeroFilterBadge() {
    const pane = document.getElementById('heroAdvancedFilters');
    const badge = document.getElementById('heroFilterBadge');
    if (!pane || !badge) return;

    const fields = pane.querySelectorAll('input, select');
    let count = 0;
    fields.forEach(field => {
        if (field.value && field.value.trim() !== '') count++;
    });

    if (count > 0) {
        badge.textContent = count;
        badge.style.display = 'inline-flex';
    } else {
        badge.style.display = 'none';
    }
}

// Suggestions rapides Hero (chips cliquables)
function quickHeroSearch(keyword) {
    const queryInput = document.getElementById('heroQuery');
    const form = document.getElementById('heroSearchForm');
    const clearBtn = document.getElementById('clearHeroQuery');
    if (queryInput) {
        queryInput.value = keyword;
        if (clearBtn) clearBtn.style.display = 'inline-flex';
        if (form) form.submit();
    }
}

// Accordéon FAQ interactif
function toggleFaq(btn) {
    const item = btn.closest('.faq-item');
    if (!item) return;
    const wasActive = item.classList.contains('active');
    
    // Fermer les autres questions
    document.querySelectorAll('.faq-item').forEach(el => {
        el.classList.remove('active');
    });

    if (!wasActive) {
        item.classList.add('active');
    }
}

// Effacer la saisie libre du Hero
function clearHeroQueryInput() {
    const queryInput = document.getElementById('heroQuery');
    const clearBtn = document.getElementById('clearHeroQuery');
    if (queryInput) {
        queryInput.value = '';
        queryInput.focus();
    }
    if (clearBtn) clearBtn.style.display = 'none';
}

// Réinitialiser tous les filtres instantanés
function clearAllFilters() {
    const homeInput = document.getElementById('homeLiveSearch');
    if (homeInput) homeInput.value = '';
    
    // Remettre le type sur Tout
    const typeGroup = document.getElementById('typeFilterHome');
    if (typeGroup) {
        typeGroup.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
        const allType = typeGroup.querySelector('[data-type="all"]');
        if (allType) allType.classList.add('active');
    }

    // Remettre la catégorie sur Tout
    const catGroup = document.getElementById('catFilterHome');
    if (catGroup) {
        catGroup.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
        const allCat = catGroup.querySelector('[data-cat="all"]');
        if (allCat) allCat.classList.add('active');
    }

    clearLiveSearch('home');
}

// Effacer la recherche en direct sur la liste
function clearLiveSearch(gridId) {
    const input = document.getElementById(gridId === 'home' ? 'homeLiveSearch' : 'listLiveSearch');
    const clearBtn = document.getElementById(gridId === 'home' ? 'clearHomeSearch' : 'clearListSearch');
    if (input) {
        input.value = '';
        input.focus();
    }
    if (clearBtn) clearBtn.style.display = 'none';
    applyFilter(gridId);
}

// Filtrage multi-critères en direct (Type + Catégorie + Mots-clés libres)
function applyFilter(gridId) {
    const catGroup  = document.getElementById('catFilter'  + (gridId === 'home' ? 'Home' : 'List'));
    const typeGroup = document.getElementById('typeFilter' + (gridId === 'home' ? 'Home' : 'List'));
    const grid      = document.getElementById(gridId === 'home' ? 'homeAnnonceGrid' : 'annoncesGrid');
    const countEl   = document.getElementById(gridId === 'home' ? 'homeResultCount' : 'listResultCount');
    const searchInput = document.getElementById(gridId === 'home' ? 'homeLiveSearch' : 'listLiveSearch');
    const clearBtn  = document.getElementById(gridId === 'home' ? 'clearHomeSearch' : 'clearListSearch');

    // Gestion du bouton cliqué (type ou catégorie) si déclenché par un clic
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

    const selCat  = catGroup ? (catGroup.querySelector('.smart-btn.active')?.dataset.cat  || 'all') : 'all';
    const selType = typeGroup ? (typeGroup.querySelector('.smart-btn.active')?.dataset.type || 'all') : 'all';

    // Mots-clés tapés librement
    const rawQuery = (searchInput ? searchInput.value : '').toLowerCase().trim();
    if (clearBtn) {
        clearBtn.style.display = rawQuery.length > 0 ? 'inline-flex' : 'none';
    }
    const queryWords = rawQuery.length > 0 ? rawQuery.split(/\s+/).filter(w => w.length > 0) : [];

    const cardSel = gridId === 'home' ? '#homeAnnonceGrid .annonce-card-pro' : '#annoncesGrid .annonce-card';
    const cards = document.querySelectorAll(cardSel);

    let visible = 0;
    cards.forEach(card => {
        const matchCat  = selCat  === 'all' || card.dataset.cat  === selCat;
        const matchType = selType === 'all' || card.dataset.type === selType;

        let matchQuery = true;
        if (queryWords.length > 0) {
            const cardText = (card.textContent || card.innerText || '').toLowerCase();
            matchQuery = queryWords.every(word => cardText.includes(word));
        }

        if (matchCat && matchType && matchQuery) {
            card.classList.remove('hidden');
            visible++;
        } else {
            card.classList.add('hidden');
        }
    });

    if (countEl) {
        countEl.textContent = visible === 0
            ? 'Aucun bien ne correspond à votre recherche.'
            : `${visible} bien${visible > 1 ? 's' : ''} trouvé${visible > 1 ? 's' : ''}`;
    }

    // Afficher/masquer message vide
    let emptyId = gridId === 'home' ? 'noResultsHome' : 'noResultsList';
    let msg = document.getElementById(emptyId);
    if (!msg && grid) {
        msg = document.createElement('div');
        msg.id = emptyId;
        msg.style.cssText = 'grid-column:1/-1;text-align:center;padding:60px 0;color:#64748b;';
        msg.innerHTML = '<i class="fas fa-search" style="font-size:2.5rem;display:block;margin-bottom:15px;color:#cbd5e1;"></i>Aucun bien ne correspond à votre recherche avec ces critères.';
        grid.appendChild(msg);
    }
    if (msg) {
        msg.style.display = visible === 0 ? 'block' : 'none';
    }
}

// Initialisation au chargement du DOM
document.addEventListener('DOMContentLoaded', function() {
    // Bouton clear pour la recherche Hero
    const heroQuery = document.getElementById('heroQuery');
    const clearHeroQuery = document.getElementById('clearHeroQuery');
    if (heroQuery && clearHeroQuery) {
        heroQuery.addEventListener('input', function() {
            clearHeroQuery.style.display = this.value.trim().length > 0 ? 'inline-flex' : 'none';
        });
    }

    // Sticky Search Bar Logic
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
