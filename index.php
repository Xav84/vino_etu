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
   
    /***************************************************/
    /** Démarrage du controleur **/
    /***************************************************/
	$oCtl = new Controler();
	$oCtl->gerer(); // gerer() permet le routage
