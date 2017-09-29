<?php
// Application HSE
// Auteur : DELAUNAY Pierre
// Dernière mise à jour : 05/09/2017 par Pierre

// ouverture d'une session
session_start();  
// inclusion des paramètres et de la bibliothéque de fonctions ("include_once" peut être remplacé par "require_once")
include_once ('../include/_inc_parametres.php');
// connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
include_once ('../include/_inc_connexion.php');
// fonctions
include ('../include/fonctions.php');

/* On récupère les valeurs du formulaire grâce à la méthode POST */

$sigle = utf8_decode($_POST['sigle']);
$libelle = utf8_decode($_POST['libelle']);
$affListe = $_POST['affListeKholle'];

// préparation de la requête insert dans la table hse_classes
$txt_req = "INSERT INTO hse_classes (classe_sigle, classe_libelle, affListeKholle) VALUES (:sigle, :libelle, :affListe);";
$req = $cnx->prepare($txt_req);
// liaison de la requête et de ses paramètres
$req->bindValue("sigle", $sigle, PDO::PARAM_STR);
$req->bindValue("libelle", $libelle, PDO::PARAM_STR);
$req->bindValue("affListe", $affListe, PDO::PARAM_STR);
// extraction des données et comptage des réponses
$req->execute();


if ($req == true) {
header("Location:listeClasse.php?req=ok"); }
else {
header("Location:listeClasse.php?req=fail"); }


?>