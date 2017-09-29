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
include ('../include/fonctions.php');
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

      <a href="listeDeclaration.php"><img src='../images/back.jpg' height='36' width='36'></a>

      <?php 

      // Récupération de l'année scolaire à partir de la table HSE_PARAMETRES

      $resultat = $cnx->query("SELECT anneescolaire FROM hse_parametres;");
      $resultat->setFetchMode(PDO::FETCH_OBJ);
        
      $ligne = $resultat->fetch();

      $anneescolaire = $ligne->anneescolaire;

      // On va cherche le nom et le prénom de l'enseignant liée à sa déclaration avec l'id de sa déclaration ($_GET['id'])

      $decID = $_GET['id'];

      $req_pre = $cnx->prepare("SELECT declaration_id, hse_declarations.enseignant_id, enseignant_nom, enseignant_prenom FROM hse_declarations, hse_enseignants WHERE hse_declarations.enseignant_id = hse_enseignants.enseignant_id AND declaration_id =:decID");
      $req_pre->bindValue(':decID', $decID, PDO::PARAM_STR);
      $req_pre->execute();
      
      $resultat=$req_pre->fetch(PDO::FETCH_OBJ);

      $enseignantID = $resultat->enseignant_id;
      $enseignantNom = $resultat->enseignant_nom;
      $enseignantPrenom = $resultat->enseignant_prenom;

      

      // On va chercher les informations de la déclaration
      $req_pre = $cnx->prepare("SELECT declaration_id, declaration_date AS declarationDate, duree, hse_declarations.typeactivite_id AS typeActID, typeactivite_libelle AS typeActLibelle, dejaexporte, precisionsAdmin, datetimesaisie, precisionsProf FROM hse_declarations, hse_typeactivite WHERE hse_typeactivite.typeactivite_id = hse_declarations.typeactivite_id AND declaration_id =:decID");
      $req_pre->bindValue(':decID', $decID, PDO::PARAM_STR);
      $req_pre->execute();
      
      $resultat=$req_pre->fetch(PDO::FETCH_OBJ);

      $decDate = $resultat->declarationDate;
      $decDate = toDateFrancais($decDate);
      $typeActID = $resultat->typeActID;
      $typeActLibelle = utf8_encode($resultat->typeActLibelle);
      // Statut modifiable, dejaexporte = 0 : l'enseignant peut tjs modifier on met Oui, sinon dejaexporte vaut 1 on met Non (on a déjà exporté)
      $modifiable = $resultat->dejaexporte;
      if ($modifiable == 0) {$modifiable = 'Oui';} else {$modifiable = 'Non';}
      $duree = $resultat->duree;
      // On récupère les précisions de l'admin 
      $precisionsAdmin = utf8_encode($resultat->precisionsAdmin);
      // OVH met automatiquement les slashes lors de l'insert, on doit retirer les slashes lors du SELECT avec stripslashes
      $precisionsAdmin = stripslashes($precisionsAdmin);
      // On convertit la date de saisie (format datetime MySQL) vers date FR
      $datetimeSaisie = toDateTimeFR($resultat->datetimesaisie);
      // On récupère les précisions du prof lors de la saisie
      $precisionsProf = utf8_encode($resultat->precisionsProf);
      if ($precisionsProf == '') {$precisionsProf = 'Aucunes';}
      // OVH met automatiquement les slashes lors de l'insert, on doit retirer les slashes lors du SELECT avec stripslashes
      $precisionsProf = stripslashes($precisionsProf);


      // On récupère l'id et le sigle de la classe, le sigle va nous être utile pour placer l'attribut selected de la liste déroulante


      $req_pre = $cnx->prepare("SELECT hse_declarations.classe_id, classe_sigle AS clsSigle FROM hse_declarations, hse_classes WHERE hse_classes.classe_id = hse_declarations.classe_id AND declaration_id =:decID");
      $req_pre->bindValue(':decID', $decID, PDO::PARAM_STR);
      $req_pre->execute();
      
      $resultat=$req_pre->fetch(PDO::FETCH_OBJ);

      $classeSigle = $resultat->clsSigle;

      if ($typeActID == 1) {
      /* Si la déclaration à modifier est de type khôlle on affiche le formulaire correspondant au type khôlle */


      /* On récupère le nombre d'étudiants et la durée par étudiant (spécifique au type khôlle) */


      $req_pre = $cnx->prepare("SELECT nbetudiants, dureeparetudiant FROM hse_declarations WHERE declaration_id =:decID");
      $req_pre->bindValue(':decID', $decID, PDO::PARAM_STR);
      $req_pre->execute();
      
      $resultat=$req_pre->fetch(PDO::FETCH_OBJ);

      $nbEtudiants = $resultat->nbetudiants;
      $dureeParEtudiant = $resultat->dureeparetudiant;

      ?>

      <form name="form3" id="form3" action="scriptModifKholle.php" method="post"> 

      <h3>Modification de la déclaration (khôlle) : </h3>

      <p>Déclaration effectuée le : <?php echo $datetimeSaisie; ?>. </p>

      <fieldset>

      <legend> Informations de l'enseignant : </legend>

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

      <legend> Informations de la déclaration : </legend>

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

      <td width="10%">Classe(s) concernée(s) * : <select name="classe" required>

      <?php

      $resultat = $cnx->query("SELECT classe_id AS Cid, classe_libelle AS Clibelle, classe_sigle AS Csigle FROM hse_classes ORDER BY classe_sigle;");
      $resultat->setFetchMode(PDO::FETCH_OBJ);
        
      $ligne = $resultat->fetch();

      while ($ligne) {


      if ($ligne->Csigle == $classeSigle) { echo "<option value=".($ligne->Cid)." selected>".utf8_encode($ligne->Csigle)."</option>"; }
      else
      { echo "<option value=".($ligne->Cid).">".utf8_encode($ligne->Csigle)."</option>"; }
      // récupération de l’enregistrement suivant du jeu d’enregistrements
      $ligne = $resultat->fetch();


      }

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

      <td width="10%">Modifiable par l'enseignant : <?php echo $modifiable; ?></td>

      </tr>


      <tr>

      <td width="10%">Ajouter une remarque visible par l'enseignant : <br/>
      <input type="textarea" name="precisionsAdmin" value="<?php echo $precisionsAdmin; ?>" size="70" >

      </td>

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

      <form name="form2" id="form2" action="scriptModifHSE.php" method="post"> 

      <h3>Modification de la déclaration HSE : </h3>

      <p>Déclaration effectuée le : <?php echo $datetimeSaisie; ?>. </p>

      <fieldset>

      <legend> Informations de l'enseignant : </legend>

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

      <legend> Informations de la déclaration : </legend>

      <table>

      <tr>
      </tr>

      <tr>
      </tr>

      <tr>

      <td width="10%">Date de l'activité * (jj/mm/aaaa) : <input name="date" type="text" pattern="^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[0-2])/20(1[7-9]|2[0-9]|30)$" value=<?php echo $decDate; ?> required> </td>

      </tr>

      <tr>
      <td width="10%">Nature de l'activité * : <select name="typeactivite" required>
      <?php

      $resultat = $cnx->query("SELECT * FROM hse_vue_listesanskholle;");
      $resultat->setFetchMode(PDO::FETCH_OBJ);

      $ligne = $resultat->fetch();

      while ($ligne) {

      if ($ligne->TypeId == $typeActID) {
      echo "<option value=\".$ligne->TypeId.\" selected>".utf8_encode($ligne->TypeLibelle)."</option>";

      }
      else {
      echo "<option value=".$ligne->TypeId.">".utf8_encode($ligne->TypeLibelle)."</option>";
      }
      // récupération de l’enregistrement suivant du jeu d’enregistrements
      $ligne = $resultat->fetch();


      }

      ?>

      </select></td>
      </tr>

      <tr>
      <td width="10%">Commentaire de l'enseignant : <?php echo $precisionsProf; ?>
      </td>
      </tr>


      <tr>

      <td width="10%">Classe(s) concernée(s) * : <select name="classe" required>

      <?php

      $resultat = $cnx->query("SELECT classe_id AS Cid, classe_libelle AS Clibelle, classe_sigle AS Csigle FROM hse_classes ORDER BY classe_sigle;");
      $resultat->setFetchMode(PDO::FETCH_OBJ);
        
      $ligne = $resultat->fetch();

      while ($ligne) {

      if ($ligne->Csigle == $classeSigle) { echo "<option value=".($ligne->Cid)." selected>".utf8_encode($ligne->Csigle)."</option>"; }
      else
      { echo "<option value=".($ligne->Cid).">".utf8_encode($ligne->Csigle)."</option>"; }
      // récupération de l’enregistrement suivant du jeu d’enregistrements
      $ligne = $resultat->fetch();


      }

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
      $nbHeures = (int) $nbHeures;
      $nbMinutes = (int)$i%60;
      if ($nbMinutes == 0) { $dureeToString = $nbHeures."h";}
      else { $dureeToString = $nbHeures."h".$nbMinutes;}

      if ($i == $duree) { echo "<option value=".$i." selected> ".$dureeToString." </option>"; }
      else { echo "<option value=".$i." > ".$dureeToString." </option>"; }
      
      $i = $i + 30;
      }
      ?>
      </select>

      </td>
      </tr>

      <tr>

      <td width="10%">Modifiable par l'enseignant : <?php echo $modifiable; ?> </td>
      
      </tr>



      <tr>

      <td width="10%">Ajouter une remarque visible par l'enseignant : <br/>
      <input type="textarea" name="precisionsAdmin" value="<?php echo $precisionsAdmin; ?>" size="70" >

      </td>

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
