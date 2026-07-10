<?php include __DIR__ . '/sa_header.php'; ?>

<div class="sa-layout">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="sa-main">
        <div class="sa-topbar">
            <button class="sa-hamburger" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
            <div class="sa-topbar-title">
                <h2><i class="fas fa-home" style="color:#22d3ee;margin-right:8px;"></i>Toutes les Annonces</h2>
                <p>Vue globale des annonces immobilières</p>
            </div>
            <div class="sa-topbar-actions">
                <div class="sa-badge-role"><i class="fas fa-crown"></i> Super Admin</div>
                <a href="<?php echo BASE_URL; ?>/admin/annonces" class="sa-btn sa-btn-primary sa-btn-sm"><i class="fas fa-plus"></i></a>
            </div>
        </div>

        <?php if(isset($_GET['success'])): ?>
        <div style="background:#064e3b;color:#6ee7b7;padding:12px 20px;border-radius:8px;margin:10px 20px 0;font-weight:600;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-check-circle"></i>
            <?php echo $_GET['success']==='deleted' ? 'Annonce supprimée avec succès.' : 'Opération réussie.'; ?>
        </div>
        <?php endif; ?>

        <div class="sa-content">
            <div class="sa-card">
                <div class="sa-card-header">
                    <h3><i class="fas fa-list" style="color:#22d3ee;margin-right:7px;"></i>Annonces (<?php echo count($annonces); ?>)</h3>
                    <input type="text" class="sa-search" id="annonceSearch" placeholder="🔍 Rechercher…" onkeyup="filterTable('annonceTable','annonceSearch')">
                </div>
                <div class="sa-table-wrap">
                <table class="sa-table" id="annonceTable">
                    <thead><tr><th>#</th><th>Titre</th><th>Catégorie</th><th>Prix</th><th>Type</th><th>Statut</th><th>Vues</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php if (!empty($annonces)): ?>
                        <?php foreach ($annonces as $a): ?>
                        <tr>
                            <td style="color:var(--sa-gray);"><?php echo $a['id']; ?></td>
                            <td>
                                <strong style="color:white;"><?php echo htmlspecialchars($a['title']); ?></strong>
                                <?php if(!empty($a['location_name'])): ?><br><span style="color:var(--sa-gray);font-size:11px;"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($a['location_name']); ?></span><?php endif; ?>
                            </td>
                            <td style="color:var(--sa-gray);white-space:nowrap;"><?php echo htmlspecialchars($a['category_name']??'—'); ?></td>
                            <td style="color:var(--sa-gold);font-weight:700;white-space:nowrap;"><?php echo number_format($a['price'],0,',',' '); ?></td>
                            <td style="color:var(--sa-accent);"><?php echo ucfirst($a['type']); ?></td>
                            <td>
                                <?php $st=$a['status']==='loué'?'contacted':($a['status']==='vendu'?'closed':'new'); ?>
                                <span class="lead-badge lead-<?php echo $st; ?>"><?php echo ucfirst($a['status']); ?></span>
                            </td>
                            <td style="color:var(--sa-gray);"><?php echo $a['views_count']; ?></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>/super-admin/annonces/delete?id=<?php echo $a['id']; ?>" class="sa-btn sa-btn-danger sa-btn-sm" onclick="return confirm('Supprimer définitivement cette annonce et toutes ses données (médias, leads, publications) ?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" style="text-align:center;padding:36px;color:var(--sa-gray);">Aucune annonce.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function filterTable(tableId, searchId) {
    const q = document.getElementById(searchId).value.toLowerCase();
    document.querySelectorAll('#'+tableId+' tbody tr').forEach(r => {
        r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>

<?php include __DIR__ . '/sa_footer.php'; ?>
