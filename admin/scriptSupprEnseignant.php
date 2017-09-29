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

if (isset($_GET['action']) && $_GET['action'] == 'supprDeclarations') {

// préparation de la requête de vidage de la table hse_declarations
$txt_req = "TRUNCATE TABLE hse_declarations";
$req = $cnx->prepare($txt_req);
// extraction des données et comptage des réponses
$req->execute();

if ($req == true) { header("Location:ajoutEnseignant.php?req=ok1"); }
else { header("Location:ajoutEnseignant.php?req=fail1"); }

}


if (isset($_GET['action']) && $_GET['action'] == 'supprEnseignants') {

// préparation de la requête de vidage de la table hse_declarations
$txt_req = "TRUNCATE TABLE hse_enseignants";
$req = $cnx->prepare($txt_req);
// extraction des données et comptage des réponses
$req->execute();

if ($req == true) { header("Location:ajoutEnseignant.php?req=ok2"); }
else { header("Location:ajoutEnseignant.php?req=fail2"); }


}




?>