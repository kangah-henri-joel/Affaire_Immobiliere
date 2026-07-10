<?php include __DIR__ . '/sa_header.php'; ?>

<div class="sa-layout">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="sa-main">
        <div class="sa-topbar">
            <button class="sa-hamburger" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
            <div class="sa-topbar-title">
                <h2><i class="fas fa-cogs" style="color:var(--sa-gray);margin-right:8px;"></i>Paramètres Globaux</h2>
                <p>Configuration de la plateforme</p>
            </div>
            <div class="sa-topbar-actions">
                <div class="sa-badge-role"><i class="fas fa-crown"></i> Super Admin</div>
            </div>
        </div>

        <div class="sa-content">
            <?php if (isset($_GET['success'])): ?>
                <div class="sa-alert sa-alert-success"><i class="fas fa-check-circle"></i> Paramètres enregistrés.</div>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>/super-admin/settings/save" method="POST">
                <div class="sa-card">
                    <div class="sa-card-header">
                        <h3><i class="fas fa-building" style="color:var(--sa-accent);margin-right:7px;"></i>Informations de l'entreprise</h3>
                    </div>
                    <div class="sa-card-body">
                        <?php if (!empty($settings)): ?>
                            <?php foreach ($settings as $key => $value): ?>
                            <div class="sa-form-group">
                                <label><?php echo ucwords(str_replace('_',' ',$key)); ?></label>
                                <input type="text" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>">
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="color:var(--sa-gray);text-align:center;padding:20px;">Aucun paramètre configuré.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="text-align:right;margin-bottom:20px;">
                    <button type="submit" class="sa-btn sa-btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
                </div>
            </form>

            <!-- Accès rapide -->
            <div class="sa-card">
                <div class="sa-card-header">
                    <h3><i class="fas fa-link" style="color:var(--sa-gold);margin-right:7px;"></i>Accès rapide</h3>
                </div>
                <div class="sa-card-body" style="display:flex;flex-wrap:wrap;gap:10px;">
                    <a href="<?php echo BASE_URL; ?>/admin/settings" class="sa-btn" style="background:rgba(99,102,241,0.1);color:var(--sa-text);border:1px solid var(--sa-border);"><i class="fas fa-cog"></i> Paramètres Admin</a>
                    <a href="<?php echo BASE_URL; ?>/admin/annonces" class="sa-btn" style="background:rgba(99,102,241,0.1);color:var(--sa-text);border:1px solid var(--sa-border);"><i class="fas fa-home"></i> Annonces</a>
                    <a href="<?php echo BASE_URL; ?>/admin/publications" class="sa-btn" style="background:rgba(99,102,241,0.1);color:var(--sa-text);border:1px solid var(--sa-border);"><i class="fas fa-bullhorn"></i> Publications</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/sa_footer.php'; ?>
