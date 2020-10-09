<?php if (isset($_SESSION['info_utilisateur'])) : ?>
    <h2>Mon compte</h2>
    <div class="compteUtilisateur" data-id_cellier="<?= $_SESSION['info_utilisateur']['id'] ?? "" ?>">

        <div class="modifierUtilisateur" vertical layout>
            <!-- Affichage prénom et nom utilisateur -->
            <h3 id="utilisateur"><?= $data['utilisateur']["nom_utilisateur"] . " " . $data['utilisateur']["prenom_utilisateur"]  ?></h3>
            <span class="test_crypto"></span>
            <?php if ($_SESSION['info_utilisateur']['type_utilisateur'] == 2) : ?>
                <!-- Modification du nom de cellier -->
                <form>
                    <h4>Modifier l'intitulé de votre cellier:</h4>
                    <p><label>Intitulé </label><textarea name="ancienIntitule" disabled><?= $_SESSION['info_utilisateur']["notes_cellier"] ?></textarea></p>
                    <p><label>Nouvel intitulé </label> <textarea name="nouvelIntitule"></textarea></p>
                    <span class="erreur intitule"></span>
                    <input type="button" name="modifierIntituleCellier" class="modifierIntituleCellier" value="Envoyer">
                </form>
            <?php endif; ?>
            <!-- Modification du courriel -->
            <form>
                <h4>Modifier votre email :</h4>
                <p><label>Courriel </label><input type="email" value=<?= $data['utilisateur']["courriel_utilisateur"] ?> name="ancienEmail" disabled></p>
                <p><label>Nouveau courriel </label><input type="email" name="nouvelEmail"></p>
                <span class="erreur courriel"></span>
                <input type="button" name="modifierEmailUtilisateur" class="modifierEmailUtilisateur" value="Envoyer">
            </form>

            <!-- Modification du mot de passe -->
            <form method="post">
                <h4>Modifier votre mot de passe :</h4>
                <p><label>Ancien mot de passe </label><input type="password" name="ancienMdp"> <i class="far fa-eye"></i><i class="far fa-eye-slash"></i></p>
                <span class="erreur ancienMdp"></span>
                <p><label>Nouveau mot de passe</label><input type="password" name="nouveauMdp"> <i class="far fa-eye"></i><i class="far fa-eye-slash"></i></p>
                <span class="erreur nouveauMdp"></span>
                <p><label>Confirmer nouveau mot de passe</label><input type="password" name="confirmationMdp"> <i class="far fa-eye"></i><i class="far fa-eye-slash"></i></p>
                <span class="erreur confirmationMdp"></span>
                <input type="button" name="modifierMdpUtilisateur" class="modifierMdpUtilisateur" value="Envoyer">
            </form>

            <!-- Suppression du compte -->
            <form>
                <h4>Supprimer votre compte :</h4>
                <input type="button" name="supprimerCompte" value="Je veux supprimer mon compte" data-id_utilisateur="<?php echo $_SESSION["info_utilisateur"]["id_utilisateur"] ?>" data-id_cellier="<?php echo $_SESSION["info_utilisateur"]["id"] ?? '' ?>" data-type_utilisateur="<?php echo $_SESSION["info_utilisateur"]["type_utilisateur"] ?>">
            </form>

        </div>

        <!-- Modal affichant le résultat de la requête : -->
        <div class="modal">
            <div class="contenu_modal">
                <p class="msg_sql"></p>
                <input type="button" class="retourGestionCompteUtilisateur" value="Retour">
            </div>
        </div>

        <!-- Modal confirmation de la suppression -->
        <div class="modal_confirmation">
            <div class="contenu_modal">
                <p class="msg_confirmation"></p>
                <input type="button" class="confirmer" value="Supprimer mon compte">
                <input type="button" class="annuler" value="Annuler">
            </div>

        </div>
    </div>
    </div>

<?php else : ?>
    <p>Accès interdit</p>
<?php endif ?>