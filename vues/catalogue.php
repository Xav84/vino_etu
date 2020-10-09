<?php if (isset($_SESSION['info_utilisateur'])) : ?>
    <div class="catalogue">
        <header>
            <form id="recherche" method="post">
                <div class="rechercheBouteilleCatalogue" vertical layout>

                    <input type="text" class="nom_bouteille_catalogue" name="nom_bouteille_catalogue" placeholder="nom, pays " value="">
                    <button type="submit" name="recherche" value="Rechercher"><i class="fa fa-search"></i></button>
                    <ul class="listeAutoCompleteCatalogue">
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
                        <?php
                        if ($_SESSION['info_utilisateur']['type_utilisateur'] == 1) {
                        ?>
                            <option <?php if (!(strcmp("id", $_POST["type"]))) {
                                        echo "selected=\"selected\"";
                                    } ?>value="id">Id</option>
                        <?php
                        }
                        ?>
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

                    <select name="type">
                        <option value="" disabled selected>Choisir un tri</option>
                        <?php
                        if ($_SESSION['info_utilisateur']['type_utilisateur'] == 1) {
                        ?>
                            <option value="id">Id</option>
                        <?php
                        }
                        ?>
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
                <input type="submit" name="tri" value="Triez">
            </form>


        </div>
        <h2>Catalogue de la SAQ</h2>
        <!----------------------------->
        <div class="container_bouteille">
            <?php
            if ($data == null) {
            ?><h4>La recherche n'a donnée aucun résultat</h4>
            <?php
            }
            foreach ($data as $cle => $bouteille) {
            ?>

                <div class="bouteille" data-quantite="">
                    <article class="vignette">

                        <div class="img">
                            <img src="https:<?php echo $bouteille['image'] ?>">
                        </div>

                        <div class="description">

                            <?php
                            if ($_SESSION['info_utilisateur']['type_utilisateur'] == 1) {
                            ?>
                                <h4 class="nom"><?php echo $bouteille['nom'] . " " . "[id:" . $bouteille['id'] . "]" ?></h4>
                            <?php
                            } else {
                            ?>
                                <h4 class="nom"><?php echo $bouteille['nom'] ?></h4>
                            <?php
                            }
                            ?>

                            <div class="description_colunne1">
                                <p class="pays"><i class='fas fa-flag'></i> <?php echo  $bouteille['pays'] ?></p>
                                <p class="type"><i class='fas fa-wine-bottle'></i> <?php echo $bouteille['type'] ?></p>
                                <p class="prix"><i class='fas fa-dollar-sign'></i> <?php echo $bouteille['prix_saq'] ?>$</p>


                            </div>
                            <div class="description_colunne2">


                                <p class="format"><i class='fas fa-wine-glass-alt'></i> <?php echo $bouteille['format'] ?></p>

                                <p><a href="<?php echo  $bouteille['url_saq'] ?>"><i class="fas fa-external-link-square-alt"></i> Voir SAQ</a></p>
                            </div>
                        </div>

                        <div class="options" data-id_bouteille="<?php echo $bouteille['id'] ?>" data-id_cellier="<?php echo ($_SESSION['info_utilisateur']['type_utilisateur'] == 2) ? $_SESSION['info_utilisateur']['id'] : "" ?>">
                            <?php
                            if ($_SESSION['info_utilisateur']['type_utilisateur'] == 1) {
                            ?>
                                <button class='btnModifier'><a href="?requete=modifierBouteilleCatalogue&id=<?php echo $bouteille['id'] ?>">Modifier</a></button>
                                <button class='btnSupprimerCatalogue'>Supprimer</button>
                            <?php
                            } else if ($_SESSION['info_utilisateur']['type_utilisateur'] == 2) {
                            ?>
                                <button name="ajouterC">Ajouter</button>
                            <?php
                            }
                            ?>
                        </div>
                    </article>
                </div>
            <?php
            }
            ?>
        </div>



    </div>
    </div>
    <!-- Modal confirmation de la suppression -->
    <div class="modal_confirmation">
        <div class="contenu_modal">
            <p class="msg_confirmation"></p>
            <input type="button" class="confirmer" value="Confirmer">
            <input type="button" class="annuler" value="Annuler">
        </div>

    </div>

    <!-- Modal confirmation de l'ajout -->
    <div class="modal">
        <div class="contenu_modal">
            <p class="msg_sql"></p>
            <input type="button" class="retour_cellier" value="Retour au catalogue">
        </div>

    </div>
<?php else : ?>
    <p>Accès interdit</p>
<?php endif ?>