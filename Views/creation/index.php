<?php
// Inclusion du fichier Autoloader.php au début
require_once '../Autoloader.php';

// Appel de la méthode register de la classe Autoloader pour l'enregistrement de l'autoloader
App\Autoloader::register();

// Vérifier si la session n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérification de l'existence du jeton CSRF dans la session
if (!isset($_SESSION['csrf_token'])) {
    // Jeton CSRF non trouvé, générer un nouveau jeton
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
}

// Échapper le titre avant de l'utiliser
$title = htmlspecialchars("Mon portfolio - Liste de mes créations", ENT_QUOTES, 'UTF-8');
?>
<h2><?php echo $title; ?></h2>
<a href="index.php?controller=creation&action=add"><button type="button" class="btn btn-primary">Ajouter une création</button></a>
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">Created_at</th>
            <th scope="col">Picture</th>
        </tr>
    </thead>
    <tbody>
        <?php
        //On boucle dans le tableau $list qui contient la liste des créations
        foreach ($list as $value) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($value->id_creation, ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($value->title, ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($value->description, ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($value->created_at, ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td><img src='" . htmlspecialchars($value->picture, ENT_QUOTES, 'UTF-8') . "' class='picture'></td>";
            // Ajout du champ caché pour le jeton CSRF dans chaque lien de suppression
            echo "<td><a href='index.php?controller=creation&action=showCreation&id=" . htmlspecialchars($value->id_creation, ENT_QUOTES, 'UTF-8') . "'><i class='fas fa-eye'></i></a></td>";
            echo "<td><a href='index.php?controller=creation&action=updateCreation&id=" . htmlspecialchars($value->id_creation, ENT_QUOTES, 'UTF-8') . "'><i class='fas fa-pen'></i></a></td>";
            echo "<td><form action='index.php?controller=creation&action=deleteCreation' method='POST'>";
            echo "<input type='hidden' name='csrf_token' value='" . $_SESSION['csrf_token'] . "'>";
            echo "<input type='hidden' name='id' value='" . htmlspecialchars($value->id_creation, ENT_QUOTES, 'UTF-8') . "'>";
            echo "<button type='submit' class='btn btn-link'><i class='fas fa-trash-alt'></i></button>";
            echo "</form></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>