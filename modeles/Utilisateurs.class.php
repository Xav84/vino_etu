<?php

/**
 * Class Utilisateurs
 * Classe qui gère les données et le côntrole des utilisateurs
 *
 */
class Utilisateurs extends Modele
{

    private $erreurs = []; //tableau pour récupérer les erreurs lors de la vérifications des données


    /**
     * Cette méthode retourne la liste des utilisateurs contenues dans la base de données
     * 
     * @return Array $rows contenant tous les utilisateurs.
     */
    public function getListeUtilisateur()
    {

        $rows = array();
        $res = $this->_db->query('SELECT * FROM vino__utilisateur WHERE type_utilisateur = 2');
        if ($res->num_rows) {
            while ($row = $res->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /**
     * Cette méthode retourne la liste des utilisateurs contenues dans la base de données
     * 
     * @return Array $rows contenant tous les utilisateurs.
     */
    public function getNombreUtilisateur()
    {

        $rows = array();
        $res = $this->_db->query('SELECT COUNT(id_utilisateur) FROM vino__utilisateur WHERE type_utilisateur = 2');
        if ($res->num_rows) {
            while ($row = $res->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /**
     * Fonction controlerUtilisateur : contrôler l'authentification de l'utilisateur dans la table vino__utilisateur
     * $identifiant
     * $mot_de_passe
     * Valeurs de retour : 1 si utilisateur avec $identifiant et $mot_de_passe trouvé, 0 sinon
     */

    function controllerUtilisateur($courriel, $mot_de_passe)
    {
        $reponse = ['erreurs' => null, 'data' => null];

        $req = "SELECT * FROM vino__utilisateur u
        WHERE courriel_utilisateur='$courriel' AND password_utilisateur = SHA2('$mot_de_passe', 256)";

        //Validation des données :
        /* courriel : */
        if (!filter_var($courriel, FILTER_VALIDATE_EMAIL)) {
            $this->erreurs['courriel'] = "Veuillez entrer un courriel valide.";
        }

        /* mot de passe : */
        if (!$mot_de_passe) {
            $this->erreurs['mdp'] = "Veuillez entrer votre mot de passe.";
        }

        if (empty($this->erreurs)) {
            if ($result = $this->_db->query($req)) {
                $reponse['data'] = $result->fetch_assoc();

                //Si l'utilisateur n'est pas administrateur, on récupère les infos de son cellier pour les mettre en variable de session :
                if ($reponse['data']['type_utilisateur'] == 2) {
                    $req_cellier = "SELECT * FROM vino__cellier
                    WHERE fk_id_utilisateur =" . $reponse['data']['id_utilisateur'];

                    if ($result_cellier = $this->_db->query($req_cellier)) {
                        foreach ($res = $result_cellier->fetch_assoc() as $key => $value)
                            $reponse['data'][$key] = $value;
                    }
                }
                $_SESSION['info_utilisateur'] =  $reponse['data'];
            }
        } else {
            $reponse['erreurs'] = $this->erreurs;
        }

        //Garder le courriel pour le pré-remplir à la création de compte en cas d'un ereur d'authentification de type "Aucun compte lié à ce courriel"
        if ($reponse['data'] == null) {
            $_SESSION['courriel_creation_compte'] = $courriel;
        }

        return $reponse;
    }



    /**
     * Cette méthode ajoute un utilisateur à la base de données
     * 
     * @param Object $data Tableau des données représentants l'utilisateur'.
     * 
     * @return Array contenant la retour de la requete SQL ($reponse['data']) et les éventuelles erreurs de formulaire $reponse['erreurs].
     */
    public function ajouterUtilisateur($data)
    {
        $reponse = ['erreurs' => null, 'data' => null, 'existant' => null];
        //Validation des données :
        /* prenom */
        if (!preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/", $data->prenom)) {
            $this->erreurs['prenom'] = "Veuillez entrer un prénom valide.";
        }

        /* nom */
        if (!preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/", $data->nom)) {
            $this->erreurs['nom'] = "Veuillez entrer un nom valide.";
        }

        /* courriel : */
        if (!filter_var($data->courriel, FILTER_VALIDATE_EMAIL)) {
            $this->erreurs['courriel'] = "Veuillez entrer un courriel valide.";
        }

        /* mot de passe : */
        if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{6,}$/", $data->mdp)) {
            $this->erreurs['mdp'] = "Votre mot de passe doit contenir 6 caractères ou plus et au moins une lettre, un nombre et un caractère spécial.";
        }

        if (empty($this->erreurs)) {
            //requete pour vérifier si l'utilisateur existe déjà dans la base de donnée (via son courriel) :
            $sql = "SELECT * FROM vino__utilisateur WHERE courriel_utilisateur= " . "'" . $data->courriel . "'";

            //Si l'utilisateur existe déjà, on affiche un message d'erreur :
            if ($this->_db->query($sql)->num_rows > 0) {
                $reponse['existant'] = true;
            } else {
                //si l'utilisateur n'existe pas déjà, on crée un nouvel utilisateur dans la base :
                $requete = "INSERT INTO vino__utilisateur (prenom_utilisateur, nom_utilisateur, password_utilisateur, courriel_utilisateur, type_utilisateur) VALUES (?,?,SHA2(?, 256),?,2)"; //type utilisateur 2 = non admin
                $stmt = $this->_db->prepare($requete);
                $stmt->bind_param('ssss', $data->prenom, $data->nom, $data->mdp, $data->courriel);
                $reponse['data'] = $stmt->execute();
                //renvoi email et mdp pour connexion immédiate après création du compte :
                $reponse['email'] = $data->courriel;
                $reponse['mdp'] = $data->mdp;
                //si l'ajout du nouvel utilisateur s'est bien passé, on créé un nouveau cellier relié à l'id du nouvel utilisateur :
                if ($reponse['data'] === true) {
                    $requete2 = "INSERT INTO vino__cellier (date_creation_cellier, notes_cellier, fk_id_utilisateur) VALUES ('" . date('Y-m-d') . "', 'Mon premier Cellier', " . $this->_db->insert_id . ")";
                    $reponse['data'] = $this->_db->query($requete2);
                }
            }
        } else {
            $reponse['erreurs'] = $this->erreurs;
        }

        return $reponse;
    }
}
// $_SESSION['info_utilisateur']['type_utilisateur'] == 2