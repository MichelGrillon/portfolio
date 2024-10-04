<?php
/*Ce fichier ne contient pas de logique d'authentification, il ne traite que des opérations CRUD 
(create, read, update, delete) sur les utilisateurs dans la base de données.
Voici comment on peut organiser nos méthodes dans la classe UserModel en fonction des fonctionnalités 
de gestion des utilisateurs (fichier Controllers/UserController.php) :
// Méthode pour enregistrer un nouvel utilisateur dans la base de données
    public function registerUser($email, $password, $username)
    {
        // Logique pour insérer un nouvel utilisateur dans la base de données
    }

    // Méthode pour récupérer les informations d'un utilisateur à partir de la base de données
    public function getUserByEmail($email)
    {
        // Logique pour récupérer les informations de l'utilisateur à partir de son email
    }

    // Méthode pour mettre à jour les informations d'un utilisateur dans la base de données
    public function updateUser($userId, $newData)
    {
        // Logique pour mettre à jour les informations de l'utilisateur dans la base de données
    }

    // Méthode pour supprimer un utilisateur de la base de données
    public function deleteUser($userId)
    {
        // Logique pour supprimer l'utilisateur de la base de données
    }

    // Méthode pour vérifier si un utilisateur existe dans la base de données
    public function userExists($email)
    {
        // Logique pour vérifier si un utilisateur existe dans la base de données
    }

    // Méthode pour gérer la connexion de l'utilisateur
    public function loginUser($email, $password)
    {
        // Logique pour vérifier les informations de connexion de l'utilisateur
    }

    // Méthode pour gérer la déconnexion de l'utilisateur
    public function logoutUser()
    {
        // Logique pour déconnecter l'utilisateur
    }
    Ces méthodes peuvent être appelées à partir du UserController pour effectuer diverses opérations de gestion 
    des utilisateurs,     telles que l'enregistrement, la connexion, la déconnexion, etc.
*/

namespace App\Models;

use PDO;

class UserModel
{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    // Méthode pour insérer un nouvel utilisateur dans la base de données
    public function createUser($email, $password, $username)
    {
        // Hashage du mot de passe
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Préparation de la requête SQL
        $query = "INSERT INTO users (email, password, username) VALUES (?, ?, ?)";
        $statement = $this->connection->prepare($query);

        // Exécution de la requête avec les paramètres fournis
        $statement->execute([$email, $hash, $username]);

        // Vérification du succès de l'opération
        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Logique pour récupérer les informations de l'utilisateur à partir de son email
    public function getUserByEmail($email)
    {
        // Préparation de la requête SQL
        $query = "SELECT * FROM users WHERE email = ?";
        $statement = $this->connection->prepare($query);

        // Exécution de la requête avec le paramètre fourni
        $statement->execute([$email]);

        // Récupération des données de l'utilisateur
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return $user;
    }

    // Autres méthodes pour la gestion des utilisateurs

    // Logique pour mettre à jour les informations de l'utilisateur dans la base de données
    public function updateUser($userId, $newEmail, $newPassword, $newUsername)
    {
        // Préparation de la requête SQL
        $query = "UPDATE users SET email = ?, password = ?, username = ? WHERE id_users = ?";
        $statement = $this->connection->prepare($query);

        // Exécution de la requête avec les paramètres fournis
        $statement->execute([$newEmail, $newPassword, $newUsername, $userId]);

        // Vérification du succès de l'opération
        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Logique pour supprimer l'utilisateur de la base de données
    public function deleteUser($userId)
    {
        // Préparation de la requête SQL
        $query = "DELETE FROM users WHERE id_users = ?";
        $statement = $this->connection->prepare($query);

        // Exécution de la requête avec le paramètre fourni
        $statement->execute([$userId]);

        // Vérification du succès de l'opération
        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Logique pour vérifier si un utilisateur existe dans la base de données
    public function userExists($email)
    {
        // Préparation de la requête SQL
        $query = "SELECT COUNT(*) FROM users WHERE email = ?";
        $statement = $this->connection->prepare($query);

        // Exécution de la requête avec le paramètre fourni
        $statement->execute([$email]);

        // Récupération du nombre d'utilisateurs avec cet email
        $count = $statement->fetchColumn();

        return $count > 0 ? true : false;
    }
}
