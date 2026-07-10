<?php
// views/admin/messages.php
include __DIR__ . '/../../views/layout_header.php';
?>
<div class="admin-container">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="admin-content">
        <header class="admin-header">
            <h1><i class="fas fa-comments" style="color:var(--secondary)"></i> Messages – Super Admin</h1>
        </header>

        <?php if (!$superAdmin): ?>
        <div class="alert-info-card">
            <i class="fas fa-info-circle"></i>
            <p>Aucun super administrateur n'est configuré pour le moment.</p>
        </div>
        <?php else: ?>

        <div class="chat-layout">
            <!-- Entête conversation -->
            <div class="chat-header">
                <div class="chat-peer">
                    <div class="peer-avatar sa">SA</div>
                    <div>
                        <strong><?php echo htmlspecialchars($superAdmin['full_name'] ?? $superAdmin['username']); ?></strong>
                        <span>Super Administrateur</span>
                    </div>
                </div>
                <div class="chat-status"><span class="dot-online"></span> Disponible</div>
            </div>

            <!-- Messages -->
            <div class="chat-messages" id="chatMessages">
                <?php if (empty($messages)): ?>
                <div class="chat-empty">
                    <i class="fas fa-comment-slash"></i>
                    <p>Aucun message pour l'instant. Démarrez la conversation !</p>
                </div>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                    <?php $isMine = ((int)$msg['sender_id'] === (int)$_SESSION['user_id']); ?>
                    <div class="chat-bubble <?php echo $isMine ? 'mine' : 'theirs'; ?>" id="msg-<?php echo $msg['id']; ?>">
                        <div class="bubble-content">
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

            <!-- Formulaire d'envoi -->
            <form action="<?php echo BASE_URL; ?>/admin/messages/send" method="POST" class="chat-form">
                <input type="hidden" name="receiver_id" value="<?php echo $superAdmin['id']; ?>">
                <div class="chat-input-row">
                    <textarea name="content" id="chatInput" placeholder="Écrivez votre message au super admin..." rows="1" required></textarea>
                    <button type="submit" class="chat-send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </main>
</div>

<?php include __DIR__ . '/../../views/layout_footer.php'; ?>
<?php include __DIR__ . '/../../views/admin/_chat_styles.php'; ?>
<script>
// Scroll to bottom on load
document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('chatMessages');
    if (el) el.scrollTop = el.scrollHeight;
    // Auto-resize textarea
    const ta = document.getElementById('chatInput');
    if (ta) {
        ta.addEventListener('input', () => { ta.style.height = 'auto'; ta.style.height = Math.min(ta.scrollHeight, 120) + 'px'; });
        ta.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); ta.form.submit(); } });
    }
});
</script>
