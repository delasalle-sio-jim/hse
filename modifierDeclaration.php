<?php
// Application HSE
// Auteur : DELAUNAY Pierre
// Dernière mise à jour : 29/06/2017 par Pierre

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
  <!-- Plugin JQuery Tooltipster -->
  <link rel="stylesheet" type="text/css" href="tooltipster/dist/css/tooltipster.bundle.min.css" />
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.0.min.js"></script>
  <script type="text/javascript" src="tooltipster/dist/js/tooltipster.bundle.min.js"></script>
  <!-- Activation de Tooltipster -->
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

      // On récupère les informations de la déclaration que l'enseignant souhaite modifier grâce à l'id de la déclaration que l'on a avec la méthode GET

      $decID = $_GET['id'];

      //$req_pre = $cnx->prepare("SELECT declaration_id, declaration_date AS declarationDate, typeactivite_id AS typeActID FROM hse_declarations WHERE declaration_id =:decID");
      $req_pre = $cnx->prepare("SELECT declaration_id, declaration_date AS declarationDate, duree, hse_declarations.typeactivite_id AS typeActID, typeactivite_libelle AS typeActLibelle, precisionsprof FROM hse_declarations, hse_typeactivite WHERE hse_typeactivite.typeactivite_id = hse_declarations.typeactivite_id AND declaration_id =:decID");
      $req_pre->bindValue(':decID', $decID, PDO::PARAM_STR);
      $req_pre->execute();
      
      $resultat=$req_pre->fetch(PDO::FETCH_OBJ);

      $decDate = $resultat->declarationDate;
      $decDate = toDateFrancais($decDate);
      $typeActID = $resultat->typeActID;
      $typeActLibelle = utf8_encode($resultat->typeActLibelle);
      $duree = $resultat->duree;
      $precisionsProf = utf8_encode($resultat->precisionsprof);
      $precisionsProf = stripslashes($precisionsProf);

      $req_pre->closeCursor();

      // On récupère l'id et le sigle de la classe, le sigle va nous être utile pour placer l'attribut selected de la liste déroulante


      $req_pre = $cnx->prepare("SELECT hse_declarations.classe_id, classe_sigle AS clsSigle FROM hse_declarations, hse_classes WHERE hse_classes.classe_id = hse_declarations.classe_id AND declaration_id =:decID");
      $req_pre->bindValue(':decID', $decID, PDO::PARAM_STR);
      $req_pre->execute();
      
      $resultat=$req_pre->fetch(PDO::FETCH_OBJ);

      $classeSigle = $resultat->clsSigle;

      $req_pre->closeCursor();

      if ($typeActID == 1) {
      /* Si la déclaration à modifier est de type khôlle on affiche le formulaire correspondant au type khôlle */


      /* On récupère le nombre d'étudiants et la durée par étudiant (spécifique au type khôlle) */


      $req_pre = $cnx->prepare("SELECT nbetudiants, dureeparetudiant FROM hse_declarations WHERE declaration_id =:decID");
      $req_pre->bindValue(':decID', $decID, PDO::PARAM_STR);
      $req_pre->execute();
      
      $resultat=$req_pre->fetch(PDO::FETCH_OBJ);

      $nbEtudiants = $resultat->nbetudiants;
      $dureeParEtudiant = $resultat->dureeparetudiant;

      $req_pre->closeCursor();

      ?>

      <a href="historiqueDeclaration.php"><img src='images/back.jpg' height='36' width='36'></a>

      <form name="form3" id="form3" action="scriptModifKholle.php" method="post"> 

      <h3>Modification de la déclaration (khôlle) : </h3>

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

      <td width="10%">Date de l'activité * (jj/mm/aaaa) : <input name="date" type="text" pattern="^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[0-2])/20(1[7-9]|2[0-9]|30)$" value=<?php echo $decDate; ?> required> </td>

      </tr>

      <tr>

      <tr>
      <td width="10%">Nature de l'activité * : Khôlle </td>
      </tr>

      <td width="10%">Classe concernée * : <select name="classe" required>

      <?php

      $resultat = $cnx->query("SELECT classe_id AS Cid, classe_libelle AS Clibelle, classe_sigle AS Csigle FROM hse_classes WHERE affListeKholle = 1 ORDER BY classe_sigle;");
      $resultat->setFetchMode(PDO::FETCH_OBJ);
        
      $ligne = $resultat->fetch();

      while ($ligne) {


      if ($ligne->Csigle == $classeSigle) { echo "<option value=".($ligne->Cid)." selected>".utf8_encode($ligne->Csigle)."</option>"; }
      else
      { echo "<option value=".($ligne->Cid).">".utf8_encode($ligne->Csigle)."</option>"; }
      // récupération de l’enregistrement suivant du jeu d’enregistrements
      $ligne = $resultat->fetch();


      }

      $resultat->closeCursor();

      ?>
      </select>

      </td>
      
      </tr>

      <tr>
      <td width="10%">Nombre d'étudiant(s) * : <input type="text" name= "nbEtu" pattern="^[0-9]{1,4}$" value=<?php echo $nbEtudiants; ?> required> </td>
      </tr>

      <tr>
      <td width="10%">Durée par étudiant * (en minutes) : <input type="text" name= "dureeParEtu" pattern="^[0-9]{1,4}$" value=<?php echo $dureeParEtudiant; ?> required> </td>
      </tr>

      <tr>

      <!--<td width="10%">Précisions : <input type="textarea" name="preci"></td>-->
      
      </tr>

      <tr>
      <input type="hidden" name="enseignantID" value=<?php echo $enseignantID; ?>>
      </tr>

      <tr>
      <input type="hidden" name="declarationID" value=<?php echo $decID; ?>>
      </tr>

      </table>
      
      </fieldset>

      <br/><br/>

      <p> (*) Champs obligatoires</p>

      <input type="submit" name="btnConnecter" id="btnConnecter" value="Valider">
      <input type="reset" name="btnAnnuler" id="btnConnecter" value="Annuler">

    </form>







      <?php
      }
      else { 
      /* Sinon on affiche le formulaire correspondant au type HSE */




        ?>

      <a href="historiqueDeclaration.php"><img src='images/back.jpg' height='36' width='36'></a>

      <form name="form1" id="form1" action="scriptModifHSE.php" method="post"> 

      <h3>Modification de la déclaration HSE : </h3>

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

      <td width="10%">Date de l'activité * (jj/mm/aaaa) : <input name="date" type="text" pattern="^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[0-2])/20(1[7-9]|2[0-9]|30)$" value=<?php echo $decDate; ?> required> </td>

      </tr>

      <tr>
      <td width="10%">Nature de l'activité * : <select id="typeactivite" name="typeactivite" required>
      <?php

      $resultat = $cnx->query("SELECT * FROM hse_vue_listesanskholle;");
      $resultat->setFetchMode(PDO::FETCH_OBJ);

      $ligne = $resultat->fetch();

      while ($ligne) {

      if ($ligne->TypeId == $typeActID) {
      echo "<option value=".$ligne->TypeId." selected>".utf8_encode($ligne->TypeLibelle)."</option>";

      }
      else {
      echo "<option value=".$ligne->TypeId.">".utf8_encode($ligne->TypeLibelle)."</option>";
      }
      // récupération de l’enregistrement suivant du jeu d’enregistrements
      $ligne = $resultat->fetch();

      }

      $resultat->closeCursor();

      ?>

      </select></td>
      </tr>


      <tr>
      <td width="10%"> Commentaire  
      <span class="tooltip" data-tooltip-content="#tooltip_content"><img src="images/info.png" height='18' width='18' /></span>

      <div class="tooltip_templates">
      <span id="tooltip_content">
      Préciser l'activité ou le nom de l'enseignant(e) remplacé(e), de l'élève concerné(e)...
      </span>
      </div>
      : <input type='textarea' name='precisionsProf' size='50' placeholder="Votre commentaire" value=<?php echo $precisionsProf; ?>>
      </td>
      </tr>



      <tr>
      <td width="10%">Classe concernée * : <select name="classe" required>

      <?php

      $resultat = $cnx->query("SELECT classe_id AS Cid, classe_libelle AS Clibelle, classe_sigle AS Csigle FROM hse_classes ORDER BY CASE classe_sigle WHEN 'Aucune' THEN 1 WHEN 'Regroupement' THEN 2 ELSE 3 END;");
      $resultat->setFetchMode(PDO::FETCH_OBJ);
        
      $ligne = $resultat->fetch();

      while ($ligne) {

      if ($ligne->Csigle == $classeSigle) { echo "<option value=".($ligne->Cid)." selected>".utf8_encode($ligne->Csigle)."</option>"; }
      else
      { echo "<option value=".($ligne->Cid).">".utf8_encode($ligne->Csigle)."</option>"; }
      // récupération de l’enregistrement suivant du jeu d’enregistrements
      $ligne = $resultat->fetch();


      }

      $resultat->closeCursor();
      
      ?>
      </select>

      </td>
      
      </tr>



      <tr>
      <td width="10%">Durée de l'activité * : 
      <select name="duree" required>
      <?php

      $i = 60;

      while ($i <= 600) {

      $nbHeures = $i/60;
      // On cast en int pour éviter 2.5h30 par exemple
      $nbHeures = (int) $nbHeures;
      $nbMinutes = (int)$i%60;
      // On évite les 2h0
      if ($nbMinutes == 0) { $dureeToString = $nbHeures."h";}
      else { $dureeToString = $nbHeures."h".$nbMinutes;}
      // On place l'attribut selected si $i == $duree, si l'enseignant avait choisi 2h la liste se place ainsi sur 2h
      if ($i == $duree) { echo "<option value=".$i." selected> ".$dureeToString." </option>"; }
      else { echo "<option value=".$i." > ".$dureeToString." </option>"; }
      
      $i = $i + 30;
      }
      ?>
      </select>






      </td>
      </tr>

      <tr>
      
      </tr>

      <tr>
      <input type="hidden" name="enseignantID" value=<?php echo $enseignantID; ?>>
      </tr>

      <tr>
      <input type="hidden" name="declarationID" value=<?php echo $decID; ?>>
      </tr>

      </table>
      
      </fieldset>

      <br/><br/>

      <p> (*) Champs obligatoires</p>

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
