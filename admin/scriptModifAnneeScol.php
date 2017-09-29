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

/* On récupère la valeur du formulaire grâce à la méthode POST */

$anneeScolaire = $_POST['anneeScolaire'];

// préparation de la requête d'UPDATE dans la table hse_classes
$txt_req = "UPDATE hse_parametres SET ANNEESCOLAIRE = :anneescolaire;";
$req = $cnx->prepare($txt_req);
// liaison de la requête et de ses paramètres
$req->bindValue("anneescolaire", $anneeScolaire, PDO::PARAM_STR);
// extraction des données et comptage des réponses
$req->execute();

if ($req == true) {

	header("Location:modifAnneeScolaire.php?req=ok");
}
else {

	header("Location:modifAnneeScolaire.php?req=fail");
}


?>