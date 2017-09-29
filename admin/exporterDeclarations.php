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
include('../include/fonctions.php');

// préparation de la requête de recherche dans la table hse_declarations
$txt_req = "SELECT count(*) FROM hse_declarations WHERE dejaExporte = 0";
$req = $cnx->prepare($txt_req);
// extraction des données et comptage des réponses
$req->execute();
$nbCount = $req->fetchColumn(0);
// libère les ressources du jeu de données
$req->closeCursor();  

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
      
      <h1>Exportation des déclarations au format CSV</h1>

      <p><strong> Déroulement de la procédure d'exportation des déclarations :</strong> <br/> <br/>
      L'exportation concerne toutes les déclarations qui n'ont pas encore été exportées. Lors du clic sur le bouton d'exportation, 
      on récupère la totalité des déclarations non déjà exportées au format CSV (utilisable avec Excel) puis on met à jour dans la base de 
      données le statut de toutes les déclaratons. Elles deviennent non modifiables par les enseignants mais toujours visibles. L'administration
      peut encore les modifier, les enseignants verront les changements mais il est préférable de faire les changements sur Excel puisqu'on ne peut
      plus les exporter à nouveau : l'exportation fonctionne seulement pour les déclarations qui n'ont pas encore été exportées.
      </p>  

      <p><strong> Effectuer l'exportation : </strong> <br/> <br/>
      
      Il y a <?php echo $nbCount; ?> déclaration(s) exportable(s) dans la base de données.

      </p>

      <br/>

      <form action="scriptExportCsv.php" method="post">
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
