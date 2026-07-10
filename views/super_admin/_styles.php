<?php include __DIR__ . '/../layout_header.php'; ?>
<?php include __DIR__ . '/dashboard.php'; // Styles réutilisés ?>

<script>
// Les styles sont déjà inclus via dashboard.php — on inclut juste ce fichier pour overrider le body
</script>

<?php
// Ce fichier est une vue autonome : on l'inclut directement sans layout_header répété.
// En réalité, on va le gérer proprement :
exit; // Empêcher la double exécution
?>
