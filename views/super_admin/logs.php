<?php include __DIR__ . '/sa_header.php'; ?>

<div class="sa-layout">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="sa-main">
        <div class="sa-topbar">
            <button class="sa-hamburger" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
            <div class="sa-topbar-title">
                <h2><i class="fas fa-history" style="color:var(--sa-accent);margin-right:8px;"></i>Journal d'Activité</h2>
                <p>Dernières opérations sur la plateforme</p>
            </div>
            <div class="sa-topbar-actions">
                <div class="sa-badge-role"><i class="fas fa-crown"></i> Super Admin</div>
            </div>
        </div>

        <div class="sa-content">
            <div class="sa-card">
                <div class="sa-tabs">
                    <div class="sa-tab active" onclick="showTab('audit-tab',this)"><i class="fas fa-history"></i> Audit Admins (<?php echo count($auditLogs); ?>)</div>
                    <div class="sa-tab" onclick="showTab('leads-tab',this)"><i class="fas fa-envelope"></i> Leads (<?php echo count($recentLeads); ?>)</div>
                    <div class="sa-tab" onclick="showTab('annonces-tab',this)"><i class="fas fa-home"></i> Annonces (<?php echo count($recentAnnonces); ?>)</div>
                    <div class="sa-tab" onclick="showTab('pubs-tab',this)"><i class="fas fa-paper-plane"></i> Pubs (<?php echo count($recentPubs); ?>)</div>
                </div>

                <div id="audit-tab" class="sa-tab-content active">
                    <?php foreach ($auditLogs as $log): ?>
                    <div class="log-item">
                        <div class="log-dot" style="background:rgba(245,158,11,0.12);color:#f59e0b;"><i class="fas fa-user-cog"></i></div>
                        <div class="log-info">
                            <strong><?php echo htmlspecialchars($log['full_name'] ?? $log['username'] ?? 'Admin'); ?></strong>
                            <span style="font-weight: 600; color: #1e293b;"><?php echo htmlspecialchars($log['action']); ?></span>
                            <span style="color:#64748b; font-size:0.85rem;"><?php echo htmlspecialchars($log['details']); ?></span>
                        </div>
                        <div class="log-time"><?php echo date('d/m H:i', strtotime($log['created_at'])); ?></div>
                    </div>
                    <?php endforeach; ?>
                    <?php if(empty($auditLogs)): ?><p style="text-align:center;padding:28px;color:var(--sa-gray);">Aucun log d'activité.</p><?php endif; ?>
                </div>

                <div id="leads-tab" class="sa-tab-content">
                    <?php foreach ($recentLeads as $l): ?>
                    <div class="log-item">
                        <div class="log-dot" style="background:rgba(99,102,241,0.15);color:#818cf8;"><i class="fas fa-envelope"></i></div>
                        <div class="log-info">
                            <strong><?php echo htmlspecialchars($l['client_name']??'Anonyme'); ?></strong>
                            <span><?php echo htmlspecialchars($l['annonce_title']??'N/A'); ?> — <?php echo htmlspecialchars($l['client_phone']??''); ?></span>
                        </div>
                        <div class="log-time"><?php echo date('d/m H:i',strtotime($l['created_at'])); ?></div>
                    </div>
                    <?php endforeach; ?>
                    <?php if(empty($recentLeads)): ?><p style="text-align:center;padding:28px;color:var(--sa-gray);">Aucun lead.</p><?php endif; ?>
                </div>

                <div id="annonces-tab" class="sa-tab-content">
                    <?php foreach ($recentAnnonces as $a): ?>
                    <div class="log-item">
                        <div class="log-dot" style="background:rgba(6,182,212,0.12);color:#22d3ee;"><i class="fas fa-home"></i></div>
                        <div class="log-info">
                            <strong><?php echo htmlspecialchars($a['title']); ?></strong>
                            <span><?php echo htmlspecialchars($a['cat']??''); ?> — <?php echo number_format($a['price'],0,',',' '); ?> FCFA</span>
                        </div>
                        <div class="log-time"><?php echo date('d/m H:i',strtotime($a['created_at'])); ?></div>
                    </div>
                    <?php endforeach; ?>
                    <?php if(empty($recentAnnonces)): ?><p style="text-align:center;padding:28px;color:var(--sa-gray);">Aucune annonce.</p><?php endif; ?>
                </div>

                <div id="pubs-tab" class="sa-tab-content">
                    <?php foreach ($recentPubs as $p): ?>
                    <div class="log-item">
                        <div class="log-dot" style="background:rgba(139,92,246,0.12);color:#a78bfa;"><i class="fas fa-paper-plane"></i></div>
                        <div class="log-info">
                            <strong><?php echo htmlspecialchars($p['annonce_title']??'N/A'); ?></strong>
                            <span><?php echo ucfirst($p['platform']); ?> — <?php echo ucfirst($p['status']); ?></span>
                        </div>
                        <div class="log-time"><?php echo date('d/m H:i',strtotime($p['scheduled_at'])); ?></div>
                    </div>
                    <?php endforeach; ?>
                    <?php if(empty($recentPubs)): ?><p style="text-align:center;padding:28px;color:var(--sa-gray);">Aucune publication.</p><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(id, el) {
    document.querySelectorAll('.sa-tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.sa-tab').forEach(t => t.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    el.classList.add('active');
}
</script>

<?php include __DIR__ . '/sa_footer.php'; ?>
