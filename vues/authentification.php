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
    <div class="authentification">

        <div vertical layout>
            <div>
                <p>Courriel : <input name="courriel" type="email"></p>
                <span class="erreur courriel"></span>
                <p>Mot de passe : <input name="mdp" type="password"></p>
                <span class="erreur identifiants_inconnus"></span>
            </div>
            <button name="validerAuthentification">Valider</button>
            <a href="?requete=creerCompte">Créer un compte</a>
        </div>
    </div>
    </main>
</body>