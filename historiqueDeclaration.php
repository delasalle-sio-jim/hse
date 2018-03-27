<?php
// Application HSE
// Auteur : DELAUNAY Pierre
// Dernière mise à jour : 11/11/2017 par Pierre

// ouverture d'une session
session_start();  
// inclusion des paramètres et de la bibliothéque de fonctions ("include_once" peut être remplacé par "require_once")
include_once ('include/_inc_parametres.php');
// connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
include_once ('include/_inc_connexion.php');
// fonctions
include('include/fonctions.php');
?>

<?php


// Récupération de l'id, du nom et du prénom de l'enseignant à partir du $_SESSION['login'];

$login = $_SESSION['login'];


$req_pre = $cnx->prepare("SELECT enseignant_id, enseignant_nom, enseignant_prenom FROM hse_enseignants WHERE enseignant_login =:login");
$req_pre->bindValue(':login', $login, PDO::PARAM_STR);
$req_pre->execute();
      
$resultat=$req_pre->fetch(PDO::FETCH_OBJ);

$enseignantID = $resultat->enseignant_id;
$enseignantNom = $resultat->enseignant_nom;
$enseignantPrenom = $resultat->enseignant_prenom;



if ( isset($_GET['action']) && $_GET['action']=="suppression" && isset($_GET['id']) ) {

$declID = $_GET['id'];

// préparation de la requête de recherche dans la table ae_administrateurs
$txt_req = "DELETE FROM hse_declarations WHERE declaration_id = :declID AND enseignant_id = :ensID";
$req = $cnx->prepare($txt_req);
// liaison de la requête et de ses paramètres
$req->bindValue("declID", $declID, PDO::PARAM_STR);
$req->bindValue("ensID", $enseignantID, PDO::PARAM_STR);
// extraction des données et comptage des réponses
$req->execute();

header("Location:historiqueDeclaration.php");

}
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

#pagination{
	/*background-color: #eaeaea; */
	padding: 10px;
}

#pagination .active{
	background-color: #012;
	color: #FFF;
	padding: 0px 5px 0px 5px;
	border-radius: 20%;
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
          <li class="selected"><a href="gestionEnseignant.php">Menu</a></li>
          <!--<li><a href="">Page 1</a></li>-->
          <li class="selected"><a href="index.php?action=deconnexion">Déconnexion</a></li>
          <li class="selected"><a>Espace enseignant</a>
        </ul>
      </div>

    </div> <!-- fin id="header" -->

    <div id="site_content"> <!-- début id="site_content" -->
      <div id="content"> <!-- début id="content" -->
   
      <a href="gestionEnseignant.php"><img src='images/back.jpg' height='36' width='36'></a>

      <h3>Historique des déclarations réalisées : </h3>

	  <p><a href="ajoutDeclarationKholle.php">Déclarer des heures d'interrogations orales (khôlles)</a>
	  <br/><a href="ajoutDeclarationHSE.php">Déclarer des heures supplémentaires (HSE)</a>
	  </p>
	  
	  
      <?php 

      // Récupération de l'année scolaire à partir de la table HSE_PARAMETRES

      $resultat = $cnx->query("SELECT anneescolaire FROM hse_parametres;");
      $resultat->setFetchMode(PDO::FETCH_OBJ);
        
      $ligne = $resultat->fetch();

      $anneescolaire = $ligne->anneescolaire;



      // On vérifie si l'enseignant a déjà effectué une déclaration


      $req_pre = $cnx->prepare("SELECT COUNT(*) AS nbDeclarations FROM hse_declarations, hse_enseignants WHERE hse_declarations.enseignant_id = hse_enseignants.enseignant_id AND enseignant_login =:login");
      $req_pre->bindValue(':login', $login, PDO::PARAM_STR);
      $req_pre->execute();
      
      $resultat=$req_pre->fetch(PDO::FETCH_OBJ);

      $nbDeclarations = $resultat->nbDeclarations;



      if ($nbDeclarations > 0) {
      // Si l'enseignant a déjà effectué une ou des déclarations alors on les affiche, le bouton modifier et le bouton supprimer apparaissent si et seulement si dejaExporter est égal à 0 (= false)

      // Pagination
          
      $lesDeclarations = $cnx->query("SELECT declaration_id AS decId, enseignant_id AS ensId, classe_id AS clsId, typeactivite_id AS typeId, Duree, Declaration_date AS decDate, DejaExporte from hse_declarations where enseignant_id = $enseignantID;");
      $nbTotalDeclarations = $lesDeclarations->rowCount();
      

      $nbDeclarationsParPage = 15; 
      $nbre_pages_max_gauche_et_droite = 4;
      
      $last_page = ceil($nbTotalDeclarations / $nbDeclarationsParPage);
      
      if(isset($_GET['page']) && is_numeric($_GET['page'])){
          $page_num = $_GET['page'];
      } else {
          $page_num = 1;
      }
      
      if($page_num < 1){
          $page_num = 1;
      } else if($page_num > $last_page) {
          $page_num = $last_page;
      }
      
      $limit = 'LIMIT '.($page_num - 1) * $nbDeclarationsParPage. ',' . $nbDeclarationsParPage;
      
      $pagination = '';
      
      if($last_page != 1){
          if($page_num > 1){
              $previous = $page_num - 1;
              $pagination .= '<a href="historiqueDeclaration.php?page='.$previous.'">Précédent</a> &nbsp; &nbsp;';
              
              for($i = $page_num - $nbre_pages_max_gauche_et_droite; $i < $page_num; $i++){
                  if($i > 0){
                      $pagination .= '<a href="historiqueDeclaration.php?page='.$i.'">'.$i.'</a> &nbsp;';
                  }
              }
          }
          
          $pagination .= '<span class="active">'.$page_num.'</span>&nbsp;';
          
          for($i = $page_num+1; $i <= $last_page; $i++){
              $pagination .= '<a href="historiqueDeclaration.php?page='.$i.'">'.$i.'</a> ';
              
              if($i >= $page_num + $nbre_pages_max_gauche_et_droite){
                  break;
              }
          }
          
          if($page_num != $last_page){
              $next = $page_num + 1;
              $pagination .= '<a href="historiqueDeclaration.php?page='.$next.'">Suivant</a> ';
          }
      }
      
      $lesDeclarations = $cnx->query("SELECT declaration_id AS decId, enseignant_id AS ensId, classe_id AS clsId, typeactivite_id AS typeId, Duree, Declaration_date AS decDate, DejaExporte from hse_declarations where enseignant_id = $enseignantID order by datetimesaisie desc $limit;");
      $lesDeclarations->setFetchMode(PDO::FETCH_OBJ);
      $laDeclaration = $lesDeclarations->fetch();
      
      // Entête du tableau

      echo "<table class='tableau'> ";
      echo "<thead>
              <tr>
                <td>Date</td>
                <td>Classe</td>
                <td>Activité</td>
                <td>Durée <br/>(<font size='2'>en minutes</font>)</td>
                <td>Voir</td>
                <td>Modifier</td>
                <td>Supprimer</td>
              </tr>
            </thead>";
      echo "<tr></tr>";

      while ($laDeclaration){

      echo "<tr><td> ".toDateFrancais($laDeclaration->decDate)."</td>";
      echo "<td> ".getClasseSigle($laDeclaration->clsId)."</td>";
      echo "<td>".getTypeActiviteLibelle($laDeclaration->typeId)."</td>";
      echo "<td>".($laDeclaration->Duree)."</td>";
      echo "<td><a href='vueDeclaration.php?id=".$laDeclaration->decId."'><img src='images/voir.png' height='16' width='16'></a></td>";

          if ($laDeclaration->DejaExporte == 0) {
          /* La liste n'a pas été encore exporté, l'enseignant peut modifier et supprimer une déclaration (en cas d'erreur...) */
          echo "<td><a href='modifierDeclaration.php?id=".$laDeclaration->decId."'><img src='images/edit.png' height='16' width='16'></a></td>";
          echo "<td><a href='historiqueDeclaration.php?action=suppression&id=".$laDeclaration->decId."'><img src='images/delete.png' height='16' width='16'></a></td>";
          }
          else
          {
          /* DejaExporte vaut 1 (soit true), l'exportation a déjà été effectué ==> on bloque la modification et la suppression */
          echo "<td> </td>";
          echo "<td> </td>";
          }

      


          $laDeclaration = $lesDeclarations->fetch();

      }

      $lesDeclarations->closeCursor();
      


      echo '<div id="pagination">'.$pagination.'</div>';
      ?>
      </table>
      <?php
      }
      else {
      // L'enseignant n'a pas encore effectué de déclarations, on affiche donc pas le tableau récap', on propose seulement un bouton retour menu

      echo "<p> Vous n'avez pas encore effectué de déclarations ! <p>";
      echo "<p><a href='gestionEnseignant.php'><input type='submit' name='btnConnecter' id='btnConnecter' value='Retour'></a></p>";



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
