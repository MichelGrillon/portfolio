<?php
// Échapper le titre avant de l'utiliser
$title = htmlspecialchars("Mon portfolio - Ajout d'une création", ENT_QUOTES, 'UTF-8');
?>
<h1><?php echo $title; ?></h1>
<?php
echo $addForm;
?>