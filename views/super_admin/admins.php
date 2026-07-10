<?php include __DIR__ . '/sa_header.php'; ?>

<div class="sa-layout">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="sa-main">
        <div class="sa-topbar">
            <button class="sa-hamburger" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
            <div class="sa-topbar-title">
                <h2><i class="fas fa-user-tie" style="color:var(--sa-accent);margin-right:8px;"></i>Administrateurs</h2>
                <p>Créer, modifier et supprimer des admins</p>
            </div>
            <div class="sa-topbar-actions">
                <div class="sa-badge-role"><i class="fas fa-crown"></i> Super Admin</div>
                <button class="sa-btn sa-btn-primary" onclick="openModal('addAdminModal')">
                    <i class="fas fa-user-plus"></i> <span class="hide-xs">Nouvel Admin</span>
                </button>
            </div>
        </div>

        <div class="sa-content">
            <?php if (isset($_GET['success'])): ?>
                <div class="sa-alert sa-alert-success"><i class="fas fa-check-circle"></i> <?php echo $_GET['success']==='deleted'?'Admin supprimé.':'Admin enregistré.'; ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['error']) && $_GET['error']==='self_delete'): ?>
                <div class="sa-alert sa-alert-danger"><i class="fas fa-exclamation-triangle"></i> Impossible de supprimer votre propre compte.</div>
            <?php endif; ?>

            <div class="sa-card">
                <div class="sa-card-header">
                    <h3><i class="fas fa-list" style="color:var(--sa-accent);margin-right:7px;"></i>Liste des Admins</h3>
                    <span style="color:var(--sa-gray);font-size:12px;"><?php echo count($admins); ?> admin(s)</span>
                </div>
                <div class="sa-table-wrap">
                <table class="sa-table">
                    <thead><tr><th>#</th><th>Nom</th><th>Login</th><th>Rôle</th><th>Créé</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php if (!empty($admins)): ?>
                            <?php foreach ($admins as $a): ?>
                            <tr>
                                <td style="color:var(--sa-gray);"><?php echo $a['id']; ?></td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:9px;">
                                        <div class="sa-avatar-sm"><?php echo strtoupper(substr($a['full_name']??$a['username'],0,1)); ?></div>
                                        <strong style="color:white;"><?php echo htmlspecialchars($a['full_name']??'—'); ?></strong>
                                    </div>
                                </td>
                                <td style="color:var(--sa-gray);">@<?php echo htmlspecialchars($a['username']); ?></td>
                                <td><span class="role-badge role-<?php echo $a['role']; ?>"><?php echo str_replace('_',' ',$a['role']); ?></span></td>
                                <td style="color:var(--sa-gray);white-space:nowrap;"><?php echo date('d/m/Y',strtotime($a['created_at'])); ?></td>
                                <td>
                                    <div style="display:flex;gap:5px;">
                                        <button class="sa-btn sa-btn-warning sa-btn-sm" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($a)); ?>)"><i class="fas fa-edit"></i></button>
                                        <?php if ($a['id'] != $_SESSION['user_id']): ?>
                                        <a href="<?php echo BASE_URL; ?>/super-admin/admins/delete?id=<?php echo $a['id']; ?>" class="sa-btn sa-btn-danger sa-btn-sm" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align:center;padding:36px;color:var(--sa-gray);">Aucun administrateur.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajout -->
<div id="addAdminModal" class="sa-modal">
    <div class="sa-modal-box">
        <button class="sa-modal-close" onclick="closeModal('addAdminModal')"><i class="fas fa-times"></i></button>
        <h2><i class="fas fa-user-plus" style="color:var(--sa-accent);margin-right:8px;"></i>Nouvel Administrateur</h2>
        <form action="<?php echo BASE_URL; ?>/super-admin/admins/save" method="POST">
            <input type="hidden" name="id" value="">
            <div class="sa-form-group"><label>Nom complet</label><input type="text" name="full_name" required placeholder="Jean Dupont"></div>
            <div class="sa-form-group"><label>Login</label><input type="text" name="username" required placeholder="jean_admin"></div>
            <div class="sa-form-group"><label>Mot de passe</label><input type="password" name="password" required placeholder="••••••••"></div>
            <div class="sa-form-group"><label>Rôle</label><select name="role"><option value="admin">Administrateur</option><option value="super_admin">Super Admin</option></select></div>
            <button type="submit" class="sa-btn sa-btn-primary" style="width:100%;justify-content:center;"><i class="fas fa-save"></i> Créer</button>
        </form>
    </div>
</div>

<!-- Modal Modification -->
<div id="editAdminModal" class="sa-modal">
    <div class="sa-modal-box">
        <button class="sa-modal-close" onclick="closeModal('editAdminModal')"><i class="fas fa-times"></i></button>
        <h2><i class="fas fa-user-edit" style="color:var(--sa-gold);margin-right:8px;"></i>Modifier l'Admin</h2>
        <form action="<?php echo BASE_URL; ?>/super-admin/admins/save" method="POST">
            <input type="hidden" name="id" id="edit_id">
            <div class="sa-form-group"><label>Nom complet</label><input type="text" name="full_name" id="edit_full_name" required></div>
            <div class="sa-form-group"><label>Login</label><input type="text" name="username" id="edit_username" required></div>
            <div class="sa-form-group"><label>Nouveau mot de passe <span style="color:var(--sa-gray);font-weight:400;">(vide = inchangé)</span></label><input type="password" name="password" placeholder="••••••••"></div>
            <div class="sa-form-group"><label>Rôle</label><select name="role" id="edit_role"><option value="admin">Administrateur</option><option value="super_admin">Super Admin</option><option value="agent">Agent</option></select></div>
            <button type="submit" class="sa-btn sa-btn-primary" style="width:100%;justify-content:center;"><i class="fas fa-save"></i> Enregistrer</button>
        </form>
    </div>
</div>

<style>
@media (max-width:480px) { .hide-xs { display:none; } }
</style>

<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
function openEditModal(a) {
    document.getElementById('edit_id').value        = a.id;
    document.getElementById('edit_full_name').value = a.full_name || '';
    document.getElementById('edit_username').value  = a.username;
    document.getElementById('edit_role').value      = a.role;
    openModal('editAdminModal');
}
document.querySelectorAll('.sa-modal').forEach(m => {
    m.addEventListener('click', e => { if(e.target===m) m.classList.remove('open'); });
});
</script>

<?php include __DIR__ . '/sa_footer.php'; ?>
