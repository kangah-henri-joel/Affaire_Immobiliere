<?php include __DIR__ . '/../layout_header.php'; ?>

<div class="admin-container">
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <main class="admin-content">
        <header class="admin-header">
            <h1>Tableau de Bord <span class="highlight">Général</span></h1>
            <p style="color: var(--gray);">Bienvenue, <?php echo $_SESSION['username']; ?>. Voici vos performances actuelles.</p>
        </header>

        <div class="stats-grid">
            <a href="<?php echo BASE_URL; ?>/admin/annonces" class="stat-card">
                <i class="fas fa-home"></i>
                <div>
                    <h4>Total Annonces</h4>
                    <p><?php echo $stats['total_annonces']; ?></p>
                </div>
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/annonces" class="stat-card">
                <i class="fas fa-eye"></i>
                <div>
                    <h4>Vues Totales</h4>
                    <p><?php echo number_format($stats['total_views'], 0, ',', ' '); ?></p>
                </div>
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/leads" class="stat-card">
                <i class="fas fa-envelope"></i>
                <div>
                    <h4>Nouveaux Leads</h4>
                    <p><?php echo $stats['total_leads']; ?></p>
                </div>
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/publications" class="stat-card">
                <i class="fas fa-paper-plane"></i>
                <div>
                    <h4>Publications</h4>
                    <p><?php echo $stats['total_publications']; ?></p>
                </div>
            </a>
        </div>

        <div class="annonce-table-card" style="padding: 30px;">
            <h3 style="margin-bottom: 20px;">Dernières Activités</h3>
            <p style="color: var(--gray);">Vos annonces récentes et interactions clients s'affichent ici.</p>
            <!-- On pourrait ajouter ici un graphique ou les derniers leads -->
        </div>
    </main>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>
