<?php
// Vérifier si la session n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérification de l'existence du jeton CSRF dans la session
if (!isset($_SESSION['csrf_token'])) {
    // Jeton CSRF non trouvé, générer un nouveau jeton
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
}

$title = htmlspecialchars("Mon portfolio - " . $creation->title, ENT_QUOTES, 'UTF-8');
?>
<article class="row justify-content-center text-center">
    <h1 class="col-12"><?php echo htmlspecialchars($creation->title, ENT_QUOTES, 'UTF-8'); ?></h1>
    <p>Date de publication: <?php echo htmlspecialchars(date("d/m/Y", strtotime($creation->created_at)), ENT_QUOTES, 'UTF-8'); ?></p>
    <img class="col-4" src="<?php echo htmlspecialchars($creation->picture, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($creation->title, ENT_QUOTES, 'UTF-8'); ?>">
    <p><?php echo htmlspecialchars($creation->description, ENT_QUOTES, 'UTF-8'); ?></p>
    <!-- Ajout d'un formulaire avec le jeton CSRF pour la protection CSRF -->
    <form action="#" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    </form>
</article>