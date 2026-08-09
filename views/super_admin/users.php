<?php include __DIR__ . '/sa_header.php'; ?>

<div class="sa-layout">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="sa-main">
        <div class="sa-topbar">
            <button class="sa-hamburger" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
            <div class="sa-topbar-title">
                <h2><i class="fas fa-users-cog" style="color:var(--sa-accent);margin-right:8px;"></i>Tous les Utilisateurs</h2>
                <p>Gérer admins, agents et super admins</p>
            </div>
            <div class="sa-topbar-actions">
                <div class="sa-badge-role"><i class="fas fa-crown"></i> Super Admin</div>
                <button class="sa-btn sa-btn-primary" onclick="openModal('addUserModal')"><i class="fas fa-user-plus"></i></button>
            </div>
        </div>

        <div class="sa-content">
            <?php if (isset($_GET['success'])): ?>
                <div class="sa-alert sa-alert-success"><i class="fas fa-check-circle"></i> Opération réussie.</div>
            <?php endif; ?>

            <div class="sa-card">
                <div class="sa-card-header">
                    <h3><i class="fas fa-list" style="color:var(--sa-accent);margin-right:7px;"></i>Tous les comptes (<?php echo count($users); ?>)</h3>
                    <input type="text" class="sa-search" id="userSearch" placeholder="🔍 Rechercher…" onkeyup="filterTable('userTable','userSearch')">
                </div>
                <div class="sa-table-wrap">
                <table class="sa-table" id="userTable">
                    <thead><tr><th>#</th><th>Nom</th><th>Login</th><th>Rôle</th><th>Changer rôle</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td style="color:var(--sa-gray);"><?php echo $u['id']; ?></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:9px;">
                                    <div class="sa-avatar-sm"><?php echo strtoupper(substr($u['full_name']??$u['username'],0,1)); ?></div>
                                    <div>
                                        <strong style="color:white;font-size:13px;"><?php echo htmlspecialchars($u['full_name']??'—'); ?></strong>
                                        <?php if($u['id']==$_SESSION['user_id']): ?><span style="color:var(--sa-gold);font-size:10px;"> (vous)</span><?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td style="color:var(--sa-gray);">@<?php echo htmlspecialchars($u['username']); ?></td>
                            <td>
                                <span class="role-badge role-<?php echo $u['role']; ?>"><?php echo str_replace('_',' ',$u['role']); ?></span>
                                <?php if($u['status'] === 'pending'): ?>
                                    <span style="background:var(--sa-warning); color:#fff; padding:2px 5px; border-radius:3px; font-size:10px; margin-left:5px;">En attente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($u['id']!=$_SESSION['user_id'] && $u['role']!=='super_admin'): ?>
                                <form action="<?php echo BASE_URL; ?>/super-admin/users/role" method="POST" style="display:flex;gap:5px;align-items:center;">
                                    <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                    <select name="role" style="background:rgba(255,255,255,0.06);border:1px solid var(--sa-border);border-radius:6px;padding:5px 8px;color:white;font-size:12px;">
                                        <option value="admin" <?php echo $u['role']==='admin'?'selected':''; ?>>Admin</option>
                                        <option value="agent" <?php echo $u['role']==='agent'?'selected':''; ?>>Agent</option>
                                        <option value="client" <?php echo $u['role']==='client'?'selected':''; ?>>Client</option>
                                    </select>
                                    <button type="submit" class="sa-btn sa-btn-warning sa-btn-sm"><i class="fas fa-exchange-alt"></i></button>
                                </form>
                                <?php else: ?><span style="color:var(--sa-gray);font-size:12px;">—</span><?php endif; ?>
                            </td>
                            <td>
                                <?php if($u['id']!=$_SESSION['user_id'] && $u['role']!=='super_admin'): ?>
                                <?php if($u['status'] === 'pending'): ?>
                                <a href="<?php echo BASE_URL; ?>/super-admin/users/validate?id=<?php echo $u['id']; ?>" class="sa-btn sa-btn-success sa-btn-sm" onclick="return confirm('Valider ce compte agent ?')" title="Valider"><i class="fas fa-check"></i></a>
                                <?php endif; ?>
                                <a href="<?php echo BASE_URL; ?>/super-admin/users/delete?id=<?php echo $u['id']; ?>" class="sa-btn sa-btn-danger sa-btn-sm" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></a>
                                <?php else: ?><span style="color:var(--sa-gray);font-size:11px;">Protégé</span><?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajout -->
<div id="addUserModal" class="sa-modal">
    <div class="sa-modal-box">
        <button class="sa-modal-close" onclick="closeModal('addUserModal')"><i class="fas fa-times"></i></button>
        <h2><i class="fas fa-user-plus" style="color:var(--sa-accent);margin-right:8px;"></i>Nouvel Utilisateur</h2>
        <form action="<?php echo BASE_URL; ?>/super-admin/users/save" method="POST">
            <input type="hidden" name="id" value="">
            <div class="sa-form-group"><label>Nom complet</label><input type="text" name="full_name" required placeholder="Jean Dupont"></div>
            <div class="sa-form-group"><label>Login</label><input type="text" name="username" required placeholder="jean_dupont"></div>
            <div class="sa-form-group"><label>Mot de passe</label><input type="password" name="password" required placeholder="••••••••"></div>
            <div class="sa-form-group"><label>Rôle</label>
                <select name="role">
                    <option value="agent">Agent</option>
                    <option value="admin">Administrateur</option>
                    <option value="super_admin">Super Admin</option>
                    <option value="client">Client</option>
                </select>
            </div>
            <button type="submit" class="sa-btn sa-btn-primary" style="width:100%;justify-content:center;"><i class="fas fa-save"></i> Créer</button>
        </form>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
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
