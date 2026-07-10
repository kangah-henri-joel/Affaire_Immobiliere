<?php include __DIR__ . '/sa_header.php'; ?>

<div class="sa-layout">
    <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="sa-main">
        <!-- TOPBAR -->
        <div class="sa-topbar">
            <button class="sa-hamburger" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
            <div class="sa-topbar-title">
                <h2><i class="fas fa-shield-alt" style="color:var(--sa-gold);margin-right:8px;"></i>Tableau de Bord Global</h2>
                <p>Vue complète de la plateforme ImmoAffaire</p>
            </div>
            <div class="sa-topbar-actions">
                <div class="sa-badge-role"><i class="fas fa-crown" style="margin-right:4px;"></i> Super Admin</div>
                <a href="<?php echo BASE_URL; ?>/logout" class="sa-btn sa-btn-danger sa-btn-sm"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>

        <div class="sa-content">

            <!-- Utilisateurs -->
            <p style="color:var(--sa-gray);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;margin:0 0 10px;">👥 Utilisateurs</p>
            <div class="sa-stats-grid" style="grid-template-columns:repeat(3,1fr);">
                <a href="<?php echo BASE_URL; ?>/super-admin/admins" class="sa-stat-card" style="--card-gradient:linear-gradient(90deg,#f59e0b,#d97706);--icon-bg:rgba(245,158,11,0.15);--icon-color:#f59e0b;">
                    <div class="sa-stat-icon"><i class="fas fa-user-shield"></i></div>
                    <h4>Super Admins</h4>
                    <div class="val"><?php echo $stats['total_super_admins']; ?></div>
                </a>
                <a href="<?php echo BASE_URL; ?>/super-admin/admins" class="sa-stat-card" style="--icon-bg:rgba(99,102,241,0.15);--icon-color:#818cf8;">
                    <div class="sa-stat-icon"><i class="fas fa-user-tie"></i></div>
                    <h4>Administrateurs</h4>
                    <div class="val"><?php echo $stats['total_admins']; ?></div>
                </a>
                <a href="<?php echo BASE_URL; ?>/super-admin/users" class="sa-stat-card" style="--card-gradient:linear-gradient(90deg,#10b981,#059669);--icon-bg:rgba(16,185,129,0.12);--icon-color:#34d399;">
                    <div class="sa-stat-icon"><i class="fas fa-users"></i></div>
                    <h4>Agents</h4>
                    <div class="val"><?php echo $stats['total_agents']; ?></div>
                </a>
            </div>

            <!-- Plateforme -->
            <p style="color:var(--sa-gray);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;margin:16px 0 10px;">🏠 Plateforme</p>
            <div class="sa-stats-grid">
                <a href="<?php echo BASE_URL; ?>/super-admin/annonces" class="sa-stat-card" style="--icon-bg:rgba(6,182,212,0.12);--icon-color:#22d3ee;">
                    <div class="sa-stat-icon"><i class="fas fa-home"></i></div>
                    <h4>Annonces</h4>
                    <div class="val"><?php echo $stats['total_annonces']; ?></div>
                    <div class="sub"><?php echo $stats['annonces_disponible']; ?> disponibles</div>
                </a>
                <div class="sa-stat-card" style="--card-gradient:linear-gradient(90deg,#06b6d4,#0891b2);--icon-bg:rgba(6,182,212,0.12);--icon-color:#22d3ee;">
                    <div class="sa-stat-icon"><i class="fas fa-eye"></i></div>
                    <h4>Vues totales</h4>
                    <div class="val"><?php echo number_format($stats['total_views'],0,',',' '); ?></div>
                </div>
                <a href="<?php echo BASE_URL; ?>/super-admin/leads" class="sa-stat-card" style="--card-gradient:linear-gradient(90deg,#f59e0b,#d97706);--icon-bg:rgba(245,158,11,0.12);--icon-color:#fbbf24;">
                    <div class="sa-stat-icon"><i class="fas fa-envelope-open-text"></i></div>
                    <h4>Leads / Clients</h4>
                    <div class="val"><?php echo $stats['total_leads']; ?></div>
                    <div class="sub"><?php echo $stats['leads_new']; ?> nouveaux</div>
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/publications" class="sa-stat-card" style="--card-gradient:linear-gradient(90deg,#8b5cf6,#7c3aed);--icon-bg:rgba(139,92,246,0.12);--icon-color:#a78bfa;">
                    <div class="sa-stat-icon"><i class="fas fa-paper-plane"></i></div>
                    <h4>Publications</h4>
                    <div class="val"><?php echo $stats['total_publications']; ?></div>
                </a>
            </div>

            <!-- Grille 2 colonnes -->
            <div class="sa-grid-2">
                <!-- Derniers Leads -->
                <div class="sa-card">
                    <div class="sa-card-header">
                        <h3><i class="fas fa-inbox" style="color:var(--sa-gold);margin-right:7px;"></i>Derniers Leads</h3>
                        <a href="<?php echo BASE_URL; ?>/super-admin/leads" class="sa-btn sa-btn-primary sa-btn-sm">Voir tout</a>
                    </div>
                    <div class="sa-table-wrap">
                    <?php if (!empty($recentLeads)): ?>
                        <table class="sa-table">
                            <thead><tr><th>Client</th><th>Statut</th></tr></thead>
                            <tbody>
                            <?php foreach ($recentLeads as $lead): ?>
                                <tr>
                                    <td>
                                        <strong style="color:white;display:block;"><?php echo htmlspecialchars($lead['client_name']); ?></strong>
                                        <span style="color:var(--sa-gray);font-size:11px;"><?php echo htmlspecialchars($lead['annonce_title'] ?? 'N/A'); ?></span>
                                    </td>
                                    <td><span class="lead-badge lead-<?php echo $lead['status']; ?>"><?php echo ucfirst($lead['status']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div style="padding:24px;text-align:center;color:var(--sa-gray);">Aucun lead.</div>
                    <?php endif; ?>
                    </div>
                </div>

                <!-- Répartition & Équipe -->
                <div class="sa-card">
                    <div class="sa-card-header">
                        <h3><i class="fas fa-chart-pie" style="color:var(--sa-accent);margin-right:7px;"></i>Répartition Leads</h3>
                    </div>
                    <div class="sa-card-body">
                        <?php $total = $stats['total_leads'] ?: 1; ?>
                        <div class="sa-donut-info">
                            <div class="sa-donut-row">
                                <div class="label"><div class="dot" style="background:#818cf8;"></div> Nouveaux</div>
                                <div class="count"><?php echo $stats['leads_new']; ?></div>
                            </div>
                            <div class="sa-progress"><div class="sa-progress-fill" style="width:<?php echo round($stats['leads_new']/$total*100); ?>%;background:linear-gradient(90deg,#6366f1,#818cf8);"></div></div>

                            <div class="sa-donut-row" style="margin-top:12px;">
                                <div class="label"><div class="dot" style="background:#fbbf24;"></div> Contactés</div>
                                <div class="count"><?php echo $stats['leads_contacted']; ?></div>
                            </div>
                            <div class="sa-progress"><div class="sa-progress-fill" style="width:<?php echo round($stats['leads_contacted']/$total*100); ?>%;background:linear-gradient(90deg,#f59e0b,#fbbf24);"></div></div>

                            <div class="sa-donut-row" style="margin-top:12px;">
                                <div class="label"><div class="dot" style="background:#34d399;"></div> Clôturés</div>
                                <div class="count"><?php echo $stats['leads_closed']; ?></div>
                            </div>
                            <div class="sa-progress"><div class="sa-progress-fill" style="width:<?php echo round($stats['leads_closed']/$total*100); ?>%;background:linear-gradient(90deg,#10b981,#34d399);"></div></div>
                        </div>

                        <hr style="border-color:var(--sa-border);margin:18px 0;">
                        <p style="color:var(--sa-gray);font-size:11px;font-weight:700;text-transform:uppercase;margin:0 0 12px;">Équipe</p>
                        <?php foreach ($allUsers as $u): ?>
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:9px;">
                            <div class="sa-avatar-sm"><?php echo strtoupper(substr($u['full_name']??$u['username'],0,1)); ?></div>
                            <div style="flex:1;min-width:0;">
                                <div style="color:white;font-size:12px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo htmlspecialchars($u['full_name']??$u['username']); ?></div>
                            </div>
                            <span class="role-badge role-<?php echo $u['role']; ?>"><?php echo str_replace('_',' ',$u['role']); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Dernières annonces -->
            <div class="sa-card">
                <div class="sa-card-header">
                    <h3><i class="fas fa-home" style="color:#22d3ee;margin-right:7px;"></i>Dernières Annonces</h3>
                    <a href="<?php echo BASE_URL; ?>/super-admin/annonces" class="sa-btn sa-btn-primary sa-btn-sm">Gérer tout</a>
                </div>
                <div class="sa-table-wrap">
                <?php if (!empty($recentAnnonces)): ?>
                    <table class="sa-table">
                        <thead><tr><th>Titre</th><th>Prix</th><th>Type</th><th>Statut</th><th>Vues</th></tr></thead>
                        <tbody>
                        <?php foreach ($recentAnnonces as $a): ?>
                            <tr>
                                <td style="font-weight:600;color:white;"><?php echo htmlspecialchars($a['title']); ?></td>
                                <td style="color:var(--sa-gold);font-weight:700;"><?php echo number_format($a['price'],0,',',' '); ?></td>
                                <td><span style="color:var(--sa-accent);"><?php echo ucfirst($a['type']); ?></span></td>
                                <td><span class="lead-badge lead-<?php echo $a['status']==='disponible'?'new':($a['status']==='vendu'?'closed':'contacted'); ?>"><?php echo ucfirst($a['status']); ?></span></td>
                                <td style="color:var(--sa-gray);"><?php echo $a['views_count']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="padding:24px;text-align:center;color:var(--sa-gray);">Aucune annonce.</div>
                <?php endif; ?>
                </div>
            </div>

        </div><!-- /sa-content -->
    </div><!-- /sa-main -->
</div><!-- /sa-layout -->

<?php include __DIR__ . '/sa_footer.php'; ?>
