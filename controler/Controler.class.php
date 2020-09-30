<?php

/**
 * Class Controler
 * Gère les requêtes HTTP
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2019-01-21
 * @license Creative Commons BY-NC 3.0 (Licence Creative Commons Attribution - Pas d’utilisation commerciale 3.0 non transposé)
 * @license http://creativecommons.org/licenses/by-nc/3.0/deed.fr
 * 
 */

class Controler
{

    /**
     * Traite la requête
     * @return void
     */
    public function gerer()
    {
        switch ($_GET['requete']) {
            case 'autocompleteBouteille':
                $this->autocompleteBouteille();
                break;
            case 'autocompleteBouteilleCellier':
                $this->autocompleteBouteilleCellier();
                break;
            case 'autocompleteBouteilleCatalogue':
                $this->autocompleteBouteilleCatalogue();
                break;
            case 'ajouterNouvelleBouteilleCellier':
                $this->ajouterNouvelleBouteilleCellier();
                break;
            case 'modifierBouteilleCellier':
                $this->modifierBouteilleCellier();
                break;
            case 'ajouterBouteilleCellier':
                $this->ajouterBouteilleCellier();
                break;
            case 'boireBouteilleCellier':
                $this->boireBouteilleCellier();
                break;
            case 'authentification':
                $this->controllerUtilisateur();
                break;
            case 'creerCompte':
                $this->creerCompte();
                break;
            case 'afficherCellier':
                $this->afficherCellier();
                break;
            case 'afficherCatalogue':
                $this->afficherCatalogue();
                break;
            case 'deconnexion':
                $this->deconnexion();
                break;
            case 'supprimerBouteille':
                $this->supprimerBouteille();
                break;
            case 'infoCodeSaq':
                $this->getCodeSaq();
                break;
            case 'afficherInterfaceAdmin':
                $this->afficherInterfaceAdmin();
                break;
            case 'afficherImportation':
                $this->afficherImportation();
                break;
            default:
                $this->accueil();
                break;
        }
    }

    /**
     * Affiche la page d'accueil sur la liste des bouteilles du cellier
     * @return files
     */
    private function accueil()
    {
        /* si l'utilisateur s'est déjà connecté et a une session ouverte : affichage de son cellier. Sinon redirection vers la page de création de compte : */
        if (isset($_SESSION['info_utilisateur'])) {
            $this->afficherCellier($_SESSION['info_utilisateur']['id_utilisateur']);
        } else {
            $this->creerCompte();
        }
    }

    /**
     * Affiche la page de création d'un compte et ajout d'un utilisateur à la base de donnée
     * @return files
     */
    private function creerCompte()
    {


        $body = json_decode(file_get_contents('php://input'));


        if (!empty($body)) {
            $utilisateur = new Utilisateurs();
            $resultat = $utilisateur->ajouterUtilisateur($body);
            echo json_encode($resultat);
        } else {
            // pas d'inclusion de l'entete car on ne veut pas avoir accès au menu
            include("vues/creerCompte.php");
            include("vues/pied.php");
        }
    }

    /**
     * Affiche la page de création d'un compte et ajout d'un utilisateur à la base de donnée
     * @return files
     */
    private function getCodeSaq()
    {


        $body = json_decode(file_get_contents('php://input'));


        if (!empty($body)) {
            $bte = new Bouteille();
            $resultat = $bte->trouverCodeSaq($body);
            // var_dump($resultat);
            echo json_encode($resultat);
        } else {

            include("vues/entete.php");
            include("vues/cellier.php");
            include("vues/pied.php");
        }
    }

    /**
     * Affiche l'interface administrateur
     * @return files
     */
    private function afficherInterfaceAdmin()
    {
        if ($_SESSION['info_utilisateur']['type_utilisateur'] == 1) {
            $utilisateur = new Utilisateurs();
            $data = $utilisateur->getListeUtilisateur();
            $dataNbUtilisateur = $utilisateur->getNombreUtilisateur();
            include("vues/entete.php");
            include("vues/interfaceAdmin.php");
            include("vues/pied.php");
        }
    }

    /**
     * Affiche la page d'importation de bouteilles
     * @return files
     */
    private function afficherImportation()
    {
        if ($_SESSION['info_utilisateur']['type_utilisateur'] == 1) {

            include("vues/entete.php");
            include("vues/updateSAQ.php");
        }
    }

    /**
     * Affiche la liste des bouteilles du cellier d'un utilisateur
     * @return files
     */
    private function afficherCellier($id_utilisateur = '')
    {
        if (isset($_POST['type']) && isset($_POST['ordre'])) {
            $type = $_POST['type'];
            $ordre = $_POST['ordre'];
            $bte = new Bouteille();

            // var_dump($type, $ordre);
            // exit;
            $data = $bte->getListeBouteilleCellierTri($type, $ordre, $_SESSION['info_utilisateur']['id']); //$_SESSION['info_utilisateur']['id'] = id du cellier
            include("vues/entete.php");
            include("vues/cellier.php");
            include("vues/pied.php");
        } else if (isset($_POST['recherche'])) {
            $recherche = $_POST['nom_bouteille_cellier'];
            $bte = new Bouteille();

            // var_dump($recherche);
            // exit;
            $data = $bte->getRechercheBouteilleCellier($recherche, $_SESSION['info_utilisateur']['id']); //$_SESSION['info_utilisateur']['id'] = id du cellier
            include("vues/entete.php");
            include("vues/cellier.php");
            include("vues/pied.php");
        } else {
            $bte = new Bouteille();
            $data = $bte->getListeBouteilleCellier($_GET['id_utilisateur'] ?? $id_utilisateur);
            include("vues/entete.php");
            include("vues/cellier.php");
            include("vues/pied.php");
        }
    }

    /**
     * Affiche la liste des bouteilles du cellier d'un utilisateur
     * @return files
     */
    private function afficherCatalogue()
    {
        if (isset($_POST['type']) && isset($_POST['ordre'])) {
            $type = $_POST['type'];
            $ordre = $_POST['ordre'];
            $bte = new Bouteille();

            // var_dump($type, $ordre);
            // exit;
            $data = $bte->getListeBouteilleCatalogueTri($type, $ordre);
            include("vues/entete.php");
            include("vues/catalogue.php");
            include("vues/pied.php");
        } else if (isset($_POST['recherche'])) {
            $recherche = $_POST['nom_bouteille_catalogue'];
            $bte = new Bouteille();

            // var_dump($recherche);
            // exit;
            $data = $bte->getRechercheBouteilleCatalogue($recherche);
            include("vues/entete.php");
            include("vues/catalogue.php");
            include("vues/pied.php");
        } else {
            $bte = new Bouteille();
            $data = $bte->getListeBouteille();
            include("vues/entete.php");
            include("vues/catalogue.php");
            include("vues/pied.php");
        }
    }

    /**
     * Récupère les résultats de la recherche d'auto-complétion.
     * @return json
     */
    private function autocompleteBouteille()
    {
        $bte = new Bouteille();
        // var_dump(file_get_contents('php://input'));
        $body = json_decode(file_get_contents('php://input'));
        // var_dump($body);
        $listeBouteille = $bte->autocomplete($body->nom);

        echo json_encode($listeBouteille);
    }

    /**
     * Récupère les résultats de la recherche d'auto-complétion.
     * @return json
     */
    private function autocompleteBouteilleCellier()
    {
        $bte = new Bouteille();
        // var_dump(file_get_contents('php://input'));
        $body = json_decode(file_get_contents('php://input'));
        // var_dump($body);
        $listeBouteille = $bte->autocompleteCellier($body->nom, $_SESSION['info_utilisateur']['id']); //$_SESSION['info_utilisateur']['id'] = id du cellier

        echo json_encode($listeBouteille);
    }

    /**
     * Récupère les résultats de la recherche d'auto-complétion dans le catalogue.
     * @return json
     */
    private function autocompleteBouteilleCatalogue()
    {
        $bte = new Bouteille();
        // var_dump(file_get_contents('php://input'));
        $body = json_decode(file_get_contents('php://input'));
        // var_dump($body);
        $listeBouteille = $bte->autocompleteCatalogue($body->nom);

        echo json_encode($listeBouteille);
    }

    /**
     * Récupère les informations sur la bouteille à ajouter et déclenche la requete sql d'ajout.
     * Si php://input est vide, affiche le formulaire d'ajout de bouteille
     * @return mixted
     */
    private function ajouterNouvelleBouteilleCellier()
    {
        $body = json_decode(file_get_contents('php://input'));


        if (!empty($body)) {
            // var_dump($body);
            $bte = new Bouteille();
            $resultat = $bte->ajouterBouteilleCellier($body);
            echo json_encode($resultat);
        } else {
            include("vues/entete.php");
            include("vues/ajouter.php");
            include("vues/pied.php");
        }
    }

    /**
     * Récupère les informations sur la bouteille à modifier et déclenche la requete sql de modification.
     * Si php://input est vide, affiche la page de modification de bouteille
     * @return mixted
     */
    private function modifierBouteilleCellier()
    {
        $body = json_decode(file_get_contents('php://input'));

        if (!empty($body)) {
            $bte = new Bouteille();
            $resultat = $bte->modifierInfoBouteilleCellier($body);
            echo json_encode($resultat);
        } else {
            $bte = new Bouteille();
            $data = $bte->getBouteilleCellier($_GET['id'], $_GET['cellier']);
            include("vues/entete.php");
            include("vues/modifier.php");
            include("vues/pied.php");
        }
    }

    /**
     * Récupère les informations sur la bouteille dont la quantité doit être modifiée et déclenche la requete sql de modification de quantité avec -1.
     * @return json
     */
    private function boireBouteilleCellier()
    {
        $body = json_decode(file_get_contents('php://input'));

        $bte = new Bouteille();
        $resultat = $bte->modifierQuantiteBouteilleCellier($body->id_bouteille, $body->id_cellier, -1);
        echo json_encode($resultat);
    }

    /**
     * Récupère les informations sur la bouteille dont la quantité doit être modifiée et déclenche la requete sql de modification de quantité avec +1.
     * @return json
     */
    private function ajouterBouteilleCellier()
    {
        $body = json_decode(file_get_contents('php://input'));

        $bte = new Bouteille();
        $resultat = $bte->modifierQuantiteBouteilleCellier($body->id_bouteille, $body->id_cellier, 1);
        echo json_encode($resultat);
    }


    /**
     * Récupère les informations d'authentification de l'utilisateur et déclenche la requete sql de controle de l'utlisateur.
     * @return json
     */
    private function controllerUtilisateur()
    {

        $body = json_decode(file_get_contents('php://input'));
        if (!empty($body)) {
            $utilisateur = new Utilisateurs();
            $resultat = $utilisateur->controllerUtilisateur($body->courriel, $body->mdp);
            echo json_encode($resultat);
        } else {
            // pas d'inclusion de l'entete car on ne veut pas avoir accès au menu
            include("vues/authentification.php");
            include("vues/pied.php");
        }
    }

    /**
     * Déconnexion d'un compte
     * @return files
     */
    private function deconnexion()
    {
        session_unset();
        session_destroy();
        $this->controllerUtilisateur();
    }


    /**
     * Récupère l'id de la bouteille à supprimer et déclenche la requete sql de suppression.
     * @return json
     */
    private function supprimerBouteille()
    {


        $body = json_decode(file_get_contents('php://input'));
        $bte = new Bouteille();
        $resultat = $bte->supprimerBouteilleCellier($_GET['id'], $_GET['cellier']);
        echo json_encode($resultat);
    }
}
