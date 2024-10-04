<?php

namespace App\Controllers;

use App\Core\Form;
use App\Entities\Creation;
use App\Models\CreationModel;

// Vérification si la session est déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class CreationController extends Controller
{
    // Méthode pour afficher la liste des créations
    public function index()
    {
        // Exemple de vérification d'authentification
        if (isset($_SESSION['user_id'])) {
            session_regenerate_id(); // Régénérer l'identifiant de session
        }
        // On instancie la classe CreationModel
        $creations = new CreationModel();

        // On stocke dans une variable le return de la méthode findAll
        $list = $creations->findAll();
        $this->render('creation/index', ['list' => $list]);
    }

    public function add()
    {
        // Générer un token CSRF
        $csrfToken = bin2hex(random_bytes(32));

        // Stocker le token CSRF dans la session de l'utilisateur
        $_SESSION['csrf_token'] = $csrfToken;

        // Exemple de vérification d'authentification
        if (isset($_SESSION['user_id'])) {
            session_regenerate_id(); // Régénérer l'identifiant de session
        }

        // On contrôle si les champs du formulaire sont remplis
        if (Form::validatePost($_POST, ['title', 'description', 'date']) && Form::validateFiles($_FILES, ['picture'])) {

            // Vérification de l'extension et du type MIME du fichier uploadé
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxFileSize = 2 * 1024 * 1024; // 2Mo

            $filename = $_FILES['picture']['name'];
            $fileType = $_FILES['picture']['type'];
            $filesize = $_FILES['picture']['size'];

            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            // Vérification de l'extension
            if (!in_array($ext, $allowedExtensions)) {
                $erreur = "Extension de fichier non autorisée.";
            }
            // Vérification du type MIME
            elseif (!in_array($fileType, $allowedMimeTypes)) {
                $erreur = "Type de fichier non autorisé.";
            }
            // Vérification de la taille du fichier
            elseif ($filesize > $maxFileSize) {
                $erreur = "La taille du fichier est trop grande.";
            } else {
                // Génération d'un nom unique pour le fichier
                $uniqueName = uniqid('', true) . '.' . $ext;

                // Chemin de destination
                $destination = "images/" . $uniqueName;

                // Déplacement du fichier uploadé vers le dossier de destination
                move_uploaded_file($_FILES['picture']['tmp_name'], $destination);

                // On stocke le chemin de l'image
                $picture = $destination;

                // On instancie l'entité "Creation"
                $creation = new Creation();

                // On l'hydrate
                $creation->setTitle($_POST['title']);
                $creation->setDescription($_POST['description']);
                $creation->setCreated_at($_POST['date']);
                $creation->setPicture($picture);

                // On instancie le model "creation"
                $model = new CreationModel();
                $model->create($creation);

                // On redirige l'utilisateur vers la liste des créations
                header("Location:index.php?controller=creation&action=index");
                exit();
            }
        } else {
            // On affiche un message d'erreur
            $erreur = !empty($_POST) ? "Le formulaire n'a pas été correctement rempli" : "";
        }

        // On instancie la classe Form pour construire le formulaire d'ajout
        $form = new Form();

        // On construit le formulaire d'ajout
        $form->startForm("#", "POST", ["enctype" => "multipart/form-data"]);
        $form->addInput("hidden", "csrf_token", ["value" => $csrfToken]);
        $form->addLabel("title", "Titre", ["class" => "form-label"]);
        $form->addInput("text", "title", ["id" => "title", "class" => "form-control", "placeholder" => "Ajouter un titre"]);
        $form->addLabel("description", "Description", ["class" => "form-label"]);
        $form->addTextarea("description", "description de la création", ["id" => "description", "class" => "form-control", "rows" => 15]);
        $form->addLabel("date", "Date de publication", ["class" => "form-label"]);
        $form->addInput("date", "date", ["id" => "date", "class" => "form-control"]);
        $form->addLabel("picture", "Image de la création", ["class" => "form-label"]);
        $form->addInput("file", "picture", ["id" => "picture", "class" => "form-control mb-2"]);
        $form->addInput("submit", "add", ["value" => "Ajouter", "class" => "btn btn-primary"]);
        $form->endForm();

        // Ajout du token CSRF au formulaire
        $form->addInput("hidden", "csrf_token", ["value" => $csrfToken, "hidden" => ""]);

        // Envoi du formulaire dans la vue add.php
        $this->render('creation/add', ["addForm" => $form->getFormElements(), "erreur" => $erreur]);
    }

    // Méthode pour afficher une création
    public function showCreation($id)
    {
        // Convertir l'identifiant en entier
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        // On instancie la classe CreationModel
        $creationModel = new CreationModel();

        // On stocke dans une variable le return de la méthode find()
        $creation = $creationModel->find($id);
        $this->render('creation/showCreation', ['creation' => $creation]);
    }

    // Méthode pour la mise à jour de la création
    public function updateCreation($id)
    {
        // Générer un token CSRF
        $csrfToken = bin2hex(random_bytes(32));

        // Stocker le token CSRF dans la session de l'utilisateur
        $_SESSION['csrf_token'] = $csrfToken;

        // Exemple de vérification d'authentification
        if (isset($_SESSION['user_id'])) {
            session_regenerate_id(); // Régénérer l'identifiant de session
        }

        // Convertir l'identifiant en entier
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        // On contrôle si les champs du formulaire sont remplis
        if (Form::validatePost($_POST, ['title', 'description', 'date', 'hidden'])) {

            // On instancie l'entité "Création"
            $creation = new Creation();

            // On l'hydrate
            $creation->setTitle($_POST['title']);
            $creation->setDescription($_POST['description']);
            $creation->setCreated_at($_POST['date']);

            // Si une nouvelle image a été uploadée
            if (Form::validateFiles($_FILES, ['picture'])) {

                // Vérification de l'extension et du type MIME du fichier uploadé
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $maxFileSize = 2 * 1024 * 1024; // 2Mo

                $filename = $_FILES['picture']['name'];
                $fileType = $_FILES['picture']['type'];
                $filesize = $_FILES['picture']['size'];

                $ext = pathinfo($filename, PATHINFO_EXTENSION);

                // Vérification de l'extension
                if (!in_array($ext, $allowedExtensions)) {
                    $erreur = "Extension de fichier non autorisée.";
                }
                // Vérification du type MIME
                elseif (!in_array($fileType, $allowedMimeTypes)) {
                    $erreur = "Type de fichier non autorisé.";
                }
                // Vérification de la taille du fichier
                elseif ($filesize > $maxFileSize) {
                    $erreur = "La taille du fichier est trop grande.";
                } else {
                    // Génération d'un nom unique pour le fichier
                    $uniqueName = uniqid('', true) . '.' . $ext;

                    // Chemin de destination
                    $destination = "images/" . $uniqueName;

                    // Déplacement du fichier uploadé vers le dossier de destination
                    move_uploaded_file($_FILES['picture']['tmp_name'], $destination);

                    // On stocke le chemin de l'image
                    $picture = $destination;

                    // On hydrate la propriété picture de la classe "Creation"
                    $creation->setPicture($picture);
                }
            } else {
                // Si aucune nouvelle image n'a été uploadée, on garde le lien de l'image par défaut du champ caché
                $creation->setPicture($_POST['hidden']);
            }

            // On instancie le modèle "creation" pour l'update
            $creations = new CreationModel();
            $creations->update($id, $creation);

            // On redirige l'utilisateur vers la liste des créations
            header("Location:index.php?controller=creation&action=index");
            exit();
        } else {
            // On affiche un message d'erreur
            $erreur = !empty($_POST) ? "Le formulaire n'a pas été correctement rempli" : "";
        }

        // On instancie le model pour récupérer les informations de la création
        $creations = new CreationModel();
        $creation = $creations->find($id);

        // On construit le formulaire de mise à jour
        $form = new Form();

        $form->startForm("#", "POST", ["enctype" => "multipart/form-data"]);
        $form->addLabel("title", "Titre", ["class" => "form-label"]);
        $form->addInput("text", "title", ["id" => "title", "class" => "form-control", "placeholder" => "Ajouter un titre", "value" => htmlspecialchars($creation->title)]);
        $form->addLabel("description", "Description", ["class" => "form-label"]);
        $form->addTextarea("description", htmlspecialchars($creation->description), ["id" => "description", "class" => "form-control", "rows" => 15]);
        $form->addLabel("date", "Date de publication", ["class" => "form-label"]);
        $form->addInput("text", "date", ["id" => "date", "class" => "form-control", "value" => htmlspecialchars($creation->created_at), "readonly" => ""]);
        $form->addLabel("picture", "Image de la création", ["class" => "form-label"]);
        $form->addInput("file", "picture", [
            "id" => "picture", "class" => "form-control mb-2"
        ]);
        $form->addInput("text", "hidden", ["id" => "hidden", "class" => "form-control", "value" => htmlspecialchars($creation->picture), "hidden" => ""]);
        $form->addInput("submit", "update", ["value" => "Modifier", "class" => "btn btn-primary"]);
        $form->endForm();

        // Ajout du token CSRF au formulaire
        $form->addInput("hidden", "csrf_token", ["value" => $csrfToken, "hidden" => ""]);

        // On renvoie vers la vue le formulaire de mise à jour et le message d'erreur potentiel
        $this->render('creation/updateCreation', ["updateForm" => $form->getFormElements(), "erreur" => $erreur]);
    }

    // Méthode pour la suppression d'une création
    public function deleteCreation($id)
    {
        // Vérification du jeton CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            // Gérer l'erreur CSRF ici
            // Par exemple, afficher un message d'erreur et rediriger l'utilisateur
            $error_message = "Erreur CSRF : Token CSRF invalide.";
            $_SESSION['error'] = $error_message;
            header("Location: index.php?controller=creation&action=index");
            exit();
        }
        // Exemple de vérification d'authentification
        if (isset($_SESSION['user_id'])) {
            session_regenerate_id(); // Régénérer l'identifiant de session
        }
        // Convertir l'identifiant en entier
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        // On récupère la création avec la méthode find()
        $creations = new CreationModel();
        $creation = $creations->find($id);

        // Vérifie si la création a été trouvée
        if (!$creation) {
            // Gérer l'erreur ici, par exemple, rediriger vers une page d'erreur
            $error_message = "La création avec l'identifiant $id n'a pas été trouvée.";
            $_SESSION['error'] = $error_message;
            header("Location: index.php?controller=creation&action=index");
            exit();
        }

        // Logique de suppression si l'utilisateur confirme la suppression
        if (isset($_POST['true'])) {
            // On instancie la classe CreationModel pour exécuter la suppression avec la méthode delete()
            // en récupérant l'id de la création du lien
            $creations = new CreationModel();
            $creations->delete($id);
            // On redirige l'utilisateur vers la liste des créations
            header("Location:index.php?controller=creation&action=index");
            exit();
        } elseif (isset($_POST['false'])) {
            // Redirection si l'utilisateur annule la suppression
            // On redirige l'utilisateur vers la liste des créations
            header("Location:index.php?controller=creation&action=index");
            exit();
        }

        // On renvoie vers la vue la création sélectionnée avec la variable $creation définie
        $this->render('creation/deleteCreation', ["creation" => $creation]);
    }
}
