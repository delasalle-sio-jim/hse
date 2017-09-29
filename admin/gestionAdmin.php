<?php
// Application HSE
// Auteur : DELAUNAY Pierre
// Dernière mise à jour : 20/09/2017 par Pierre

// ouverture d'une session
session_start();  
// inclusion des paramètres et de la bibliothéque de fonctions ("include_once" peut être remplacé par "require_once")
include_once ('../include/_inc_parametres.php');
// connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
include_once ('../include/_inc_connexion.php');

// si l'utilisateur n'est pas un admin
if ($_SESSION['type'] != 'administration') { header('Location: ../index.php'); }
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

       <div class="sidebar">
        <img src="../images/logoHSE.png">
      </div>

      <div id="content"> <!-- début id="content" -->
       
        <h2 class='align'><font color='black'>Espace administration</font></h2>

        <div id="menu-vertical">
        <br/>
        <a href="gestionAdminDeclaration.php" class="bouton-menu">Gestion des déclarations</a>
        <a href="gestionAdminAppli.php" class="bouton-menu">Gestion de l'application</a>
        </div>


      </div> <!-- fin id="content" -->
    </div> <!-- fin id="site_content" -->

      </div> <!-- fin id="content" -->
    </div> <!-- fin id="site_content" -->

    <div id="footer"> 
      <a href="http://www.lycee-delasalle.com/">Lycée De La Salle - RENNES</a>
    </div>

  </div> <!-- fin id="main" -->

</body>
</html>
