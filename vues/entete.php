<?php if(isset($_SESSION['info_utilisateur'])) : ?>
<!DOCTYPE html>
<html lang="fr">

<head>

    <title>Un petit verre de vino</title>

    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="no-cache">
    <meta name="viewport" content="width=device-width, minimum-scale=0.5, initial-scale=1.0, user-scalable=yes">

    <link rel="stylesheet" href="./css/normalize.css" type="text/css" media="screen">
    <link rel="stylesheet" href="./css/base_h5bp.css" type="text/css" media="screen">
    <link rel="stylesheet" href="./css/main.css" type="text/css" media="screen">
    <base href="<?php echo BASEURL; ?>">
    <script src="./js/main.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <link href="https://fonts.googleapis.com/css2?family=Bentham&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Patua+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Patua+One&family=Syne&display=swap" rel="stylesheet">


</head>

<body>
    <header class="header">
        <div class="logo_nav">
            <!-- si l'utilisateur n'est pas admin, le clic sur le logo redirige vers le cellier : -->
             <?php if($_SESSION['info_utilisateur']['type_utilisateur'] == 2) :?>
            <a href="?requete=accueil" class="logo"><img src="./images/logo_vino.png"></a>
            <!-- si l'utilisateur est admin, le clic sur le logo redirige vers l'interface admin : -->
            <?php elseif($_SESSION['info_utilisateur']['type_utilisateur'] == 1) : ?>
            <a href="?requete=afficherInterfaceAdmin" class="logo"><img src="./images/logo_vino.png"></a>
            <?php endif; ?> 
            <nav class="nav">
                <label for="toggle">&#9776;</label>
                <input type="checkbox" id="toggle" />
                <ul class="menu">
                <?php
                    if ($_SESSION['info_utilisateur']['type_utilisateur'] == 2) {
                    ?>
                        <li><a href="?requete=accueil">Mon cellier</a></li>
                        <li><a href="?requete=ajouterNouvelleBouteilleCellier">Ajouter une bouteille au cellier</a></li>
                    <?php
                    }
                    ?>
                    <?php
                    if ($_SESSION['info_utilisateur']['type_utilisateur'] == 1) {
                    ?>
                        <li><a href="?requete=afficherInterfaceAdmin">Interface Admin</a></li>
                        <li><a href="?requete=afficherImportation">Importation SAQ</a></li>
                    <?php
                    }
                    ?>
                    <li><a href="?requete=afficherCatalogue">Catalogue de la SAQ</a></li>
                    <li> <a href="?requete=deconnexion"> Déconnexion</a></li>
                    <li class="utlisateur_connecte"><a href="index.php?requete=modifierCompteUtilisateur"><i class='fas fa-user-circle'></i>Mon compte</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
<?php endif?>
