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
?>

<!DOCTYPE HTML>
<html>

<head>
  <title>Application HSE</title>
  <link rel="shortcut icon" href="images/favicon.ico"/>
  <meta http-equiv="keywords" name="keywords" content="" />
  <meta http-equiv="description" name="description" content="" />
  <meta http-equiv="content-type" content="text/html; charset=utf8" />
  <link rel="stylesheet" type="text/css" href="style/style.css" />
</head>

<body>
  <div id="main"> <!-- début id="main"-->

    <div id="header"> <!-- début id="header" -->
      <div id="logo">

      </div>

      <div id="menubar">
        <ul id="menu">
          <!-- class="selected" permet de mettre en évidence la page actuelle -->
          <li class="selected"><a href="gestionEnseignant.php">Menu</a></li>
          <!--<li><a href="">Page 1</a></li>-->
          <li class="selected"><a href="index.php?action=deconnexion">Déconnexion</a></li>
          <li class="selected"><a>Espace enseignant</a>
        </ul>
      </div>

    </div> <!-- fin id="header" -->

    <div id="site_content"> <!-- début id="site_content" -->

       <div class="sidebar">
        <img src="images/logoHSE.png">
      </div>

      <div id="content"> <!-- début id="content" -->

        <h2 class='align'><font color='black'>Bienvenue sur l'application</font></h2>

        <div id="menu-vertical">
        <a href="ajoutDeclarationHSE.php" class="bouton-menu">Déclarer des heures supplémentaires (HSE)</a>
        <a href="ajoutDeclarationKholle.php" class="bouton-menu">Déclarer des heures d'interrogations orales (khôlles)</a>
        <a href="historiqueDeclaration.php" class="bouton-menu">Historique de vos déclarations</a>
        </div>




        

      </div> <!-- fin id="content" -->
    </div> <!-- fin id="site_content" -->

    <div id="footer"> 
      <a href="http://www.lycee-delasalle.com/">Lycée De La Salle - RENNES</a>
    </div>

  </div> <!-- fin id="main" -->

</body>
</html>
