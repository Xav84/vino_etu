<?php if(isset($_SESSION['info_utilisateur'])) : ?>
<div class="modifier">

    <div class="modifierBouteille" vertical layout>

        <div class="remplir_Champs">
            Modification d'une bouteille du catalogue <i class="fas fa-info-circle"></i>
            <span id="fenetre_info">Pour Modifier les informations d'une bouteille il suffit de modifier les valeurs des champs suivants</span>
        </div>
        <p>Veuillez modifier les champs suivants <br>
            <span>* Champs obligatoires</span></p>

        <p><label>Nom </label><input class="nom_bouteille" type="text" name="nom" data-id="<?php echo $data[0]["id"] ?>" value='<?php echo $data[0]["nom"] ?>'></p>
        <span class='erreur nom'></span>
        <p><label>Pays </label><input type="text" name="pays" value='<?php echo $data[0]["pays"] ?>'></p>
        <span class='erreur pays'></span>
        <p><label>Prix *</label><input input type=number name="prix_saq" value='<?php echo $data[0]["prix_saq"] ?>'></p>
        <span class='erreur prix'></span>
        <p><label>Type </label><select name="type"></p>
        <option <?php if ($data[0]["type"] == "Vin rouge") {
                    echo "selected=\"selected\"";
                } ?>value="1">Vin rouge</option>
        <option <?php if ($data[0]["type"] == "Vin blanc") {
                    echo "selected=\"selected\"";
                } ?>value="2">Vin blanc</option>
        </select>

        <button name="modifierBouteilleCatalogue">Modifier la bouteille</button>
    </div>
    <div class="modal">
        <div class="contenu_modal">
            <p class="msg_sql"></p>
            <input type="button" class="retour_catalogue" value="Retour au catalogue">
        </div>

    </div>
</div>
</div>
<?php else : ?>
    <p>Accès interdit</p>
<?php endif?>