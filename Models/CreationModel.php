<?php

namespace App\Models;

use Exception;
use PDO;
use App\Core\DbConnect;
use App\Entities\Creation;

class CreationModel extends DbConnect
{
    // Méthode pour récupérer toutes les créations
    public function findAll()
    {
        // Construction de la requête SELECT
        $this->request = "SELECT * FROM creation";
        // Exécution de la requête
        $result = $this->connection->query($this->request);
        // Récupération des résultats dans un tableau associatif
        $list = $result->fetchAll();
        // Retourne le tableau de résultats
        return $list;
    }

    // Méthode pour récupérer une création par son identifiant
    public function find(int $id)
    {
        // Préparation de la requête SELECT avec une clause WHERE
        $this->request = $this->connection->prepare("SELECT * FROM creation WHERE id_creation = :id_creation");
        // Liaison du paramètre
        $this->request->bindParam(":id_creation", $id, PDO::PARAM_INT);
        // Exécution de la requête
        $this->request->execute();
        // Récupération de la création sous forme de tableau associatif
        $creation = $this->request->fetch();
        // Retourne la création
        return $creation;
    }

    // Méthode pour créer une nouvelle création
    public function create(Creation $creation)
    {
        // Préparation de la requête INSERT INTO
        $this->request = $this->connection->prepare("INSERT INTO creation VALUES (NULL, :title, :description, :created_at, :picture)");
        // Liaison des paramètres avec les valeurs de l'objet Creation
        $this->request->bindValue(":title", $creation->getTitle());
        $this->request->bindValue(":description", $creation->getDescription());
        $this->request->bindValue(":created_at", $creation->getCreated_at());
        $this->request->bindValue(":picture", $creation->getPicture());
        // Exécution de la requête avec gestion des erreurs
        $this->executeTryCatch();
    }

    // Méthode pour mettre à jour une création existante
    public function update(int $id, Creation $creation)
    {
        // Préparation de la requête UPDATE avec une clause WHERE
        $this->request = $this->connection->prepare("UPDATE creation SET title = :title, description = :description, created_at = :created_at, picture = :picture WHERE id_creation = :id_creation");
        // Liaison des paramètres avec les valeurs de l'objet Creation et l'identifiant
        $this->request->bindValue(":id_creation", $id, PDO::PARAM_INT);
        $this->request->bindValue(":title", $creation->getTitle());
        $this->request->bindValue(":description", $creation->getDescription());
        $this->request->bindValue(":created_at", $creation->getCreated_at());
        $this->request->bindValue(":picture", $creation->getPicture());
        // Exécution de la requête avec gestion des erreurs
        $this->executeTryCatch();
    }

    // Méthode pour supprimer une création
    public function delete(int $id)
    {
        // Préparation de la requête DELETE avec une clause WHERE
        $this->request = $this->connection->prepare("DELETE FROM creation WHERE id_creation = :id_creation");
        // Liaison du paramètre
        $this->request->bindParam(":id_creation", $id, PDO::PARAM_INT);
        // Exécution de la requête avec gestion des erreurs
        $this->executeTryCatch();
    }

    // Méthode privée pour exécuter une requête avec gestion des erreurs
    private function executeTryCatch()
    {
        try {
            $this->request->execute();
        } catch (Exception $e) {
            // En cas d'erreur, affiche le message d'erreur et termine le script
            die('Erreur : ' . $e->getMessage());
        }
        // Ferme le curseur, permettant à la requête d'être de nouveau exécutée
        $this->request->closeCursor();
    }
}
