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

<body id="body_authentification">


    <header class="header">
        <div class="logo_nav connexion">
            <a href="?requete=accueil" class="logo"><img src="./images/logo_vino.png"></a>
        </div>
        <!--             <h1>Un petit verre de vino ?</h1>-->

    </header>
    <main id="authentification">
        <div class="authentification">

            <div class=info_utilisateur vertical layout>

                <div class="form">
                    <p class="titre_connexion">Connexion au compte <br>
                        <span>* Champs obligatoires</span></p>
                    <p class="message">Nouvel utilisateur?<a href="?requete=creerCompte"> Créer un compte</a></p>
                    <form class="authentification-form" method="post">
                        <p><label>Courriel *</label><br>

                            <input type="email" id="courriel" name="courriel" placeholder="courriel" required value="<?= $_SESSION['courriel_creation_compte'] ?? '' ?>" /></p>
                        <span class="erreur courriel"></span>

                        <p><label>Mot de passe *</label><br>
                            <input type="password" name="mdp" placeholder="Mot de passe" required /></p>
                        <span class="erreur mdp"></span>

                        <span class="erreur identifiants_inconnus"></span>
                    </form>

                    <button name="validerAuthentification">Valider</button>


                </div>
            </div>
        </div>
    </main>
</body>