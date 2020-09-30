<div class="ajouter">

    <div id="ajouter_une_bouteille">
        <div class="remplir_Champs">
            Ajouter une nouvelle bouteille au cellier <i class="fas fa-info-circle"></i>
            <span id="fenetre_info">Faites une recherche d'une bouteille dans le champs de recherche et la sélectionner puis compléter le formulaire pour l'ajouter au cellier</span>
        </div>
        <form id="recherche_ajout">
            <input type="text" name="nom_bouteille" placeholder="Rechercher une bouteille">
        </form>
        <ul class="listeAutoComplete">
        </ul>
        <p>Veuillez remplir les champs suivants <br>
            <span>* Champs obligatoires</span></p>
        <p><label>Nom </label><span name="nom" data-id_cellier="<?= $_SESSION['info_utilisateur']['id'] ?>" class="nom_bouteille"></span></p>
        <span id="messageSAQ"></span>
        <p><label>Prix * </label><input input type="number" name="prix" placeholder="Entrer le prix" value="" id="prix_bouteille"></p>
        <span class='erreur prix'></span>
        <p><label>Code SAQ </label><span name="code_saq"></span></p>


        <p><label>Millesime </label><input type="text" name="millesime" placeholder="Entrer le millesime"></p>
        <span class='erreur millesime'></span>
        <p><label>Quantite * </label><input input type="text" name="quantite" value="1"></p>
        <span class='erreur quantite'></span>
        <p><label>Date achat * </label><input type="date" name="date_achat"></p>
        <span class='erreur date_achat'></span>
        <p><label>Garde </label><input input type="text" name="garde_jusqua" placeholder="Entrer la date de conservation"></p>
        <span class='erreur garde_jusqua'></span>
        <p><label>Notes </label><input input type="text" name="notes" placeholder="Entrer une note"></p>
        <span class='erreur notes'></span>

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