<?php
// views/super_admin/admin_conversation.php
include __DIR__ . '/sa_header.php';
?>
<div class="sa-layout">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="sa-main">
        <div class="sa-topbar">
            <button class="sa-hamburger" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
            <div class="sa-topbar-title">
                <a href="<?php echo BASE_URL; ?>/super-admin/messages" style="color:var(--sa-gray); text-decoration:none; font-size:0.82rem; font-weight:700; display:inline-flex; align-items:center; gap:6px; margin-bottom:4px;">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
                <h2>Conversation avec <?php echo htmlspecialchars($admin['full_name'] ?? $admin['username']); ?></h2>
            </div>
        </div>

        <div class="sa-content" style="padding-bottom:10px;">
            <div class="sa-chat-box">
                <!-- Messages list -->
                <div class="sa-chat-messages" id="saChatMessages">
                    <?php if (empty($messages)): ?>
                    <div class="sa-chat-empty">
                        <i class="fas fa-comment-slash"></i>
                        <p>Aucun message encore. Démarrez la discussion !</p>
                    </div>
                    <?php else: ?>
                        <?php foreach ($messages as $msg): ?>
                        <?php $isMine = ((int)$msg['sender_id'] === (int)$_SESSION['user_id']); ?>
                        <div class="sa-chat-bubble <?php echo $isMine ? 'mine' : 'theirs'; ?>">
                            <div class="sa-bubble-content">
                                <p><?php echo nl2br(htmlspecialchars($msg['content'])); ?></p>
                                <span class="sa-bubble-time">
                                    <?php echo date('d/m H:i', strtotime($msg['created_at'])); ?>
                                    <?php if ($isMine && $msg['is_read']): ?><i class="fas fa-check-double" style="color:var(--sa-accent)" title="Lu"></i><?php endif; ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Form -->
                <form action="<?php echo BASE_URL; ?>/super-admin/messages/conversation?admin_id=<?php echo $admin['id']; ?>" method="POST" class="sa-chat-form">
                    <div class="sa-chat-input-row">
                        <textarea name="content" id="saChatInput" placeholder="Tapez votre réponse ici..." rows="1" required></textarea>
                        <button type="submit" class="sa-chat-send-btn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.sa-chat-box { background: #151f32; border: 1px solid var(--sa-border); border-radius: 12px; height: calc(100vh - 200px); min-height: 480px; display: flex; flex-direction: column; overflow: hidden; }
.sa-chat-messages { flex: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 16px; background: rgba(0,0,0,0.15); }
.sa-chat-empty { text-align: center; color: var(--sa-gray); margin: auto; }
.sa-chat-empty i { font-size: 2.5rem; color: var(--sa-border); display: block; margin-bottom: 12px; }

.sa-chat-bubble { display: flex; }
.sa-chat-bubble.mine { justify-content: flex-end; }
.sa-chat-bubble.theirs { justify-content: flex-start; }

.sa-bubble-content { max-width: 70%; padding: 12px 16px; border-radius: 12px; }
.sa-chat-bubble.mine .sa-bubble-content { background: var(--sa-accent); color: var(--sa-bg); border-bottom-right-radius: 2px; }
.sa-chat-bubble.mine .sa-bubble-content p { color: var(--sa-bg); }
.sa-chat-bubble.theirs .sa-bubble-content { background: rgba(255,255,255,0.06); border: 1px solid var(--sa-border); color: white; border-bottom-left-radius: 2px; }

.sa-bubble-content p { margin: 0 0 4px; font-size: 0.88rem; line-height: 1.45; }
.sa-bubble-time { font-size: 0.65rem; color: var(--sa-gray); display: flex; align-items: center; justify-content: flex-end; gap: 4px; }

.sa-chat-form { padding: 16px 20px; border-top: 1px solid var(--sa-border); background: #151f32; }
.sa-chat-input-row { display: flex; gap: 12px; align-items: flex-end; }
.sa-chat-input-row textarea { flex: 1; padding: 12px 16px; border: 1px solid var(--sa-border); border-radius: 8px; background: rgba(0,0,0,0.2); color: white; font-family: inherit; font-size: 0.9rem; resize: none; max-height: 120px; overflow-y: auto; }
.sa-chat-input-row textarea:focus { outline: none; border-color: var(--sa-accent); }
.sa-chat-send-btn { width: 44px; height: 44px; border-radius: 50%; background: var(--sa-accent); color: var(--sa-bg); border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; font-size: 0.95rem; flex-shrink: 0; }
.sa-chat-send-btn:hover { background: white; transform: scale(1.05); }
</style>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('saChatMessages');
    if (el) el.scrollTop = el.scrollHeight;
    const ta = document.getElementById('saChatInput');
    if (ta) {
        ta.addEventListener('input', () => { ta.style.height = 'auto'; ta.style.height = Math.min(ta.scrollHeight, 120) + 'px'; });
        ta.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); ta.form.submit(); } });
    }
});
</script>
<?php include __DIR__ . '/sa_footer.php'; ?>
