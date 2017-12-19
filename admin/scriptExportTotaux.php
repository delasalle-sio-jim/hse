<?php ob_start(); ?>
<?php
// Application HSE
// Auteur : DELAUNAY Pierre
// Dernière mise à jour : 19/12/2017 par Pierre

// ouverture d'une session
session_start();  
// inclusion des paramètres et de la bibliothéque de fonctions ("include_once" peut être remplacé par "require_once")
include_once ('../include/_inc_parametres.php');
// connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
include_once ('../include/_inc_connexion.php');

//--------------------------------------------------------------------------
// Première partie : Exportation au format CSV.
//
//
//--------------------------------------------------------------------------

$req = "SELECT * FROM hse_vue_totauxpect1";

$reponse = $cnx->query($req) or die('erreur');
$handle = fopen('php://output', 'w');
//add BOM to fix UTF-8 in Excel
fputs($handle, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

//On insère les titres
fputcsv($handle, array('PECT1'), ';');
fputcsv($handle, array('EnseignantNom', 'EnseignantPrénom', 'Total'), ';');
$reponse->setFetchMode(PDO::FETCH_ASSOC);
while($donnees = $reponse->fetch()) {
	$donnees = array_map("utf8_encode", $donnees);
	fputcsv($handle, $donnees, ';');
}
$reponse->closeCursor();
fputcsv($handle, array(' '), ';');
fputcsv($handle, array('PECT2'), ';');
fputcsv($handle, array('EnseignantNom', 'EnseignantPrénom', 'Total'), ';');

$req2 = "SELECT * FROM hse_vue_totauxpect2";
$reponse = $cnx->query($req2) or die('erreur');
//On insère les titres
$reponse->setFetchMode(PDO::FETCH_ASSOC);
while($donnees = $reponse->fetch()) {
    $donnees = array_map("utf8_encode", $donnees);
    fputcsv($handle, $donnees, ';');
}
$reponse->closeCursor();

fclose($handle);

header('Content-Type: text/csv');
// Nom fichier
$nomFichier= 'Exportation_totaux_'.date('j-m-Y');
header('Content-Disposition: attachment;filename='.$nomFichier.'.csv');




ob_flush();

?>