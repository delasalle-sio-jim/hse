<?php ob_start(); ?>
<?php
// Application HSE
// Auteur : DELAUNAY Pierre
// Dernière mise à jour : 10/11/2017 par Pierre

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

$tabExclu = array();

if (isset($_POST['exclu']))
{
	foreach($_POST['exclu'] as $idExclu) {
	    $tabExclu[] = $idExclu;
	}

	$req = "SELECT * FROM hse_vue_listeexportcsv ";
	$req .= "WHERE decID NOT IN (";

	$req2 = "UPDATE hse_declarations SET dejaExporte = 1 WHERE declaration_id NOT IN (";
	
	$req3 = "ALTER VIEW hse_vue_exporttotaux2 AS SELECT * FROM hse_vue_exporttotaux WHERE decID NOT IN (";

	$countTab = count($tabExclu);

	for ($i = 0 ; $i <= $countTab - 1 ; $i++) 
	{
	  
	    if ($i == $countTab - 1) $req .= $tabExclu[$i].");";
	    else $req .= $tabExclu[$i].", ";
	    
	    if ($i == $countTab - 1) $req2 .= $tabExclu[$i].");";
	    else $req2 .= $tabExclu[$i].", ";
	    
	    if ($i == $countTab - 1) $req .= $tabExclu[$i].");";
	    else $req .= $tabExclu[$i].", ";
	    
	    if ($i == $countTab - 1) $req3 .= $tabExclu[$i].");";
	    else $req3 .= $tabExclu[$i].", ";
	}

}
else
{

	$req = "SELECT * FROM hse_vue_listeexportcsv";
	$req2 = "UPDATE hse_declarations SET dejaExporte = 1";
	$req3 = "ALTER VIEW hse_vue_exporttotaux2 AS SELECT * FROM hse_vue_exporttotaux";

}
//echo $req;
//echo $req2;
//echo $req3;

$alterView = $cnx->query($req3) or die('erreur requete alter view');

$reponse = $cnx->query($req) or die('erreur req');
$handle = fopen('php://output', 'w');
//add BOM to fix UTF-8 in Excel
fputs($handle, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

//On insère les titres
fputcsv($handle, array('Enveloppe', 'Prénom', 'Nom', 'DuréeBaseDécimale', 'Mois', 'Activité', 'Date activité', 'Classe', 'Commentaire', 'Id'), ';');
$reponse->setFetchMode(PDO::FETCH_ASSOC);
while($donnees = $reponse->fetch()) {
	$donnees = array_map("utf8_encode", $donnees);
	fputcsv($handle, $donnees, ';');
}
$reponse->closeCursor();


fputcsv($handle, array(''), ';');
fputcsv($handle, array('EnseignantNom', 'EnseignantPrénom', 'PECT1', 'PECT2', 'HSE', 'Total'), ';');

$reponse = $cnx->query("SELECT * FROM hse_vue_totauxparprof") or die('erreur');
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
// importées précedemment. On met à jour la table hse_parametres (historique).
//
//--------------------------------------------------------------------------

$req = $cnx->prepare($req2);
$req->execute();

$auteurExport = $_SESSION['login'];
$dateTimeExport = date("Y-m-d H:i:s");
// préparation de la requête update dans la table hse_parametres
$txt_req = "UPDATE hse_parametres SET dateTimeExport = :dt, auteurExport = :auteur;";
$req = $cnx->prepare($txt_req);

// liaison de la requête et de ses paramètres
$req->bindValue("dt", $dateTimeExport, PDO::PARAM_STR);
$req->bindValue("auteur", $auteurExport, PDO::PARAM_STR);
// extraction des données et comptage des réponses
$req->execute();

ob_flush();

?>