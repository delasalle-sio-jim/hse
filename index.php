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
?>

<!DOCTYPE HTML>
<html>

<head>
  <title>Application HSE</title>
  <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico"/>
  <meta http-equiv="keywords" name="keywords" content="" />
  <meta http-equiv="description" name="description" content="" />
  <meta http-equiv="content-type" content="text/html; charset=utf8" />
  <link rel="stylesheet" type="text/css" href="style/style.css" />
  <!-- plugin JQuery Tooltipster -->
  <link rel="stylesheet" type="text/css" href="tooltipster/dist/css/tooltipster.bundle.min.css" />
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.0.min.js"></script>
  <script type="text/javascript" src="tooltipster/dist/js/tooltipster.bundle.min.js"></script>
  <script>
        $(document).ready(function() {
            $('.tooltip').tooltipster();
        });
  </script>
</head>

<body>
  <div id="main"> <!-- début id="main"-->

    <div id="header"> <!-- début id="header" -->

      <div id="logo">
        <!--<div id="logo_text">
          <h1><a href="index.php">Lycée<span class="logo_colour">DLS</span></a></h1>
          <h2>Application déclaration HSE</h2>
        </div>-->
      </div>

      <div id="menubar">
        <ul id="menu">
          <!-- class="selected" permet de mettre en évidence la page actuelle -->
          <li class="selected"><a href="index.php">Accueil</a></li>
          <?php if ( isset($_SESSION['type']) && $_SESSION['type'] == 'enseignant') { echo "<li class='selected'><a href='gestionEnseignant.php'>Retour Menu</a></li>"; } ?>
          <?php if ( isset($_SESSION['type']) && $_SESSION['type'] == 'administration') { echo "<li class='selected'><a href='admin/gestionAdmin.php'>Retour Menu</a></li>"; } ?>
          <?php if (isset($_SESSION['login'])) { ?>
          <li class="selected"><a href="index.php?action=deconnexion">Déconnexion</a></li>
          <?php } ?>
          <!--<li><a href="">Page 1</a></li>-->
        </ul>
      </div>

    </div> <!-- fin id="header" -->

    <div id="site_content"> <!-- début id="site_content" -->

      <div class="sidebar">
        <img src="images/logoHSE.png">
      </div>

        <?php

        /* Vérification de la variable action */
        if (isset($_GET['action'])&& $_GET['action']=="connexion")
        {

            $typeUtilisateur = getTypeUtilisateur($_POST['txtlog'],$_POST['txtmdp']);

            if ($typeUtilisateur == 'enseignant') { $_SESSION['login']=$_POST['txtlog']; $_SESSION['type']='enseignant'; header("Location:gestionEnseignant.php"); }
            if ($typeUtilisateur == 'administration') { $_SESSION['login']=$_POST['txtlog']; $_SESSION['type']='administration'; header("Location:admin/gestionAdmin.php"); }
            if ($typeUtilisateur == 'inconnu') { echo "<font color='red'> Erreur : identifiant ou mot de passe incorrect ! </font>"; }

        }
    
        elseif (isset($_GET['action']) && $_GET['action']=="deconnexion")
        {
         session_unset();
         session_destroy();
         /* retour à la page php index */
         header("Location:index.php");
        }
      

      ?>


        <div id="content"> <!-- début id="content" #4170f4 -->

        <h1><font color="#3E94EF">Bienvenue sur l'application hse/khôlle</font></h1>
        <p>Ce module vous permet de déclarer vos heures supplémentaires (hse) ou vos heures d’interrogations orales (khôlles).</p>

        <h2><font color="#3E94EF">Accéder à l'application</font></h2>
        <p>Veuillez utiliser vos codes de connexion au réseau du lycée.</p>

        <?php

        if (isset($_SESSION['login'])) {

        echo "<p> Vous êtes déjà connecté sous l'identifiant : ".$_SESSION['login']." . <br/>";
        echo "Pour vous déconnecter, appuyez sur le bouton Déconnexion dans le menu en haut. </p>";

        }
        else { ?>

        <form name="form1" id="form1" action="index.php?action=connexion" method="post">
        <p>
          <label for="txtIdentifiant">Identifiant :&nbsp; &nbsp;</label>
          <input type="text" name="txtlog" id="txtlog" placeholder="Mon identifiant" pattern="^[a-z]+\.[a-z0-9]{1,2}$" required>
        </p>
        <p>
          <label for="txtMotDePasse">Mot de passe :</label>
          <input type="password" name="txtmdp" id="txtmdp" maxlength="20" placeholder="Mon mot de passe" pattern="^[a-z]{6,6}$" required>
        </p>
        <p>
          <input type="submit" name="btnConnecter" id="btnConnecter" value="Me connecter">
        </p>
        </form>

        <?php
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