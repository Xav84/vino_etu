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

<body id="body_creer_compte">
    <header>
        <aside>



        </aside>




        <div class="logo_nav connexion">
            <a href="?requete=accueil" class="logo"><img src="./images/logo_vino.png"></a>
        </div>

    </header>
    <main id="creer-compte">
        <form class="creer-compte">
            <div class="container">
                <h1 >Mon compte</h1>
                
                <p class="message">Veuillez s'il vous plait remplir les champs suivants pour créér un compte :<br>
                <span>* Champs obligatoires</span></p>
                <p><label>Prénom *</label><input type="text" name="prenom" placeholder="Prenom" required></p>
                <span class = "erreur prenom"></span>
                <p><label>Nom *</label><input type="text" name="nom" placeholder="Nom" required></p>
                <span class = "erreur nom"></span>
                <p><label>Courriel *</label><input type="email" name="courriel" placeholder="Courriel" required value = "<?= $_SESSION['courriel_creation_compte'] ?? '' ?>"></p>
                <span class = "erreur courriel"></span>
                <p><label>Mot de passe *</label><input type="password" name="mdp" placeholder="Mot de passe" required></p>
                <span class = "erreur mdp"></span>
                <span class = "erreur resultat"></span>

                <div class="confirmer">
                    <button type="submit" class="confirmerCompte">Créer un compte</button>
                    <a href="?requete=authentification">J'ai déjà un compte</a>

                </div>
            </div>
        </form>
    </main>
</body>
