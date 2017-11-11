<?php
// Application HSE
// Auteur : DELAUNAY Pierre
// Dernière mise à jour : 06/11/2017 par Pierre

// ouverture d'une session
session_start();  
// inclusion des paramètres et de la bibliothéque de fonctions ("include_once" peut être remplacé par "require_once")
include_once ('../include/_inc_parametres.php');
// connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
include_once ('../include/_inc_connexion.php');
// fonctions
include('../include/fonctions.php');

// si l'utilisateur n'est pas un admin
if ($_SESSION['type'] != 'administration') { header('Location: ../index.php'); }
?>

<?php
if ( isset($_GET['action']) && $_GET['action']=="suppression" && isset($_GET['id']) ) {

$declID = $_GET['id'];

// préparation de la requête de DELETE suppression de la déclaration
$txt_req = "DELETE FROM hse_declarations WHERE declaration_id = :declID;";
$req = $cnx->prepare($txt_req);
// liaison de la requête et de ses paramètres
$req->bindValue("declID", $declID, PDO::PARAM_STR);
// extraction des données et comptage des réponses
$req->execute();

header("Location:listeDeclaration.php");

}
?>

<!DOCTYPE HTML>
<html>

<head>
  <title>Application HSE</title>
  <link rel="shortcut icon" href="../images/favicon.ico"/>
  <meta http-equiv="keywords" name="keywords" content="" />
  <meta http-equiv="description" name="description" content="" />
  <meta http-equiv="content-type" content="text/html; charset=utf8" />
  <link rel="stylesheet" type="text/css" href="../style/style.css" />
  <style type="text/css">

  /* ----------------- Mise en forme des tableaux -------------------- */

/* styles des tableaux */
table {
  /* pas d'espace entre les cellules */
  border-spacing: 0;
  
  /* largeur, marges et centrage horizontal */
  width: 700px;
  margin: 30px auto 30px auto;
  
  /* arrière-plan */
  background-color: rgb(230, 230, 230);
}

/* styles des tableaux ayant la propriété class="tableau" */
.tableau {
  /* bordure pour l'ensemble du tableau */
  border: 1px solid #000;
}


/* styles des cellules des tableaux ayant la propriété class="tableau" */
.tableau td, .tableau th {
  /* les cellules sont encadrés en haut et à gauche */
  border-left: 1px solid #000;
  border-top: 1px solid #000;
  padding: 5px;
  text-align: center;
}

  </style>

</head>

<body>
  <div id="main"> <!-- début id="main"-->

    <div id="header"> <!-- début id="header" -->
      <div id="logo">

      </div>

      <div id="menubar">
        <ul id="menu">
          <!-- class="selected" permet de mettre en évidence la page actuelle -->
          <li class="selected"><a href="gestionAdmin.php">Menu</a></li>
          <li class="selected"><a href="../index.php?action=deconnexion">Déconnexion</a></li>
          <li class="selected"><a>Espace administration</a>
        </ul>
      </div>

    </div> <!-- fin id="header" -->

    <div id="site_content"> <!-- début id="site_content" -->
      <div id="content"> <!-- début id="content" -->
   
      <a href="gestionAdminDeclaration.php"><img src='../images/back.jpg' height='36' width='36'></a> 
      
      <h1>Liste des déclarations réalisées par les enseignants : </h1>

  <fieldset>
  <legend></legend>
  <form method="post" action="">
  <table>

  <tr>
  <td>
	Type de déclaration * : <select name="typeDeclaration">
	<option value="tri_all" >Toutes les déclarations</option>
	<option value="tri_pasImport" >Déclarations non importées</option>
	<option value="tri_import" >Déclarations déjà importées</option>
	</select>
  </td>
  </tr>

  <tr>
  <td>
  Activité concernée * :  <select name="activite">   
  <option value="acti_all"> Toutes les activités </option>
  <?php

  $txtreq = "SELECT typeactivite_id As TypeId, typeactivite_libelle As TypeLibelle FROM hse_typeactivite;";
  $resultat = $cnx->query($txtreq);
  $resultat->setFetchMode(PDO::FETCH_OBJ);

  $ligne = $resultat->fetch();

  while ($ligne) {

  echo "<option value=".($ligne->TypeId).">".utf8_encode($ligne->TypeLibelle)."</option>";
  // récupération de l’enregistrement suivant du jeu d’enregistrements
  $ligne = $resultat->fetch();


  }

  ?>
  </select>
  </td>
  </tr>


  <tr>
  <td>
  Nom de l'enseignant * : <select name="nom">   
  <option value="ens_all"> Tous les enseignants </option>
  <?php

  $txtreq = "SELECT enseignant_id As ensId, enseignant_nom AS ensNom, enseignant_prenom AS ensPrenom FROM hse_enseignants ORDER BY enseignant_nom, enseignant_prenom ;";
  $resultat = $cnx->query($txtreq);
  $resultat->setFetchMode(PDO::FETCH_OBJ);

  $ligne = $resultat->fetch();

  while ($ligne) {

  $nomComplet = utf8_encode($ligne->ensNom)." ".utf8_encode($ligne->ensPrenom);
  echo "<option value=".($ligne->ensId).">".$nomComplet."</option>";
  // récupération de l’enregistrement suivant du jeu d’enregistrements
  $ligne = $resultat->fetch();


  }
  ?>
  </select>
  </td>
  </tr>

  <tr>
  <td>
  <input type="submit" name="form" value="Rechercher"/>
  </td>
  </tr>

  </table>

 
	</form>

  </fieldset>

<?php
/* L'administrateur choisit une recherche à 3 critères */

if ( !empty($_POST['typeDeclaration']) AND !empty($_POST['activite']) AND !empty($_POST['nom'])) {

// https://openclassrooms.com/forum/sujet/recherche-avec-plusieurs-criteres-86345

$sql = "SELECT * FROM hse_vue_listedeclarations ";
// on récupère les critères sélectionnés
extract($_POST);
 
$choix = array();

if (!empty($typeDeclaration))
{
    
    if ($typeDeclaration == 'tri_all') { $rien; }
    elseif ($typeDeclaration == 'tri_pasImport') { $choix[] = "dejaexporte = 0"; }
    elseif ($typeDeclaration == 'tri_import') { $choix[] = "dejaexporte = 1";}

}
 
if (!empty($activite))
{
    if ($activite == 'acti_all') { $rien; }
    else {$choix[] = "typeActiviteId = $activite"; }
}
 
if (!empty($nom))
{
    if ($nom == 'ens_all') { $rien; }
    else { $choix[] = "ensId = $nom"; }
}
 
$first = true;
$conditions = '';
 
foreach($choix as $c)
{
    // si c'est la premiere condition, on met where, sinon on met and
    if ($first)
    {
        $first = false;
        $conditions .= " WHERE $c ";
    }
    else
    {
        $conditions .= " AND $c ";
    }
}

$requete = $sql.$conditions;
$reqcount = "SELECT COUNT(*) As Nb FROM hse_vue_listedeclarations".$conditions;


$resultat = $cnx->query($reqcount) or die ('Erreur : aucune déclaration trouvée !');
$resultat->setFetchMode(PDO::FETCH_OBJ);
$ligne = $resultat->fetch();

$count = $ligne->Nb;

	if ($count != 0 ) {

	    $lesDeclarations = $cnx->query($requete) or die ('Erreur : aucune déclaration trouvée !');
		$lesDeclarations->setFetchMode(PDO::FETCH_OBJ);
		$uneDeclaration = $lesDeclarations->fetch();

		// Entête du tableau khôlles

		if ($activite == 1 ) {
      		echo "<table class='tableau'> ";
      		echo "<thead>
              <tr>
                <td>Nom</td>
                <td>Date de l'activité</td>
                <td>Classe</td>
                <td>Activité</td>
                <td>Nombre d'étudiants</td>
                <td>Durée par étudiant</td>
                <td>Modifier</td>
                <td>Supprimer</td>
              </tr>
            </thead>";
      		echo "<tr></tr>";

      			while ($uneDeclaration) {

      			    echo "<tr><td> ".utf8_encode($uneDeclaration->ensNom)." ".substr(($uneDeclaration->ensPrenom), 0, 1)."."."</td>";
      				echo "<td> ".toDateFrancais($uneDeclaration->decDate)."</td>";
      				echo "<td> ".getClasseSigle($uneDeclaration->classeID)."</td>";
      				echo "<td>".getTypeActiviteLibelle($uneDeclaration->typeActiviteID)."</td>";
      				echo "<td> ".$uneDeclaration->nbetudiants."</td>";
      				echo "<td> ".$uneDeclaration->dureeParEtudiant."</td>";
      				echo "<td><a href='modifierDeclaration.php?id=".$uneDeclaration->decID."'><img src='../images/edit.png' height='16' width='16'></a></td>";
      				echo "<td><a href='listeDeclaration.php?action=suppression&id=".$uneDeclaration->decID."'><img src='../images/delete.png' height='16' width='16'></a></td>";

      				$uneDeclaration = $lesDeclarations->fetch();
      			}

      		$lesDeclarations->closeCursor();
      		echo "</table>";

      	}
      	else {

      		// Entête du tableau HSE

      		echo "<table class='tableau'> ";
      		echo "<thead>
              <tr>
                <td>Nom</td>
                <td>Date de l'activité</td>
                <td>Classe</td>
                <td>Activité</td>
                <td>Durée</td>
                <td>Commentaire</td>
                <td>Modifier</td>
                <td>Supprimer</td>
              </tr>
            </thead>";
      		echo "<tr></tr>";

      			while ($uneDeclaration) {

      			    echo "<tr><td> ".utf8_encode($uneDeclaration->ensNom)." ".substr(($uneDeclaration->ensPrenom), 0, 1)."."."</td>";
      				echo "<td> ".toDateFrancais($uneDeclaration->decDate)."</td>";
      				echo "<td> ".getClasseSigle($uneDeclaration->classeID)."</td>";
      				echo "<td>".getTypeActiviteLibelle($uneDeclaration->typeActiviteID)."</td>";
      				echo "<td> ".$uneDeclaration->duree."</td>";
      				if (($uneDeclaration->precisionsprof) == null) { echo "<td style='background-color : grey; opacity: 0.8;'> </td>";} else {
      				echo "<td> ".utf8_encode($uneDeclaration->precisionsprof)."</td>";
      				}
      				echo "<td><a href='modifierDeclaration.php?id=".$uneDeclaration->decID."'><img src='../images/edit.png' height='16' width='16'></a></td>";
      				echo "<td><a href='listeDeclaration.php?action=suppression&id=".$uneDeclaration->decID."'><img src='../images/delete.png' height='16' width='16'></a></td>";

      				$uneDeclaration = $lesDeclarations->fetch();
      			}

      		$lesDeclarations->closeCursor();
      		echo "</table>";

      	}
	}
	else {

	echo "<p>Erreur : aucune déclaration trouvée !</p>";

	}


}











?>
    </div> <!-- fin id="content" -->
    </div> <!-- fin id="site_content" -->

    <div id="footer"> 
      <a href="http://www.lycee-delasalle.com/">Lycée De La Salle - RENNES</a>
    </div>

  </div> <!-- fin id="main" -->

</body>
</html>
