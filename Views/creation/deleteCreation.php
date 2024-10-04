<?php
// Vérifier si une session est déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    // Si aucune session n'est démarrée, démarrer une nouvelle session
    session_start();
}

// Vérifie si le jeton CSRF est défini dans la session, sinon le génère
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
}

// Récupère le titre pour l'affichage
$title = htmlspecialchars("Mon portfolio - Suppression d'une création", ENT_QUOTES, 'UTF-8');
?>

<div class="alert alert-warning" role="alert">
    <?php if (isset($creation) && !empty($creation)) : ?>
        <p>Voulez-vous supprimer la création : <strong><?php echo htmlspecialchars($creation->title, ENT_QUOTES, 'UTF-8'); ?></strong> ?</p>
        <!-- Formulaire de suppression avec champ caché pour le jeton CSRF -->
        <form action="#" method="POST">
            <!-- Champ caché pour l'identifiant de la création à supprimer -->
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($creation->id_creation, ENT_QUOTES, 'UTF-8'); ?>">
            <!-- Champ caché pour le jeton CSRF -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input class="btn btn-danger" type="submit" name="true" value="OUI">
            <input class="btn btn-primary" type="submit" name="false" value="NON">
        </form>
    <?php else : ?>
        <p>La création à supprimer n'a pas pu être trouvée.</p>
    <?php endif; ?>
</div>