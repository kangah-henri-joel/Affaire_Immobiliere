<?php
// views/super_admin/visitors.php
include __DIR__ . '/sa_header.php';
?>
<div class="sa-layout">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="sa-main">
        <div class="sa-topbar">
            <button class="sa-hamburger" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
            <div class="sa-topbar-title">
                <h2><i class="fas fa-eye" style="color:var(--sa-accent);margin-right:8px;"></i>Suivi des Visiteurs</h2>
                <p>Statistiques et logs de fréquentation globale du site public</p>
            </div>
            <div class="sa-topbar-actions">
                <div class="sa-badge-role"><i class="fas fa-chart-line"></i> Analytics</div>
            </div>
        </div>

        <div class="sa-content">
            <!-- Grid de Statistiques Clés -->
            <div class="sa-grid-4">
                <div class="sa-card-stat">
                    <div class="sa-stat-header">
                        <span class="title">Visiteurs Uniques (Session)</span>
                        <div class="icon" style="background:rgba(99,102,241,0.1);color:#6366f1;"><i class="fas fa-users"></i></div>
                    </div>
                    <h2><?php echo number_format($stats['unique_visitors'] ?? 0, 0, ',', ' '); ?></h2>
                    <p class="desc">Basé sur les jetons de session</p>
                </div>
                <div class="sa-card-stat">
                    <div class="sa-stat-header">
                        <span class="title">Adresses IP Uniques</span>
                        <div class="icon" style="background:rgba(16,185,129,0.1);color:#10b981;"><i class="fas fa-network-wired"></i></div>
                    </div>
                    <h2><?php echo number_format($stats['unique_ips'] ?? 0, 0, ',', ' '); ?></h2>
                    <p class="desc">IP distinctes enregistrées</p>
                </div>
                <div class="sa-card-stat">
                    <div class="sa-stat-header">
                        <span class="title">Total des Pages Vues</span>
                        <div class="icon" style="background:rgba(245,158,11,0.1);color:#f59e0b;"><i class="fas fa-mouse-pointer"></i></div>
                    </div>
                    <h2><?php echo number_format($stats['total_visits'] ?? 0, 0, ',', ' '); ?></h2>
                    <p class="desc">Hits globaux du site</p>
                </div>
                <div class="sa-card-stat">
                    <div class="sa-stat-header">
                        <span class="title">Visites Aujourd'hui</span>
                        <div class="icon" style="background:rgba(244,63,94,0.1);color:#f43f5e;"><i class="fas fa-calendar-day"></i></div>
                    </div>
                    <h2><?php echo number_format($stats['today_visits'] ?? 0, 0, ',', ' '); ?></h2>
                    <p class="desc">Depuis 00h00 locale</p>
                </div>
            </div>

            <!-- Graphiques et pages -->
            <div class="sa-grid-2" style="margin-top:20px;">
                <!-- Pages les plus consultées -->
                <div class="sa-card">
                    <div class="sa-card-header">
                        <h3><i class="fas fa-file-alt"></i> Top des Pages Visitées</h3>
                    </div>
                    <div class="sa-card-body" style="padding:20px;">
                        <ul class="progress-bar-list-sa">
                            <?php 
                            $maxVisits = 1;
                            if (!empty($topPages)) {
                                $maxVisits = max(array_column($topPages, 'visits')) ?: 1;
                            }
                            foreach ($topPages as $page):
                                $percentage = ($page['visits'] / $maxVisits) * 100;
                            ?>
                            <li>
                                <div class="progress-bar-label-sa">
                                    <span class="page-url"><?php echo htmlspecialchars($page['page_visited']); ?></span>
                                    <span class="page-count"><?php echo $page['visits']; ?> v. (<?php echo $page['unique_v']; ?> uniq)</span>
                                </div>
                                <div class="progress-bar-bg-sa">
                                    <div class="progress-bar-fill-sa" style="width: <?php echo $percentage; ?>%"></div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Activité des 15 derniers jours -->
                <div class="sa-card">
                    <div class="sa-card-header">
                        <h3><i class="fas fa-chart-bar"></i> Activité des 15 derniers jours</h3>
                    </div>
                    <div class="sa-card-body" style="padding:20px;">
                        <div class="bar-chart-container-sa">
                            <?php 
                            $chartDays = array_slice($byDay, 0, 15);
                            $chartDays = array_reverse($chartDays);
                            $maxDayVisits = 1;
                            if (!empty($chartDays)) {
                                $maxDayVisits = max(array_column($chartDays, 'visits')) ?: 1;
                            }
                            foreach ($chartDays as $day):
                                $heightPercent = ($day['visits'] / $maxDayVisits) * 80;
                            ?>
                            <div class="bar-chart-col-sa">
                                <div class="bar-tooltip-sa"><?php echo $day['visits']; ?> v.<br><?php echo $day['unique_v']; ?> uniq</div>
                                <div class="bar-col-fill-sa" style="height: <?php echo max($heightPercent, 5); ?>%"></div>
                                <div class="bar-col-label-sa"><?php echo date('d/m', strtotime($day['date'])); ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des derniers visiteurs uniques -->
            <div class="sa-card" style="margin-top:20px;">
                <div class="sa-card-header">
                    <h3><i class="fas fa-history"></i> Visiteurs Uniques Récents (100 max)</h3>
                    <input type="text" class="sa-search" id="visitorSearch" placeholder="🔍 Rechercher…" onkeyup="filterTable('visitorTable','visitorSearch')">
                </div>
                <div class="sa-table-wrap">
                    <table class="sa-table" id="visitorTable">
                        <thead>
                            <tr>
                                <th>Adresse IP</th>
                                <th>Première Visite</th>
                                <th>Dernière Visite</th>
                                <th>Nombre de Hits</th>
                                <th>Navigateur / OS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent)): ?>
                                <?php foreach ($recent as $r): ?>
                                <tr>
                                    <td><span class="ip-badge-sa"><i class="fas fa-shield-alt"></i> <?php echo htmlspecialchars($r['ip_address']); ?></span></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($r['first_visit'])); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($r['last_visit'])); ?></td>
                                    <td><strong style="color:var(--sa-gold)"><?php echo $r['page_count']; ?></strong> pages</td>
                                    <td><span class="ua-text-sa" title="<?php echo htmlspecialchars($r['user_agent']); ?>"><?php echo htmlspecialchars(mb_strimwidth($r['user_agent'], 0, 70, '...')); ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" style="text-align:center; padding:30px 0;">Aucun visiteur enregistré.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Stats cards in SA */
.sa-grid-4 { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
.sa-card-stat { background: #151f32; border: 1px solid var(--sa-border); border-radius: 12px; padding: 20px; }
.sa-stat-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.sa-stat-header .title { font-size: 0.78rem; font-weight: 700; color: var(--sa-gray); text-transform: uppercase; }
.sa-stat-header .icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.sa-card-stat h2 { font-size: 1.6rem; font-weight: 800; color: white; margin: 0 0 4px; }
.sa-card-stat .desc { font-size: 0.7rem; color: var(--sa-gray); margin: 0; }

.sa-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

/* Progress bars SA */
.progress-bar-list-sa { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 14px; }
.progress-bar-label-sa { display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 4px; }
.progress-bar-label-sa .page-url { font-weight: 700; color: white; font-family: monospace; }
.progress-bar-label-sa .page-count { color: var(--sa-gray); }
.progress-bar-bg-sa { width: 100%; height: 6px; background: rgba(255,255,255,0.06); border-radius: 10px; }
.progress-bar-fill-sa { height: 100%; background: linear-gradient(90deg, #6366f1, #e0a96d); border-radius: 10px; }

/* Charts SA */
.bar-chart-container-sa { display: flex; align-items: flex-end; justify-content: space-between; height: 160px; }
.bar-chart-col-sa { display: flex; flex-direction: column; align-items: center; flex: 1; height: 100%; justify-content: flex-end; position: relative; cursor: pointer; }
.bar-col-fill-sa { width: 55%; background: linear-gradient(180deg, var(--sa-gold), #b45309); border-radius: 4px 4px 0 0; transition: all 0.2s; }
.bar-chart-col-sa:hover .bar-col-fill-sa { background: linear-gradient(180deg, #f59e0b, #ef4444); transform: scaleX(1.1); }
.bar-col-label-sa { font-size: 0.65rem; color: var(--sa-gray); font-weight: 700; margin-top: 6px; }
.bar-tooltip-sa { position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%) translateY(-6px); background: #0f172a; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.65rem; white-space: nowrap; visibility: hidden; opacity: 0; transition: all 0.2s; z-index: 10; box-shadow: 0 4px 10px rgba(0,0,0,0.3); border: 1px solid var(--sa-border); }
.bar-chart-col-sa:hover .bar-tooltip-sa { visibility: visible; opacity: 1; transform: translateX(-50%) translateY(0); }

/* Badges */
.ip-badge-sa { background: rgba(255,255,255,0.05); color: white; border: 1px solid var(--sa-border); font-weight: 700; padding: 4px 8px; border-radius: 4px; font-size: 0.78rem; }
.ua-text-sa { color: var(--sa-gray); font-size: 0.75rem; }
</style>
<script>
function filterTable(tableId, searchId) {
    const q = document.getElementById(searchId).value.toLowerCase();
    document.querySelectorAll('#'+tableId+' tbody tr').forEach(r => {
        r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>
<?php include __DIR__ . '/sa_footer.php'; ?>
