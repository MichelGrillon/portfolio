<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Core\Connect;
use App\Models\UserModel;

class UserController extends Controller
{
    protected $userModel;

    public function __construct($connection)
    {
        // Instancier le modèle utilisateur avec la connexion à la base de données
        $this->userModel = new UserModel($connection);
    }

    public function showRegistrationForm()
    {
        // Génération du jeton CSRF
        $csrfToken = bin2hex(openssl_random_pseudo_bytes(32));
        // Enregistrement du jeton CSRF dans la session
        $_SESSION['csrf_token'] = $csrfToken;

        // Affichage du formulaire d'inscription avec le jeton CSRF
        $this->render('register', ['csrf_token' => $csrfToken]);
    }

    public function register()
    {
        // Vérification du jeton CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            // Jeton CSRF invalide, redirection ou affichage d'un message d'erreur
            echo "Erreur CSRF : Jeton CSRF invalide.";
            return;
        }

        // Traitement de l'inscription
        $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : null;
        $password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : null;
        $username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : null;

        try {
            // Utilisation de requêtes préparées pour éviter les injections SQL
            $this->userModel->createUser($email, $password, $username);

            // Redirection après l'inscription
            header("Location: /index.php?login=auth");
            exit;
        } catch (\Exception $e) {
            // Gestion de l'erreur
            echo "Erreur: " . $e->getMessage();
        }
    }

    public function showLoginForm()
    {
        // Génération du jeton CSRF
        $csrfToken = bin2hex(openssl_random_pseudo_bytes(32));
        // Enregistrement du jeton CSRF dans la session
        $_SESSION['csrf_token'] = $csrfToken;

        // Affichage du formulaire de connexion avec le jeton CSRF
        $this->render('login', ['csrf_token' => $csrfToken]);
    }

    public function login()
    {
        // Utilisation d'un chemin absolu complet pour inclure auth.php
        include_once '/../Core/auth.php';
    }

    public function logout()
    {
        // Utilisation d'un chemin absolu complet pour inclure logout.php
        include_once '/../Core/logout.php';
    }
}
