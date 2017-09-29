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

      $req_pre = $cnx->prepare("SELECT declaration_id, declaration_date AS declarationDate, duree, hse_declarations.typeactivite_id AS typeActID, typeactivite_libelle AS typeActLibelle, precisionsAdmin, datetimeSaisie, precisionsProf FROM hse_declarations, hse_typeactivite WHERE hse_typeactivite.typeactivite_id = hse_declarations.typeactivite_id AND declaration_id =:decID");
      $req_pre->bindValue(':decID', $decID, PDO::PARAM_STR);
      $req_pre->execute();
      
      $resultat=$req_pre->fetch(PDO::FETCH_OBJ);

      $decDate = $resultat->declarationDate;
      $decDate = toDateFrancais($decDate);
      $typeActID = $resultat->typeActID;
      $typeActLibelle = utf8_encode($resultat->typeActLibelle);
      $duree = $resultat->duree;
      $precisionsAdmin = utf8_encode($resultat->precisionsAdmin);
      $precisionsAdmin = stripslashes($precisionsAdmin);
      if ($precisionsAdmin == '') {$precisionsAdmin = 'Aucun';}
      $datetimeSaisie = toDateTimeFR($resultat->datetimeSaisie);
      $precisionsProf = utf8_encode($resultat->precisionsProf);
      if ($precisionsProf == '') {$precisionsProf = 'Aucun';}
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


      <h3>Récapitulatif de votre déclaration (khôlle) : </h3>

      <p>Vous avez effectué votre déclaration le : <?php echo $datetimeSaisie; ?>. </p>

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

      <td width="10%">Date de l'activité (jj/mm/aaaa) : <?php echo $decDate; ?> </td>

      </tr>

      <tr>

      <tr>
      <td width="10%">Nature de l'activité : Khôlle </td>
      </tr>

      <td width="10%">Classe(s) concernée(s) : <?php echo $classeSigle; ?></td>
      
      </tr>

      <tr>
      <td width="10%">Nombre d'étudiant(s) : <?php echo $nbEtudiants; ?> </td>
      </tr>

      <tr>
      <td width="10%">Durée par étudiant (en minutes) : <?php echo $dureeParEtudiant; ?> </td>
      </tr>

      <tr>
      
      </tr>


      </table>
      
      </fieldset>


      <br/><br/>

      <fieldset>
      <table>
      <legend> Administration : </legend>

      <tr> 
      </tr>

      <tr>
      <td width="10%">Commentaire de l'administration : <br/> <?php echo $precisionsAdmin; ?> </td>
      </tr>      


      </fieldset>
      </table>


      <?php
      }
      else { 
      /* Sinon on affiche le formulaire correspondant au type HSE */




        ?>

      <a href="historiqueDeclaration.php"><img src='images/back.jpg' height='36' width='36'></a>

      <h3>Récapitulatif de votre déclaration (HSE) : </h3>

      <p>Vous avez effectué votre déclaration le : <?php echo $datetimeSaisie; ?>. </p>

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

      <td width="10%">Date de l'activité (jj/mm/aaaa) : <?php echo $decDate; ?> </td>

      </tr>

      <tr>
      <td width="10%">Nature de l'activité : <?php echo $typeActLibelle; ?> </td>
      </tr>

      <tr>
      <td width="10%">Commentaire : <?php echo $precisionsProf; ?> </td>
      </tr>


      <tr>
      <td width="10%">Classe(s) concernée(s) : <?php echo $classeSigle; ?> </td>
      </tr>



      <tr>
      <td width="10%">Durée de l'activité (en minutes) : <?php echo $duree; ?> </td>
      </tr>

      <tr>   
      </tr>

      </table>
      
      </fieldset>

      <br/><br/>


      <fieldset>
      <table>
      <legend> Administration : </legend>

      <tr> 
      </tr>

      <tr>
      <td width="10%">Commentaire de l'administration : <br/> <?php echo $precisionsAdmin; ?> </td>
      </tr>      


      </fieldset>
      </table>



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
