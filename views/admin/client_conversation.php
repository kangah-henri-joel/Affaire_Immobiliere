<?php
// views/admin/client_conversation.php
include __DIR__ . '/../../views/layout_header.php';
?>
<div class="admin-container">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="admin-content">
        <header class="admin-header">
            <a href="<?php echo BASE_URL; ?>/admin/client-messages" class="btn-back"><i class="fas fa-arrow-left"></i> Retour</a>
            <h1>Conversation avec <?php echo htmlspecialchars($conv['client_name'] ?? 'Client'); ?></h1>
        </header>

        <div class="conv-meta-bar">
            <?php if (!empty($conv['annonce_title'])): ?>
            <span><i class="fas fa-home"></i> Annonce : <strong><?php echo htmlspecialchars($conv['annonce_title']); ?></strong></span>
            <?php endif; ?>
            <?php if (!empty($conv['client_email'])): ?>
            <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($conv['client_email']); ?></span>
            <?php endif; ?>
            <span><i class="fas fa-calendar"></i> Depuis le <?php echo date('d/m/Y', strtotime($conv['created_at'])); ?></span>
        </div>

        <div class="chat-layout" style="height: calc(100vh - 240px);">
            <div class="chat-header">
                <div class="chat-peer">
                    <div class="peer-avatar client"><?php echo strtoupper(substr($conv['client_name'] ?? 'C', 0, 1)); ?></div>
                    <div>
                        <strong><?php echo htmlspecialchars($conv['client_name'] ?? 'Client'); ?></strong>
                        <span>Client</span>
                    </div>
                </div>
            </div>
            <div class="chat-messages" id="chatMessages">
                <?php if (empty($messages)): ?>
                <div class="chat-empty">
                    <i class="fas fa-comment-slash"></i>
                    <p>Aucun message dans cette conversation.</p>
                </div>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                    <?php $isMine = ($msg['sender_type'] === 'admin'); ?>
                    <div class="chat-bubble <?php echo $isMine ? 'mine' : 'theirs'; ?>" id="msg-<?php echo $msg['id']; ?>">
                        <div class="bubble-content">
                            <?php if (!$isMine): ?>
                            <small class="bubble-sender"><?php echo htmlspecialchars($msg['sender_name'] ?? 'Client'); ?></small>
                            <?php endif; ?>
                            <p><?php echo nl2br(htmlspecialchars($msg['content'])); ?></p>
                            <span class="bubble-time">
                                <?php echo date('d/m H:i', strtotime($msg['created_at'])); ?>
                                <?php if ($isMine && $msg['is_read']): ?><i class="fas fa-check-double" title="Lu"></i><?php endif; ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <form action="<?php echo BASE_URL; ?>/admin/client-messages/conversation?id=<?php echo $conv['id']; ?>" method="POST" class="chat-form">
                <div class="chat-input-row">
                    <textarea name="content" id="chatInput" placeholder="Répondre au client..." rows="1" required></textarea>
                    <button type="submit" class="chat-send-btn"><i class="fas fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
    </main>
</div>
<style>
.btn-back { display: inline-flex; align-items: center; gap: 6px; color: var(--gray); font-size: 0.85rem; font-weight: 600; text-decoration: none; }
.btn-back:hover { color: var(--primary); }
.conv-meta-bar { display: flex; flex-wrap: wrap; gap: 16px; padding: 12px 0 16px; font-size: 0.82rem; color: var(--gray); }
.conv-meta-bar span { display: flex; align-items: center; gap: 6px; }
.bubble-sender { font-size: 0.7rem; font-weight: 700; opacity: 0.6; display: block; margin-bottom: 4px; }
</style>
<?php include __DIR__ . '/../../views/layout_footer.php'; ?>
<?php include __DIR__ . '/_chat_styles.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('chatMessages');
    if (el) el.scrollTop = el.scrollHeight;
    const ta = document.getElementById('chatInput');
    if (ta) {
        ta.addEventListener('input', () => { ta.style.height = 'auto'; ta.style.height = Math.min(ta.scrollHeight, 120) + 'px'; });
        ta.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); ta.form.submit(); } });
    }
});
</script>
