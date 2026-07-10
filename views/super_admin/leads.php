<?php include __DIR__ . '/sa_header.php'; ?>

<div class="sa-layout">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="sa-main">
        <div class="sa-topbar">
            <button class="sa-hamburger" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
            <div class="sa-topbar-title">
                <h2><i class="fas fa-address-book" style="color:var(--sa-gold);margin-right:8px;"></i>Clients / Leads</h2>
                <p>Suivi complet de tous les contacts</p>
            </div>
            <div class="sa-topbar-actions">
                <div class="sa-badge-role"><i class="fas fa-crown"></i> Super Admin</div>
            </div>
        </div>

        <div class="sa-content">
            <?php if (isset($_GET['success'])): ?>
                <div class="sa-alert sa-alert-success"><i class="fas fa-check-circle"></i> Opération réussie.</div>
            <?php endif; ?>

            <!-- Stats mini -->
            <div class="sa-stats-mini">
                <div class="sa-stat-mini">
                    <div class="icon" style="background:rgba(99,102,241,0.15);color:#818cf8;"><i class="fas fa-inbox"></i></div>
                    <div><h5>Nouveaux</h5><div class="n"><?php echo $stats['new']; ?></div></div>
                </div>
                <div class="sa-stat-mini">
                    <div class="icon" style="background:rgba(245,158,11,0.15);color:#fbbf24;"><i class="fas fa-phone-alt"></i></div>
                    <div><h5>Contactés</h5><div class="n"><?php echo $stats['contacted']; ?></div></div>
                </div>
                <div class="sa-stat-mini">
                    <div class="icon" style="background:rgba(16,185,129,0.12);color:#34d399;"><i class="fas fa-check-double"></i></div>
                    <div><h5>Clôturés</h5><div class="n"><?php echo $stats['closed']; ?></div></div>
                </div>
            </div>

            <div class="sa-card">
                <div class="sa-card-header">
                    <h3><i class="fas fa-list" style="color:var(--sa-gold);margin-right:7px;"></i>Tous les leads</h3>
                    <input type="text" class="sa-search" id="leadSearch" placeholder="🔍 Rechercher…" onkeyup="filterTable('leadTable','leadSearch')">
                </div>
                <div class="sa-table-wrap">
                <table class="sa-table" id="leadTable">
                    <thead><tr><th>#</th><th>Client</th><th>Contact</th><th>Statut</th><th>Date</th><th>Actions</th></tr></thead>
                    <tbody>
                    <?php if (!empty($leads)): ?>
                        <?php foreach ($leads as $lead): ?>
                        <tr>
                            <td style="color:var(--sa-gray);"><?php echo $lead['id']; ?></td>
                            <td>
                                <strong style="color:white;display:block;"><?php echo htmlspecialchars($lead['client_name']??'—'); ?></strong>
                                <span style="color:var(--sa-gray);font-size:11px;"><?php echo htmlspecialchars(substr($lead['message']??'',0,40)); ?>…</span>
                            </td>
                            <td>
                                <div style="color:var(--sa-text);font-size:12px;"><?php echo htmlspecialchars($lead['client_phone']??''); ?></div>
                                <div style="color:var(--sa-gray);font-size:11px;"><?php echo htmlspecialchars($lead['client_email']??''); ?></div>
                            </td>
                            <td><span class="lead-badge lead-<?php echo $lead['status']; ?>"><?php echo ucfirst($lead['status']); ?></span></td>
                            <td style="color:var(--sa-gray);white-space:nowrap;font-size:12px;"><?php echo date('d/m/Y',strtotime($lead['created_at'])); ?></td>
                            <td>
                                <div style="display:flex;gap:5px;">
                                    <button class="sa-btn sa-btn-warning sa-btn-sm" onclick="openStatusModal(<?php echo $lead['id']; ?>,'<?php echo $lead['status']; ?>')"><i class="fas fa-edit"></i></button>
                                    <a href="<?php echo BASE_URL; ?>/super-admin/leads/delete?id=<?php echo $lead['id']; ?>" class="sa-btn sa-btn-danger sa-btn-sm" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center;padding:36px;color:var(--sa-gray);">Aucun lead.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal statut -->
<div id="statusModal" class="sa-modal">
    <div class="sa-modal-box" style="max-width:340px;">
        <button class="sa-modal-close" onclick="closeModal('statusModal')"><i class="fas fa-times"></i></button>
        <h2><i class="fas fa-tag" style="color:var(--sa-gold);margin-right:8px;"></i>Changer le statut</h2>
        <form action="<?php echo BASE_URL; ?>/super-admin/leads/status" method="POST">
            <input type="hidden" name="lead_id" id="status_lead_id">
            <div class="sa-form-group"><label>Statut</label>
                <select name="status" id="status_select">
                    <option value="new">Nouveau</option>
                    <option value="contacted">Contacté</option>
                    <option value="closed">Clôturé</option>
                </select>
            </div>
            <button type="submit" class="sa-btn sa-btn-primary" style="width:100%;justify-content:center;"><i class="fas fa-save"></i> Mettre à jour</button>
        </form>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
function openStatusModal(id, status) {
    document.getElementById('status_lead_id').value = id;
    document.getElementById('status_select').value  = status;
    openModal('statusModal');
}
function filterTable(tableId, searchId) {
    const q = document.getElementById(searchId).value.toLowerCase();
    document.querySelectorAll('#'+tableId+' tbody tr').forEach(r => {
        r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
document.querySelectorAll('.sa-modal').forEach(m => {
    m.addEventListener('click', e => { if(e.target===m) m.classList.remove('open'); });
});
</script>

<?php include __DIR__ . '/sa_footer.php'; ?>
