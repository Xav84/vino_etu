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
     * Fonction controlerUtilisateur : contrôler l'authentification de l'utilisateur dans la table vino__utilisateur
     * $identifiant
     * $mot_de_passe
     * Valeurs de retour : 1 si utilisateur avec $identifiant et $mot_de_passe trouvé, 0 sinon
     */

    function controllerUtilisateur($courriel, $mot_de_passe)
    {
        $reponse = ['erreurs' => null, 'data' => null];
        //SHA : mot de passe en clair
        // INNER JOIN pour récupérer l'id du cellier
        $req = "SELECT * FROM vino__utilisateur u
        INNER JOIN vino__cellier c ON u.id_utilisateur = c.fk_id_utilisateur
        WHERE courriel_utilisateur='$courriel' AND password_utilisateur = '$mot_de_passe'";

        //SHA : hachage du mot de passe
        // $req = "SELECT * FROM vino__utilisateur FROM vino__utilisateur u INNER JOIN vino__cellier c ON u.id_utilisateur = c.fk_id_utilisateurWHERE courriel_utilisateur='$identifiant' AND password_utilisateur = SHA2('$mot_de_passe', 256)";

        //Validation des données :
        /* courriel : */
        if(!filter_var($courriel, FILTER_VALIDATE_EMAIL)) {
            $this->erreurs['courriel'] = "Veuillez entrer un courriel valide.";
        }

        if (empty($this->erreurs)) {
            if ($result = $this->_db->query($req)) {
                $reponse['data'] = $result->fetch_assoc();
                $_SESSION['info_utilisateur'] =  $reponse['data'];
            } 
        } else {
            $reponse['erreurs'] = $this->erreurs;
            // $reponse['courriel'] = $courriel;
            // throw new Exception("Erreur de requête sur la base de données", 1);
        }

        return $reponse;
    }
}
