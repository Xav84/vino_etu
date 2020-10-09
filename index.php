<?php

/**
 * Fichier de lancement du MVC, Il appel le var.init et le gabarit HTML 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2019-01-21
 * @license Creative Commons BY-NC 3.0 (Licence Creative Commons Attribution - Pas d’utilisation commerciale 3.0 non transposé)
 * @license http://creativecommons.org/licenses/by-nc/3.0/deed.fr
 * 
 */



/***************************************************/
/** Fichier de configuration, contient les define et l'autoloader **/
/***************************************************/
require_once('./dataconf.php');
require_once("./config.php");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");


/***************************************************/
/** Initialisation des variables **/
/***************************************************/
require_once("./var.init.php");

/***************************************************/
/** Démarrage de la session utilisateur **/
/***************************************************/

session_start();

// si la session dure depuis plus de 2h, retour à la page d'accueil (login) :
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 7200)) {
    session_unset();     
    session_destroy();   
    header('Location: http://localhost/vino_etu/'); //redirection vers le login
}
$_SESSION['LAST_ACTIVITY'] = time();


/***************************************************/
/** Démarrage du controleur **/
/***************************************************/
$oCtl = new Controler();
$oCtl->gerer(); // gerer() permet le routage
