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

$libelle = utf8_decode($_POST['libelle']);

/* Si l'administrateur ne saisit pas d'enveloppe */
if ($_POST['enveloppe']) 
{ $enveloppe = utf8_decode($_POST['enveloppe']); }
else { $enveloppe = "";}

// pas besoin d'utiliser la fonction addslashes pour gérer les apostrophes chez OVH

// préparation de la requête insert dans la table hse_typeactivite
$txt_req = "INSERT INTO hse_typeactivite (typeactivite_libelle, typeactivite_enveloppe) VALUES (:libelle, :enveloppe);";
$req = $cnx->prepare($txt_req);
// liaison de la requête et de ses paramètres
$req->bindValue("libelle", $libelle, PDO::PARAM_STR);
$req->bindValue("enveloppe", $enveloppe, PDO::PARAM_STR);
// extraction des données et comptage des réponses
$req->execute();

if ($req == true) {
header("Location:listeActivite.php?req=ok"); }
else { header("Location:listeActivite.php?req=fail"); }

?>