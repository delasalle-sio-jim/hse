<?php
// Application HSE
// Auteur : DELAUNAY Pierre
// Dernière mise à jour : 02/08/2017 par Pierre

include_once ('_inc_parametres.php');
include_once ('_inc_connexion.php');

//--------------------------------------- fonctions.php -------------------------------------------------------
// Ce fichier php contient différentes fonctions utiles comparables à des méthodes d'instances voire statiques
// en POO.
//
// La fonction : getTypeUtilisateur utilisée dans la page index.php sert tout simplement à la connexion de 
// l'utilisateur. Cette fonction a besoin de 2 paramètres : le login et le mot de passe et renvoie une chaîne 
// de caractères ("enseignant", "administration", "inconnu").
//
// La fonction : getMois  utilisée dans les pages : ajoutDeclarationHSE.php et ajoutDeclarationKholle.php renvoie
// simplement le libellé du mois en cours (en français).
//
// Les fonctions : getClasseLibelle, getClasseSigle, getTypeActiviteLibelle renvoient une chaîne de caractères en
// fonction de l'id envoyé en paramètre. Ces fonctions sont comparables à des accesseurs (getters) en POO.
//
// Les fonctions : toDateFrancais, toDateUS, toDateTimeFR sont des fonctions de conversions pour MySQL. Format US 
// lors de l'INSERT, UPDATE   Format FR lors du SELECT.
//
//-------------------------------------------------------------------------------------------------------------

function getTypeUtilisateur($login, $motDePasse)
{	

	global $cnx;

	// préparation de la requête de recherche dans la table hse_enseignants
	$txt_req = "SELECT count(*) FROM hse_enseignants WHERE enseignant_login = :login AND enseignant_mdp = :motDePasseChiffre";
	$req = $cnx->prepare($txt_req);
	// liaison de la requête et de ses paramètres
	$req->bindValue("login", $login, PDO::PARAM_STR);
	$req->bindValue("motDePasseChiffre", sha1($motDePasse), PDO::PARAM_STR);
	// extraction des données et comptage des réponses
	$req->execute();
	$nbReponses = $req->fetchColumn(0);
	// libère les ressources du jeu de données
	$req->closeCursor();		
	// fourniture de la réponse
	if ($nbReponses == 1) return "enseignant";

	// préparation de la requête de recherche dans la table hse_administration
	$txt_req = "SELECT count(*) FROM hse_administration WHERE adm_login = :login AND adm_mdp = :motDePasseCrypte";
	$req = $cnx->prepare($txt_req);
	// liaison de la requête et de ses paramètres
	$req->bindValue("login", $login, PDO::PARAM_STR);
	$req->bindValue("motDePasseCrypte", sha1($motDePasse), PDO::PARAM_STR);
	// extraction des données et comptage des réponses
	$req->execute();
	$nbReponses = $req->fetchColumn(0);
	// libère les ressources du jeu de données
	$req->closeCursor();
	// fourniture de la réponse
	if ($nbReponses == 1) return "administration";		

	// si on arrive ici, c'est que l'authentification est incorrecte
	return "inconnu";
}


function getMois() 
{
	$mois = date('m');

	if ($mois == 1) { $moisLibelle = 'Janvier'; return $moisLibelle; }
	elseif ($mois == 2) { $moisLibelle = 'Février'; return $moisLibelle; }
	elseif ($mois == 3) { $moisLibelle = 'Mars'; return $moisLibelle; }
	elseif ($mois == 4) { $moisLibelle = 'Avril'; return $moisLibelle; }
	elseif ($mois == 5) { $moisLibelle = 'Mai'; return $moisLibelle; }
	elseif ($mois == 6) { $moisLibelle = 'Juin'; return $moisLibelle; }
	elseif ($mois == 7) { $moisLibelle = 'Juillet'; return $moisLibelle; }
	elseif ($mois == 8) { $moisLibelle = 'Août'; return $moisLibelle; }
	elseif ($mois == 9) { $moisLibelle = 'Septembre'; return $moisLibelle; }
	elseif ($mois == 10) { $moisLibelle = 'Octobre'; return $moisLibelle; }
	elseif ($mois == 11) { $moisLibelle = 'Novembre'; return $moisLibelle; }
	elseif ($mois == 12) { $moisLibelle = 'Décembre'; return $moisLibelle; }

}

function getClasseLibelle($id)
{

	global $cnx;

	$lesClasses = $cnx->query("SELECT classe_id, classe_libelle AS clsLibelle FROM hse_classes WHERE classe_id = $id;");
    $lesClasses->setFetchMode(PDO::FETCH_OBJ);
    $laClasse = $lesClasses->fetch();

	$classeLibelle = $laClasse->clsLibelle;

	// libère les ressources du jeu de données
	$lesClasses->closeCursor();		
	
	return $classeLibelle;

}

function getClasseSigle($id)
{

	global $cnx;

	$lesClasses = $cnx->query("SELECT classe_id, classe_sigle AS clsSigle FROM hse_classes WHERE classe_id = $id;");
    $lesClasses->setFetchMode(PDO::FETCH_OBJ);
    $laClasse = $lesClasses->fetch();

	$classeSigle = $laClasse->clsSigle;

	// libère les ressources du jeu de données
	$lesClasses->closeCursor();		
	
	return $classeSigle;
}

function getTypeActiviteLibelle($id)
{

	global $cnx;

	$lesTA = $cnx->query("SELECT typeactivite_id, typeactivite_libelle AS TAlib FROM hse_typeactivite WHERE typeactivite_id = $id;");
    $lesTA->setFetchMode(PDO::FETCH_OBJ);
    $leTA = $lesTA->fetch();

	$TAlibelle = $leTA->TAlib;

	// libère les ressources du jeu de données
	$lesTA->closeCursor();		
	
	return utf8_encode($TAlibelle);
}

function toDateFrancais($Date) {
		// extraction des 3 sous-chaines
		$jour = substr($Date,8,2);
		$mois = substr($Date,5,2);
		$annee = substr($Date,0,4);
		// renvoi de la concaténation de la date au format français
		return $jour.'/'.$mois.'/'.$annee;
}

function toDateUS($Date) {
		// extraction des 3 sous-chaines
		$jour = substr($Date,0,2);
		$mois = substr($Date,3,2);
		$annee = substr($Date,6,4);
		// renvoi de la concaténation de la date au format français
		return $annee.'-'.$mois.'-'.$jour;
}

function toDateTimeFR($DateTime) {
		$time = substr($DateTime,11);
		// extraction des 3 sous-chaines
		$jour = substr($DateTime,8,2);
		$mois = substr($DateTime,5,2);
		$annee = substr($DateTime,0,4);
		// renvoi de la concaténation du datetime au format français
		return $jour.'/'.$mois.'/'.$annee.' à '.$time;
}

?>