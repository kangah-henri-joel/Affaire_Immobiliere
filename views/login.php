<?php include 'layout_header.php'; ?>

<section class="login-section">
    <div class="container">
        <div class="login-card">
            <h2>Espace Administrateur</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="<?php echo BASE_URL; ?>/login" method="POST">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" class="btn-primary">Se connecter</button>
            </form>
        </div>
    </div>
</section>

<style>
.login-section {
    padding: 100px 0;
    display: flex;
    justify-content: center;
}
.login-card {
    max-width: 400px;
    margin: 0 auto;
    background: var(--white);
    padding: 40px;
    border-radius: 15px;
    box-shadow: var(--shadow);
}
.login-card h2 {
    margin-bottom: 30px;
    text-align: center;
}
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
}
.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}
.btn-primary {
    width: 100%;
    background: var(--primary);
    color: var(--white);
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}
.btn-primary:hover {
    background: #1d4ed8;
}
.alert-danger {
    background: #fee2e2;
    color: #ef4444;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
}
</style>

<?php include 'layout_footer.php'; ?>
