<?php include __DIR__ . '/../layout_header.php'; ?>

<div class="admin-container">
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <main class="admin-content">
        <header class="admin-header">
            <h1>Mon <span class="highlight">Journal d'Activité</span></h1>
            <p style="color: var(--gray);">Historique en temps réel de toutes vos actions effectuées sur la plateforme.</p>
        </header>

        <div class="annonce-table-card" style="padding: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary);">Historique des Actions</h3>
                <span style="font-size: 0.85rem; color: var(--gray); background: #f1f5f9; padding: 4px 12px; border-radius: 20px; font-weight: 600;">
                    <?php echo count($logs); ?> action(s) récente(s)
                </span>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date & Heure</th>
                            <th>Action</th>
                            <th>Détails / Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($logs)): ?>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td style="white-space: nowrap; color: var(--primary); font-weight: 600; font-size: 0.88rem; width: 180px;">
                                        <i class="far fa-clock" style="margin-right: 6px; color: var(--secondary);"></i>
                                        <?php echo date('d/m/Y à H:i:s', strtotime($log['created_at'])); ?>
                                    </td>
                                    <td style="width: 220px;">
                                        <?php
                                            $actionType = $log['action'];
                                            $badgeColor = '#475569';
                                            $badgeBg = '#f1f5f9';
                                            
                                            if (strpos($actionType, 'Création') !== false || strpos($actionType, 'Ajout') !== false) {
                                                $badgeColor = '#166534';
                                                $badgeBg = '#dcfce7';
                                            } elseif (strpos($actionType, 'Modification') !== false || strpos($actionType, 'Mise à jour') !== false) {
                                                $badgeColor = '#9a3412';
                                                $badgeBg = '#ffedd5';
                                            } elseif (strpos($actionType, 'Suppression') !== false) {
                                                $badgeColor = '#991b1b';
                                                $badgeBg = '#fee2e2';
                                            } elseif (strpos($actionType, 'Réponse') !== false) {
                                                $badgeColor = '#075985';
                                                $badgeBg = '#e0f2fe';
                                            }
                                        ?>
                                        <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; color: <?php echo $badgeColor; ?>; background: <?php echo $badgeBg; ?>;">
                                            <?php echo htmlspecialchars($actionType); ?>
                                        </span>
                                    </td>
                                    <td style="color: var(--primary); font-size: 0.9rem; line-height: 1.5;">
                                        <?php echo htmlspecialchars($log['details'] ?? 'Aucun détail fourni.'); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 50px; color: var(--gray);">
                                    <div style="font-size: 2.5rem; margin-bottom: 15px; color: #cbd5e1;">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    Aucune action enregistrée pour le moment.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>
