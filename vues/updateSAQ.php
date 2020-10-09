<?php if(isset($_SESSION['info_utilisateur'])) : ?>
<h2>Importation de bouteilles de la SAQ</h2>

<form id="importer" method="post">
    <div class="importerBouteilles" vertical layout>

        <input type="number" class="nombre_bouteille" name="nombre_bouteille" placeholder="24, 48 ou 96 " value="">
        <button type="submit" name="importer" value=""><i class="far fa-arrow-alt-circle-down"></i></button>
        <ul class="listeImportation">
        </ul>
    </div>
</form>
<?php

$page = 1;

if (isset($_POST['importer'])) {
    if (preg_match('/^((24||48||96))$/', $_POST['nombre_bouteille'])) {

        $nombreProduit = $_POST['nombre_bouteille']; //24, 48 ou 96	

        $saq = new SAQ();

        for ($i = 1; $i < 2; $i++)    //permet d'importer séquentiellement plusieurs pages.
        {
            echo "<h2>page " . ($page) . "</h2>";
            $page++;
            $nombre = $saq->getProduits($nombreProduit, $page + $i);
            echo "importation : " . $nombre . "<br>";
        }
    } else echo "<h2 class='erreur'>Veuillez entrer les valeurs :24, 48 ou 96";
}
?>
<?php else : ?>
    <p>Accès interdit</p>
<?php endif?>