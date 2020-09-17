<div class="ajouter">

    <div class="nouvelleBouteille" vertical layout>
        Recherche : <input type="text" name="nom_bouteille">
        <ul class="listeAutoComplete">
        </ul>
        <div>
            <p>Nom : <span name="nom" data-id_cellier="<?= $_SESSION['info_utilisateur']['id'] ?>" class="nom_bouteille"></span></p>
            <p>Prix : <span name="prix"></span></p>
            <p>Code SAQ : <span name="code_saq"></span></p>
            <p>Millesime : <input name="millesime"></p>
            <span class='erreur millesime'></span>
            <p>Quantite : <input type="number" name="quantite" value="1"></p>
            <span class='erreur quantite'></span>
            <p>Date achat : <input type="date" name="date_achat"></p>
            <span class='erreur date_achat'></span>
            <p>Garde : <input name="garde_jusqua"></p>
            <span class='erreur garde_jusqua'></span>
            <p>Notes <input name="notes"></p>
            <span class='erreur notes'></span>
        </div>
        <button name="ajouterBouteilleCellier">Ajouter la bouteille</button>
    </div>

    <div class="modal">
        <div class="contenu_modal">
            <p class="msg_sql"></p>
            <input type="button" class="retour_cellier" value="Retour au cellier">
        </div>

    </div>
</div>
</div>