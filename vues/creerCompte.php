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
</head>

<body>
    <header>
        <h1>Un petit verre de vino ?</h1>
    </header>
    <main>
        <form class="creer-compte">
            <div class="container">
                <h1>Mon compte</h1>
                <p>Veuillez s'il vous plait remplir les champs suivants pour crééer un compte :</p>

                <p><input type="text" name="prenom" placeholder="Prenom" required></p>
                <p><input type="text" name="nom" placeholder="Nom" required></p>
                <p><input type="text" name="identifiant" placeholder="Identifiant" required></p>
                <p><input type="email" name="courriel" placeholder="Courriel" required></p>
                <p><input type="password" name="mdp" placeholder="Mot de passe" required></p>

                <div class="confirmer">
                    <button type="submit" class="confirmerCompte">Créer un compte</button>
                    <a href="?requete=authentification">J'ai déjà un compte</a>

                </div>
            </div>
        </form>
    </main>
</body>