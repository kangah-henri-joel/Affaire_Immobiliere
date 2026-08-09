<?php
// views/admin/_chat_styles.php  – styles partagés pour les interfaces de chat
?>
<style>
/* ── CHAT LAYOUT ──────────────────────────────────────────────── */
.chat-layout {
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow);
    display: flex;
    flex-direction: column;
    height: calc(100vh - 180px);
    min-height: 500px;
    overflow: hidden;
}
.chat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 24px;
    background: linear-gradient(135deg, #0f172a, #1e293b);
    border-bottom: 1px solid rgba(255,255,255,0.08);
}
.chat-peer { display: flex; align-items: center; gap: 14px; }
.peer-avatar {
    width: 44px; height: 44px; border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; font-weight: 800; color: white;
}
.peer-avatar.sa { background: linear-gradient(135deg, #f59e0b, #ef4444); }
.peer-avatar.client { background: linear-gradient(135deg, #10b981, #3b82f6); }
.chat-peer strong { display: block; color: white; font-size: 0.95rem; }
.chat-peer span { color: rgba(255,255,255,0.5); font-size: 0.75rem; }
.chat-status { display: flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.6); font-size: 0.8rem; }
.dot-online { width: 8px; height: 8px; border-radius: 50%; background: #10b981; animation: blink 1.5s infinite; }
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    background: #f8fafc;
}
.chat-empty {
    text-align: center; color: var(--gray); margin: auto;
}
.chat-empty i { font-size: 3rem; color: #e2e8f0; display: block; margin-bottom: 12px; }

.chat-bubble { display: flex; }
.chat-bubble.mine { justify-content: flex-end; }
.chat-bubble.theirs { justify-content: flex-start; }

.bubble-content {
    max-width: 70%;
    padding: 12px 16px;
    border-radius: 16px;
    position: relative;
}
.chat-bubble.mine .bubble-content {
    background: var(--primary);
    color: white;
    border-bottom-right-radius: 4px;
}
.chat-bubble.theirs .bubble-content {
    background: white;
    color: var(--primary);
    border: 1.5px solid #e2e8f0;
    border-bottom-left-radius: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.bubble-content p { margin: 0 0 4px; font-size: 0.9rem; line-height: 1.5; }
.bubble-time {
    font-size: 0.68rem;
    opacity: 0.6;
    display: flex;
    align-items: center;
    gap: 4px;
    justify-content: flex-end;
}
.bubble-time i { font-size: 0.7rem; color: var(--secondary); opacity: 1; }

.chat-form { padding: 16px 24px; background: white; border-top: 1.5px solid #e2e8f0; }
.chat-input-row { display: flex; gap: 12px; align-items: flex-end; }
.chat-input-row textarea {
    flex: 1; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 12px;
    font-family: inherit; font-size: 0.9rem; resize: none; transition: border 0.2s;
    max-height: 120px; overflow-y: auto;
}
.chat-input-row textarea:focus { outline: none; border-color: var(--secondary); }
.chat-send-btn {
    width: 46px; height: 46px; border-radius: 50%;
    background: var(--primary); color: white; border: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.2s; flex-shrink: 0;
    font-size: 1rem;
}
.chat-send-btn:hover { background: var(--secondary); color: var(--primary); transform: scale(1.05); }

.alert-info-card { background: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: 12px; padding: 20px 24px; display: flex; align-items: center; gap: 14px; color: #1d4ed8; }
.alert-info-card i { font-size: 1.5rem; }

@media (max-width: 768px) {
    .chat-layout { height: calc(100vh - 120px); min-height: 400px; border-radius: 12px; }
    .chat-header { padding: 12px 14px; }
    .chat-messages { padding: 14px; gap: 10px; }
    .bubble-content { max-width: 85%; padding: 10px 12px; }
    .bubble-content p { font-size: 0.85rem; }
    .chat-form { padding: 10px 12px; }
    .chat-input-row textarea { padding: 10px; font-size: 0.85rem; }
    .chat-send-btn { width: 40px; height: 40px; font-size: 0.9rem; }
}
</style>
