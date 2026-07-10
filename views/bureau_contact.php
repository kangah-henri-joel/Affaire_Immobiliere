<?php
// views/bureau_contact.php
include __DIR__ . '/layout_header.php';
?>
<section class="contact-bureau-section container" style="padding: 60px 0;">
    <div class="contact-bureau-grid">
        <!-- Formulaire de contact -->
        <div class="contact-form-card">
            <h2>Contacter <?php echo htmlspecialchars($bureau['nom'] ?? 'le Bureau'); ?></h2>
            <p class="subtitle">Envoyez-nous un message directement. Nous vous répondrons dans les plus brefs délais.</p>

            <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> Votre message a été transmis avec succès à ce bureau !
            </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>/bureau/contact?id=<?php echo $bureau['id']; ?>" method="POST" class="premium-form">
                <div class="form-group">
                    <label>Votre Nom / Pseudo <span class="required">*</span></label>
                    <input type="text" name="name" required placeholder="Ex: Jean Dupont">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Téléphone (Optionnel)</label>
                        <input type="tel" name="phone" placeholder="Ex: +225 07 00 00 00 00">
                    </div>
                    <div class="form-group">
                        <label>Adresse Email (Optionnel)</label>
                        <input type="email" name="email" placeholder="Ex: jean.dupont@example.com">
                    </div>
                </div>

                <div class="form-group">
                    <label>Votre Message <span class="required">*</span></label>
                    <textarea name="message" rows="5" required placeholder="Écrivez votre question ou demande de renseignement ici..."></textarea>
                </div>

                <button type="submit" class="btn-submit-contact">
                    <i class="fas fa-paper-plane"></i> Envoyer le Message
                </button>
            </form>
        </div>

        <!-- Infos Bureau -->
        <div class="bureau-details-panel">
            <div class="bureau-details-box">
                <?php if (!empty($bureau['logo'])): ?>
                <img src="<?php echo BASE_URL . $bureau['logo']; ?>" alt="Logo" class="bureau-details-logo">
                <?php else: ?>
                <div class="bureau-details-avatar"><?php echo strtoupper(substr($bureau['nom'] ?? 'B', 0, 1)); ?></div>
                <?php endif; ?>

                <h3><?php echo htmlspecialchars($bureau['nom']); ?></h3>
                <span class="officiel-badge"><i class="fas fa-building"></i> Bureau Agréé</span>

                <hr class="b-details-divider">

                <div class="b-details-list">
                    <?php if (!empty($bureau['adresse'])): ?>
                    <div class="b-details-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Adresse</strong>
                            <p><?php echo htmlspecialchars($bureau['adresse']); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($bureau['phone_whatsapp'])): ?>
                    <div class="b-details-item">
                        <i class="fab fa-whatsapp" style="color:#25d366;"></i>
                        <div>
                            <strong>WhatsApp</strong>
                            <p><?php echo htmlspecialchars($bureau['phone_whatsapp']); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($bureau['phone_tel'])): ?>
                    <div class="b-details-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>Téléphone</strong>
                            <p><?php echo htmlspecialchars($bureau['phone_tel']); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($bureau['phone_fixe'])): ?>
                    <div class="b-details-item">
                        <i class="fas fa-phone-alt"></i>
                        <div>
                            <strong>Fixe</strong>
                            <p><?php echo htmlspecialchars($bureau['phone_fixe']); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($bureau['email'])): ?>
                    <div class="b-details-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>Email direct</strong>
                            <p><?php echo htmlspecialchars($bureau['email']); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="bureau-responsable-info">
                    <i class="fas fa-user-shield"></i>
                    <span>Administrateur responsable : <strong><?php echo htmlspecialchars($bureau['admin_name']); ?></strong></span>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.contact-bureau-grid { display: grid; grid-template-columns: 1.3fr 1fr; gap: 40px; }
.contact-form-card { background: white; border-radius: 16px; padding: 35px; box-shadow: var(--shadow); border: 1.5px solid #f1f5f9; }
.contact-form-card h2 { font-size: 1.6rem; font-weight: 800; color: var(--primary); margin: 0 0 8px; }
.contact-form-card .subtitle { font-size: 0.9rem; color: var(--gray); margin-bottom: 24px; }

.premium-form { display: flex; flex-direction: column; gap: 20px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-group label { display: block; font-size: 0.85rem; font-weight: 700; color: var(--primary); margin-bottom: 6px; }
.form-group input, .form-group textarea { width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-family: inherit; font-size: 0.9rem; }
.form-group input:focus, .form-group textarea:focus { outline: none; border-color: var(--secondary); }
.required { color: #ef4444; }
.btn-submit-contact { background: var(--primary); color: white; border: none; padding: 14px; border-radius: 8px; font-weight: 700; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s; }
.btn-submit-contact:hover { background: var(--secondary); color: var(--primary); }

/* Details Panel */
.bureau-details-panel { background: #f8fafc; border-radius: 16px; border: 1.5px solid #e2e8f0; padding: 30px; display: flex; flex-direction: column; align-items: center; text-align: center; }
.bureau-details-box { width: 100%; display: flex; flex-direction: column; align-items: center; }
.bureau-details-logo { width: 80px; height: 80px; object-fit: contain; border-radius: 12px; border: 2px solid #e2e8f0; margin-bottom: 16px; background: white; }
.bureau-details-avatar { width: 80px; height: 80px; border-radius: 12px; background: linear-gradient(135deg, #3b82f6, #6366f1); color: white; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800; margin-bottom: 16px; }
.bureau-details-panel h3 { font-size: 1.25rem; font-weight: 700; color: var(--primary); margin: 0 0 6px; }
.officiel-badge { background: #fef3c7; color: #92400e; font-size: 0.7rem; font-weight: 800; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; }
.b-details-divider { width: 100%; border: 0; border-top: 1.5px solid #e2e8f0; margin: 20px 0; }

.b-details-list { width: 100%; display: flex; flex-direction: column; gap: 16px; text-align: left; }
.b-details-item { display: flex; gap: 12px; align-items: flex-start; }
.b-details-item i { width: 32px; height: 32px; border-radius: 50%; background: white; border: 1.5px solid #e2e8f0; display: flex; align-items: center; justify-content: center; color: var(--primary); flex-shrink: 0; font-size: 0.88rem; }
.b-details-item strong { font-size: 0.72rem; color: var(--gray); text-transform: uppercase; display: block; margin-bottom: 2px; }
.b-details-item p { font-size: 0.88rem; color: var(--primary); margin: 0; font-weight: 600; }

.bureau-responsable-info { margin-top: 24px; padding: 12px; background: white; border-radius: 8px; border: 1px solid #e2e8f0; width: 100%; font-size: 0.8rem; display: flex; align-items: center; gap: 8px; color: var(--primary); justify-content: center; }

@media (max-width: 900px) { .contact-bureau-grid { grid-template-columns: 1fr; } .form-row { grid-template-columns: 1fr; } }
</style>
<?php include __DIR__ . '/layout_footer.php'; ?>
