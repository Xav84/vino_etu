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
        $res = $this->_db->query('SELECT * FROM vino__utilisateur u
        INNER JOIN vino__cellier c ON c.fk_id_utilisateur = u.id_utilisateur
        WHERE type_utilisateur = 2');
        if ($res->num_rows) {
            while ($row = $res->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /**
     * Cette méthode retourne les infos contenues dans la table vino_cellier relié à un utilisateur donné
     * 
     * @return Array $rows contenant tous les infos.
     */
    public function getInfoCellierUtilisateur($id_utilisateur)
    {

        $rows = array();
        $res = $this->_db->query('SELECT * FROM vino__cellier WHERE fk_id_utilisateur = ' . $id_utilisateur);
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



    /**
     * Cette méthode retourne les informations d'un utilisateur à partir de son email
     * 
     * @param String $email l'email de l'utilisateur' connecté.
     * 
     * @return Array contenant la retour de la requete SQL ($reponse['data'])
     */
    public function getUtilisateur($email)
    {
        $reponse = ["data" => null];
        $sql = "SELECT * FROM vino__utilisateur
        WHERE courriel_utilisateur = ?";

        $stmt = $this->_db->prepare($sql);
        $stmt->bind_param('s', $email);
        $reponse['data'] = $stmt->execute();

        //si la requete s'est bien exécutée :
        if ($reponse['data'] === true) {
            $reponse['utilisateur'] = $stmt->get_result()->fetch_assoc();
        }
        return $reponse;
    }


    /**
     * Cette méthode modifie l'intitulé du cellier d'un utilisateur
     * 
     * @param String $id_utilisateur l'id de l'utilisateur' connecté.
     * 
     * @return Array contenant la retour de la requete SQL ($reponse['data'])
     */
    public function modifierIntituleCellier($id_cellier, $nouvelIntitule)
    {
        $reponse = ["data" => null];
        $sql = "UPDATE vino__cellier SET notes_cellier = ? WHERE id = ?";

        $stmt = $this->_db->prepare($sql);
        $stmt->bind_param('si', $nouvelIntitule, $id_cellier);

        //si la requete s'est bien exécutée :
        if ($stmt->execute()) {
            $reponse['data'] = true;
            /* si la requête de modification s'est bien passée, remplacer l'ancien intitulé du celleir par le nouveau dans la variable de session : */
            $_SESSION['info_utilisateur']['notes_cellier'] = $nouvelIntitule;
        }
        return $reponse;
    }

    /**
     * Cette méthode modifie l'email de l'utilisateur dans la base de données
     * 
     * @param String : 
     * $ancienEmail l'ancien email de l'utilisateur connecté.
     * $nouvelEmail le nouvel email de l'utilisateur.
     * 
     * @return Array $reponse contenant la retour de la requete SQL, les eventuelles erreurs et le nouvel email
     */

    public function modifierEmailUtilisateur($ancienEmail, $nouvelEmail)
    {
        $reponse = ["data" => null, "erreurs" => null, "nouvelEmail" => $nouvelEmail];

        /* validation des données :*/
        /* courriel : */
        if (!filter_var($nouvelEmail, FILTER_VALIDATE_EMAIL)) {
            $this->erreurs['courriel'] = "Veuillez entrer un courriel valide.";
        }

        if (empty($this->erreurs)) {
            /* Vérifier si l'adresse email existe déjà dans la base de données : */
            $reponse["data"] = $this->_db->query("SELECT * FROM vino__utilisateur WHERE courriel_utilisateur = '" . $nouvelEmail . "'")->num_rows;


            /* Si l'adresse n'existe pas déjà on remplace l'ancien email par le nouveau, sinon affichage d'une erreur : */
            if ($reponse["data"] === 0) {
                $sql = "UPDATE vino__utilisateur SET courriel_utilisateur = '" . $nouvelEmail . "' WHERE courriel_utilisateur = '" . $ancienEmail . "'";
                $reponse['data'] = $this->_db->query($sql);

                /* si la requête de modification s'est bien passée, remplacer les infos de courriel dans la variable de session : */
                if ($reponse['data'] === true) {
                    $_SESSION['info_utilisateur']['courriel_utilisateur'] = $nouvelEmail;
                }
            } else {
                $reponse['erreurs']['existant'] = "Ce courriel existe déjà";
            }
        } else {
            $reponse['erreurs'] = $this->erreurs;
        }

        return $reponse;
    }


    /**
     * Cette méthode modifie le mot de passe de l'utilisateur dans la base de données
     * 
     * @param String $ancienMdp le mot de passe de l'utilisateur connecté.
     * 
     * @return Array $reponse contenant la retour de la requete SQL, les eventuelles erreurs
     */
    public function modifierMdpUtilisateur($ancienMdp, $nouveauMdp, $confirmationMdp, $email)
    {
        $reponse = ["data" => null, "erreurs" => null];

        /* validation des données :*/
        /* mot de passe au bon format: */
        if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{6,}$/", $nouveauMdp)) {
            $this->erreurs['mdpInvalide'] = "Votre mot de passe doit contenir 6 caractères ou plus et au moins une lettre, un nombre et un caractère spécial.";
        }

        /* l'ancien mot de passe est le bon : */
        if (hash("sha256", $ancienMdp) !== $_SESSION['info_utilisateur']['password_utilisateur'])
            $this->erreurs['mauvaisMdp'] = "Le mot de passe entré est incorrect.";

        /* Le nouveau mot de passe et la confirmation sont identiques : */
        if ($nouveauMdp !== $confirmationMdp)
            $this->erreurs['mdpDifferent'] = "Les deux mots de passe entrés sont différents.";


        if (empty($this->erreurs)) {
            $sql = "UPDATE vino__utilisateur SET password_utilisateur = SHA2('" . $nouveauMdp . "', 256) WHERE courriel_utilisateur = '" . $email . "'";
            $reponse['data'] = $this->_db->query($sql);

            /* si la requête de modification s'est bien passée, remplacer les infos de mode de passe dans la variable de session : */
            if ($reponse['data'] === true) {
                $_SESSION['info_utilisateur']['password_utilisateur'] = hash('sha256', $nouveauMdp);
            }
        } else {
            $reponse['erreurs'] = $this->erreurs;
        }

        return $reponse;
    }


    /**
     * Cette méthode supprime le compte de l'utilisateur dans la base de données
     * 
     * @param String $email l'adresse email de l'utilisateur connecté.
     * 
     * @return Array $reponse contenant la retour de la requete SQL
     */
    public function supprimerCompteUtilisateur($id_utilisateur, $id_cellier, $type_utlisateur)
    {
        $reponse = ["data" => false, "rollback" => null];

        try {
            // Début de la transaction :
            $this->_db->begin_transaction();

            //Si l'utilisateur n'est pas administrateur, on supprime les bouteilles dans le cellier et le cellier :
            if ($type_utlisateur == 2) {
                //1-supprimer les bouteilles du cellier :
                $sql1 = "DELETE FROM cellier__bouteille WHERE vino__cellier_id =" . $id_cellier;
                if ($this->_db->query($sql1)) $reponse['data'] = true;
                else throw new Exception("Erreur lors de la suppression des bouteilles du cellier.");

                //2-supprimer le cellier :
                $sql2 = "DELETE FROM vino__cellier WHERE id =" . $id_cellier;
                if ($this->_db->query($sql2)) $reponse['data'] = true;
                else throw new Exception("Erreur lors de la suppression du cellier.");
            }

            //3-supprimer l'utilisateur :
            $sql3 = "DELETE FROM vino__utilisateur WHERE id_utilisateur =" . $id_utilisateur;
            if ($this->_db->query($sql3)) $reponse['data'] = true;
            else throw new Exception("Erreur lors de la suppression de l'utilisateur.");

            $this->_db->commit();
        } catch (Exception $e) {
            $this->_db->rollback();
            $reponse["rollback"] = $e->getMessage();
        }

        return $reponse;
    }
}
