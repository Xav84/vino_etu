<?php if(isset($_SESSION['info_utilisateur'])) : ?>

<div class="modifier">

    <div class="modifierBouteille" vertical layout>

        <div class="remplir_Champs">
            Modification d'une bouteille du cellier <i class="fas fa-info-circle"></i>
            <span id="fenetre_info">Pour Modifier les informations d'une bouteille il suffit de modifier les valeurs des champs suivants</span>
        </div>
        <p>Veuillez modifier les champs suivants <br>
            <span>* Champs obligatoires</span></p>
        <p class="nom_bouteille" data-id="<?php echo $data[0]["vino__bouteille_id"] ?>" data-id_cellier="<?= $_GET['cellier'] ?>">Modifier : <span data-id=""></span><?php echo $data[0]["nom"] ?></p>

        <p><label>Millesime </label><input type="text" name="millesime" value='<?php if ($data[0]["millesime"] == 0) echo "0000";
                                                                                else echo $data[0]["millesime"] ?>'></p>
        <span class='erreur millesime'></span>
        <p><label>Quantite dans le cellier * </label> <input input type="text" name="quantite" value='<?php echo $data[0]["quantite"] ?>'></p>
        <span class='erreur quantite'></span>
        <p><label>Date achat * </label><input type="date" name="date_achat" value='<?php echo $data[0]["date_achat"] ?>'></p>
        <span class='erreur date_achat'></span>
        <p><label>Prix *</label><input input type=number name="prix" value='<?php echo $data[0]["prix"] ?>'></p>
        <span class='erreur prix'></span>
        <p><label>Garde </label> <input type="text" name="garde_jusqua" value='<?php if ($data[0]["garde_jusqua"] == 0) echo "";
                                                                                else if ($data[0]["garde_jusqua"] == "non") echo "";
                                                                                else echo $data[0]["garde_jusqua"] ?>'></p>
        <span class='erreur garde_jusqua'></span>
        <p><label>Commentaire </label> <input type="text" name="notes" value='<?php echo $data[0]["notes"] ?>'></p>
        <span class='erreur notes'></span>
        <p><label>Évaluation</label><input type="number" name="note_degustation" max="5" min="0" value='<?php echo $data[0]["note_degustation"] ?>'></p>
        <span class='erreur evaluation'></span>
        <button name="modifierBouteilleCellier">Modifier la bouteille</button>
    </div>
    <div class="modal">
        <div class="contenu_modal">
            <p class="msg_sql"></p>
            <input type="button" class="retour_cellier" value="Retour au cellier">
        </div>

    </div>
</div>
</div>

<?php else : ?>
    <p>Accès interdit</p>
<?php endif?>