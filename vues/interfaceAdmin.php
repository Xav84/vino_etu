<h2>Interface Admin</h2>

<div class="container_bouteille">

    <h2>Utilisateurs inscrits : <?php echo $dataNbUtilisateur[0]['COUNT(id_utilisateur)'] . " " ?></h2>
    <?php
    foreach ($data as $cle => $utilisateur) {


    ?>
        <div class="container_bouteille">
            <div class="bouteille" data-quantite="">
                <article class="vignette">

                    <div class="img">
                        <img src="<?php BASEURL ?>images/user_icon.jpg">
                    </div>

                    <div class="description">

                        <h4 class="nom"><?php echo $utilisateur['prenom_utilisateur'] . " " . $utilisateur['nom_utilisateur'] ?></h4>

                        <div class="description_colunne1">
                            <p class="pays"><i class="fas fa-id-card"></i> <?php echo  $utilisateur['id_utilisateur'] ?></p>
                            <p class="type"><i class="fas fa-at"></i> <?php echo $utilisateur['courriel_utilisateur'] ?></p>

                        </div>

                    </div>
                    <div class="options" data-id_utilisateur="<?php echo $utilisateur['id_utilisateur'] ?>">

                        <button class='btnSupprimer'><a href="?requete=supprimerutilisateur&id=<?php echo $utilisateur['id_utilisateur'] ?>">Supprimer</a></button>

                    </div>



                </article>
            </div>

        </div>


    <?php
    }

    ?>
</div>