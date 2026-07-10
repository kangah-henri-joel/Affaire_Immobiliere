<?php
// views/admin/visitors.php
include __DIR__ . '/../../views/layout_header.php';
?>
<div class="admin-container">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="admin-content">
        <header class="admin-header">
            <h1><i class="fas fa-eye" style="color:var(--secondary)"></i> Suivi des Visiteurs</h1>
        </header>

        <!-- Cartes Statistiques Clés -->
        <div class="stats-dashboard-grid">
            <div class="stat-premium-card stat-indigo">
                <div class="card-glow"></div>
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-numbers">
                    <h3><?php echo number_format($stats['unique_visitors'] ?? 0, 0, ',', ' '); ?></h3>
                    <p>Visiteurs Uniques (Session)</p>
                </div>
            </div>
            <div class="stat-premium-card stat-emerald">
                <div class="card-glow"></div>
                <div class="stat-icon"><i class="fas fa-network-wired"></i></div>
                <div class="stat-numbers">
                    <h3><?php echo number_format($stats['unique_ips'] ?? 0, 0, ',', ' '); ?></h3>
                    <p>Adresses IP Uniques</p>
                </div>
            </div>
            <div class="stat-premium-card stat-amber">
                <div class="card-glow"></div>
                <div class="stat-icon"><i class="fas fa-mouse-pointer"></i></div>
                <div class="stat-numbers">
                    <h3><?php echo number_format($stats['total_visits'] ?? 0, 0, ',', ' '); ?></h3>
                    <p>Total des Pages Vues</p>
                </div>
            </div>
            <div class="stat-premium-card stat-rose">
                <div class="card-glow"></div>
                <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-numbers">
                    <h3><?php echo number_format($stats['today_visits'] ?? 0, 0, ',', ' '); ?></h3>
                    <p>Visites Aujourd'hui</p>
                </div>
            </div>
        </div>

        <div class="visitors-analytics-row">
            <!-- Pages les plus visitées -->
            <div class="analytics-panel-card">
                <div class="panel-header">
                    <h3><i class="fas fa-file-alt"></i> Top des Pages Visitées</h3>
                </div>
                <div class="panel-body">
                    <ul class="progress-bar-list">
                        <?php 
                        $maxVisits = 1;
                        if (!empty($topPages)) {
                            $maxVisits = max(array_column($topPages, 'visits')) ?: 1;
                        }
                        foreach ($topPages as $page):
                            $percentage = ($page['visits'] / $maxVisits) * 100;
                        ?>
                        <li>
                            <div class="progress-bar-label">
                                <span class="page-url"><?php echo htmlspecialchars($page['page_visited']); ?></span>
                                <span class="page-count"><strong><?php echo $page['visits']; ?></strong> v. (<?php echo $page['unique_v']; ?> uniq)</span>
                            </div>
                            <div class="progress-bar-bg">
                                <div class="progress-bar-fill" style="width: <?php echo $percentage; ?>%"></div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Graphique Evolution par jour (Barres HTML/CSS stylisées) -->
            <div class="analytics-panel-card">
                <div class="panel-header">
                    <h3><i class="fas fa-chart-bar"></i> Activité des 15 derniers jours</h3>
                </div>
                <div class="panel-body">
                    <div class="bar-chart-container">
                        <?php 
                        $chartDays = array_slice($byDay, 0, 15);
                        $chartDays = array_reverse($chartDays);
                        $maxDayVisits = 1;
                        if (!empty($chartDays)) {
                            $maxDayVisits = max(array_column($chartDays, 'visits')) ?: 1;
                        }
                        foreach ($chartDays as $day):
                            $heightPercent = ($day['visits'] / $maxDayVisits) * 80; // max 80% height
                        ?>
                        <div class="bar-chart-col">
                            <div class="bar-tooltip"><?php echo $day['visits']; ?> visites<br><?php echo $day['unique_v']; ?> uniques</div>
                            <div class="bar-col-fill" style="height: <?php echo max($heightPercent, 5); ?>%"></div>
                            <div class="bar-col-label"><?php echo date('d/m', strtotime($day['date'])); ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des Visiteurs Récents -->
        <div class="recent-visitors-panel">
            <div class="panel-header">
                <h3><i class="fas fa-history"></i> Visiteurs Uniques Récents</h3>
            </div>
            <div class="table-container">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Adresse IP</th>
                            <th>Première Visite</th>
                            <th>Dernière Visite</th>
                            <th>Pages Consulter</th>
                            <th>Navigateur / OS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recent)): ?>
                            <?php foreach ($recent as $r): ?>
                            <tr>
                                <td><span class="ip-badge"><i class="fas fa-shield-alt"></i> <?php echo htmlspecialchars($r['ip_address']); ?></span></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($r['first_visit'])); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($r['last_visit'])); ?></td>
                                <td><strong style="color:var(--secondary)"><?php echo $r['page_count']; ?></strong> pages</td>
                                <td><span class="ua-text" title="<?php echo htmlspecialchars($r['user_agent']); ?>"><?php echo htmlspecialchars(mb_strimwidth($r['user_agent'], 0, 70, '...')); ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align:center; padding:30px 0;">Aucun visiteur enregistré pour le moment.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<style>
/* Stat Cards Premium */
.stats-dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
.stat-premium-card { background: linear-gradient(135deg, #1e293b, #0f172a); border-radius: 16px; padding: 24px; position: relative; overflow: hidden; display: flex; align-items: center; gap: 20px; box-shadow: var(--shadow); border: 1px solid rgba(255,255,255,0.05); }
.card-glow { position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.03) 0%, transparent 70%); transition: transform 0.5s; }
.stat-premium-card:hover .card-glow { transform: translate(10%, 10%); }
.stat-icon { width: 54px; height: 54px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; }
.stat-numbers h3 { font-size: 1.8rem; font-weight: 800; color: white; margin: 0 0 4px; }
.stat-numbers p { font-size: 0.8rem; color: rgba(255,255,255,0.5); font-weight: 600; margin: 0; }

.stat-indigo .stat-icon { background: linear-gradient(135deg, #6366f1, #4f46e5); box-shadow: 0 4px 14px rgba(99,102,241,0.4); }
.stat-emerald .stat-icon { background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 14px rgba(16,185,129,0.4); }
.stat-amber .stat-icon { background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 4px 14px rgba(245,158,11,0.4); }
.stat-rose .stat-icon { background: linear-gradient(135deg, #f43f5e, #e11d48); box-shadow: 0 4px 14px rgba(244,63,94,0.4); }

/* Panels and charts */
.visitors-analytics-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 30px; }
.analytics-panel-card, .recent-visitors-panel { background: white; border-radius: 16px; box-shadow: var(--shadow); border: 1.5px solid #f1f5f9; overflow: hidden; }
.panel-header { padding: 18px 24px; border-bottom: 1.5px solid #f1f5f9; }
.panel-header h3 { font-size: 1rem; font-weight: 700; color: var(--primary); margin: 0; display: flex; align-items: center; gap: 8px; }
.panel-body { padding: 24px; }

/* Progress bar list */
.progress-bar-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 16px; }
.progress-bar-label { display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 6px; }
.page-url { font-weight: 700; color: var(--primary); font-family: monospace; }
.page-count { color: var(--gray); }
.progress-bar-bg { width: 100%; height: 8px; background: #f1f5f9; border-radius: 10px; overflow: hidden; }
.progress-bar-fill { height: 100%; background: linear-gradient(90deg, #6366f1, #8b5cf6); border-radius: 10px; }

/* Bar Chart */
.bar-chart-container { display: flex; align-items: flex-end; justify-content: space-between; height: 200px; padding-top: 20px; }
.bar-chart-col { display: flex; flex-direction: column; align-items: center; flex: 1; height: 100%; justify-content: flex-end; position: relative; cursor: pointer; }
.bar-col-fill { width: 60%; background: linear-gradient(180deg, var(--secondary), #d97706); border-radius: 4px 4px 0 0; transition: all 0.2s; }
.bar-chart-col:hover .bar-col-fill { background: linear-gradient(180deg, #f59e0b, #ef4444); transform: scaleX(1.1); }
.bar-col-label { font-size: 0.68rem; color: var(--gray); font-weight: 700; margin-top: 8px; }
.bar-tooltip { position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%) translateY(-6px); background: #0f172a; color: white; padding: 6px 10px; border-radius: 6px; font-size: 0.7rem; white-space: nowrap; visibility: hidden; opacity: 0; transition: all 0.2s; z-index: 10; box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
.bar-tooltip::after { content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%); border: 5px solid transparent; border-top-color: #0f172a; }
.bar-chart-col:hover .bar-tooltip { visibility: visible; opacity: 1; transform: translateX(-50%) translateY(0); }

/* Table styling */
.premium-table { width: 100%; border-collapse: collapse; text-align: left; }
.premium-table th { font-size: 0.8rem; font-weight: 800; color: var(--primary); text-transform: uppercase; padding: 16px 24px; border-bottom: 1.5px solid #f1f5f9; }
.premium-table td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; font-size: 0.88rem; }
.premium-table tr:last-child td { border-bottom: none; }
.ip-badge { background: #f1f5f9; color: var(--primary); font-weight: 700; padding: 6px 10px; border-radius: 6px; font-size: 0.8rem; }
.ua-text { color: var(--gray); font-size: 0.78rem; font-family: sans-serif; }

@media (max-width: 900px) { .visitors-analytics-row { grid-template-columns: 1fr; } }
</style>
<?php include __DIR__ . '/../../views/layout_footer.php'; ?>
