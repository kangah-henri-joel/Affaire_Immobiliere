<?php
// views/admin/client_messages.php
include __DIR__ . '/../../views/layout_header.php';
?>
<div class="admin-container">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="admin-content">
        <header class="admin-header">
            <h1><i class="fas fa-comment-dots" style="color:var(--secondary)"></i> Messages Clients</h1>
        </header>

        <?php if (empty($convList)): ?>
        <div class="empty-conv-state">
            <i class="fas fa-inbox"></i>
            <h3>Aucune conversation</h3>
            <p>Les clients qui vous enverront des messages depuis les annonces apparaîtront ici.</p>
        </div>
        <?php else: ?>
        <div class="conv-list-grid">
            <?php foreach ($convList as $conv): ?>
            <a href="<?php echo BASE_URL; ?>/admin/client-messages/conversation?id=<?php echo $conv['id']; ?>" class="conv-card <?php echo ($conv['unread_count'] > 0) ? 'has-unread' : ''; ?>">
                <div class="conv-avatar"><?php echo strtoupper(substr($conv['client_name'] ?? 'C', 0, 1)); ?></div>
                <div class="conv-info">
                    <div class="conv-name-row">
                        <strong><?php echo htmlspecialchars($conv['client_name'] ?? 'Client'); ?></strong>
                        <?php if ($conv['unread_count'] > 0): ?>
                        <span class="unread-badge"><?php echo $conv['unread_count']; ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($conv['annonce_title'])): ?>
                    <span class="conv-annonce"><i class="fas fa-home"></i> <?php echo htmlspecialchars($conv['annonce_title']); ?></span>
                    <?php endif; ?>
                    <p class="conv-last-msg">
                        <?php if (!empty($conv['last_content'])): ?>
                        <?php if ($conv['last_sender'] === 'admin'): ?><em>Vous : </em><?php endif; ?>
                        <?php echo htmlspecialchars(mb_strimwidth($conv['last_content'], 0, 60, '...')); ?>
                        <?php else: ?>
                        <em>Aucun message encore.</em>
                        <?php endif; ?>
                    </p>
                    <span class="conv-time"><?php echo date('d/m H:i', strtotime($conv['last_message_at'])); ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
</div>
<style>
.empty-conv-state { text-align: center; padding: 80px 20px; color: var(--gray); }
.empty-conv-state i { font-size: 4rem; color: #e2e8f0; display: block; margin-bottom: 20px; }
.empty-conv-state h3 { font-size: 1.3rem; font-weight: 700; color: var(--primary); margin-bottom: 8px; }
.conv-list-grid { display: flex; flex-direction: column; gap: 0; background: white; border-radius: 16px; box-shadow: var(--shadow); overflow: hidden; }
.conv-card { display: flex; align-items: center; gap: 16px; padding: 18px 24px; text-decoration: none; color: var(--primary); border-bottom: 1px solid #f1f5f9; transition: background 0.15s; }
.conv-card:last-child { border-bottom: none; }
.conv-card:hover { background: #f8fafc; }
.conv-card.has-unread { background: #fff7ed; }
.conv-card.has-unread:hover { background: #fef3c7; }
.conv-avatar { width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #3b82f6); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: white; flex-shrink: 0; }
.conv-info { flex: 1; min-width: 0; }
.conv-name-row { display: flex; align-items: center; gap: 8px; margin-bottom: 2px; }
.conv-name-row strong { font-size: 0.95rem; font-weight: 700; }
.unread-badge { background: var(--secondary); color: var(--primary); font-size: 0.65rem; font-weight: 800; padding: 2px 7px; border-radius: 20px; }
.conv-annonce { font-size: 0.75rem; color: var(--secondary); font-weight: 600; display: block; margin-bottom: 3px; }
.conv-last-msg { font-size: 0.82rem; color: var(--gray); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin: 0; }
.conv-time { font-size: 0.72rem; color: var(--gray); margin-top: 4px; display: block; }
</style>
<?php include __DIR__ . '/../../views/layout_footer.php'; ?>
