<?php
// Application HSE
// Auteur : DELAUNAY Pierre
// Dernière mise à jour : 25/09/2017 par Pierre

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
          <!--<li><a href="">Page 1</a></li>-->
          <li class="selected"><a href="../index.php?action=deconnexion">Déconnexion</a></li>
        </ul>
      </div>

    </div> <!-- fin id="header" -->

    <div id="site_content"> <!-- début id="site_content" -->
      <div id="content"> <!-- début id="content" -->
      
      <a href="ajoutEnseignant.php"><img src='../images/back.jpg' height='36' width='36'></a>

      <h1>Ajout d'un enseignant : </h1>

      <form name="form1" id="form1" action="scriptAjoutEnseignantUnique.php" method="post"> 

      <fieldset>
      <legend>Informations : </legend>
      <table>

      <tr></tr>
      <tr></tr>

      <tr>
      <td width="10%">
      Identifiant :&emsp; <input type='text' name="identifiant" size="30" placeholder="Un identifiant ex: martin.m" required>
      </td>
      </tr>

      <tr>
      <td width="10%">
      Mot de passe : <input type='text' name="mdp" size="30" placeholder="6 lettres aléatoires ex: abcdef" required>
      </td>
      </tr>
      
      <tr>
      <td width="10%">
      Nom : <input type='text' name="nom" size="30" placeholder="Un nom ex: MARTIN" required>
      </td>
      </tr>
      
      <tr>
      <td width="10%">
      Prénom : <input type='text' name="prenom" size="30" placeholder="Un prénom ex: Martin" required>
      </td>
      </tr>

      </table>
      </fieldset>

      <br/><br/>

      <?php if (isset($_GET['req']) && $_GET['req'] == "ok") {

        echo "<p><font color='green'>Opération effectuée avec succès ! </font></p>";
      }
      elseif (isset($_GET['req']) && $_GET['req'] == "fail") {
         
        echo "<p><font color='red'>Erreur ! </font></p>"; }

      ?>
      
      <p> Tous les champs obligatoires sont obligatoires.</p>

      <input type="submit" name="btnConnecter" id="btnConnecter" value="Valider">
      <input type="reset" name="btnAnnuler" id="btnConnecter" value="Annuler">

      </form>

      </div> <!-- fin id="content" -->
    </div> <!-- fin id="site_content" -->

    <div id="footer"> 
      <a href="http://www.lycee-delasalle.com/">Lycée De La Salle - RENNES</a>
    </div>

  </div> <!-- fin id="main" -->

</body>
</html>