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

// On récupère l'id du type activité Khôlle pour l'ajout, on fait une requête pour chercher l'id au cas où il y aurait une modification dans le futur

$resultat = $cnx->query("SELECT * from hse_vue_kholle;");
$resultat->setFetchMode(PDO::FETCH_OBJ);      
$ligne = $resultat->fetch();

$typeactID = $ligne->type;

$enseignantID = $_POST['enseignantID'];
$classeID = $_POST['classe'];
/* On insert en format US pour MySQL */
$declarationDate = toDateUS($_POST['date']);
$nbEtu = $_POST['nbEtu'];
$dureeParEtu = $_POST['dureeParEtu'];
$dejaexp = 0;
$duree = ($dureeParEtu * $nbEtu);
/* $today = date("Y-m-d H:i:s");  format datetime mysql */
$datetimeSaisie = date("Y-m-d H:i:s");

// préparation de la requête insert dans la table hse_declarations
$txt_req = "INSERT INTO hse_declarations (ENSEIGNANT_ID, TYPEACTIVITE_ID, CLASSE_ID, DUREE, DECLARATION_DATE, DEJAEXPORTE, NBETUDIANTS, DUREEPARETUDIANT, DATETIMESAISIE) VALUES (:ensID, :typeactID, :classeID, :duree, :decDate, :dejaexp, :nbEtu, :dureeParEtu, :datetimeSaisie);";
$req = $cnx->prepare($txt_req);
// liaison de la requête et de ses paramètres
$req->bindValue("ensID", $enseignantID, PDO::PARAM_STR);
$req->bindValue("typeactID", $typeactID, PDO::PARAM_STR);
$req->bindValue("classeID", $classeID, PDO::PARAM_STR);
$req->bindValue("duree", $duree, PDO::PARAM_STR);
$req->bindValue("decDate", $declarationDate, PDO::PARAM_STR);
$req->bindValue("dejaexp", $dejaexp, PDO::PARAM_STR);
$req->bindValue("nbEtu", $nbEtu, PDO::PARAM_STR);
$req->bindValue("dureeParEtu", $dureeParEtu, PDO::PARAM_STR);
$req->bindValue("datetimeSaisie", $datetimeSaisie, PDO::PARAM_STR);
// extraction des données et comptage des réponses
$req->execute();


header("Location:historiqueDeclaration.php");


?>