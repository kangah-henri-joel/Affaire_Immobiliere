<?php include 'layout_header.php'; ?>

<section class="auth-section">
    <div class="container" style="display: flex; justify-content: center; width: 100%;">
        <div class="auth-card">
            
            <?php if (isset($success_register)): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success_register; ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($error_register)): ?>
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error_register; ?></div>
            <?php endif; ?>

            
            <!-- TAB CONNEXION -->
            <div id="tab-login" class="auth-content active">
                <h2 class="auth-form-title">Bon retour !</h2>
                <p class="auth-form-subtitle">Connectez-vous pour accéder à votre espace personnel.</p>
                <form action="<?php echo BASE_URL; ?>/login" method="POST">
                    <div class="form-group">
                        <label for="username">EMAIL</label>
                        <input type="text" name="username" id="username" placeholder="jojo@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="password">MOT DE PASSE</label>
                        <input type="password" name="password" id="password" placeholder="Entrez votre mot de passe" required>
                    </div>
                    <button type="submit" class="btn-auth-gradient">SE CONNECTER</button>

                    <div class="auth-footer-switch">
                        Pas de compte ? <a href="javascript:void(0)" onclick="switchTab('register')">Créer un compte</a>
                    </div>
                </form>
            </div>

            <!-- TAB INSCRIPTION -->
            <div id="tab-register" class="auth-content" style="display:none;">
                <h2 class="auth-form-title">Créer un compte</h2>
                <p class="auth-form-subtitle">Rejoignez-nous et gérez vos recherches ou annonces facilement.</p>
                <form action="<?php echo BASE_URL; ?>/register" method="POST">
                    
                    <div class="form-group">
                        <label>JE SUIS UN :</label>
                        <select name="type" id="user_type" onchange="toggleAgentFields()" class="auth-select">
                            <option value="client" <?php echo (isset($type) && $type=='client')?'selected':''; ?>>Client (Recherche de biens)</option>
                            <option value="agent" <?php echo (isset($type) && $type=='agent')?'selected':''; ?>>Agent Immobilier (Publication de biens)</option>
                        </select>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label>NOM</label>
                            <input type="text" name="nom" placeholder="Votre nom" required>
                        </div>
                        <div class="form-group">
                            <label>PRÉNOMS</label>
                            <input type="text" name="prenom" placeholder="Votre prénom" required>
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label>NOM D'UTILISATEUR</label>
                            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
                        </div>
                        <div class="form-group">
                            <label>MOT DE PASSE</label>
                            <input type="password" name="password" placeholder="Créer un mot de passe" required>
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label>EMAIL</label>
                            <input type="email" name="email" placeholder="votre@email.com" required>
                        </div>
                        <div class="form-group">
                            <label>TÉLÉPHONE</label>
                            <input type="text" name="phone" placeholder="+225 07 XX XX XX XX" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>PAYS</label>
                        <input type="text" name="country" placeholder="Ex: Côte d'Ivoire" required>
                    </div>

                    <!-- Champs spécifiques Agent -->
                    <div id="agent-fields" style="display: <?php echo (isset($type) && $type=='agent')?'block':'none'; ?>; background:#f8fafc; padding:15px; border-radius:12px; margin-bottom:15px; border:1.5px solid #e2e8f0;">
                        <h4 style="margin-bottom:10px; color:var(--primary); font-size:0.9rem;">Informations de l'Entreprise</h4>
                        <div class="form-group">
                            <label>NOM DE L'ENTREPRISE</label>
                            <input type="text" name="company_name" id="company_name">
                        </div>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label>TÉLÉPHONE PRO.</label>
                                <input type="text" name="company_phone">
                            </div>
                            <div class="form-group">
                                <label>EMAIL PRO.</label>
                                <input type="email" name="company_email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>ADRESSE DU BUREAU</label>
                            <input type="text" name="company_address">
                        </div>
                        <div class="form-group">
                            <label>VILLE</label>
                            <input type="text" name="company_city" placeholder="Ex: Abidjan">
                        </div>
                        <div class="form-group">
                            <label>DESCRIPTION COURTE</label>
                            <textarea name="company_desc" rows="2" class="auth-textarea"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn-auth-gradient">S'INSCRIRE</button>

                    <div class="auth-footer-switch">
                        Déjà un compte ? <a href="javascript:void(0)" onclick="switchTab('login')">Se connecter</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>

<style>
.auth-section {
    padding: 70px 0;
    min-height: calc(100vh - 160px);
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
}
.auth-card {
    width: 100%;
    max-width: 480px;
    background: #ffffff;
    padding: 45px 38px;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
    border: 1px solid rgba(226, 232, 240, 0.8);
}
.auth-form-title {
    margin-bottom: 6px;
    text-align: center;
    font-size: 1.5rem;
    font-weight: 800;
    color: #0f172a;
}
.auth-form-subtitle {
    margin-bottom: 25px;
    text-align: center;
    font-size: 0.9rem;
    color: #64748b;
}
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #334155;
}
.form-group input, .auth-select, .auth-textarea {
    width: 100%;
    padding: 14px 18px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    color: #0f172a;
    background: #ffffff;
    font-family: inherit;
    transition: all 0.2s ease;
}
.form-group input::placeholder {
    color: #94a3b8;
    font-weight: 400;
    font-size: 0.92rem;
}
.form-group input:focus, .auth-select:focus, .auth-textarea:focus {
    outline: none;
    border-color: #c026d3;
    background: #ffffff;
    box-shadow: 0 0 0 4px rgba(192, 38, 211, 0.1);
}
.form-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.btn-auth-gradient {
    width: 100%;
    background: linear-gradient(90deg, #881337 0%, #c026d3 100%);
    color: #ffffff;
    padding: 16px;
    border: none;
    border-radius: 16px;
    font-size: 0.95rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(192, 38, 211, 0.35);
    margin-top: 20px;
    margin-bottom: 18px;
    display: block;
}
.btn-auth-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(192, 38, 211, 0.45);
    filter: brightness(1.05);
}
.auth-footer-switch {
    text-align: center;
    margin-top: 10px;
    font-size: 0.92rem;
    color: #64748b;
    font-weight: 500;
}
.auth-footer-switch a {
    color: #4f46e5;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: color 0.2s;
}
.auth-footer-switch a:hover {
    color: #c026d3;
    text-decoration: underline;
}
.alert-danger {
    background: #fee2e2;
    color: #b91c1c;
    padding: 12px 16px;
    border-radius: 10px;
    margin-bottom: 20px;
    text-align: center;
    font-size: 0.88rem;
    font-weight: 600;
}
.alert-success {
    background: #dcfce7;
    color: #15803d;
    padding: 12px 16px;
    border-radius: 10px;
    margin-bottom: 20px;
    text-align: center;
    font-size: 0.88rem;
    font-weight: 600;
}

@media (max-width: 576px) {
    .auth-card {
        padding: 30px 20px;
    }
    .form-row-2 {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function switchTab(tab) {
    document.querySelectorAll('.auth-content').forEach(content => content.style.display = 'none');
    
    if (tab === 'login') {
        document.getElementById('tab-login').style.display = 'block';
    } else {
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
