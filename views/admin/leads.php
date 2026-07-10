<?php include __DIR__ . '/../layout_header.php'; ?>

<div class="admin-container">
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <main class="admin-content">
        <header class="admin-header">
            <h1>Gestion des <span class="highlight">Leads</span></h1>
            <p>Interagissez avec vos clients potentiels et répondez à leurs demandes.</p>
        </header>

        <?php if(isset($_GET['success']) && $_GET['success'] == 'replied'): ?>
            <div style="background: #ecfdf5; color: #065f46; padding: 15px; border-radius: 10px; margin-bottom: 20px; font-weight: 600;">
                <i class="fas fa-check-circle"></i> Votre réponse a été envoyée avec succès au client.
            </div>
        <?php endif; ?>

        <div class="annonce-table-card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Bien concerné</th>
                            <th>Message</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($leads)): ?>
                            <?php foreach($leads as $l): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo $l['client_name']; ?></strong><br>
                                        <small style="color: var(--gray)"><?php echo $l['client_email']; ?></small><br>
                                        <small style="color: var(--gray)"><?php echo $l['client_phone']; ?></small>
                                    </td>
                                    <td><?php echo $l['annonce_title'] ?? '<span class="status-tag" style="background:#f1f5f9; color:#64748b">Contact Général</span>'; ?></td>
                                    <td>
                                        <div style="max-width: 300px; font-size: 0.9rem;">
                                            <p>"<?php echo $l['message']; ?>"</p>
                                            <?php if($l['admin_reply']): ?>
                                                <div style="margin-top: 10px; padding: 10px; background: #f8fafc; border-left: 3px solid var(--secondary); font-style: italic;">
                                                    <strong>Ma réponse :</strong> <?php echo $l['admin_reply']; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($l['admin_reply']): ?>
                                            <span class="badge-status published">Répondu</span>
                                            <br><small style="color: var(--gray)"><?php echo date('d/m/Y', strtotime($l['replied_at'])); ?></small>
                                        <?php else: ?>
                                            <span class="badge-status pending">En attente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn-sm" onclick="openReplyModal(<?php echo htmlspecialchars(json_encode($l), ENT_QUOTES, 'UTF-8'); ?>)" title="Répondre">
                                            <i class="fas fa-reply"></i> Répondre
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align:center; padding: 40px; color: var(--gray);">Aucun lead pour le moment.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal Réponse -->
<div id="replyModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="toggleModal('replyModal')">&times;</span>
        <h2>Répondre à <span class="highlight" id="replyClientName"></span></h2>
        <p style="margin-bottom: 20px; color: var(--gray);">Un email sera envoyé automatiquement à l'adresse du client.</p>
        
        <form action="<?php echo BASE_URL; ?>/admin/leads/reply" method="POST">
            <input type="hidden" name="lead_id" id="replyLeadId">
            
            <div class="form-group">
                <label>Rappel du message client :</label>
                <div id="clientMessageReminder" style="padding: 15px; background: #f8fafc; border-radius: 10px; margin-bottom: 20px; font-style: italic; font-size: 0.9rem; color: var(--primary);"></div>
            </div>

            <div class="form-group">
                <label>Votre réponse</label>
                <textarea name="reply_text" rows="8" required placeholder="Tapez votre message ici..."></textarea>
            </div>

            <button type="submit" class="btn-submit">Envoyer la réponse</button>
        </form>
    </div>
</div>

<script>
function toggleModal(id) {
    const modal = document.getElementById(id);
    modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
}

function openReplyModal(lead) {
    document.getElementById('replyLeadId').value = lead.id;
    document.getElementById('replyClientName').innerText = lead.client_name;
    document.getElementById('clientMessageReminder').innerText = '"' + lead.message + '"';
    toggleModal('replyModal');
}
</script>

<?php include __DIR__ . '/../layout_footer.php'; ?>
