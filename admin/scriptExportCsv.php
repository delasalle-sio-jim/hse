<?php
// Application HSE
// Auteur : DELAUNAY Pierre
// Dernière mise à jour : 04/10/2017 par Pierre

// ouverture d'une session
session_start();  
// inclusion des paramètres et de la bibliothéque de fonctions ("include_once" peut être remplacé par "require_once")
include_once ('../include/_inc_parametres.php');
// connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
include_once ('../include/_inc_connexion.php');
// fonctions
include ('../include/fonctions.php');

//--------------------------------------------------------------------------
// Première partie : Exportation au format CSV.
//
//
//--------------------------------------------------------------------------

$reponse = $cnx->query('SELECT * FROM hse_vue_listeexportcsv;');
$handle = fopen('php://output', 'w');
//add BOM to fix UTF-8 in Excel
fputs($handle, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

//On insère les titres
fputcsv($handle, array('Enveloppe', 'Prénom', 'Nom', 'DuréeBaseDécimale', 'Mois', 'Activité', 'Date de l\'activité', 'Classe', 'Commentaire'), ';');
$reponse->setFetchMode(PDO::FETCH_ASSOC);
while($donnees = $reponse->fetch()) {
	$donnees = array_map("utf8_encode", $donnees);
	fputcsv($handle, $donnees, ';');
}
$reponse->closeCursor();
fclose($handle);

header('Content-Type: text/csv');
// Nom fichier
$nomFichier= 'Liste_declarations_'.date('j-m-Y');
header('Content-Disposition: attachment;filename='.$nomFichier.'.csv');


//--------------------------------------------------------------------------
// Deuxième partie : On change le statut (dejaExporte) des déclarations
// importées précedemment.
//
//--------------------------------------------------------------------------

$dejaExporte = 1;

// préparation de la requête update dans la table hse_declarations
$txt_req = "UPDATE hse_declarations SET dejaExporte = :dejaExporte;";
$req = $cnx->prepare($txt_req);

// liaison de la requête et de ses paramètres
$req->bindValue("dejaExporte", $dejaExporte, PDO::PARAM_STR);

// extraction des données et comptage des réponses
$req->execute();

?>