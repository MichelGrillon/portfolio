<?php

namespace App\Core;

class Form
{
    // Attribut contenant le code du formulaire
    private $formElements;

    // Le getter pour lire le contenu de l'attribut $formElements
    public function getFormElements()
    {
        return $this->formElements;
    }

    // Méthode permettant d'ajouter un ou des attributs
    private function addAttributes(array $attributes): string
    {
        $att = "";
        // chaque attribut est parcouru
        foreach ($attributes as $attribute => $value) {
            // on stocke chaque attribut et sa valeur dans la variable $att. exemple: id = "title"
            $att .= " $attribute=\"" . htmlspecialchars($value) . "\"";
        }
        return $att;
    }

    // Méthode permettant de démarrer le formulaire
    public function startForm(string $action = '#', string $method = 'POST', array $attributes = []): self
    {
        // Début du formulaire avec les attributs d'action et de méthode
        $this->formElements = "<form action='" . htmlspecialchars($action) . "' method='" . htmlspecialchars($method) . "'";

        // Ajout des autres attributs passés en paramètre
        $this->formElements .= isset($attributes) ? $this->addAttributes($attributes) . ">" : ">";

        return $this;
    }

    // Méthode permettant d'ajouter un label
    public function addLabel(string $for, string $text, array $attributes = []): self
    {
        // on ajoute la balise label et l'attribut 'for", en échappant les valeurs des attributs
        $this->formElements .= "<label for='" . htmlspecialchars($for) . "'";
        $this->formElements .= isset($attributes) ? $this->addAttributes($attributes) . ">" : ">";
        $this->formElements .= htmlspecialchars($text) . "</label>";
        return $this;
    }

    // Méthode permettant d'ajouter un champ
    public function addInput(string $type, string $name, array $attributes = []): self
    {
        // on ajoute la balise input et les attributs 'type", "name", en échappant les valeurs des attributs
        $this->formElements .= "<input type='" . htmlspecialchars($type) . "' name='" . htmlspecialchars($name) . "'";
        $this->formElements .= isset($attributes) ? $this->addAttributes($attributes) . ">" : ">";
        return $this;
    }

    // Méthode permettant d'ajouter un champ textarea
    public function addTextarea(string $name, string $text = '', array $attributes = []): self
    {
        // on ajoute la balise textarea et l'attribut "name", en échappant les valeurs des attributs
        $this->formElements .= "<textarea name='" . htmlspecialchars($name) . "'";
        $this->formElements .= isset($attributes) ? $this->addAttributes($attributes) . ">" : ">";
        $this->formElements .= htmlspecialchars($text) . "</textarea>";
        return $this;
    }

    // Méthode permettant d'ajouter un champ select
    public function addSelect(string $name, array $options, array $attributes = []): self
    {
        // on ajoute la balise select et l'attribut "name", en échappant les valeurs des attributs
        $this->formElements .= "<select name='" . htmlspecialchars($name) . "'";
        $this->formElements .= isset($attributes) ? $this->addAttributes($attributes) . ">" : ">";
        // on ajoute la ou les balises options avec sa valeur et son texte, en échappant les valeurs
        foreach ($options as $key => $value) {
            $this->formElements .= "<option value='" . htmlspecialchars($key) . "'>" . htmlspecialchars($value) . "</option>";
        }
        $this->formElements .= "</select>";
        return $this;
    }

    // Méthode permettant de fermer le formulaire
    public function endForm(): self
    {
        $this->formElements .= "</form>";
        return $this;
    }

    // Méthode permettant de tester les champs. Les paramètres représentent les valeurs en POST et le nom des champs
    public static function validatePost(array $post, array $fields): bool
    {
        // chaque champ est parcouru
        foreach ($fields as $field) {
            // on teste si les champs sont vides ou non présents
            if (empty($post[$field]) || !isset($post[$field])) {
                return false;
            }
        }
        return true;
    }

    // Méthode permettant de tester les champs. Les paramètres représentent les valeurs en FILES et le nom des champs
    public static function validateFiles(array $files, array $fields): bool
    {
        // chaque champ est parcouru
        foreach ($fields as $field) {
            // on teste si les champs sont déclarés et sans erreurs
            if (isset($files[$field]) && $files[$field]['error'] == 0) {
                return true;
            }
        }
        return false;
    }
}
