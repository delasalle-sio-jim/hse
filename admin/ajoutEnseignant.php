<?php
// Application HSE
// Auteur : DELAUNAY Pierre
// Dernière mise à jour : 13/10/2017 par Pierre

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


// fonction qui va nous être utile pour le traitement des chaînes CSV / Source : http://www.commentcamarche.net/forum/affich-3153195-php-trouver-le-ieme-caractere-ds-un-string

function pos_car($chaine,$caractere,$num_occurrence){
    $pos = -1; 
    for ($i=1;$i<=$num_occurrence;$i++){ 
        $pos = strpos($chaine, $caractere, $pos+1); 
    } 
return $pos;
}

?>

<!DOCTYPE HTML>
<html>

<head>
  <title>Application HSE</title>
  <link rel="shortcut icon" href="../images/favicon.ico"/>
  <meta http-equiv="keywords" name="keywords" content="" />
  <meta http-equiv="description" name="description" content="" />
  <meta http-equiv="content-type" content="text/html; charset=utf8" />
  <link rel="stylesheet" type="text/css" href="../style/styleAjoutEnseignant.css" />
  <script>
  $('#file-select-button').click(function(){
    $('.upload input').click();
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
          <li class="selected"><a href="gestionAdmin.php">Menu</a></li>
          <li class="selected"><a href="../index.php?action=deconnexion">Déconnexion</a></li>
          <li class="selected"><a>Espace administration</a>
        </ul>
      </div>

    </div> <!-- fin id="header" -->

    <div id="site_content"> <!-- début id="site_content" -->
      <div id="content"> <!-- début id="content" -->
        <div class="upload">

          <a href="gestionAdminAppli.php"><img src='../images/back.jpg' height='36' width='36'></a> 

		  <h1 class="browse">Ajout d'un enseignant seulement : </h1>
		  
		  <p><center><a href='ajoutEnseignantUnique.php'><input type='submit' name='btnConnecter' id='btnConnecter' value='Ajouter un enseignant'></a></center></p>
		  
		  <br><br><br>
		  <hr>
		  <br><br><br>
		  
          <h1 class="browse">Procédure d'ajout en début d'année : </h1>

          <p><strong> Déroulement de la procédure :</strong> <br/>
          L'ajout des utilisateurs s'effectue avant chaque rentrée scolaire en septembre, de préférence quand la liste des enseignants est complète.
          Il est possible de faire des ajouts en cours d'année si un nouveau enseignant arrive, il faudra simplement importer le fichier CSV avec seulement une ligne.
          </p>



          <p><strong> Première étape : </strong> <br/>
          Avant toute chose, il est nécessaire de supprimer l'ensemble des déclarations. Cette opération est évidemment précédée par un archivage des déclarations effectuées
          sur l'année scolaire précédente.
          </p>



          <form action="scriptSupprEnseignant.php?action=supprDeclarations" method="post">
          <center>
          <p>
          <input type="submit" value="Supprimer" onclick="if(!confirm('Voulez-vous supprimer les déclarations ?')) return false;">
          </p>
          </center>
          </form>

          <?php 
          if (isset($_GET['req']) && $_GET['req'] == 'ok1') {

           echo "<center>Suppresion effectuée avec succès ! </center><br/>"; }

          elseif (isset($_GET['req']) && $_GET['req'] == 'fail1') { echo "<font color='red'>Echec de l'opération de suppression !</font></center> <br/>"; }
          ?>

          <p><strong> Seconde étape : </strong> <br/>
          On vide l'ensemble des enseignants enregistrés dans la base de données. Les enseignants ne pourront plus voir leurs déclarations des années scolaires précedentes.
          Cependant un archivage est prévu par l'administration.
          </p>

          <form action="scriptSupprEnseignant.php?action=supprEnseignants" method="post">
          <center>
          <p>
          <input type="submit" value="Supprimer" onclick="if(!confirm('Voulez-vous supprimer les enseignants ?')) return false;">
          </p>
          </center>
          </form>


          <?php 
          if (isset($_GET['req']) && $_GET['req'] == 'ok2') {

           echo "<center>Suppresion effectuée avec succès ! </center> <br/>"; }

          elseif (isset($_GET['req']) && $_GET['req'] == 'fail2') { echo "<center><font color='red'>Echec de l'opération de suppression !</font></center> <br/>"; }
          ?>

          <p><strong> Troisième étape : </strong> <br/>
          On importe le fichier contenant les informations des enseignants au format CSV grâce à l'outil ci-dessous. L'ajout est ensuite réalisé, les enseignants pourront
          se connecter avec leurs identifiants du lycée. En cas d'oubli ou de perte, il faudra demander à Mme Le Boussard.
          </p>


          <p><strong> Outil d'importation : </strong> <br/></p>

          <form method="post" name="form1" id="form1" action="ajoutEnseignant.php?action=upload" enctype="multipart/form-data">
          
          <input type="file" name="fichierCSV" id="fichierCSV" class="browse" required> <br/><br/>

          <p><strong>Taille maximale :</strong> 600 Ko. <br/>
          <strong>Format supporté :</strong> CSV (séparateurs : point-virgule). </p>

          <br/><br/>
          <center>
          <input type="submit" name="btnConnecter" id="btnConnecter" value="Valider">
          </center>

          </form>

          <?php

          if (isset($_GET['action']) && $_GET['action'] == 'upload') {

            // On récupère le fichier pour le stocker sur le serveur afin de le traiter
            $nomfichier = $_FILES['fichierCSV']['name'];
            // emplacement temporaire durant l'exécution du script
            $fichier = $_FILES['fichierCSV']['tmp_name'];
            // déplacement de son emplacement temporaire sur le serveur vers une destination finale ici le répertoire upload
            move_uploaded_file( $_FILES['fichierCSV']['tmp_name'],"upload/".$_FILES['fichierCSV']['name']);

            echo "<br/><br/>Fichier uploadé sur le serveur avec succès.";
            echo "<br/><br/>Nom du fichier : ".$nomfichier." , ce fichier est stocké dans le dossier admin/upload pour le traitement. <br/><br/> ";

            $nomfichier = 'upload/'.$nomfichier;
            // ouverture du fichier en lecture
            $f=fopen($nomfichier,'rb') ;
            // récupération du contenu du fichier dans le tableau $lignes
            $lignes=file($nomfichier);
            $lignes = array_map("utf8_encode", $lignes);
            // initialisation de l'indice du tableau
            $i=0;
            // compteur ajout réussi
            $compteurAjout = 0;
            // tant que le tableau contient une ligne
            while (!empty($lignes[$i])) {
            // récupération de la ligne en cours dans la variable $uneLigne
            $uneLigne = $lignes[$i];
            // recherche du 1er point virgule
            $pos=strpos($uneLigne,';');

            // vous pouvez décommenter les // echo ... pour débugger si besoin
            // extraction de la sous-chaine nom

            $nom = substr($uneLigne,0,$pos);
            //echo 'Nom : '.$nom.'<br/>';

            // extraction de la sous-chaine prénom

            $pos2=pos_car($uneLigne,';',2);
            $prenom = substr($uneLigne, $pos+1, ($pos2-($pos+1)));
            //echo 'Prénom : '.$prenom.'<br/>';

            // extraction de la sous-chaine login

            $pos3 = strrpos($uneLigne,';');
            $login = substr($uneLigne, $pos2+1, ($pos3 - ($pos2+1)));
            //echo 'Login : '.$login.'<br/>';

            // extraction de la sous-chaine Mot de passe

            $motDePasse = substr($uneLigne, $pos3 + 1, 6);          
            //echo 'Mot de passe : '.$motDePasse.'<br/>';

            // chiffrement du mot de passe en sha1
            $motDePasse = sha1($motDePasse);
            //echo 'Mot de passe chiffré en sha1 : '.$motDePasse.'<br/>'; 


                // préparation de la requête insert dans la table hse_enseignants
                $txt_req = "INSERT INTO hse_enseignants (enseignant_nom, enseignant_prenom, enseignant_login, enseignant_mdp) VALUES (:nom, :prenom, :login, :motDePasse);";
                $req = $cnx->prepare($txt_req);
                // liaison de la requête et de ses paramètres
                $req->bindValue("nom", $nom, PDO::PARAM_STR);
                $req->bindValue("prenom", utf8_decode($prenom), PDO::PARAM_STR);
                $req->bindValue("login", $login, PDO::PARAM_STR);
                $req->bindValue("motDePasse", $motDePasse, PDO::PARAM_STR);
                // extraction des données et comptage des réponses
                $req->execute();

                if ($req == true) { echo "Ajout réussi dans la base pour ".$nom." ".$prenom."<br/>"; $compteurAjout = $compteurAjout+1; }
                else { echo "Ajout échoué dans la base pour ".$nom." ".$prenom."<br/>"; }



            // incrémentation de l'indice du tableau
            echo '<br/>';
            $i=$i+1;
            }
            echo "<br/>".$compteurAjout." enseignants ont été ajoutés ! ";
            // tableau lu entièrement
            // fermeture du fichier
            fclose($f);

            // on supprime le fichier uploadé 
            // SOURCE : https://openclassrooms.com/courses/supprimer-des-fichiers-sur-le-serveur-grace-a-php

            $dossier_traite = "upload";
            
            $repertoire = opendir($dossier_traite); // On définit le répertoire dans lequel on souhaite travailler.

            while (false !== ($fichier = readdir($repertoire))) // On lit chaque fichier du répertoire dans la boucle.
            {
            $chemin = $dossier_traite."/".$fichier; // On définit le chemin du fichier à effacer.
 
            // Si le fichier n'est pas un répertoire…
                if ($fichier != ".." AND $fichier != "." AND !is_dir($fichier))
                {
                unlink($chemin); // On efface.
                echo "<br/><br/> Le fichier ".$fichier." a bien été supprimé dans le dossier upload ! ";
                }
            }
            closedir($repertoire); // Ne pas oublier de fermer le dossier



          }



          ?>








        </div>
      </div> <!-- fin id="content" -->
    </div> <!-- fin id="site_content" -->

    <div id="footer"> 
      <a href="http://www.lycee-delasalle.com/">Lycée De La Salle - RENNES</a>
    </div>

  </div> <!-- fin id="main" -->

</body>
</html>
