<?php include __DIR__ . '/../layout_header.php'; ?>

<div class="admin-container">
    <?php include __DIR__ . '/sidebar.php'; ?>

    <main class="admin-content">
        <header class="admin-header">
            <h1>Mon <span class="highlight">Profil</span></h1>
            <p style="color: var(--gray);">Gérez vos informations personnelles, vos contacts et votre photo.</p>
        </header>

        <?php if(isset($_GET['success'])): ?>
            <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 10px; margin-bottom: 20px; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-check-circle" style="font-size:1.2rem;"></i> Profil mis à jour avec succès !
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>/admin/profil" method="POST" enctype="multipart/form-data">
            <div class="profile-grid">

                <!-- === COLONNE GAUCHE : AVATAR === -->
                <div class="profile-card avatar-card">
                    <h3 class="card-section-title"><i class="fas fa-camera"></i> Photo de profil / Logo</h3>

                    <div class="avatar-wrapper">
                        <?php
                            $avatarSrc = (!empty($user['avatar']))
                                ? BASE_URL . $user['avatar']
                                : 'https://ui-avatars.com/api/?name=' . urlencode($user['full_name']) . '&background=0f172a&color=f59e0b&size=120&bold=true';
                        ?>
                        <img id="avatarPreview" src="<?php echo $avatarSrc; ?>" alt="Avatar" class="avatar-img">
                        <label for="avatarInput" class="avatar-overlay" title="Changer la photo">
                            <i class="fas fa-camera"></i>
                            <span>Changer</span>
                        </label>
                        <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display:none;" onchange="previewAvatar(this)">
                    </div>
                    <p class="avatar-hint">JPG, PNG, WEBP • Max 2 Mo</p>

                    <div class="profile-identity">
                        <strong><?php echo htmlspecialchars($user['full_name']); ?></strong>
                        <span>@<?php echo htmlspecialchars($user['username']); ?></span>
                    </div>
                </div>

                <!-- === COLONNE DROITE : FORMULAIRE === -->
                <div class="profile-forms">

                    <!-- Informations générales -->
                    <div class="profile-card">
                        <h3 class="card-section-title"><i class="fas fa-user"></i> Informations générales</h3>
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label>Nom complet</label>
                                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Nom d'utilisateur</label>
                                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nouveau mot de passe <span style="color:var(--gray); font-weight:400;">(laisser vide pour ne pas changer)</span></label>
                            <input type="password" name="password" placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Numéros de contact -->
                    <div class="profile-card">
                        <h3 class="card-section-title"><i class="fas fa-phone-alt"></i> Numéros de contact</h3>
                        <p class="card-desc">Ces numéros seront utilisés pour le statut WhatsApp des publications.</p>

                        <!-- WhatsApp -->
                        <div class="contact-field-row">
                            <div class="contact-type-badge whatsapp-badge">
                                <i class="fab fa-whatsapp"></i>
                                <span>WhatsApp</span>
                            </div>
                            <div class="form-group" style="flex:1; margin-bottom:0;">
                                <input type="tel" name="phone_whatsapp"
                                    value="<?php echo htmlspecialchars($user['phone_whatsapp'] ?? ''); ?>"
                                    placeholder="+225 07 XX XX XX XX">
                            </div>
                            <?php if(!empty($user['phone_whatsapp'])): ?>
                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $user['phone_whatsapp']); ?>"
                               target="_blank" class="btn-contact-test btn-whatsapp" title="Tester WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <?php endif; ?>
                        </div>

                        <!-- Téléphone mobile -->
                        <div class="contact-field-row">
                            <div class="contact-type-badge tel-badge">
                                <i class="fas fa-mobile-alt"></i>
                                <span>Mobile</span>
                            </div>
                            <div class="form-group" style="flex:1; margin-bottom:0;">
                                <input type="tel" name="phone_tel"
                                    value="<?php echo htmlspecialchars($user['phone_tel'] ?? ''); ?>"
                                    placeholder="+225 05 XX XX XX XX">
                            </div>
                            <?php if(!empty($user['phone_tel'])): ?>
                            <a href="tel:<?php echo htmlspecialchars($user['phone_tel']); ?>"
                               class="btn-contact-test btn-tel" title="Appeler">
                                <i class="fas fa-phone"></i>
                            </a>
                            <?php endif; ?>
                        </div>

                        <!-- Fixe -->
                        <div class="contact-field-row">
                            <div class="contact-type-badge fixe-badge">
                                <i class="fas fa-phone-square"></i>
                                <span>Fixe</span>
                            </div>
                            <div class="form-group" style="flex:1; margin-bottom:0;">
                                <input type="tel" name="phone_fixe"
                                    value="<?php echo htmlspecialchars($user['phone_fixe'] ?? ''); ?>"
                                    placeholder="+225 27 XX XX XX XX">
                            </div>
                            <?php if(!empty($user['phone_fixe'])): ?>
                            <a href="tel:<?php echo htmlspecialchars($user['phone_fixe']); ?>"
                               class="btn-contact-test btn-fixe" title="Appeler">
                                <i class="fas fa-phone"></i>
                            </a>
                            <?php endif; ?>
                        </div>

                        <!-- Info sur le statut WhatsApp -->
                        <div class="whatsapp-status-info">
                            <i class="fab fa-whatsapp"></i>
                            <div>
                                <strong>Statut WhatsApp pour les publications</strong>
                                <p>Le numéro WhatsApp renseigné ci-dessus sera automatiquement utilisé pour diffuser les publications sur votre statut WhatsApp.</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 16px;">
                        <i class="fas fa-save"></i> Enregistrer toutes les modifications
                    </button>
                </div>
            </div>
        </form>
    </main>
</div>

<style>
.profile-grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 25px;
    align-items: start;
}

.profile-card {
    background: var(--white);
    border-radius: 20px;
    box-shadow: var(--shadow);
    padding: 30px;
    margin-bottom: 25px;
}

.profile-card:last-child { margin-bottom: 0; }

.avatar-card {
    text-align: center;
    position: sticky;
    top: 100px;
}

.card-section-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 12px;
    border-bottom: 2px solid var(--border);
}

.card-section-title i { color: var(--secondary); }

.card-desc {
    font-size: 0.85rem;
    color: var(--gray);
    margin-bottom: 20px;
    background: #f8fafc;
    padding: 10px 15px;
    border-radius: 8px;
    border-left: 3px solid var(--secondary);
}

/* Avatar */
.avatar-wrapper {
    position: relative;
    display: inline-block;
    margin: 0 auto 10px;
}

.avatar-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--secondary);
    display: block;
}

.avatar-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15,23,42,0.5);
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.75rem;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.3s;
    cursor: pointer;
}

.avatar-wrapper:hover .avatar-overlay { opacity: 1; }
.avatar-overlay i { font-size: 1.4rem; }

.avatar-hint {
    font-size: 0.78rem;
    color: var(--gray);
    margin-bottom: 20px;
}

.profile-identity {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding-top: 15px;
    border-top: 1px solid var(--border);
}

.profile-identity strong {
    font-size: 1rem;
    color: var(--primary);
}

.profile-identity span {
    font-size: 0.85rem;
    color: var(--gray);
}

/* Contact rows */
.contact-field-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}

.contact-type-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 70px;
    min-width: 70px;
    padding: 10px 6px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 700;
    gap: 4px;
}

.contact-type-badge i { font-size: 1.3rem; }

.whatsapp-badge { background: #dcfce7; color: #15803d; }
.tel-badge      { background: #dbeafe; color: #1d4ed8; }
.fixe-badge     { background: #fef3c7; color: #92400e; }

.btn-contact-test {
    width: 42px;
    min-width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    transition: var(--transition);
}

.btn-whatsapp { background: #dcfce7; color: #15803d; }
.btn-whatsapp:hover { background: #15803d; color: white; }
.btn-tel { background: #dbeafe; color: #1d4ed8; }
.btn-tel:hover { background: #1d4ed8; color: white; }
.btn-fixe { background: #fef3c7; color: #92400e; }
.btn-fixe:hover { background: #92400e; color: white; }

.form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 15px;
}

/* WhatsApp status info box */
.whatsapp-status-info {
    display: flex;
    gap: 15px;
    align-items: flex-start;
    background: linear-gradient(135deg, #dcfce7, #f0fdf4);
    border: 1px solid #86efac;
    border-radius: 12px;
    padding: 16px;
    margin-top: 20px;
}

.whatsapp-status-info > i {
    font-size: 2rem;
    color: #16a34a;
    flex-shrink: 0;
    margin-top: 2px;
}

.whatsapp-status-info strong {
    display: block;
    color: #15803d;
    margin-bottom: 4px;
    font-size: 0.9rem;
}

.whatsapp-status-info p {
    color: #166534;
    font-size: 0.82rem;
    line-height: 1.5;
}

@media (max-width: 900px) {
    .profile-grid { grid-template-columns: 1fr; }
    .avatar-card { position: static; }
    .form-grid-2 { grid-template-columns: 1fr; }
}
</style>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include __DIR__ . '/../layout_footer.php'; ?>
