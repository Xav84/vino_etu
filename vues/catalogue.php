<div class="cellier">
    <form id="tri" method="post">
        <h2>Catalogue de la SAQ</h2>
        <h3><strong>Critères de tri :</strong></h3>
        <?php
        //Vérifie si un champs de tri "type" a déja été appliqué
        //Si oui le laisse sélectionné au submit ou refresh

        if (isset($_POST["type"])) {
        ?>
            <label>Type</label>
            <select name="type">

                <option <?php if (!(strcmp("nom", $_POST["type"]))) {
                            echo "selected=\"selected\"";
                        } ?>value="nom">Nom</option>
                <option <?php if (!(strcmp("pays", $_POST["type"]))) {
                            echo "selected=\"selected\"";
                        } ?>value="pays">Pays</option>
                <option <?php if (!(strcmp("type", $_POST["type"]))) {
                            echo "selected=\"selected\"";
                        } ?>value="type">Type de vin</option>
                <option <?php if (!(strcmp("prix_saq", $_POST["type"]))) {
                            echo "selected=\"selected\"";
                        } ?>value="prix_saq">Prix</option>
            </select>
        <?php
            //Si aucun champs sélectionné 
        } else {
        ?>
            <label>Type</label>
            <select name="type">
                <option value="" disabled selected>Choisir un tri</option>
                <option value="nom">Nom</option>
                <option value="pays">Pays</option>
                <option value="type">Type de vin</option>
                <option value="prix_saq">Prix</option>
            </select>
        <?php
        }
        //Vérifie si un champs de tri "ordre" a déja été appliqué
        //Si oui le laisse sélectionné au submit ou refresh
        if (isset($_POST["ordre"])) {
        ?>
            <label>Ordre</label>
            <select name="ordre">

                <option <?php if (!(strcmp("ASC", $_POST["ordre"]))) {
                            echo "selected=\"selected\"";
                        } ?>value="ASC" selected>Croissant</option>
                <option <?php if (!(strcmp("DESC", $_POST["ordre"]))) {
                            echo "selected=\"selected\"";
                        } ?>value="DESC">Décroissant</option>
            </select>
        <?php
            //Si aucun champs sélectionné
        } else {
        ?>
            <label>Ordre</label>
            <select name="ordre">

                <option value="ASC" selected>Croissant</option>
                <option value="DESC">Décroissant</option>
            </select>
        <?php
        }
        ?>
        <input type="submit" name="tri" value="Triez">
    </form>


    <form id="recherche_cellier" method="post">
        <div class="rechercheBouteilleCellier" vertical layout>
            <h3><strong>Recherchez dans le catalogue:</strong></h3>
            <input type="text" placeholder="nom, pays ou code saq" class="nom_bouteille_cellier" name="nom_bouteille_cellier">
            <input type="submit" name="recherche" value="Rechercher">
            <ul class="listeAutoCompleteCellier">
            </ul>

    </form>
    <!----------------------------->

    <?php
    if ($data == null) {
    ?><h4>La recherche n'a donnée aucun résultat</h4>
    <?php
    }
    foreach ($data as $cle => $bouteille) {
    ?>
        <div class="bouteille" data-quantite="">
            <div class="img">
                <img src="https:<?php echo $bouteille['image'] ?>">
            </div>
            <div class="description">
                <p class="nom">Nom : <?php echo $bouteille['nom'], " " . "[" . "id " . $bouteille['id'] . "]" ?></p>
                <p class="pays">Pays : <?php echo $bouteille['pays'] ?></p>
                <p class="type">Type : <?php echo $bouteille['type'] ?></p>
                <p class="prix">Prix : <?php echo $bouteille['prix_saq'] ?>$</p>
                <p class="format">Format : <?php echo $bouteille['format'] ?></p>
                <p><a href="<?php echo $bouteille['url_saq'] ?>">Voir SAQ</a></p>
            </div>
        </div>
</div>
<?php
    }
?>
</div>