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
  <!-- plugin JQuery Tooltipster -->
  <link rel="stylesheet" type="text/css" href="../tooltipster/dist/css/tooltipster.bundle.min.css" />
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.0.min.js"></script>
  <script type="text/javascript" src="../tooltipster/dist/js/tooltipster.bundle.min.js"></script>
  <script>
        $(document).ready(function() {
            $('.tooltip').tooltipster();
        });
  </script>
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
      
      <a href="listeClasse.php"><img src='../images/back.jpg' height='36' width='36'></a>

      <?php

      /* On effectue un ajout, on affiche le formulaire d'ajout de classe */

      if (isset($_GET['action'])&& $_GET['action']=="ajout") {
      ?>

      <h1>Ajout d'une nouvelle classe : </h1>

      <form name="form1" id="form1" action="scriptAjoutClasse.php" method="post"> 

      <fieldset>
      <legend>Informations : </legend>
      <table>

      <tr></tr>
      <tr></tr>

      <tr>
      <td width="10%">
      Sigle de la classe * : <input type='text' name="sigle" placeholder="Un sigle" required>
      </td>
      </tr>


      <tr>
      <td width="10%">
      Libellé de la classe * : <input type='text' name="libelle" size="60" placeholder="Un libellé" required>
      </td>
      </tr>

      <tr>
      <td width="10%">
      Afficher cette classe dans la liste déroulante classes khôlle * <span class="tooltip" data-tooltip-content="#tooltip_content"><img src="../images/info.png" height='18' width='18' /></span>

      <div class="tooltip_templates">
        <span id="tooltip_content">
          <strong>"Non" : la classe apparaît seulement dans la liste des classes HSE. </br>
          "Oui" : la classe apparaît dans les listes des classe HSE et khôlles.
           </strong>
        </span>
      </div>
      : <select name="affListeKholle" required>
      <option value="0" selected>Non</option>
      <option value="1">Oui</option>
      </td>
      </tr>



      </table>
      </fieldset>

      <br/><br/>

      <p> (*) Champs obligatoires</p>
      <p> Remarque : Seul le sigle apparaîtra dans la liste déroulante ! </p>

      <input type="submit" name="btnConnecter" id="btnConnecter" value="Valider">
      <input type="reset" name="btnAnnuler" id="btnConnecter" value="Annuler">

      </form>

      <?php
      }
      elseif (isset($_GET['action'])&& $_GET['action']=="modifier" && isset($_GET['id']) ) {
      /* On effectue une modification, on vérifie bien que l'id existe dans le GET si l'administrateur change l'adresse http par accident ou autre erreur possible... */

      $classeID = $_GET['id'];

      /* On va chercher le sigle, le libellé existant, affListeKholle coresspondant à l'id à l'aide d'une requête */

      $req_pre = $cnx->prepare("SELECT classe_id AS classeID, classe_sigle AS classeSigle, classe_libelle AS classeLibelle, affListeKholle FROM hse_classes WHERE classe_id = :classeID;");
      $req_pre->bindValue(':classeID', $classeID, PDO::PARAM_STR);
      $req_pre->execute();
      
      $resultat=$req_pre->fetch(PDO::FETCH_OBJ);

      $sigle = utf8_encode($resultat->classeSigle);
      $libelle = utf8_encode($resultat->classeLibelle);
      $affListe = $resultat->affListeKholle;

      /* Après avoir tout récupérer, on affiche le formulaire de modification ! */
      ?>

      <h1>Modification d'une classe : </h1>

      <form name="form2" id="form2" action="scriptModifClasse.php" method="post"> 

      <fieldset>
      <legend>Informations : </legend>
      <table>

      <tr></tr>
      <tr></tr>

      <tr>
      <td width="10%">
      Sigle de la classe * : <input type='text' name="sigle" value='<?php echo $sigle; ?>' required>
      </td>
      </tr>


      <tr>
      <td width="10%">
      Libellé de la classe * : <input type='text' name="libelle" size="60" value='<?php echo $libelle; ?>' required>
      </td>
      </tr>

      <tr>
      <td width="10%">
      Afficher cette classe dans la liste déroulante classes khôlle *  
      <span class="tooltip" data-tooltip-content="#tooltip_content"><img src="../images/info.png" height='18' width='18' /></span>

      <div class="tooltip_templates">
        <span id="tooltip_content">
          <strong>"Non" : la classe apparaît seulement dans la liste des classes HSE. </br>
          "Oui" : la classe apparaît dans les listes des classe HSE et khôlles.
           </strong>
        </span>
      </div>
      : <select name="affListeKholle" required>
      <?php 

      if ($affListe == 0) {
      /* Si $affListe == 0 alors on affiche non */
      echo '<option value="0" selected>Non</option>';
      echo '<option value="1">Oui</option>';
      }
      else {
      /* Si $affListe == 0 alors on affiche oui */
      echo '<option value="0">Non</option>';
      echo '<option value="1" selected>Oui</option>';
      }

      ?>
      </td>
      </tr>


      <input type="hidden" name="classeID" value=<?php echo $classeID;?> >

      </table>
      </fieldset>

      <br/><br/>

      <p> (*) Champs obligatoires</p>
      <p> Remarque : Seul le sigle apparaîtra dans la liste déroulante ! </p>

      <input type="submit" name="btnConnecter" id="btnConnecter" value="Valider">
      <input type="reset" name="btnAnnuler" id="btnConnecter" value="Annuler">

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
