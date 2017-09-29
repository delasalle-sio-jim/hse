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
          <li class="selected"><a>Espace administration</a>
        </ul>
      </div>

    </div> <!-- fin id="header" -->

    <div id="site_content"> <!-- début id="site_content" -->
      <div id="content"> <!-- début id="content" -->
   
      <a href="gestionAdminAppli.php"><img src='../images/back.jpg' height='36' width='36'></a> 

      <h1>Liste des types d'activités : </h1>


      <?php

      $resultat = $cnx->query("SELECT COUNT(*) AS Nb FROM hse_typeactivite;");
      $resultat->setFetchMode(PDO::FETCH_OBJ);
        
      $ligne = $resultat->fetch();

      $countActivites = $ligne->Nb;

      if ($countActivites > 0) {
      /* On a bien des activités présentes dans la bdd, on affiche le tableau */
      
      $lesActivites = $cnx->query("SELECT typeactivite_id AS typeActiviteID, typeactivite_libelle AS activiteLibelle FROM hse_typeactivite;");
      $lesActivites->setFetchMode(PDO::FETCH_OBJ);
      $uneActivite = $lesActivites->fetch();

      // Entête du tableau

      echo "<table class='tableau'> ";
      echo "<thead>
              <tr>
                <td>Libellé de l'activité</td>
                <td>Modifier</td>
                <td>Supprimer</td>
              </tr>
            </thead>";
      echo "<tr></tr>";

      while ($uneActivite) {

      $libelleActivite = utf8_encode($uneActivite->activiteLibelle);
      $libelleActivite = stripslashes($libelleActivite);
      echo "<tr><td> ".$libelleActivite."</td>";
      if ($uneActivite->typeActiviteID == 1) {
      /* On bloque volontairement les modifications et suppressions du type activite : khôlles car une modification entraînerait de multiples erreurs dans l'application */
      echo "<td></td>";
      echo "<td></td>";
      }
      else {
      echo "<td><a href='ajoutModifActivite.php?action=modifier&id=".$uneActivite->typeActiviteID."'><img src='../images/edit.png' height='16' width='16'></a></td>";
      echo "<td><a href='listeActivite.php?action=suppression&id=".$uneActivite->typeActiviteID."'><img src='../images/delete.png' height='16' width='16'></a></td></tr>";
      }

      $uneActivite = $lesActivites->fetch();

      }



      $lesActivites->closeCursor();


      echo "</table>";

      }
      else {
      /* Pas d'activités présentes dans la bdd, on le signale à l'administrateur */
        echo "<p> Aucune activité n'est présente dans la base de données ! </p>";
      }



      ?>


      <?php
      /* Si on cherche à supprimer, on vérifie l'action, le type de l'action et l'id du GET */
      if ( isset($_GET['action']) && $_GET['action']=="suppression" && isset($_GET['id']) ) {

      $typeIDsuppr = $_GET['id'];

      /* On vérifie l'intégrité référentielle, on supprime la classe seulement si elle n'est pas présente dans la table hse_declarations (colonne classe_id) ! */

      $resultat = $cnx->query("SELECT COUNT(*) AS Nb FROM hse_declarations WHERE typeactivite_id = $typeIDsuppr;");
      $resultat->setFetchMode(PDO::FETCH_OBJ);
        
      $ligne = $resultat->fetch();

      $countActivitesSuppr = $ligne->Nb;

          if ($countActivitesSuppr > 0 ) {
          /* Si $countActivitesSuppr > 0 alors classe_id est présent dans la table hse_declarations donc on ne peut pas effectuer l'opération de suppression, MySQL affichera un message d'erreur ! */
          /* On signale à l'administrateur */

          echo "<p><font color='red'> La suppresion du type d'activite n'est pas possible ! <br/> Vous devez d'abord supprimer les déclarations contenant ce type d'activité. </font></p>";
          }
          else { 
          /* Si $countActivitesSuppr = 0 alors on peut effectuer l'opération de suppression */

          // préparation de la requête de recherche dans la table ae_administrateurs
          $txt_req = "DELETE FROM hse_typeactivite WHERE typeactivite_id = :typeactiviteID;";
          $req = $cnx->prepare($txt_req);
          // liaison de la requête et de ses paramètres
          $req->bindValue("typeactiviteID", $typeIDsuppr, PDO::PARAM_STR);
          // extraction des données et comptage des réponses
          $req->execute();

          header("Location:listeActivite.php");

          }
      }

      ?>


      <?php if (isset($_GET['req']) && $_GET['req'] == "ok") {

        echo "<p><font color='green'>Opération effectuée avec succès ! </font></p>";
      }
      elseif (isset($_GET['req']) && $_GET['req'] == "fail") {
         
        echo "<p><font color='red'>Erreur ! </font></p>"; }

      ?>

      <p> Remarque : Le type d'activité : "Khôlle" est volontairement non modifiable. <br/>
      Une modification pourrait provoquer des erreurs dans l'application. </p>
      <p><center><a href='ajoutModifActivite.php?action=ajout'><input type='submit' name='btnConnecter' id='btnConnecter' value='Ajouter une activité'></a></center></p>




      </div> <!-- fin id="content" -->
    </div> <!-- fin id="site_content" -->

    <div id="footer"> 
      <a href="http://www.lycee-delasalle.com/">Lycée De La Salle - RENNES</a>
    </div>

  </div> <!-- fin id="main" -->

</body>
</html>
