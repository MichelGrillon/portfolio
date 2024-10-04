<?php

namespace App\Entities;

class Creation
{
    // Propriétés privées représentant les champs de la table "creation"
    private $id_creation;
    private $title;
    private $description;
    private $created_at;
    private $picture;

    // Getters et setters avec échappement des données pour le titre et la description

    /**
     * Getter pour récupérer la valeur du titre en échappant les caractères spéciaux
     */
    public function getTitle()
    {
        return htmlspecialchars($this->title);
    }

    /**
     * Setter pour définir la valeur du titre
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Getter pour récupérer la valeur de la description en échappant les caractères spéciaux
     */
    public function getDescription()
    {
        return htmlspecialchars($this->description);
    }

    /**
     * Setter pour définir la valeur de la description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    // Autres getters et setters inchangés

    /**
     * Getter pour récupérer la valeur de l'ID de création
     */
    public function getId_creation()
    {
        return $this->id_creation;
    }

    /**
     * Setter pour définir la valeur de l'ID de création
     * @return self
     */
    public function setId_creation($id_creation)
    {
        $this->id_creation = $id_creation;
        return $this;
    }

    /**
     * Getter pour récupérer la valeur de la date de création
     */
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * Setter pour définir la valeur de la date de création
     * @return self
     */
    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * Getter pour récupérer la valeur de l'image
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Setter pour définir la valeur de l'image
     * @return self
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
        return $this;
    }
}
