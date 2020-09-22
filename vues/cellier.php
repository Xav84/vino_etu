<div class="cellier">
    <form id="tri" method="post">
        <h2><strong>Votre cellier</strong></h2>
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
                <option <?php if (!(strcmp("quantite", $_POST["type"]))) {
                            echo "selected=\"selected\"";
                        } ?>value="quantite">Quantité</option>
                <option <?php if (!(strcmp("date_achat", $_POST["type"]))) {
                            echo "selected=\"selected\"";
                        } ?>value="date_achat">Date d'achat</option>
                <option <?php if (!(strcmp("prix", $_POST["type"]))) {
                            echo "selected=\"selected\"";
                        } ?>value="prix">Prix</option>
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
                <option value="quantite">Quantité</option>
                <option value="date_achat">Date d'achat</option>
                <option value="prix">Prix</option>
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

    <!-- Recherche dans le celier -->
    <form id="recherche_cellier" method="post">
        <div class="rechercheBouteilleCellier" vertical layout>
            <h3><strong>Recherchez dans votre cellier:</strong></h3>
            <input type="text" placeholder="nom, pays ou id" class="nom_bouteille_cellier" name="nom_bouteille_cellier">
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
                <p class="quantite">Quantité : <span><?php echo $bouteille['quantite'] ?></span></p>
                <p class="pays">Pays : <?php echo $bouteille['pays'] ?></p>
                <p class="type">Type : <?php echo $bouteille['type'] ?></p>
                <p class="type">Prix : <?php echo $bouteille['prix'] ?>$</p>
                <p class="notes">Notes : <?php echo $bouteille['notes'] ?></p>
                <p class="millesime">Millesime : <?php echo $bouteille['millesime'] ?></p>
                <p class="date_achat">Date d'achat : <?php echo $bouteille['date_achat'] ?></p>
                <p><a href="<?php echo $bouteille['url_saq'] ?>">Voir SAQ</a></p>
            </div>

            <div class="options" data-id_bouteille="<?php echo $bouteille['vino__bouteille_id'] ?>" data-id_cellier="<?php echo $bouteille['vino__cellier_id'] ?>">

                <button class='btnModifier'><a href="?requete=modifierBouteilleCellier&id=<?php echo $bouteille['id'] ?>&cellier=<?php echo $bouteille['vino__cellier_id'] ?>">Modifier</a></button>
                <button class='btnAjouter'>Ajouter</button>
                <button class='btnBoire'>Boire</button>

            </div>
        </div>
    <?php
    }
    ?>
</div>