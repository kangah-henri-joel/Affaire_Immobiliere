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
                    <button class="smart-btn cat-btn active" data-cat="all" onclick="applyFilter('list')">
                        <i class="fas fa-th-large"></i> Tout
                    </button>
                    <button class="smart-btn cat-btn" data-cat="terrain" onclick="applyFilter('list')">
                        <i class="fas fa-mountain"></i> Terrain
                    </button>
                    <button class="smart-btn cat-btn" data-cat="maison" onclick="applyFilter('list')">
                        <i class="fas fa-home"></i> Maison
                    </button>
                    <button class="smart-btn cat-btn" data-cat="engins" onclick="applyFilter('list')">
                        <i class="fas fa-car"></i> Engins (Voiture, Moto)
                    </button>
                </div>
            </div>
            <div class="filter-result-count" id="listResultCount">
                <?php echo count($annonces); ?> bien<?php echo count($annonces) > 1 ? 's' : ''; ?> disponible<?php echo count($annonces) > 1 ? 's' : ''; ?>
            </div>
        </div>

        <div class="filter-bar">
            <form action="<?php echo BASE_URL; ?>/annonces" method="GET">
                <div class="filter-group">
                    <label>Catégorie</label>
                    <select name="category">
                        <option value="">Toutes</option>
                        <option value="terrain" <?php echo (($_GET['category'] ?? '') === 'terrain') ? 'selected' : ''; ?>>Terrains</option>
                        <option value="maison" <?php echo (($_GET['category'] ?? '') === 'maison') ? 'selected' : ''; ?>>Maisons</option>
                        <option value="vehicule" <?php echo (($_GET['category'] ?? '') === 'vehicule') ? 'selected' : ''; ?>>Véhicules</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Prix Max (FCFA)</label>
                    <input type="number" name="price_max" placeholder="Ex: 10000000" value="<?php echo htmlspecialchars($_GET['price_max'] ?? ''); ?>">
                </div>
                <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> Filtrer</button>
            </form>
        </div>

        <div class="annonce-grid" id="annoncesGrid">
            <?php if (empty($annonces)): ?>
                <p style="grid-column:1/-1;text-align:center;color:var(--gray);padding:60px 0;">Aucune annonce trouvée.</p>
            <?php else: ?>
                <?php foreach ($annonces as $annonce): ?>
                    <div class="annonce-card"
                         data-type="<?php echo $annonce['type']; ?>"
                         data-cat="<?php
                            $slug = strtolower($annonce['category_name'] ?? '');
                            if (str_contains($slug, 'terrain')) echo 'terrain';
                            elseif (str_contains($slug, 'maison') || str_contains($slug, 'studio') || str_contains($slug, 'magasin') || str_contains($slug, 'appartement')) echo 'maison';
                            elseif (str_contains($slug, 'v') || str_contains($slug, 'engin') || str_contains($slug, 'auto') || str_contains($slug, 'car')) echo 'engins';
                            else echo 'autre';
                         ?>">
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
                                        require_once __DIR__ . '/../../config/SiteUrl.php';
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
</style>

<script>
function applyFilter(gridId) {
    const catGroup  = document.getElementById('catFilter'  + (gridId === 'home' ? 'Home' : 'List'));
    const typeGroup = document.getElementById('typeFilter' + (gridId === 'home' ? 'Home' : 'List'));
    const grid      = document.getElementById(gridId === 'home' ? 'homeAnnonceGrid' : 'annoncesGrid');
    const countEl   = document.getElementById(gridId === 'home' ? 'homeResultCount' : 'listResultCount');

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
            : `${visible} bien${visible > 1 ? 's' : ''} disponible${visible > 1 ? 's' : ''}`;
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
</script>


<?php include __DIR__ . '/../layout_footer.php'; ?>

