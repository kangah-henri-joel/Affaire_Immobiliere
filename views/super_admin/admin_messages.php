<?php
// views/super_admin/admin_messages.php
include __DIR__ . '/sa_header.php';
?>
<div class="sa-layout">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="sa-main">
        <div class="sa-topbar">
            <button class="sa-hamburger" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
            <div class="sa-topbar-title">
                <h2><i class="fas fa-comments" style="color:var(--sa-accent);margin-right:8px;"></i>Messages des Administrateurs</h2>
                <p>Échanges internes avec les administrateurs et les agents</p>
            </div>
        </div>

        <div class="sa-content">
            <?php if (empty($threads)): ?>
            <div class="sa-empty-state">
                <i class="fas fa-inbox"></i>
                <h3>Aucun message</h3>
                <p>Aucun administrateur ou agent ne vous a encore envoyé de message.</p>
            </div>
            <?php else: ?>
            <div class="sa-card">
                <div class="sa-card-header">
                    <h3><i class="fas fa-comments"></i> Discussions En Cours</h3>
                </div>
                <div class="sa-thread-list">
                    <?php foreach ($threads as $th): ?>
                    <a href="<?php echo BASE_URL; ?>/super-admin/messages/conversation?admin_id=<?php echo $th['admin_id']; ?>" class="sa-thread-item <?php echo ($th['unread_count'] > 0) ? 'has-unread' : ''; ?>">
                        <div class="sa-thread-avatar">
                            <?php if (!empty($th['avatar'])): ?>
                            <img src="<?php echo BASE_URL . $th['avatar']; ?>" alt="avatar">
                            <?php else: ?>
                            <div class="sa-avatar-sm"><?php echo strtoupper(substr($th['admin_name'] ?? $th['username'] ?? 'A', 0, 1)); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="sa-thread-info">
                            <div class="sa-thread-name-row">
                                <strong><?php echo htmlspecialchars($th['admin_name'] ?? $th['username']); ?></strong>
                                <?php if ($th['unread_count'] > 0): ?>
                                <span class="sa-unread-count"><?php echo $th['unread_count']; ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="sa-thread-last-msg">
                                <?php echo htmlspecialchars(mb_strimwidth($th['last_content'], 0, 80, '...')); ?>
                            </p>
                            <span class="sa-thread-time"><?php echo date('d/m/Y H:i', strtotime($th['last_message_at'])); ?></span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.sa-empty-state { text-align: center; padding: 60px 20px; color: var(--sa-gray); }
.sa-empty-state i { font-size: 3.5rem; color: var(--sa-border); display: block; margin-bottom: 16px; }
.sa-empty-state h3 { font-size: 1.2rem; color: white; margin-bottom: 8px; }
.sa-thread-list { display: flex; flex-direction: column; }
.sa-thread-item { display: flex; align-items: center; gap: 16px; padding: 18px 24px; border-bottom: 1px solid var(--sa-border); text-decoration: none; color: white; transition: background 0.15s; }
.sa-thread-item:last-child { border-bottom: none; }
.sa-thread-item:hover { background: rgba(255,255,255,0.03); }
.sa-thread-item.has-unread { background: rgba(224, 169, 109, 0.05); }
.sa-thread-item.has-unread:hover { background: rgba(224, 169, 109, 0.08); }
.sa-thread-avatar img { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 1.5px solid var(--sa-border); }
.sa-thread-info { flex: 1; min-width: 0; }
.sa-thread-name-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px; }
.sa-thread-name-row strong { font-size: 0.95rem; }
.sa-unread-count { background: var(--sa-accent); color: var(--sa-bg); font-size: 0.65rem; font-weight: 800; padding: 2px 7px; border-radius: 20px; }
.sa-thread-last-msg { font-size: 0.82rem; color: var(--sa-gray); margin: 0 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.sa-thread-time { font-size: 0.72rem; color: var(--sa-gray); }
</style>
<?php include __DIR__ . '/sa_footer.php'; ?>
