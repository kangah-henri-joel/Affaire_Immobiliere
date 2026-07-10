<?php
// views/bureaux.php
include __DIR__ . '/layout_header.php';
?>
<section class="bureaux-hero-section">
    <div class="container hero-content">
        <h1>Nos Bureaux Officiels</h1>
        <p>Retrouvez et contactez nos représentations physiques partout en Côte d'Ivoire.</p>
    </div>
</section>

<section class="bureaux-list-section container">
    <?php if (empty($bureaux)): ?>
    <div class="no-bureaux-box">
        <i class="fas fa-building"></i>
        <h3>Aucun bureau officiel n'est enregistré pour le moment.</h3>
        <p>Revenez plus tard ou contactez l'agence principale.</p>
        <a href="<?php echo BASE_URL; ?>/contact" class="btn">Contacter l'Agence</a>
    </div>
    <?php else: ?>
    <div class="bureaux-grid">
        <?php foreach ($bureaux as $b): ?>
        <div class="bureau-premium-card">
            <div class="bureau-card-header">
                <?php if (!empty($b['logo'])): ?>
                <img src="<?php echo BASE_URL . $b['logo']; ?>" alt="Logo" class="b-logo">
                <?php else: ?>
                <div class="b-avatar-fallback"><?php echo strtoupper(substr($b['nom'] ?? 'B', 0, 1)); ?></div>
                <?php endif; ?>
                <div class="b-header-meta">
                    <h3><?php echo htmlspecialchars($b['nom'] ?? 'Bureau'); ?></h3>
                    <span class="b-admin-tag"><i class="fas fa-user-shield"></i> Resp: <?php echo htmlspecialchars($b['admin_name']); ?></span>
                </div>
            </div>

            <div class="bureau-card-body">
                <?php if (!empty($b['description'])): ?>
                <p class="b-desc"><?php echo htmlspecialchars($b['description']); ?></p>
                <?php endif; ?>

                <?php if (!empty($b['adresse'])): ?>
                <p class="b-addr"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($b['adresse']); ?></p>
                <?php endif; ?>
            </div>

            <div class="bureau-card-footer">
                <!-- Actions -->
                <div class="b-actions-row">
                    <?php if (!empty($b['phone_whatsapp'])): ?>
                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $b['phone_whatsapp']); ?>" target="_blank" class="b-btn b-btn-wa">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($b['phone_tel'])): ?>
                    <a href="tel:<?php echo htmlspecialchars($b['phone_tel']); ?>" class="b-btn b-btn-tel">
                        <i class="fas fa-phone"></i> Appeler
                    </a>
                    <?php endif; ?>
                </div>
                <a href="<?php echo BASE_URL; ?>/bureau/contact?id=<?php echo $b['id']; ?>" class="b-btn-primary-full">
                    <i class="fas fa-envelope"></i> Envoyer un Message
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<style>
.bureaux-hero-section { background: linear-gradient(135deg, #0f172a, #1e293b); color: white; padding: 60px 0; text-align: center; }
.bureaux-hero-section h1 { font-size: 2.2rem; font-weight: 800; margin-bottom: 8px; }
.bureaux-hero-section p { font-size: 1rem; opacity: 0.8; max-width: 600px; margin: 0 auto; }

.bureaux-list-section { padding: 50px 0; }
.no-bureaux-box { text-align: center; padding: 60px 20px; background: white; border-radius: 16px; box-shadow: var(--shadow); max-width: 600px; margin: 0 auto; }
.no-bureaux-box i { font-size: 3.5rem; color: #cbd5e1; margin-bottom: 20px; display: block; }
.no-bureaux-box h3 { font-size: 1.25rem; font-weight: 700; color: var(--primary); margin-bottom: 8px; }
.no-bureaux-box p { color: var(--gray); font-size: 0.9rem; margin-bottom: 20px; }

.bureaux-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; }
.bureau-premium-card { background: white; border-radius: 16px; box-shadow: var(--shadow); border: 1.5px solid #f1f5f9; display: flex; flex-direction: column; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; }
.bureau-premium-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.08); }

.bureau-card-header { padding: 24px; border-bottom: 1.5px solid #f1f5f9; display: flex; align-items: center; gap: 16px; }
.b-logo { width: 56px; height: 56px; object-fit: contain; border-radius: 8px; border: 1.5px solid #e2e8f0; }
.b-avatar-fallback { width: 56px; height: 56px; border-radius: 8px; background: linear-gradient(135deg, #3b82f6, #6366f1); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 800; }
.b-header-meta h3 { font-size: 1.05rem; font-weight: 700; color: var(--primary); margin: 0 0 4px; }
.b-admin-tag { background: #f1f5f9; color: var(--primary); font-size: 0.68rem; font-weight: 700; padding: 3px 8px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px; }

.bureau-card-body { padding: 24px; flex: 1; }
.b-desc { font-size: 0.88rem; color: #475569; line-height: 1.5; margin-bottom: 14px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
.b-addr { font-size: 0.82rem; color: var(--gray); font-weight: 600; display: flex; align-items: center; gap: 6px; }

.bureau-card-footer { padding: 24px; background: #f8fafc; border-top: 1.5px solid #f1f5f9; display: flex; flex-direction: column; gap: 12px; }
.b-actions-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.b-btn { display: flex; align-items: center; justify-content: center; gap: 6px; padding: 10px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; text-decoration: none; transition: all 0.2s; }
.b-btn-wa { background: #e0f2fe; color: #0369a1; }
.b-btn-wa:hover { background: #25d366; color: white; }
.b-btn-tel { background: #f1f5f9; color: var(--primary); }
.b-btn-tel:hover { background: var(--primary); color: white; }
.b-btn-primary-full { width: 100%; background: var(--primary); color: white; text-align: center; padding: 12px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.2s; }
.b-btn-primary-full:hover { background: var(--secondary); color: var(--primary); }
</style>
<?php include __DIR__ . '/layout_footer.php'; ?>
