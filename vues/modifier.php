<div class="modifier">

    <div class="modifierBouteille" vertical layout>
        <h2 class="nom_bouteille" data-id="<?php echo $data[0]["vino__bouteille_id"] ?>"  data-id_cellier="<?= $_GET['cellier'] ?>">Modifier : <span data-id=""></span><?php echo $data[0]["nom"] ?></h2>
        <div>

            <p>Date achat : <input type="date" name="date_achat" value='<?php echo $data[0]["date_achat"] ?>'></p>
            <span class='erreur date_achat'></span>
            <p>Garde : <input name="garde_jusqua" value='<?php echo $data[0]["garde_jusqua"] ?>'></p>
            <span class='erreur garde_jusqua'></span>
            <p>Notes <input name="notes" value='<?php echo $data[0]["notes"] ?>'></p>
            <span class='erreur notes'></span>
            <p>Prix : <input name="prix" value='<?php echo $data[0]["prix"] ?>'></p>
            <span class='erreur prix'></span>
            <p>Quantite dans le cellier: <input name="quantite" value='<?php echo $data[0]["quantite"] ?>'></p>
            <span class='erreur quantite'></span>
            <p>Millesime : <input name="millesime" value='<?php echo $data[0]["millesime"] ?>'></p>
            <span class='erreur millesime'></span>
        </div>
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