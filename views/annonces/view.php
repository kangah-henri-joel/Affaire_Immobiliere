<?php include __DIR__ . '/../layout_header.php'; ?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<?php
// Rendre global pour la fonction récursive de l'arbre des commentaires
$GLOBALS['knownAuthorName'] = $knownAuthorName ?? '';

function renderCommentTree($comments, $parentId = null, $depth = 0) {
    $filtered = array_filter($comments, function($c) use ($parentId) {
        return $c['parent_id'] == $parentId;
    });

    if (empty($filtered)) return;

    $isAdminOrSuper = isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'super_admin']);
    
    foreach ($filtered as $comment):
        $indent = $depth * 30; // indentation pour les réponses imbriquées
    ?>
        <div class="comment-item-wrapper" style="margin-left: <?php echo $indent; ?>px; border-left: <?php echo $depth > 0 ? '2px solid var(--secondary)' : 'none'; ?>; padding-left: <?php echo $depth > 0 ? '14px' : '0'; ?>; margin-top: 15px;">
            <div class="comment-item" style="background: <?php echo $depth > 0 ? '#f8fafc' : 'white'; ?>; border: 1.5px solid var(--border); border-radius: 12px; padding: 16px; display: flex; justify-content: space-between; align-items: flex-start; gap: 15px;">
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
                        <span style="font-weight: 700; color: var(--primary); font-size: 0.95rem; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fas fa-user-circle" style="color: var(--gray); font-size: 1.1rem;"></i>
                            <?php echo htmlspecialchars($comment['author_name']); ?>
                        </span>
                        <span style="color: var(--gray); font-size: 0.75rem;">
                            Le <?php echo date('d/m/Y à H:i', strtotime($comment['created_at'])); ?>
                        </span>
                        <span class="comment-id-badge">#<?php echo $comment['id']; ?></span>
                    </div>
                    <p style="font-size: 0.92rem; color: #475569; line-height: 1.5; margin: 0; white-space: pre-line; padding-left: 24px;">
                        <?php echo htmlspecialchars($comment['content']); ?>
                    </p>
                    
                    <!-- Bouton Répondre -->
                    <button type="button" class="btn-reply-toggle" onclick="toggleReplyForm(<?php echo $comment['id']; ?>)">
                        <i class="fas fa-reply"></i> Répondre
                    </button>
                    
                    <!-- Formulaire de réponse caché -->
                    <div id="reply-form-<?php echo $comment['id']; ?>" class="reply-form-container" style="display:none; margin-top:10px; padding-left:24px;">
                        <form action="<?php echo BASE_URL; ?>/annonce/comment" method="POST" style="background:#f1f5f9; padding:15px; border-radius:8px; border:1px solid var(--border); margin:0;">
                            <input type="hidden" name="annonce_id" value="<?php echo $comment['annonce_id']; ?>">
                            <input type="hidden" name="parent_id" value="<?php echo $comment['id']; ?>">
                            
                            <?php if (empty($GLOBALS['knownAuthorName'])): ?>
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <input type="text" name="author_name" placeholder="Votre nom / pseudo" required style="width:100%; padding:8px 12px; border:1px solid var(--border); border-radius:6px; font-size:0.85rem;">
                                </div>
                            <?php else: ?>
                                <input type="hidden" name="author_name" value="<?php echo htmlspecialchars($GLOBALS['knownAuthorName']); ?>">
                                <div style="font-size:0.8rem; font-weight:700; margin-bottom:8px; color:var(--primary);"><i class="fas fa-user"></i> <?php echo htmlspecialchars($GLOBALS['knownAuthorName']); ?> (Enregistré)</div>
                            <?php endif; ?>
                            
                            <div class="form-group" style="margin-bottom: 10px;">
                                <textarea name="content" placeholder="Répondre à ce message..." rows="2" required style="width:100%; padding:8px 12px; border:1px solid var(--border); border-radius:6px; font-size:0.85rem; resize:vertical;"></textarea>
                            </div>
                            <button type="submit" class="btn-submit-comment" style="padding:6px 12px; font-size:0.8rem; border-radius:6px;"><i class="fas fa-paper-plane"></i> Répondre</button>
                        </form>
                    </div>
                </div>
                
                <?php if ($isAdminOrSuper): ?>
                    <form action="<?php echo BASE_URL; ?>/annonce/comment/delete" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce commentaire ?');" style="margin: 0;">
                        <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                        <input type="hidden" name="annonce_id" value="<?php echo $comment['annonce_id']; ?>">
                        <button type="submit" class="btn-delete-comment" title="Supprimer le commentaire">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
            
            <!-- Rendu récursif des enfants -->
            <?php renderCommentTree($comments, $comment['id'], $depth + 1); ?>
        </div>
    <?php
    endforeach;
}
?>

<section class="annonce-detail">
    <div class="container">
        <div class="detail-grid">
            <div class="main-content">
                <!-- Carousel Médias principal -->
                <div class="media-carousel-container">
                    <div class="media-gallery" id="mediaGallery">
                        <?php if (!empty($media)): ?>
                            <?php foreach ($media as $index => $item): ?>
                                <div class="media-item <?php echo $index === 0 ? 'active' : ''; ?>" onclick="openLightbox(<?php echo $index; ?>)">
                                    <?php if ($item['media_type'] === 'video'): ?>
                                        <video class="main-media" muted>
                                            <source src="<?php echo BASE_URL . $item['file_path']; ?>" type="video/mp4">
                                        </video>
                                        <span class="video-play-overlay"><i class="fas fa-play"></i></span>
                                    <?php else: ?>
                                        <img src="<?php echo BASE_URL . $item['file_path']; ?>" alt="<?php echo $annonce['title']; ?>" class="main-media">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <img src="<?php echo BASE_URL; ?>/assets/images/placeholder.jpg" class="main-media">
                        <?php endif; ?>
                    </div>
                    <?php if (count($media) > 1): ?>
                        <button class="carousel-btn prev" onclick="moveSlide(-1)"><i class="fas fa-chevron-left"></i></button>
                        <button class="carousel-btn next" onclick="moveSlide(1)"><i class="fas fa-chevron-right"></i></button>
                        <div class="carousel-dots" id="carouselDots">
                            <?php foreach ($media as $index => $item): ?>
                                <span class="dot <?php echo $index === 0 ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $index; ?>)"></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Galerie des miniatures supplémentaires -->
                <?php if (count($media) > 1): ?>
                <div class="media-thumbnail-grid">
                    <?php foreach ($media as $idx => $item): ?>
                        <div class="media-thumb-item <?php echo ($idx === 0) ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $idx; ?>)">
                            <?php if ($item['media_type'] === 'video'): ?>
                                <video src="<?php echo BASE_URL . $item['file_path']; ?>" style="width:100%; height:100%; object-fit:cover;"></video>
                                <span class="thumb-video-icon"><i class="fas fa-play"></i></span>
                            <?php else: ?>
                                <img src="<?php echo BASE_URL . $item['file_path']; ?>" alt="thumbnail">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <div class="description-box">
                    <h1><?php echo $annonce['title']; ?></h1>
                    <p class="price-big"><?php echo number_format($annonce['price'], 0, ',', ' '); ?> FCFA</p>
                    <div class="meta-info">
                        <span><i class="fas fa-tag"></i> <?php echo $annonce['category_name']; ?></span>
                        <span><i class="fas fa-map-marker-alt"></i> <?php echo $annonce['location_name']; ?></span>
                        <span><i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($annonce['created_at'])); ?></span>
                        <span><i class="fas fa-eye"></i> <?php echo $annonce['views_count']; ?> vues</span>
                    </div>
                    <hr>
                    <h3>Description</h3>
                    <div class="text-content">
                        <?php echo nl2br($annonce['description']); ?>
                    </div>
                </div>

                <!-- Localisation -->
                <div class="map-box">
                    <div class="map-box-header">
                        <div>
                            <h3><i class="fas fa-map-marker-alt" style="color:var(--danger);"></i> Localisation du bien</h3>
                            <p class="map-address-text"><?php echo htmlspecialchars($annonce['location_name'] ?? 'Non précisée'); ?></p>
                        </div>
                        <?php if($annonce['latitude'] && $annonce['longitude']): ?>
                        <div class="map-nav-btns">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $annonce['latitude']; ?>,<?php echo $annonce['longitude']; ?>" target="_blank" class="btn-nav btn-gmaps">
                                <i class="fas fa-route"></i> Google Maps
                            </a>
                            <a href="https://waze.com/ul?ll=<?php echo $annonce['latitude']; ?>,<?php echo $annonce['longitude']; ?>&navigate=yes" target="_blank" class="btn-nav btn-waze">
                                <i class="fas fa-car"></i> Waze
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if($annonce['latitude'] && $annonce['longitude']): ?>
                    <div class="map-layer-toggle">
                        <button class="layer-btn active" onclick="switchLayer('plan', this)"><i class="fas fa-map"></i> Plan</button>
                        <button class="layer-btn" onclick="switchLayer('satellite', this)"><i class="fas fa-satellite"></i> Satellite</button>
                        <button class="layer-btn" onclick="goToMyLocationView()"><i class="fas fa-crosshairs"></i> Ma position</button>
                    </div>
                    <div id="map" style="height: 420px; border-radius: 0 0 12px 12px;"></div>
                    <div class="map-coords-bar">
                        <span><i class="fas fa-circle" style="color:#ef4444;font-size:0.55rem;"></i> Zone approximative du bien</span>
                        <span><?php echo number_format((float)$annonce['latitude'], 4); ?>, <?php echo number_format((float)$annonce['longitude'], 4); ?></span>
                    </div>
                    <?php else: ?>
                    <div class="no-map-placeholder">
                        <i class="fas fa-map-marked-alt"></i>
                        <p>Localisation précise non disponible.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Section Commentaires -->
                <div class="comments-box" id="comments-section" style="margin-top: 30px; background: var(--white); padding: 30px; border-radius: 15px; box-shadow: var(--shadow);">
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 20px; color: var(--primary); display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-comments" style="color: var(--secondary);"></i> Commentaires (<?php echo count($comments ?? []); ?>)
                    </h3>

                    <!-- Formulaire d'ajout de commentaire de niveau racine -->
                    <form action="<?php echo BASE_URL; ?>/annonce/comment" method="POST" style="margin-bottom: 30px; background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid var(--border);">
                        <input type="hidden" name="annonce_id" value="<?php echo $annonce['id']; ?>">
                        
                        <?php if (empty($knownAuthorName)): ?>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label style="display: block; font-weight: 700; margin-bottom: 6px; font-size: 0.85rem; color: var(--primary);">Pseudo / Nom</label>
                            <input type="text" name="author_name" placeholder="Ex: Jean Paul" required 
                                   style="width: 100%; padding: 12px; border: 1.5px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 0.9rem;">
                        </div>
                        <?php else: ?>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label style="display: block; font-weight: 700; margin-bottom: 6px; font-size: 0.85rem; color: var(--primary);">Poster en tant que :</label>
                            <div style="background:#e0f2fe; color:#0369a1; padding:8px 12px; border-radius:6px; display:inline-block; font-weight:700; font-size:0.88rem;">
                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($knownAuthorName); ?>
                            </div>
                            <input type="hidden" name="author_name" value="<?php echo htmlspecialchars($knownAuthorName); ?>">
                        </div>
                        <?php endif; ?>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label style="display: block; font-weight: 700; margin-bottom: 6px; font-size: 0.85rem; color: var(--primary);">Votre message</label>
                            <textarea name="content" placeholder="Que pensez-vous de ce bien ? Des questions ?" rows="3" required
                                      style="width: 100%; padding: 12px; border: 1.5px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 0.9rem; resize: vertical;"></textarea>
                        </div>
                        <button type="submit" class="btn-submit-comment">
                            <i class="fas fa-paper-plane"></i> Poster le commentaire
                        </button>
                    </form>

                    <!-- Liste des commentaires structurés en arbre -->
                    <div class="comments-list" style="display: flex; flex-direction: column; gap: 20px;">
                        <?php if (empty($comments)): ?>
                            <div style="text-align: center; padding: 30px 10px; color: var(--gray);">
                                <i class="far fa-comment-dots" style="font-size: 2rem; color: var(--border); display: block; margin-bottom: 10px;"></i>
                                <p style="font-style: italic; font-size: 0.9rem; margin: 0;">Aucun commentaire pour le moment. Soyez le premier à réagir !</p>
                            </div>
                        <?php else: ?>
                            <!-- Rendu de la racine -->
                            <?php renderCommentTree($comments, null, 0); ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <aside class="sidebar">
                <div class="contact-card">
                    <h3>Intéressé par ce bien ?</h3>
                    <form action="<?php echo BASE_URL; ?>/annonce/contact" method="POST">
                        <input type="hidden" name="annonce_id" value="<?php echo $annonce['id']; ?>">
                        <div class="form-group">
                            <input type="text" name="client_name" placeholder="Votre nom complet" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="client_email" placeholder="Votre email" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="client_phone" placeholder="Votre téléphone" required>
                        </div>
                        <div class="form-group">
                            <textarea name="message" placeholder="Votre message..." rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn-submit-contact">Envoyer ma demande</button>
                    </form>
                </div>

                <div class="contact-card" style="margin-top: 20px;">
                    <h3>Contact Rapide</h3>
                    <?php
                    require_once __DIR__ . '/../../config/SiteUrl.php';
                    $waNumber = $annonce['author_whatsapp'] ?? null;
                    if ($waNumber) {
                        $waClean   = preg_replace('/[^0-9]/', '', $waNumber);
                        $waDisplay = $waNumber;
                    }
                    $annLink   = SiteUrl::annonce($annonce['id']);
                    $imgAbsUrl = SiteUrl::media($annonce['image_path'] ?? null);

                    $waMsgMain = "\u{1F3E0} *" . $annonce['title'] . "*\n"
                               . "\u{1F4B0} Prix : " . number_format($annonce['price'], 0, ',', ' ') . " FCFA\n"
                               . "\u{1F4CD} " . ($annonce['location_name'] ?? '') . "\n"
                               . "\u{1F5C2} " . ($annonce['category_name'] ?? '') . ' — ' . ucfirst($annonce['type']) . "\n\n"
                               . "\u{1F449} Voir le bien : " . $annLink . "\n\n"
                               . "Bonjour, je suis intéressé par ce bien. Merci !";
                    ?>
                    <?php if (!empty($waNumber)): ?>
                    <a href="https://wa.me/<?php echo $waClean; ?>?text=<?php echo rawurlencode($waMsgMain); ?>"
                       class="btn-whatsapp" target="_blank">
                        <i class="fab fa-whatsapp"></i> WhatsApp Direct
                    </a>
                    <p style="color: var(--gray); font-size: 0.82rem; margin-top: 8px; text-align: center;">
                        <?php echo $waDisplay; ?>
                    </p>
                    <?php endif; ?>
                </div>

                <?php if (!empty($annonce['author_name']) || !empty($annonce['author_whatsapp']) || !empty($annonce['author_phone'])): ?>
                <!-- Carte Agent -->
                <div class="agent-card">
                    <div class="agent-card-header">
                        <div class="agent-avatar">
                            <?php if (!empty($annonce['author_avatar'])): ?>
                                <img src="<?php echo BASE_URL . $annonce['author_avatar']; ?>" alt="<?php echo htmlspecialchars($annonce['author_name'] ?? ''); ?>">
                            <?php else: ?>
                                <span><?php echo strtoupper(substr($annonce['author_name'] ?? $annonce['published_by'] ?? 'A', 0, 1)); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="agent-info">
                            <div class="agent-badge">Agent Immobilier</div>
                            <h4><?php echo htmlspecialchars($annonce['author_name'] ?? $annonce['published_by'] ?? 'Notre équipe'); ?></h4>
                            <p>Publié le <?php echo date('d/m/Y', strtotime($annonce['created_at'])); ?></p>
                        </div>
                    </div>
                    <div class="agent-contacts">
                        <?php if (!empty($annonce['author_whatsapp'])): ?>
                        <?php
                            $waAgent    = preg_replace('/[^0-9]/', '', $annonce['author_whatsapp']);
                            $annLinkAg  = SiteUrl::annonce($annonce['id']);
                            $imgAgUrl   = SiteUrl::media($annonce['image_path'] ?? null);
                            $waMsgAgent = "\u{1F3E0} *" . $annonce['title'] . "*\n"
                                        . "\u{1F4B0} Prix : " . number_format($annonce['price'], 0, ',', ' ') . " FCFA\n"
                                        . "\u{1F4CD} " . ($annonce['location_name'] ?? '') . "\n"
                                        . "\u{1F5C2} " . ($annonce['category_name'] ?? '') . ' — ' . ucfirst($annonce['type']) . "\n\n"
                                        . "\u{1F449} Voir le bien : " . $annLinkAg . "\n\n"
                                        . "Bonjour " . ($annonce['author_name'] ?? '') . ", je suis intéressé par ce bien. Merci !";
                        ?>
                        <a href="https://wa.me/<?php echo $waAgent; ?>?text=<?php echo rawurlencode($waMsgAgent); ?>"
                           class="agent-btn agent-btn-wa" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                            <div>
                                <span class="btn-label">WhatsApp</span>
                                <span class="btn-value"><?php echo htmlspecialchars($annonce['author_whatsapp']); ?></span>
                            </div>
                        </a>
                        <?php endif; ?>

                        <?php if (!empty($annonce['author_phone'])): ?>
                        <a href="tel:<?php echo $annonce['author_phone']; ?>" class="agent-btn agent-btn-tel">
                            <i class="fas fa-phone-alt"></i>
                            <div>
                                <span class="btn-label">Appeler</span>
                                <span class="btn-value"><?php echo htmlspecialchars($annonce['author_phone']); ?></span>
                            </div>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

            </aside>
        </div>
    </div>
</section>

<!-- Lightbox Modal Fullscreen -->
<div id="lightboxModal" class="lightbox-modal">
    <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
    <button class="lightbox-nav lightbox-prev" onclick="changeLightboxMedia(-1)"><i class="fas fa-chevron-left"></i></button>
    <div class="lightbox-content" id="lightboxContent"></div>
    <button class="lightbox-nav lightbox-next" onclick="changeLightboxMedia(1)"><i class="fas fa-chevron-right"></i></button>
</div>

<!-- Widget Chat Flottant Client-Admin -->
<?php if (!empty($annonce['user_id'])): ?>
<div class="client-chat-widget" id="clientChatWidget">
    <button class="chat-widget-trigger" onclick="toggleChatWidget()">
        <span class="unread-dot-chat" id="chatUnreadDot" style="display:none;"></span>
        <i class="fas fa-comments"></i>
        <span>Discuter avec l'agent</span>
    </button>
    <div class="chat-widget-panel" id="chatWidgetPanel">
        <div class="chat-panel-header">
            <div class="agent-avatar-mini">
                <?php if (!empty($annonce['author_avatar'])): ?>
                    <img src="<?php echo BASE_URL . $annonce['author_avatar']; ?>" alt="avatar">
                <?php else: ?>
                    <div class="fallback-av"><?php echo strtoupper(substr($annonce['author_name'] ?? 'A', 0, 1)); ?></div>
                <?php endif; ?>
            </div>
            <div class="agent-meta-mini">
                <h4><?php echo htmlspecialchars($annonce['author_name'] ?? 'Agent'); ?></h4>
                <span>En ligne</span>
            </div>
            <button class="btn-close-chat" onclick="toggleChatWidget()">&times;</button>
        </div>

        <div class="chat-panel-messages" id="chatPanelMessages">
            <?php if (empty($clientConvMessages)): ?>
                <div class="chat-empty-state">
                    <i class="fas fa-paper-plane" style="font-size:2rem;color:var(--border);display:block;margin-bottom:10px;"></i>
                    <p>Posez vos questions à l'agent en charge de cette annonce.</p>
                </div>
            <?php else: ?>
                <?php foreach ($clientConvMessages as $msg): ?>
                    <div class="chat-panel-bubble <?php echo ($msg['sender_type'] === 'client') ? 'mine' : 'theirs'; ?>">
                        <div class="bubble-inner">
                            <p><?php echo nl2br(htmlspecialchars($msg['content'])); ?></p>
                            <span class="bubble-time"><?php echo date('H:i', strtotime($msg['created_at'])); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <form action="<?php echo BASE_URL; ?>/annonce/message" method="POST" class="chat-panel-form">
            <input type="hidden" name="annonce_id" value="<?php echo $annonce['id']; ?>">
            <input type="hidden" name="admin_id" value="<?php echo $annonce['user_id']; ?>">
            
            <?php if (empty($_SESSION['client_token']) || empty($knownAuthorName)): ?>
                <div class="chat-panel-identity">
                    <input type="text" name="client_name" placeholder="Votre nom" required style="width:50%; padding:6px; border:1px solid var(--border); border-radius:4px; font-size:0.75rem;">
                    <input type="email" name="client_email" placeholder="Votre email" required style="width:50%; padding:6px; border:1px solid var(--border); border-radius:4px; font-size:0.75rem;">
                </div>
            <?php else: ?>
                <input type="hidden" name="client_name" value="<?php echo htmlspecialchars($knownAuthorName); ?>">
                <input type="hidden" name="client_email" value="client@immoaffaire.ci">
            <?php endif; ?>

            <div class="chat-panel-input-row" style="display:flex; gap:6px; margin-top:6px;">
                <textarea name="content" placeholder="Écrire un message..." required rows="1" id="chatWidgetInput" style="flex:1; padding:8px; border:1px solid var(--border); border-radius:6px; font-family:inherit; font-size:0.85rem; resize:none;"></textarea>
                <button type="submit" class="btn-send-chat" style="background:var(--primary); color:white; border:none; width:34px; height:34px; border-radius:50%; cursor:pointer; display:flex; align-items:center; justify-content:center;"><i class="fas fa-paper-plane"></i></button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- Leaflet CSS/JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    <?php if($annonce['latitude'] && $annonce['longitude']): ?>
    const lat  = <?php echo (float)$annonce['latitude']; ?>;
    const lng  = <?php echo (float)$annonce['longitude']; ?>;
    const title = <?php echo json_encode(htmlspecialchars($annonce['title'])); ?>;
    const loc   = <?php echo json_encode(htmlspecialchars($annonce['location_name'] ?? '')); ?>;
    const price = "<?php echo number_format($annonce['price'], 0, ',', ' '); ?> FCFA";
    const img   = "<?php echo !empty($media[0]['file_path']) ? BASE_URL . $media[0]['file_path'] : BASE_URL . '/assets/images/placeholder.jpg'; ?>";
    const type  = "<?php echo ucfirst($annonce['type']); ?>";

    const map = L.map('map', { zoomControl: true }).setView([lat, lng], 16);

    const planLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org">OpenStreetMap</a>', maxZoom: 19
    });
    const satLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; Esri', maxZoom: 19
    });
    planLayer.addTo(map);

    window._leafletMapView = map;
    window._mapLayersView = { plan: planLayer, satellite: satLayer };

    L.circle([lat, lng], {
        radius: 80,
        color: '#ef4444',
        fillColor: '#fecaca',
        fillOpacity: 0.25,
        weight: 2,
        dashArray: '6,4'
    }).addTo(map);

    const icon = L.divIcon({
        html: '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="44" viewBox="0 0 36 44"><path d="M18 0C8.06 0 0 8.06 0 18c0 13.5 18 26 18 26S36 31.5 36 18C36 8.06 27.94 0 18 0z" fill="#ef4444"/><circle cx="18" cy="18" r="9" fill="white"/><circle cx="18" cy="18" r="5" fill="#ef4444"/></svg>',
        iconSize: [36, 44], iconAnchor: [18, 44], popupAnchor: [0, -46], className: ''
    });

    const popup = `
        <div style="width:230px;font-family:'Outfit',sans-serif;border-radius:12px;overflow:hidden;">
            <img src="${img}" onerror="this.src='<?php echo BASE_URL; ?>/assets/images/placeholder.jpg'"
                 style="width:100%;height:130px;object-fit:cover;display:block;">
            <div style="padding:14px;">
                <span style="font-size:0.7rem;font-weight:800;text-transform:uppercase;
                      background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;">${type}</span>
                <h4 style="margin:8px 0 4px;font-size:0.95rem;color:#0f172a;font-weight:700;">${title}</h4>
                <p style="font-size:1.05rem;font-weight:800;color:#f59e0b;margin:0 0 5px;">${price}</p>
                <p style="font-size:0.8rem;color:#64748b;margin:0 0 12px;">
                    <span style="color:#ef4444;">&#9679;</span> ${loc}
                </p>
                <div style="display:flex;gap:6px;">
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}"
                       target="_blank"
                       style="flex:1;text-align:center;background:#4285f4;color:white;padding:8px 4px;border-radius:8px;font-size:0.78rem;font-weight:700;text-decoration:none;">
                       &#128663; Google Maps
                    </a>
                    <a href="https://waze.com/ul?ll=${lat},${lng}&navigate=yes"
                       target="_blank"
                       style="flex:1;text-align:center;background:#33ccff;color:#0f172a;padding:8px 4px;border-radius:8px;font-size:0.78rem;font-weight:700;text-decoration:none;">
                       &#128663; Waze
                    </a>
                </div>
            </div>
        </div>`;

    L.marker([lat, lng], { icon })
        .addTo(map)
        .bindPopup(popup, { maxWidth: 250, minWidth: 230 })
        .openPopup();
    <?php endif; ?>

    // Auto-scroll chat floating widget
    const cMsgs = document.getElementById('chatPanelMessages');
    if (cMsgs) cMsgs.scrollTop = cMsgs.scrollHeight;

    // Prefill widget height on input
    const cInput = document.getElementById('chatWidgetInput');
    if (cInput) {
        cInput.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                cInput.form.submit();
            }
        });
    }
});

function switchLayer(type, btn) {
    document.querySelectorAll('.layer-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const map = window._leafletMapView;
    const layers = window._mapLayersView;
    if (!map || !layers) return;
    Object.values(layers).forEach(l => { try { map.removeLayer(l); } catch(e){} });
    if (type === 'satellite') layers.satellite.addTo(map);
    else layers.plan.addTo(map);
}

function goToMyLocationView() {
    if (!navigator.geolocation) return alert('Géolocalisation non supportée.');
    navigator.geolocation.getCurrentPosition(pos => {
        const map = window._leafletMapView;
        if (!map) return;
        const { latitude, longitude } = pos.coords;
        const annoceLat = <?php echo $annonce['latitude'] ? (float)$annonce['latitude'] : 5.3484; ?>;
        const annoceLng = <?php echo $annonce['longitude'] ? (float)$annonce['longitude'] : -4.0305; ?>;

        L.circle([latitude, longitude], { radius: 20, color: '#6366f1', fillOpacity: 0.5 }).addTo(map);
        L.marker([latitude, longitude], {
            icon: L.divIcon({
                html: '<div style="background:#6366f1;width:16px;height:16px;border-radius:50%;border:3px solid white;box-shadow:0 0 10px rgba(99,102,241,0.8);"></div>',
                iconSize:[16,16], iconAnchor:[8,8], className:''
            })
        }).addTo(map).bindPopup('<b>Vous êtes ici</b>').openPopup();

        map.fitBounds(L.latLngBounds([[latitude, longitude], [annoceLat, annoceLng]]), { padding: [60, 60], maxZoom: 16 });
    }, () => alert("Impossible d'obtenir votre position."));
}

// Logic Carousel
let currentSlideIdx = 0;
const slides = document.querySelectorAll('.media-item');
const thumbs = document.querySelectorAll('.media-thumb-item');

function showSlide(index) {
    if (index >= slides.length) currentSlideIdx = 0;
    else if (index < 0) currentSlideIdx = slides.length - 1;
    else currentSlideIdx = index;
    
    slides.forEach(s => s.classList.remove('active'));
    if(slides[currentSlideIdx]) slides[currentSlideIdx].classList.add('active');
    
    // Update dots
    const dots = document.querySelectorAll('.dot');
    dots.forEach(d => d.classList.remove('active'));
    if (dots[currentSlideIdx]) dots[currentSlideIdx].classList.add('active');

    // Update thumbs
    thumbs.forEach(t => t.classList.remove('active'));
    if (thumbs[currentSlideIdx]) thumbs[currentSlideIdx].classList.add('active');
    
    document.querySelectorAll('video').forEach(v => v.pause());
}

function currentSlide(index) {
    showSlide(index);
}

function moveSlide(step) {
    showSlide(currentSlideIdx + step);
}

// Logic Lightbox
const mediaData = <?php echo json_encode($media); ?>;
const baseUrl = "<?php echo BASE_URL; ?>";
let lightboxActiveIdx = 0;

function openLightbox(index) {
    lightboxActiveIdx = index;
    const modal = document.getElementById('lightboxModal');
    modal.style.display = 'flex';
    renderLightboxContent();
}

function closeLightbox() {
    document.getElementById('lightboxModal').style.display = 'none';
    const content = document.getElementById('lightboxContent');
    content.innerHTML = ''; // Arrêter les vidéos
}

function renderLightboxContent() {
    const content = document.getElementById('lightboxContent');
    content.innerHTML = '';
    const item = mediaData[lightboxActiveIdx];
    if (!item) return;

    if (item.media_type === 'video') {
        const video = document.createElement('video');
        video.src = baseUrl + item.file_path;
        video.controls = true;
        video.autoplay = true;
        video.className = 'lightbox-media-el';
        content.appendChild(video);
    } else {
        const img = document.createElement('img');
        img.src = baseUrl + item.file_path;
        img.className = 'lightbox-media-el';
        content.appendChild(img);
    }
}

function changeLightboxMedia(step) {
    lightboxActiveIdx += step;
    if (lightboxActiveIdx >= mediaData.length) lightboxActiveIdx = 0;
    else if (lightboxActiveIdx < 0) lightboxActiveIdx = mediaData.length - 1;
    renderLightboxContent();
}

// Logic Reply Form
function toggleReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    form.style.display = form.style.display === 'block' ? 'none' : 'block';
}

// Logic Chat Flottant
function toggleChatWidget() {
    const panel = document.getElementById('chatWidgetPanel');
    panel.classList.toggle('open');
    const cMsgs = document.getElementById('chatPanelMessages');
    if (cMsgs) cMsgs.scrollTop = cMsgs.scrollHeight;
}
</script>

<style>
.annonce-detail { padding: 60px 0; }
.detail-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 40px; }
.media-carousel-container { position: relative; border-radius: 20px; overflow: hidden; box-shadow: var(--shadow); margin-bottom: 15px; background: #000; }
.media-gallery { display: flex; transition: transform 0.5s ease-in-out; }
.media-item { min-width: 100%; height: 480px; display: none; position: relative; cursor: zoom-in; }
.media-item.active { display: block; }
.main-media { width: 100%; height: 100%; object-fit: cover; }
video.main-media { object-fit: contain; }
.video-play-overlay { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0,0,0,0.6); color: white; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; pointer-events: none; }

.carousel-btn { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.2); color: white; border: none; padding: 18px; cursor: pointer; border-radius: 50%; backdrop-filter: blur(5px); transition: var(--transition); z-index: 10; }
.carousel-btn:hover { background: var(--secondary); color: var(--primary); }
.prev { left: 20px; }
.next { right: 20px; }
.carousel-dots { position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); display: flex; gap: 10px; z-index: 10; }
.dot { width: 10px; height: 10px; border-radius: 50%; background: rgba(255,255,255,0.3); cursor: pointer; }
.dot.active { background: var(--secondary); width: 25px; border-radius: 10px; }

/* Grid miniatures */
.media-thumbnail-grid { display: flex; gap: 10px; margin-bottom: 30px; overflow-x: auto; padding: 4px 0; }
.media-thumb-item { width: 80px; height: 60px; border-radius: 8px; overflow: hidden; border: 2.5px solid transparent; cursor: pointer; position: relative; flex-shrink: 0; }
.media-thumb-item.active { border-color: var(--secondary); }
.media-thumb-item img { width: 100%; height: 100%; object-fit: cover; }
.thumb-video-icon { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0,0,0,0.6); color: white; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; }

.description-box { background: var(--white); padding: 40px; border-radius: 15px; box-shadow: var(--shadow); margin-bottom: 30px; }
.price-big { font-size: 2.5rem; font-weight: 700; color: var(--secondary); margin: 10px 0 20px; }
.meta-info { display: flex; gap: 20px; color: var(--gray); font-size: 0.95rem; margin-bottom: 20px; flex-wrap: wrap; }
.text-content { font-size: 1.1rem; line-height: 1.8; color: #475569; }

/* Map */
.map-box { background: var(--white); border-radius: 15px; box-shadow: var(--shadow); overflow: hidden; }
.map-box-header { display: flex; justify-content: space-between; align-items: flex-start; padding: 25px 25px 0; gap: 15px; flex-wrap: wrap; }
.map-box-header h3 { font-size: 1.1rem; font-weight: 700; color: var(--primary); margin-bottom: 5px; }
.map-address-text { color: var(--gray); font-size: 0.9rem; }
.map-nav-btns { display: flex; gap: 8px; flex-wrap: wrap; }
.btn-nav { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 8px; font-size: 0.82rem; font-weight: 700; text-decoration: none; transition: var(--transition); white-space: nowrap; }
.btn-gmaps { background: #e8f0fe; color: #1967d2; }
.btn-gmaps:hover { background: #4285f4; color: white; }
.btn-waze { background: #e0f7fa; color: #0288d1; }
.btn-waze:hover { background: #33ccff; color: #0f172a; }
.map-layer-toggle { display: flex; gap: 6px; padding: 15px 25px 10px; }
.layer-btn { border: 1.5px solid var(--border); background: white; border-radius: 8px; padding: 7px 14px; font-size: 0.82rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: var(--transition); color: var(--gray); font-family: inherit; }
.layer-btn.active, .layer-btn:hover { background: var(--primary); border-color: var(--primary); color: white; }
.map-coords-bar { display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; background: #f8fafc; border-top: 1px solid var(--border); font-size: 0.75rem; color: var(--gray); }
.no-map-placeholder { padding: 60px 20px; text-align: center; color: var(--gray); }
.no-map-placeholder i { font-size: 3rem; color: var(--border); margin-bottom: 15px; display: block; }
.leaflet-popup-content-wrapper { border-radius: 12px !important; padding: 0 !important; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.2) !important; }
.leaflet-popup-content { margin: 0 !important; }

/* Contact sidebar cards */
.contact-card { background: var(--white); padding: 30px; border-radius: 15px; box-shadow: var(--shadow); text-align: center; border: 1.5px solid #e2e8f0; }
.btn-whatsapp { display: block; background: #25d366; color: var(--white); padding: 15px; border-radius: 10px; font-weight: 600; margin-top: 15px; text-decoration: none; }
.btn-submit-contact { width: 100%; background: var(--primary); color: var(--white); border: none; padding: 15px; border-radius: 10px; font-weight: 600; cursor: pointer; }
.form-group { margin-bottom: 15px; }
.form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit; }

@media (max-width: 768px) {
    .detail-grid { grid-template-columns: 1fr; }
    .media-item { height: 320px; }
}

/* Lightbox Modal */
.lightbox-modal { display: none; position: fixed; z-index: 9999; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15,23,42,0.95); align-items: center; justify-content: center; }
.lightbox-close { position: absolute; top: 20px; right: 30px; color: white; font-size: 2.5rem; cursor: pointer; transition: color 0.2s; z-index: 10001; }
.lightbox-close:hover { color: var(--secondary); }
.lightbox-nav { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.1); border: none; color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1.5rem; z-index: 10000; transition: background 0.2s; }
.lightbox-nav:hover { background: var(--secondary); color: var(--primary); }
.lightbox-prev { left: 30px; }
.lightbox-next { right: 30px; }
.lightbox-content { max-width: 80%; max-height: 80%; display: flex; align-items: center; justify-content: center; }
.lightbox-media-el { max-width: 100%; max-height: 80vh; object-fit: contain; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }

/* Comments list buttons */
.comment-id-badge { font-size: 0.72rem; color: var(--gray); background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-weight: 700; }
.btn-reply-toggle { background: none; border: none; color: var(--secondary); font-weight: 700; font-size: 0.8rem; cursor: pointer; margin-top: 8px; display: inline-flex; align-items: center; gap: 4px; padding: 0 24px; transition: color 0.2s; }
.btn-reply-toggle:hover { color: var(--primary); }
.btn-delete-comment { background: #fef2f2; border: none; color: var(--danger); cursor: pointer; padding: 8px; border-radius: 8px; transition: var(--transition); }
.btn-delete-comment:hover { background: var(--danger); color: white; }
.btn-submit-comment { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; }
.btn-submit-comment:hover { background: var(--secondary); color: var(--primary); }

/* Floating chat widget client-admin */
.client-chat-widget { position: fixed; bottom: 30px; right: 30px; z-index: 999; font-family: inherit; }
.chat-widget-trigger { background: var(--primary); color: white; border: none; padding: 14px 22px; border-radius: 30px; font-weight: 700; font-size: 0.92rem; display: flex; align-items: center; gap: 10px; cursor: pointer; box-shadow: 0 8px 24px rgba(15,23,42,0.25); transition: all 0.25s ease; position: relative; }
.chat-widget-trigger:hover { background: var(--secondary); color: var(--primary); transform: translateY(-2px); }
.unread-dot-chat { width: 10px; height: 10px; background: #ef4444; border: 2px solid var(--primary); border-radius: 50%; position: absolute; top: 0; right: 4px; }
.chat-widget-panel { display: none; position: absolute; bottom: 65px; right: 0; width: 340px; height: 440px; background: white; border-radius: 16px; border: 1.5px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.15); flex-direction: column; overflow: hidden; }
.chat-widget-panel.open { display: flex; }

.chat-panel-header { background: var(--primary); color: white; padding: 14px 20px; display: flex; align-items: center; gap: 12px; }
.agent-avatar-mini img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
.agent-avatar-mini .fallback-av { width: 36px; height: 36px; border-radius: 50%; background: var(--secondary); color: var(--primary); font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 1rem; }
.agent-meta-mini h4 { margin: 0; font-size: 0.88rem; font-weight: 700; color: white; }
.agent-meta-mini span { font-size: 0.68rem; opacity: 0.8; }
.btn-close-chat { background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; margin-left: auto; }

.chat-panel-messages { flex: 1; overflow-y: auto; padding: 16px; background: #f8fafc; display: flex; flex-direction: column; gap: 12px; }
.chat-empty-state { text-align: center; color: var(--gray); margin: auto; padding: 20px; font-size: 0.8rem; }
.chat-panel-bubble { display: flex; }
.chat-panel-bubble.mine { justify-content: flex-end; }
.chat-panel-bubble.theirs { justify-content: flex-start; }
.chat-panel-bubble .bubble-inner { max-width: 80%; padding: 10px 14px; border-radius: 12px; }
.chat-panel-bubble.mine .bubble-inner { background: var(--primary); color: white; border-bottom-right-radius: 2px; }
.chat-panel-bubble.mine .bubble-inner p { color: white; margin: 0 0 2px; font-size: 0.82rem; }
.chat-panel-bubble.theirs .bubble-inner { background: white; border: 1px solid #e2e8f0; color: var(--primary); border-bottom-left-radius: 2px; }
.chat-panel-bubble.theirs .bubble-inner p { color: var(--primary); margin: 0 0 2px; font-size: 0.82rem; }
.bubble-time { font-size: 0.6rem; color: var(--gray); display: block; text-align: right; }

.chat-panel-form { padding: 12px; border-top: 1.5px solid #e2e8f0; background: white; }
.chat-panel-identity { display: flex; gap: 6px; margin-bottom: 6px; }

/* Agent Card Premium details */
.agent-card { background: var(--white); border-radius: 15px; box-shadow: var(--shadow); margin-top: 16px; overflow: hidden; border: 1.5px solid #e2e8f0; }
.agent-card-header { display: flex; align-items: center; gap: 14px; padding: 20px 20px 16px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
.agent-avatar { width: 54px; height: 54px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800; color: white; border: 3px solid rgba(255,255,255,0.15); flex-shrink: 0; overflow: hidden; }
.agent-avatar img { width: 100%; height: 100%; object-fit: cover; }
.agent-info { flex: 1; }
.agent-badge { display: inline-block; background: rgba(245,158,11,0.2); color: #f59e0b; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; padding: 3px 10px; border-radius: 20px; margin-bottom: 5px; }
.agent-info h4 { color: white; font-size: 1rem; font-weight: 700; margin: 0 0 3px; }
.agent-info p { color: rgba(255,255,255,0.45); font-size: 0.78rem; margin: 0; }
.agent-contacts { display: flex; flex-direction: column; gap: 8px; padding: 16px; }
.agent-btn { display: flex; align-items: center; gap: 14px; padding: 13px 16px; border-radius: 10px; text-decoration: none; font-family: inherit; transition: all 0.2s; cursor: pointer; }
.agent-btn i { font-size: 1.3rem; flex-shrink: 0; width: 24px; text-align: center; }
.agent-btn div { display: flex; flex-direction: column; }
.btn-label { font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.75; }
.btn-value { font-size: 0.95rem; font-weight: 700; }
.agent-btn-wa { background: #f0fdf4; color: #15803d; border: 1.5px solid #bbf7d0; }
.agent-btn-wa:hover { background: #25d366; color: white; border-color: #25d366; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(37,211,102,0.3); }
.agent-btn-tel { background: #eff6ff; color: #1d4ed8; border: 1.5px solid #bfdbfe; }
.agent-btn-tel:hover { background: #1d4ed8; color: white; border-color: #1d4ed8; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(29,78,216,0.3); }
</style>

<?php include __DIR__ . '/../layout_footer.php'; ?>
