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
          <li class="selected"><a href="index.php?action=deconnexion">Déconnexion</a></li>
          <li class="selected"><a>Espace enseignant</a>
        </ul>
      </div>

    </div> <!-- fin id="header" -->

    <div id="site_content"> <!-- début id="site_content" -->
      <div id="content"> <!-- début id="content" -->

      <a href="gestionEnseignant.php"><img src='images/back.jpg' height='36' width='36'></a>
      
      <form name="form2" id="form2" action="scriptAjoutKholle.php" method="post"> 
      <?php 

      // Récupération de l'année scolaire à partir de la table HSE_PARAMETRES

      $resultat = $cnx->query("SELECT anneescolaire FROM hse_parametres;");
      $resultat->setFetchMode(PDO::FETCH_OBJ);
        
      $ligne = $resultat->fetch();

      $anneescolaire = $ligne->anneescolaire;

      $resultat->closeCursor();

      // Récupération de l'id, du nom et du prénom de l'enseignant à partir du $_SESSION['login'];

      $login = $_SESSION['login'];


      $req_pre = $cnx->prepare("SELECT enseignant_id, enseignant_nom, enseignant_prenom FROM hse_enseignants WHERE enseignant_login =:login");
      $req_pre->bindValue(':login', $login, PDO::PARAM_STR);
      $req_pre->execute();
      
      $resultat=$req_pre->fetch(PDO::FETCH_OBJ);

      $enseignantID = $resultat->enseignant_id;
      $enseignantNom = $resultat->enseignant_nom;
      $enseignantPrenom = $resultat->enseignant_prenom;

      $req_pre->closeCursor();

      ?>

      <h3>Déclaration Heures d'Interrogations Orales (khôlles) : </h3>

      <h3> Mois : <?php echo getMois(); ?> <?php echo date("Y"); ?> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Année scolaire : <?php echo $anneescolaire; ?> </h3>

      <fieldset>

      <legend> Vos informations : </legend>

      <table>

      <tr>
      </tr>

      <tr>
      </tr>

      <tr>

      <td width="10%">Nom : <?php echo utf8_encode($enseignantNom); ?></td>
      <td width="10%">Prénom : <?php echo utf8_encode($enseignantPrenom); ?></td>

      </tr>

      </table>

      </fieldset>

      <br/><br/>

      <fieldset>

      <legend> Votre déclaration : </legend>

      <table>

      <tr>
      </tr>

      <tr>
      </tr>

      <tr>

      <td width="10%">Date de l'activité * (jj/mm/aaaa) : <input name="date" type="text" placeholder="30/01/2018" pattern="^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[0-2])/20(1[7-9]|2[0-9]|30)$" required> </td>

      </tr>

      <tr>
      <td width="10%">Activité * : Khôlle
      </td>
      </tr>

      <tr>

      <td width="10%">Classe(s) concernée(s) * : <select name="classe" required>

      <?php

      $resultat = $cnx->query("SELECT classe_id AS Cid, classe_libelle AS Clibelle, classe_sigle AS Csigle FROM hse_classes WHERE affListeKholle = 1 ORDER BY classe_sigle;");
      $resultat->setFetchMode(PDO::FETCH_OBJ);
        
      $ligne = $resultat->fetch();

      while ($ligne) {

      echo "<option value=".($ligne->Cid).">".utf8_encode($ligne->Csigle)."</option>";
      // récupération de l’enregistrement suivant du jeu d’enregistrements
      $ligne = $resultat->fetch();


      }

      $resultat->closeCursor();

      ?>
      </select>

      </td>
      
      </tr>

      <tr>
      <td width="10%">Nombre d'étudiant(s) * : <input type="text" name= "nbEtu" pattern="^[0-9]{1,4}$" required> </td>
      </tr>

      <tr>
      <td width="10%">Durée par étudiant * (en minutes) : <input type="text" name= "dureeParEtu" pattern="^[0-9]{1,4}$" required> </td>
      </tr>

      <tr>     
      </tr>

      <tr>
      <input type="hidden" name="enseignantID" value=<?php echo $enseignantID; ?>>
      </tr>

      </table>
      
      </fieldset>

      <br/><br/>

      <p> (*) Tous les champs sont obligatoires</p>

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
