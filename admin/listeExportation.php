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

// préparation de la requête de recherche dans la table hse_declarations
$txt_req = "SELECT count(*) FROM hse_declarations WHERE dejaExporte = 0";
$req = $cnx->prepare($txt_req);
// extraction des données et comptage des réponses
$req->execute();
$nbCount = $req->fetchColumn(0);
// libère les ressources du jeu de données
$req->closeCursor();

// préparation de la requête de recherche dans la table hse_parametres
$laReq = $cnx->query("SELECT dateTimeExport, auteurExport FROM hse_parametres");
$laReq->setFetchMode(PDO::FETCH_OBJ);
// extraction des données et comptage des réponses
$data = $laReq->fetch();
$auteurExport = ($data->auteurExport);
$dateTimeExport = toDateTimeFR($data->dateTimeExport);
// libère les ressources du jeu de données
$laReq->closeCursor(); 

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

input[type="checkbox"] {
    display:inline-block;
    width:19px;
    height:19px;
    margin:-2px 10px 0 0;
    vertical-align:middle;
}
  </style>

<script>
function cocherOuDecocherTout(cochePrincipale) {
    var coches = document.getElementById('tableau')
                             .getElementsByTagName('input');
    for(var i = 0 ; i < coches.length ; i++) {
        var c = coches[i];
        if(c.type.toUpperCase() == 'CHECKBOX' & c != cochePrincipale) {
            c.checked = cochePrincipale.checked;
        }
    }
    return true;
}
</script>

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
      
      <h1>Choisir les déclarations à exporter :</h1>
      
      <p>La dernière exportation a eu lieu le <?php echo $dateTimeExport; ?> par <?php echo $auteurExport."."; ?><br/>
      Il y a <?php echo $nbCount; ?> déclaration(s) exportable(s) dans la base de données.<br/>
      (*) : Les déclarations cochées ne seront pas exportées.<br/><br/>
      <label for="allcheck"> Cocher/décocher toutes les déclarations : </label><input id='checkAll' type='checkbox' name='checkAll' value="" onclick="return cocherOuDecocherTout(this);" />
      </p>
      
<form name="form3" id="form3" action="scriptExportCsv.php" method="post"> 
<?php

	$lesDeclarations = $cnx->query("SELECT * FROM hse_vue_listedeclarations WHERE dejaExporte = 0 ORDER BY datetimesaisie DESC") or die ('Erreur : aucune déclaration trouvée !');
	$lesDeclarations->setFetchMode(PDO::FETCH_OBJ);
	$uneDeclaration = $lesDeclarations->fetch();
	
    echo "<table class='tableau' id='tableau'> ";
      	echo "<thead>
              <tr>
                <td>Nom</td>
                <td>Prénom</td>
                <td>Date de l'activité</td>
                <td>Classe</td>
                <td>Activité</td>
                <td>Date de la déclaration</td>
                <td><font size='2'>Exclure (*)</font></td>
              </tr>
            </thead>";
    echo "<tr></tr>";


    while ($uneDeclaration) {

      				echo "<tr><td> ".utf8_encode($uneDeclaration->ensNom)."</td>";
      				echo "<td> ".utf8_encode($uneDeclaration->ensPrenom)."</td>";
      				echo "<td> ".toDateFrancais($uneDeclaration->decDate)."</td>";
      				echo "<td> ".getClasseSigle($uneDeclaration->classeID)."</td>";
      				echo "<td>".getTypeActiviteLibelle($uneDeclaration->typeActiviteID)."</td>";
      				echo "<td> ".toDateTimeFRtableau($uneDeclaration->datetimesaisie)."</td>";
      				echo "<td>  <input type='checkbox' name='exclu[]' value=".($uneDeclaration->decID)." /></td>";


      				$uneDeclaration = $lesDeclarations->fetch();
      			}

      		$lesDeclarations->closeCursor();
    echo "</table>";




?>
	<center>
      <p>
      <input type="submit" value="Exporter" onclick="if(!confirm('Voulez-vous exporter les déclarations ?')) return false;">
      </p>
   	</center>

</form>

    </div> <!-- fin id="content" -->
   </div> <!-- fin id="site_content" -->

    <div id="footer"> 
      <a href="http://www.lycee-delasalle.com/">Lycée De La Salle - RENNES</a>
    </div>

  </div> <!-- fin id="main" -->

</body>
</html>