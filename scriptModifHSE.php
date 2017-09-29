<?php
// Application HSE
// Auteur : DELAUNAY Pierre
// Dernière mise à jour : 27/06/2017 par Pierre

// ouverture d'une session
session_start();  
// inclusion des paramètres et de la bibliothéque de fonctions ("include_once" peut être remplacé par "require_once")
include_once ('include/_inc_parametres.php');
// connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
include_once ('include/_inc_connexion.php');
// fonctions
include ('include/fonctions.php');

/* On a besoin de l'id enseignant pour l'update, récupérée grâce à l'hidden */
$enseignantID = $_POST['enseignantID'];

/* On a besoin de l'id de la déclaration à mettre à jour, récupérée grâce à l'hidden également, requête update avec 2 conditions */
$declarationID = $_POST['declarationID'];

/* Les données pour l'update (format HSE), on a besoin de la date, la nature de l'activité, la classe et la durée */

$typeactID = $_POST['typeactivite'];
$classeID = $_POST['classe'];
$duree = $_POST['duree'];
/* On update en format US pour MySQL */
$declarationDate = toDateUS($_POST['date']);
/* Récupération du commentaire de l'enseignant si il y en a un */
if ( isset($_POST['precisionsProf']) ) { $precisionsProf = utf8_decode($_POST['precisionsProf']); } else { $precisionsProf = '';}
$precisionsProf = addslashes($precisionsProf);

// préparation de la requête insert dans la table hse_declarations
$txt_req = "UPDATE hse_declarations SET classe_id = :classeID, typeactivite_id = :typeactID, duree = :duree, declaration_date = :declarationDate, precisionsProf = :precisionsProf WHERE enseignant_id = :ensID AND declaration_id = :decID;";
$req = $cnx->prepare($txt_req);
// liaison de la requête et de ses paramètres
$req->bindValue("classeID", $classeID, PDO::PARAM_STR);
$req->bindValue("typeactID", $typeactID, PDO::PARAM_STR);
$req->bindValue("duree", $duree, PDO::PARAM_STR);
$req->bindValue("declarationDate", $declarationDate, PDO::PARAM_STR);
$req->bindValue("precisionsProf", $precisionsProf, PDO::PARAM_STR);
$req->bindValue("ensID", $enseignantID, PDO::PARAM_STR);
$req->bindValue("decID", $declarationID, PDO::PARAM_STR);

// extraction des données et comptage des réponses
$req->execute();

header("Location:historiqueDeclaration.php");




?>