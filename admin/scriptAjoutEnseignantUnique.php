<?php
// Application HSE
// Auteur : DELAUNAY Pierre
// Dernière mise à jour : 13/10/2017 par Pierre

// ouverture d'une session
session_start();  
// inclusion des paramètres et de la bibliothéque de fonctions ("include_once" peut être remplacé par "require_once")
include_once ('../include/_inc_parametres.php');
// connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
include_once ('../include/_inc_connexion.php');
// fonctions
include ('../include/fonctions.php');

/* On récupère les valeurs du formulaire grâce à la méthode POST */

$nom = utf8_decode($_POST['nom']);
$prenom = utf8_decode($_POST['prenom']);
$identifiant = $_POST['identifiant'];
$mdp = $_POST['mdp'];


// préparation de la requête insert dans la table hse_enseignants
$txt_req = "INSERT INTO hse_enseignants (enseignant_nom, enseignant_prenom, enseignant_login, enseignant_mdp) VALUES (:nom, :prenom, :identifiant, :mdp);";
$req = $cnx->prepare($txt_req);
// liaison de la requête et de ses paramètres
$req->bindValue("nom", $nom, PDO::PARAM_STR);
$req->bindValue("prenom", $prenom, PDO::PARAM_STR);
$req->bindValue("identifiant", $identifiant, PDO::PARAM_STR);
$req->bindValue("mdp", sha1($mdp), PDO::PARAM_STR);
// extraction des données et comptage des réponses
$req->execute();


if ($req == true) {
header("Location:ajoutEnseignantUnique.php?req=ok"); }
else {
header("Location:ajoutEnseignantUnique.php?req=fail"); }


?>