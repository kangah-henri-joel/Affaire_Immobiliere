<?php include __DIR__ . '/../layout_header.php'; ?>

<div class="admin-container">
    <aside class="admin-sidebar">
        <ul>
            <li><a href="<?php echo BASE_URL; ?>/admin"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/annonces"><i class="fas fa-home"></i> Annonces</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/publications"><i class="fas fa-bullhorn"></i> Publications</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/leads"><i class="fas fa-envelope"></i> Leads</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/consultants"><i class="fas fa-users"></i> Consultants</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/profil" class="active"><i class="fas fa-user-circle"></i> Profil</a></li>
            <li><a href="<?php echo BASE_URL; ?>/logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </aside>
    
    <main class="admin-content">
        <header class="admin-header">
            <h1>Mon <span class="highlight">Profil</span></h1>
            <p style="color: var(--gray);">Gérez vos informations personnelles et vos accès.</p>
        </header>

        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success" style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i> Profil mis à jour avec succès !
            </div>
        <?php endif; ?>

        <div class="annonce-table-card" style="padding: 40px; max-width: 600px;">
            <form action="<?php echo BASE_URL; ?>/admin/profil" method="POST">
                <div class="form-group">
                    <label>Nom complet</label>
                    <input type="text" name="full_name" value="<?php echo $user['full_name']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Nom d'utilisateur</label>
                    <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Nouveau mot de passe (Laissez vide pour ne pas changer)</label>
                    <input type="password" name="password" placeholder="••••••••">
                </div>
                
                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
            </form>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>
