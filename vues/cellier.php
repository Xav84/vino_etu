<div class="cellier">
    <header>



        <!-- Recherche dans le celier -->
        <form id="recherche" method="post">
            <div class="rechercheBouteilleCellier" vertical layout>

                <input type="text" class="nom_bouteille_cellier" name="nom_bouteille_cellier" placeholder="id, nom, pays " value="">
                <button type="submit" name="recherche" value="Rechercher"><i class="fa fa-search"></i></button>
                <ul class="listeAutoCompleteCellier">
                </ul>
            </div>
        </form>


    </header>
    <div class="tri_cellier">

        <form id="tri" method="post">

            <?php
            //Vérifie si un champs de tri "type" a déja été appliqué
            //Si oui le laisse sélectionné au submit ou refresh
            if (isset($_POST["type"])) {
            ?>

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
                </select>
            <?php
                //Si aucun champs sélectionné 
            } else {
            ?>

                <select name="type">
                    <option value="" disabled selected>Choisir un tri</option>
                    <option value="nom">Nom</option>
                    <option value="pays">Pays</option>
                    <option value="type">Type de vin</option>
                    <option value="quantite">Quantité</option>
                    <option value="date_achat">Date d'achat</option>
                </select>
            <?php
            }
            //Vérifie si un champs de tri "ordre" a déja été appliqué
            //Si oui le laisse sélectionné au submit ou refresh
            if (isset($_POST["ordre"])) {
            ?>

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

                <select name="ordre">

                    <option value="ASC" selected>Croissant</option>
                    <option value="DESC">Décroissant</option>
                </select>
            <?php
            }
            ?>
            <input type="submit" name="tri" value="Triez"></form>
    </div>
    <!-- MODIF XAVIER -->
    <h2>Cellier<br><small><?php echo $dataCellier[0]['notes_cellier'] . " " ?><br>
            Création : <?php echo $dataCellier[0]['date_creation_cellier'] ?></small>
    </h2>
    <!-- FIN MODIF -->
    <div class="container_bouteille">
        <?php
        if ($data == null) {
        ?><h4>Aucune bouteille trouvée</h4>

        <?php
        } ?>


        <?php
        foreach ($data as $cle => $bouteille) {
        ?>
            <div class="container_bouteille">
                <div class="bouteille" data-quantite="">
                    <article class="vignette">

                        <div class="img">
                            <img src="https:<?php echo $bouteille['image'] ?>">
                        </div>

                        <div class="description">

                            <h4 class="nom"><?php echo $bouteille['nom'] ?></h4>

                            <p class="quantite">Quantité : <span><?php echo $bouteille['quantite'] ?></span></p>
                            <div class="description_colunne1">
                                <p class="pays"><i class='fas fa-flag'></i> <?php echo  $bouteille['pays'] ?></p>
                                <p class="type"><i class='fas fa-wine-bottle'></i> <?php echo $bouteille['type'] ?></p>
                                <p class="notes"><i class='far fa-sticky-note'></i> <?php echo  $bouteille['notes'] ?></p>
                            </div>
                            <div class="description_colunne2">
                                <p class="millesime"><i class='fas fa-wine-glass-alt'></i> <?php echo  $bouteille['millesime'] ?></p>



                                <p class="date_achat"><i class='far fa-calendar-alt'></i> <?php echo  $bouteille['date_achat'] ?></p>

                                <p><a href="<?php echo  $bouteille['url_saq'] ?>"><i class="fas fa-external-link-square-alt"></i> Voir SAQ</a></p>
                            </div>
                        </div>

                        <div class="options" data-id_bouteille="<?php echo $bouteille['vino__bouteille_id'] ?>" data-id_cellier="<?php echo $bouteille['vino__cellier_id'] ?>">

                            <button class='btnModifier'><a href="?requete=modifierBouteilleCellier&id=<?php echo $bouteille['id'] ?>&cellier=<?php echo $bouteille['vino__cellier_id'] ?>">Modifier</a></button>
                            <button class='btnAjouter'>Ajouter</button>
                            <button class='btnBoire'>Boire</button>
                            <button class='btnSupprimer'><a href="?requete=supprimerBouteille&id=<?php echo $bouteille['id'] ?>&cellier=<?php echo $bouteille['vino__cellier_id'] ?>">Supprimer</a></button>

                        </div>
                    </article>
                </div>

            </div>


        <?php
        }
        ?>
    </div>