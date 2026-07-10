<?php
// views/admin/sidebar.php
$currentUrl = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
function admin_active($path) {
    global $currentUrl;
    $cleanCurrent = rtrim($currentUrl, '/');
    $cleanPath    = rtrim(BASE_URL . $path, '/');
    return ($cleanCurrent === $cleanPath) ? 'active' : '';
}

$_trashModel  = new AnnonceModel();
$_role        = $_SESSION['user_role'] ?? 'agent';
$_trashUserId = ($_role === 'super_admin') ? null : (int)$_SESSION['user_id'];
$_trashCount  = $_trashModel->countTrashed($_trashUserId);

// Compter les messages non lus
try {
    $_clientMsgModel   = new ClientMessageModel();
    $_internalMsgModel = new InternalMessageModel();
    $_unreadClient     = $_clientMsgModel->countAdminUnread($_SESSION['user_id']);
    $_unreadInternal   = $_internalMsgModel->countUnread($_SESSION['user_id']);
} catch (\Exception $e) {
    $_unreadClient   = 0;
    $_unreadInternal = 0;
}
?>
<aside class="admin-sidebar">
    <ul>
        <li><a href="<?php echo BASE_URL; ?>/admin" class="<?php echo admin_active('/admin'); ?>"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="<?php echo BASE_URL; ?>/admin/annonces" class="<?php echo admin_active('/admin/annonces'); ?>"><i class="fas fa-home"></i> Annonces</a></li>
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/annonces/corbeille" class="<?php echo admin_active('/admin/annonces/corbeille'); ?>" style="position:relative;">
                <i class="fas fa-trash-alt"></i> Corbeille
                <?php if ($_trashCount > 0): ?>
                <span class="sidebar-badge"><?php echo $_trashCount; ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li><a href="<?php echo BASE_URL; ?>/admin/publications" class="<?php echo admin_active('/admin/publications'); ?>"><i class="fas fa-bullhorn"></i> Publications</a></li>
        <li><a href="<?php echo BASE_URL; ?>/admin/leads" class="<?php echo admin_active('/admin/leads'); ?>"><i class="fas fa-envelope"></i> Leads</a></li>

        <li class="sidebar-section-label">Communication</li>
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/messages" class="<?php echo admin_active('/admin/messages'); ?>" style="position:relative;">
                <i class="fas fa-comments"></i> Super Admin
                <?php if ($_unreadInternal > 0): ?>
                <span class="sidebar-badge"><?php echo $_unreadInternal; ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/client-messages" class="<?php echo admin_active('/admin/client-messages'); ?>" style="position:relative;">
                <i class="fas fa-comment-dots"></i> Clients
                <?php if ($_unreadClient > 0): ?>
                <span class="sidebar-badge"><?php echo $_unreadClient; ?></span>
                <?php endif; ?>
            </a>
        </li>

        <li class="sidebar-section-label">Mon Espace</li>
        <li><a href="<?php echo BASE_URL; ?>/admin/bureau" class="<?php echo admin_active('/admin/bureau'); ?>"><i class="fas fa-building"></i> Mon Bureau</a></li>
        <li><a href="<?php echo BASE_URL; ?>/admin/consultants" class="<?php echo admin_active('/admin/consultants'); ?>"><i class="fas fa-users"></i> Consultants</a></li>
        <li><a href="<?php echo BASE_URL; ?>/admin/visitors" class="<?php echo admin_active('/admin/visitors'); ?>"><i class="fas fa-eye"></i> Visiteurs</a></li>
        <li><a href="<?php echo BASE_URL; ?>/admin/settings" class="<?php echo admin_active('/admin/settings'); ?>"><i class="fas fa-cogs"></i> Entreprise</a></li>
        <li><a href="<?php echo BASE_URL; ?>/admin/profil" class="<?php echo admin_active('/admin/profil'); ?>"><i class="fas fa-user-circle"></i> Profil</a></li>
        <li><a href="<?php echo BASE_URL; ?>/admin/journal" class="<?php echo admin_active('/admin/journal'); ?>"><i class="fas fa-history"></i> Mon Journal</a></li>
        <li><a href="<?php echo BASE_URL; ?>/logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
    </ul>
</aside>

<style>
.sidebar-section-label {
    font-size: 0.65rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: rgba(255,255,255,0.3);
    padding: 16px 20px 6px;
    pointer-events: none;
}
.sidebar-badge {
    position: absolute;
    top: 50%;
    right: 14px;
    transform: translateY(-50%);
    background: #ef4444;
    color: white;
    font-size: 0.65rem;
    font-weight: 800;
    min-width: 18px;
    height: 18px;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 5px;
    line-height: 1;
    animation: pulse-badge 1.8s infinite;
}
/* Garder pour compat */
.sidebar-trash-badge { position: absolute; top: 50%; right: 14px; transform: translateY(-50%); background: #ef4444; color: white; font-size: 0.65rem; font-weight: 800; min-width: 18px; height: 18px; border-radius: 50px; display: inline-flex; align-items: center; justify-content: center; padding: 0 5px; }
@keyframes pulse-badge {
    0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.5); }
    50%       { box-shadow: 0 0 0 5px rgba(239,68,68,0); }
}
</style>
