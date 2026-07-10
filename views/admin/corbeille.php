<?php include __DIR__ . '/../layout_header.php'; ?>

<div class="admin-container">
    <?php include __DIR__ . '/sidebar.php'; ?>

    <main class="admin-content">
        <header class="admin-header">
            <div class="header-flex">
                <div>
                    <h1><i class="fas fa-trash-alt" style="color:#ef4444;"></i> Corbeille</h1>
                    <p style="color:var(--gray);font-size:0.9rem;margin-top:4px;">
                        Les annonces ici sont masquées du site. Restaurez-les ou supprimez-les définitivement.
                    </p>
                </div>
                <a href="<?php echo BASE_URL; ?>/admin/annonces" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Retour aux Annonces
                </a>
            </div>
        </header>

        <?php if (isset($_GET['success'])): ?>
        <div class="alert-success">
            <?php if ($_GET['success'] === 'restored'): ?>
                <i class="fas fa-check-circle"></i> Annonce restaurée avec succès.
            <?php elseif ($_GET['success'] === 'deleted'): ?>
                <i class="fas fa-check-circle"></i> Annonce supprimée définitivement.
            <?php elseif ($_GET['success'] === 'emptied'): ?>
                <i class="fas fa-check-circle"></i> La corbeille a été vidée.
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($annonces)): ?>
        <div class="trash-actions-bar">
            <span class="trash-count">
                <i class="fas fa-trash-alt"></i>
                <?php echo count($annonces); ?> annonce<?php echo count($annonces) > 1 ? 's' : ''; ?> dans la corbeille
            </span>
            <form action="<?php echo BASE_URL; ?>/admin/annonces/empty-trash" method="POST"
                  onsubmit="return confirm('⚠️ Vider la corbeille supprimera DÉFINITIVEMENT toutes les annonces. Cette action est irréversible. Continuer ?');">
                <button type="submit" class="btn-empty-trash">
                    <i class="fas fa-fire-alt"></i> Vider la corbeille
                </button>
            </form>
        </div>
        <?php endif; ?>

        <div class="trash-grid">
            <?php if (empty($annonces)): ?>
            <div class="trash-empty">
                <div class="trash-empty-icon">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <h3>La corbeille est vide</h3>
                <p>Les annonces que vous supprimez apparaîtront ici avant leur suppression définitive.</p>
                <a href="<?php echo BASE_URL; ?>/admin/annonces" class="btn-back" style="display:inline-flex;margin-top:10px;">
                    <i class="fas fa-arrow-left"></i> Retour aux annonces
                </a>
            </div>
            <?php else: ?>
                <?php foreach ($annonces as $a): ?>
                <div class="trash-card">
                    <div class="trash-card-img">
                        <?php if (($a['media_type'] ?? 'image') === 'video'): ?>
                            <div class="trash-video-placeholder">
                                <i class="fas fa-play-circle"></i>
                            </div>
                        <?php else: ?>
                            <img src="<?php echo BASE_URL . ($a['image_path'] ?? '/assets/images/placeholder.jpg'); ?>"
                                 alt="<?php echo htmlspecialchars($a['title']); ?>">
                        <?php endif; ?>
                        <div class="trash-overlay">
                            <i class="fas fa-trash-alt"></i>
                            <span>Supprimée</span>
                        </div>
                    </div>
                    <div class="trash-card-body">
                        <div class="trash-card-meta">
                            <span class="trash-cat-badge"><?php echo htmlspecialchars($a['category_name']); ?></span>
                            <span class="trash-type-badge <?php echo $a['type']; ?>"><?php echo ucfirst($a['type']); ?></span>
                        </div>
                        <h3 class="trash-card-title"><?php echo htmlspecialchars($a['title']); ?></h3>
                        <p class="trash-card-loc">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($a['location_name'] ?? 'Lieu non précisé'); ?>
                        </p>
                        <p class="trash-card-price"><?php echo number_format($a['price'], 0, ',', ' '); ?> FCFA</p>
                        <p class="trash-card-date">
                            <i class="fas fa-calendar-times"></i>
                            Supprimée le <?php echo date('d/m/Y à H:i', strtotime($a['deleted_at'])); ?>
                        </p>
                        <div class="trash-card-actions">
                            <form action="<?php echo BASE_URL; ?>/admin/annonces/restore" method="POST" style="flex:1;">
                                <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                                <button type="submit" class="btn-restore">
                                    <i class="fas fa-undo-alt"></i> Restaurer
                                </button>
                            </form>
                            <form action="<?php echo BASE_URL; ?>/admin/annonces/hard-delete" method="POST" style="flex:1;"
                                  onsubmit="return confirm('❌ Supprimer définitivement « <?php echo addslashes($a['title']); ?> » ? Cette action est irréversible.');">
                                <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                                <button type="submit" class="btn-hard-delete">
                                    <i class="fas fa-times-circle"></i> Supprimer définitivement
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</div>

<style>
/* ── EN-TÊTE ────────────────────────────────── */
.header-flex { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
.btn-back {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; border-radius: 10px;
    background: var(--light); color: var(--primary);
    border: 1.5px solid var(--border); font-weight: 700;
    font-size: 0.88rem; text-decoration: none;
    transition: all 0.2s;
}
.btn-back:hover { background: var(--primary); color: white; border-color: var(--primary); }

/* ── ALERTE ─────────────────────────────────── */
.alert-success {
    background: #f0fdf4; border: 1.5px solid #bbf7d0;
    color: #15803d; padding: 14px 20px; border-radius: 12px;
    margin-bottom: 24px; font-weight: 600;
    display: flex; align-items: center; gap: 10px;
}

/* ── BARRE D'ACTIONS ────────────────────────── */
.trash-actions-bar {
    display: flex; justify-content: space-between; align-items: center;
    background: #fff7ed; border: 1.5px solid #fed7aa;
    border-radius: 12px; padding: 14px 20px; margin-bottom: 28px;
}
.trash-count {
    display: flex; align-items: center; gap: 8px;
    font-weight: 700; color: #92400e; font-size: 0.9rem;
}
.btn-empty-trash {
    display: inline-flex; align-items: center; gap: 8px;
    background: #ef4444; color: white; border: none;
    padding: 9px 20px; border-radius: 8px;
    font-weight: 700; font-size: 0.85rem; cursor: pointer;
    transition: all 0.2s;
}
.btn-empty-trash:hover { background: #dc2626; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(239,68,68,0.35); }

/* ── GRILLE DES CARTES ──────────────────────── */
.trash-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
}

/* ── ÉTAT VIDE ──────────────────────────────── */
.trash-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
    color: var(--gray);
}
.trash-empty-icon {
    width: 90px; height: 90px;
    background: #fef2f2; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 24px;
    font-size: 2.5rem; color: #fca5a5;
}
.trash-empty h3 { font-size: 1.4rem; color: var(--primary); margin-bottom: 10px; }
.trash-empty p { font-size: 0.95rem; max-width: 360px; margin: 0 auto; }

/* ── CARTES ─────────────────────────────────── */
.trash-card {
    background: white;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.07);
    border: 1.5px solid #e2e8f0;
    opacity: 0.9;
    transition: all 0.25s;
}
.trash-card:hover { opacity: 1; transform: translateY(-4px); box-shadow: 0 8px 30px rgba(0,0,0,0.12); }

.trash-card-img {
    position: relative; height: 200px; overflow: hidden;
}
.trash-card-img img { width: 100%; height: 100%; object-fit: cover; filter: grayscale(30%); }
.trash-video-placeholder {
    width: 100%; height: 100%; background: #1e293b;
    display: flex; align-items: center; justify-content: center;
    font-size: 3rem; color: #64748b;
}
.trash-overlay {
    position: absolute; inset: 0;
    background: rgba(239,68,68,0.12);
    display: flex; flex-direction: column;
    align-items: flex-start; justify-content: flex-start;
    padding: 14px;
    gap: 4px;
}
.trash-overlay i { font-size: 1.1rem; color: #ef4444; }
.trash-overlay span {
    font-size: 0.72rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: 1px;
    color: #ef4444; background: rgba(255,255,255,0.9);
    padding: 3px 10px; border-radius: 20px;
}

/* ── CORPS DE CARTE ─────────────────────────── */
.trash-card-body { padding: 20px; }
.trash-card-meta { display: flex; gap: 8px; margin-bottom: 10px; flex-wrap: wrap; }
.trash-cat-badge {
    font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.8px; color: var(--secondary);
}
.trash-type-badge {
    font-size: 0.7rem; font-weight: 700;
    padding: 2px 9px; border-radius: 20px;
}
.trash-type-badge.vente { background: #fef3c7; color: #92400e; }
.trash-type-badge.location { background: #dcfce7; color: #15803d; }

.trash-card-title {
    font-size: 1.05rem; font-weight: 700; color: var(--primary);
    margin-bottom: 6px; line-height: 1.3;
}
.trash-card-loc { font-size: 0.83rem; color: var(--gray); margin-bottom: 4px; }
.trash-card-price { font-size: 1.2rem; font-weight: 800; color: var(--secondary); margin-bottom: 6px; }
.trash-card-date {
    font-size: 0.78rem; color: #ef4444; font-weight: 600;
    margin-bottom: 16px;
    display: flex; align-items: center; gap: 6px;
}

/* ── ACTIONS CARTE ──────────────────────────── */
.trash-card-actions { display: flex; gap: 8px; }
.btn-restore, .btn-hard-delete {
    display: flex; align-items: center; justify-content: center;
    gap: 7px; padding: 10px 8px; border-radius: 9px;
    border: none; font-weight: 700; font-size: 0.8rem;
    cursor: pointer; transition: all 0.2s; width: 100%;
}
.btn-restore {
    background: #f0fdf4; color: #15803d; border: 1.5px solid #bbf7d0;
}
.btn-restore:hover {
    background: #22c55e; color: white; border-color: #22c55e;
    transform: translateY(-1px); box-shadow: 0 4px 12px rgba(34,197,94,0.3);
}
.btn-hard-delete {
    background: #fef2f2; color: #ef4444; border: 1.5px solid #fecaca;
}
.btn-hard-delete:hover {
    background: #ef4444; color: white; border-color: #ef4444;
    transform: translateY(-1px); box-shadow: 0 4px 12px rgba(239,68,68,0.3);
}

@media (max-width: 768px) {
    .trash-grid { grid-template-columns: 1fr; }
    .trash-actions-bar { flex-direction: column; gap: 12px; align-items: stretch; text-align: center; }
}
</style>

<?php include __DIR__ . '/../layout_footer.php'; ?>
