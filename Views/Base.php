<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Affichage dynamique de la variable $title -->
    <title><?= isset($title) ? htmlspecialchars($title, ENT_QUOTES, 'UTF-8') : "Mon Portfolio" ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/cff33ecd93.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <header class="text-center">
            <h1>Mon Portfolio</h1>
        </header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Mon Portfolio</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggle-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=creation&action=index">Mes créations</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            <!-- Affichage dynamique de la variable $content -->
            <?= $content ?>
            <!-- Ajouter un lien de déconnexion -->
            <?php if (isset($_SESSION['username']) && !empty($_SESSION['username'])) : ?>
                <a href="../Core/logout.php" class="btn btn-danger">Se déconnecter</a>
            <?php endif; ?>
        </main>
        <footer class="text-center">
            <p>Mon portfolio &copy; Copyright : Production interne - &reg; Tous droits réservés - Michel Grillon - 2024</p>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>