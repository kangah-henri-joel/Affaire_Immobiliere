<?php include __DIR__ . '/../layout_header.php'; ?>

<div class="admin-container">
    <aside class="admin-sidebar">
        <ul>
            <li><a href="<?php echo BASE_URL; ?>/admin"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/annonces"><i class="fas fa-home"></i> Annonces</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/publications"><i class="fas fa-bullhorn"></i> Publications</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/leads"><i class="fas fa-envelope"></i> Leads</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/consultants" class="active"><i class="fas fa-users"></i> Consultants</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/profil"><i class="fas fa-user-circle"></i> Profil</a></li>
            <li><a href="<?php echo BASE_URL; ?>/logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </aside>
    
    <main class="admin-content">
        <header class="admin-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1>Gestion des <span class="highlight">Consultants</span></h1>
                    <p style="color: var(--gray);">Gérez votre équipe d'agents immobiliers.</p>
                </div>
                <button class="btn-primary" onclick="toggleModal('addConsultantModal')"><i class="fas fa-user-plus"></i> Nouveau Consultant</button>
            </div>
        </header>

        <div class="annonce-table-card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nom complet</th>
                            <th>Identifiant</th>
                            <th>Date d'ajout</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($agents)): ?>
                            <?php foreach($agents as $agent): ?>
                                <tr>
                                    <td style="font-weight: 600;"><?php echo $agent['full_name']; ?></td>
                                    <td><?php echo $agent['username']; ?></td>
                                    <td style="color: var(--gray);"><?php echo date('d/m/Y', strtotime($agent['created_at'])); ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/admin/consultants/delete?id=<?php echo $agent['id']; ?>" class="btn-sm" style="color: var(--danger);" onclick="return confirm('Supprimer ce consultant ?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="text-align:center; padding: 40px; color: var(--gray);">Aucun consultant enregistré.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal Ajout Consultant -->
<div id="addConsultantModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <span class="close" onclick="toggleModal('addConsultantModal')">&times;</span>
        <h2>Ajouter un <span class="highlight">Consultant</span></h2>
        <form action="<?php echo BASE_URL; ?>/admin/consultants/save" method="POST">
            <input type="hidden" name="role" value="agent">
            <div class="form-group">
                <label>Nom complet</label>
                <input type="text" name="full_name" required>
            </div>
            <div class="form-group">
                <label>Identifiant (Login)</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-submit">Créer le compte</button>
        </form>
    </div>
</div>

<script>
function toggleModal(id) {
    const modal = document.getElementById(id);
    modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
}
</script>

<?php include __DIR__ . '/../layout_footer.php'; ?>
