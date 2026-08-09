<?php include 'layout_header.php'; ?>

<section class="auth-section">
    <div class="container">
        <div class="auth-card">
            
            <?php if (isset($success_register)): ?>
                <div class="alert alert-success"><?php echo $success_register; ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($error_register)): ?>
                <div class="alert alert-danger"><?php echo $error_register; ?></div>
            <?php endif; ?>

            <div class="auth-tabs">
                <button class="tab-btn active" onclick="switchTab('login')">Connexion</button>
                <button class="tab-btn" onclick="switchTab('register')">Inscription</button>
            </div>

            <!-- TAB CONNEXION -->
            <div id="tab-login" class="auth-content active">
                <h2>Bon retour !</h2>
                <form action="<?php echo BASE_URL; ?>/login" method="POST">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur ou Email</label>
                        <input type="text" name="username" id="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                    <button type="submit" class="btn-primary">Se connecter</button>
                </form>
            </div>

            <!-- TAB INSCRIPTION -->
            <div id="tab-register" class="auth-content" style="display:none;">
                <h2>Créer un compte</h2>
                <form action="<?php echo BASE_URL; ?>/register" method="POST">
                    
                    <div class="form-group">
                        <label>Je suis un :</label>
                        <select name="type" id="user_type" onchange="toggleAgentFields()" style="width:100%; padding:10px; border-radius:8px; border:1px solid #e2e8f0;">
                            <option value="client" <?php echo (isset($type) && $type=='client')?'selected':''; ?>>Client (Recherche de biens)</option>
                            <option value="agent" <?php echo (isset($type) && $type=='agent')?'selected':''; ?>>Agent Immobilier (Publication de biens)</option>
                        </select>
                    </div>

                    <div class="form-row" style="display:flex; gap:10px;">
                        <div class="form-group" style="flex:1;">
                            <label>Nom</label>
                            <input type="text" name="nom" required>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Prénoms</label>
                            <input type="text" name="prenom" required>
                        </div>
                    </div>

                    <div class="form-row" style="display:flex; gap:10px;">
                        <div class="form-group" style="flex:1;">
                            <label>Nom d'utilisateur</label>
                            <input type="text" name="username" required>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Mot de passe</label>
                            <input type="password" name="password" required>
                        </div>
                    </div>

                    <div class="form-row" style="display:flex; gap:10px;">
                        <div class="form-group" style="flex:1;">
                            <label>Email</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Téléphone</label>
                            <input type="text" name="phone" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Pays</label>
                        <input type="text" name="country" placeholder="Ex: Côte d'Ivoire" required>
                    </div>

                    <!-- Champs spécifiques Agent -->
                    <div id="agent-fields" style="display: <?php echo (isset($type) && $type=='agent')?'block':'none'; ?>; background:#f8fafc; padding:15px; border-radius:8px; margin-bottom:15px; border:1px solid #e2e8f0;">
                        <h4 style="margin-bottom:10px; color:var(--primary);">Informations de l'Entreprise</h4>
                        <div class="form-group">
                            <label>Nom de l'entreprise</label>
                            <input type="text" name="company_name" id="company_name">
                        </div>
                        <div class="form-row" style="display:flex; gap:10px;">
                            <div class="form-group" style="flex:1;">
                                <label>Téléphone pro.</label>
                                <input type="text" name="company_phone">
                            </div>
                            <div class="form-group" style="flex:1;">
                                <label>Email pro.</label>
                                <input type="email" name="company_email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Adresse du bureau</label>
                            <input type="text" name="company_address">
                        </div>
                        <div class="form-group">
                            <label>Ville</label>
                            <input type="text" name="company_city" placeholder="Ex: Abidjan">
                        </div>
                        <div class="form-group">
                            <label>Description courte</label>
                            <textarea name="company_desc" rows="2" style="width:100%; padding:10px; border-radius:8px; border:1px solid #e2e8f0;"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">S'inscrire</button>
                </form>
            </div>

        </div>
    </div>
</section>

<style>
.auth-section {
    padding: 60px 0;
    display: flex;
    justify-content: center;
    background: #f1f5f9;
}
.auth-card {
    width: 100%;
    max-width: 500px;
    background: var(--white);
    padding: 40px;
    border-radius: 15px;
    box-shadow: var(--shadow);
}
.auth-card h2 {
    margin-bottom: 25px;
    text-align: center;
}
.auth-tabs {
    display: flex;
    margin-bottom: 30px;
    border-bottom: 2px solid #e2e8f0;
}
.tab-btn {
    flex: 1;
    background: none;
    border: none;
    padding: 10px 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--gray);
    cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
}
.tab-btn.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
}
.form-group {
    margin-bottom: 15px;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    font-size: 0.9rem;
}
.form-group input {
    width: 100%;
    padding: 10px;
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
    margin-top: 10px;
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
.alert-success {
    background: #dcfce7;
    color: #166534;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
}
</style>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.auth-content').forEach(content => content.style.display = 'none');
    
    if (tab === 'login') {
        document.querySelector('.tab-btn:nth-child(1)').classList.add('active');
        document.getElementById('tab-login').style.display = 'block';
    } else {
        document.querySelector('.tab-btn:nth-child(2)').classList.add('active');
        document.getElementById('tab-register').style.display = 'block';
    }
}

function toggleAgentFields() {
    const type = document.getElementById('user_type').value;
    const agentFields = document.getElementById('agent-fields');
    if (type === 'agent') {
        agentFields.style.display = 'block';
        document.getElementById('company_name').required = true;
    } else {
        agentFields.style.display = 'none';
        document.getElementById('company_name').required = false;
    }
}

<?php if(isset($error_register) || isset($success_register) || (isset($type) && $type)): ?>
    switchTab('register');
<?php endif; ?>
</script>

<?php include 'layout_footer.php'; ?>
