<?php
// views/super_admin/sidebar.php
$currentUrl = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
function sa_active($path) {
    global $currentUrl;
    return (strpos($currentUrl, $path) !== false) ? 'active' : '';
}
$initials = strtoupper(substr($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'S', 0, 1));

// Compter les messages non lus
try {
    $_internalMsgModel = new InternalMessageModel();
    $_unreadCount = $_internalMsgModel->countUnread($_SESSION['user_id']);
} catch (\Exception $e) {
    $_unreadCount = 0;
}
?>
<!-- Overlay mobile -->
<div class="sa-overlay" id="saOverlay" onclick="closeSidebar()"></div>

<aside class="sa-sidebar" id="saSidebar">
    <div class="sa-sidebar-brand">
        <div class="sa-brand-icon"><i class="fas fa-crown"></i></div>
        <div class="sa-brand-text">
            <h3>ImmoAffaire</h3>
            <span>⚡ Super Admin</span>
        </div>
    </div>

    <nav class="sa-nav">
        <div class="sa-nav-section">Général</div>
        <a href="<?php echo BASE_URL; ?>/super-admin" class="<?php echo (rtrim($currentUrl,'/') === rtrim(BASE_URL.'/super-admin','/')) ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="<?php echo BASE_URL; ?>/super-admin/logs" class="<?php echo sa_active('/super-admin/logs'); ?>">
            <i class="fas fa-history"></i> Journal d'activité
        </a>
        <a href="<?php echo BASE_URL; ?>/super-admin/visitors" class="<?php echo sa_active('/super-admin/visitors'); ?>">
            <i class="fas fa-eye"></i> Visiteurs du site
        </a>

        <div class="sa-nav-divider"></div>
        <div class="sa-nav-section">Gestion des accès</div>
        <a href="<?php echo BASE_URL; ?>/super-admin/admins" class="<?php echo sa_active('/super-admin/admins'); ?>">
            <i class="fas fa-user-tie"></i> Administrateurs
        </a>
        <a href="<?php echo BASE_URL; ?>/super-admin/users" class="<?php echo sa_active('/super-admin/users'); ?>">
            <i class="fas fa-users-cog"></i> Tous les utilisateurs
        </a>
        <a href="<?php echo BASE_URL; ?>/super-admin/leads" class="<?php echo sa_active('/super-admin/leads'); ?>">
            <i class="fas fa-address-book"></i> Clients / Leads
        </a>

        <div class="sa-nav-divider"></div>
        <div class="sa-nav-section">Communication</div>
        <a href="<?php echo BASE_URL; ?>/super-admin/messages" class="<?php echo sa_active('/super-admin/messages'); ?>" style="position:relative;">
            <i class="fas fa-comments"></i> Messages des Admins
            <?php if ($_unreadCount > 0): ?>
            <span class="sidebar-badge-sa"><?php echo $_unreadCount; ?></span>
            <?php endif; ?>
        </a>

        <div class="sa-nav-divider"></div>
        <div class="sa-nav-section">Immobilier</div>
        <a href="<?php echo BASE_URL; ?>/super-admin/annonces" class="<?php echo sa_active('/super-admin/annonces'); ?>">
            <i class="fas fa-home"></i> Toutes les annonces
        </a>
        <a href="<?php echo BASE_URL; ?>/admin/publications">
            <i class="fas fa-bullhorn"></i> Publications
        </a>

        <div class="sa-nav-divider"></div>
        <div class="sa-nav-section">Configuration</div>
        <a href="<?php echo BASE_URL; ?>/super-admin/settings" class="<?php echo sa_active('/super-admin/settings'); ?>">
            <i class="fas fa-cogs"></i> Paramètres
        </a>
        <a href="<?php echo BASE_URL; ?>/admin" style="color:var(--sa-gray);">
            <i class="fas fa-arrow-circle-right"></i> Panneau Admin
        </a>

        <div class="sa-nav-divider"></div>
        <a href="<?php echo BASE_URL; ?>/logout" style="color:#f87171;">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>
    </nav>

    <div class="sa-sidebar-footer">
        <div class="sa-avatar-sm"><?php echo $initials; ?></div>
        <div class="info">
            <strong><?php echo htmlspecialchars(substr($_SESSION['full_name'] ?? $_SESSION['username'], 0, 20)); ?></strong>
            <span><i class="fas fa-crown" style="font-size:9px;"></i> Super Admin</span>
        </div>
    </div>
</aside>

<style>
.sidebar-badge-sa {
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
}
</style>

<script>
function openSidebar() {
    document.getElementById('saSidebar').classList.add('open');
    document.getElementById('saOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    document.getElementById('saSidebar').classList.remove('open');
    document.getElementById('saOverlay').classList.remove('open');
    document.body.style.overflow = '';
}
</script>
