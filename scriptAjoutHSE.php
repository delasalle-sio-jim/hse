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

$enseignantID = $_POST['enseignantID'];
$typeactID = $_POST['typeactivite'];
$classeID = $_POST['classe'];
$duree = $_POST['duree'];
/* On insert en format US pour MySQL */
$declarationDate = toDateUS($_POST['date']);
$dejaexp = 0;
/* $today = date("Y-m-d H:i:s");  format datetime mysql */
$datetimeSaisie = date("Y-m-d H:i:s");
/* Récupération du commentaire de l'enseignant si il y en a un */
if ( isset($_POST['precisionsProf']) ) { $precisionsProf = utf8_decode($_POST['precisionsProf']); } else { $precisionsProf = '';}

// préparation de la requête insert dans la table hse_declarations
$txt_req = "INSERT INTO hse_declarations (ENSEIGNANT_ID, TYPEACTIVITE_ID, CLASSE_ID, DUREE, DECLARATION_DATE, DEJAEXPORTE, DATETIMESAISIE, PRECISIONSPROF) VALUES (:ensID, :typeactID, :classeID, :duree, :decDate, :dejaexp, :datetimeSaisie, :precisionsProf);";
$req = $cnx->prepare($txt_req);
// liaison de la requête et de ses paramètres
$req->bindValue("ensID", $enseignantID, PDO::PARAM_STR);
$req->bindValue("typeactID", $typeactID, PDO::PARAM_STR);
$req->bindValue("classeID", $classeID, PDO::PARAM_STR);
$req->bindValue("duree", $duree, PDO::PARAM_STR);
$req->bindValue("decDate", $declarationDate, PDO::PARAM_STR);
$req->bindValue("dejaexp", $dejaexp, PDO::PARAM_STR);
$req->bindValue("datetimeSaisie", $datetimeSaisie, PDO::PARAM_STR);
$req->bindValue("precisionsProf", $precisionsProf, PDO::PARAM_STR);

// extraction des données et comptage des réponses
$req->execute();


header("Location:historiqueDeclaration.php");


?>