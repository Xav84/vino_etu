<?php

/**
 * Class Controler
 * Gère les requêtes HTTP
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2019-01-21
 * @license Creative Commons BY-NC 3.0 (Licence Creative Commons Attribution - Pas d’utilisation commerciale 3.0 non transposé)
 * @license http://creativecommons.org/licenses/by-nc/3.0/deed.fr
 * 
 */

class Controler
{

	/**
	 * Traite la requête
	 * @return void
	 */
	public function gerer()
	{
		switch ($_GET['requete']) {
			case 'autocompleteBouteille':
				$this->autocompleteBouteille();
				break;
			case 'autocompleteBouteilleCellier':
				$this->autocompleteBouteilleCellier();
				break;
			case 'ajouterNouvelleBouteilleCellier':
				$this->ajouterNouvelleBouteilleCellier();
				break;
			case 'modifierBouteilleCellier':
				$this->modifierBouteilleCellier();
				break;
			case 'ajouterBouteilleCellier':
				$this->ajouterBouteilleCellier();
				break;
			case 'boireBouteilleCellier':
				$this->boireBouteilleCellier();
				break;
			default:
				$this->accueil();
				break;
		}
	}

	/**
	 * Affiche la page d'accueil sur la liste des bouteilles du cellier avec tri si le bouton tri est déclenché
	 * @return files
	 */
	private function accueil()
	{
		if (isset($_POST['type']) && isset($_POST['ordre'])) {
			$type = $_POST['type'];
			$ordre = $_POST['ordre'];
			$bte = new Bouteille();

			// var_dump($type, $ordre);
			// exit;
			$data = $bte->getListeBouteilleCellierTri($type, $ordre);
			include("vues/entete.php");
			include("vues/cellier.php");
			include("vues/pied.php");
		}
		if (isset($_POST['recherche'])) {
			$recherche = $_POST['nom_bouteille_cellier'];
			$bte = new Bouteille();

			// var_dump($recherche);
			// exit;
			$data = $bte->getRechercheBouteilleCellier($recherche);
			include("vues/entete.php");
			include("vues/cellier.php");
			include("vues/pied.php");
		} else {
			$bte = new Bouteille();
			$data = $bte->getListeBouteilleCellier();
			include("vues/entete.php");
			include("vues/cellier.php");
			include("vues/pied.php");
		}
	}

	/**
	 * Récupère les résultats de la recherche d'auto-complétion.
	 * @return json
	 */
	private function autocompleteBouteille()
	{
		$bte = new Bouteille();
		// var_dump(file_get_contents('php://input'));
		$body = json_decode(file_get_contents('php://input'));
		// var_dump($body);
		$listeBouteille = $bte->autocomplete($body->nom);

		echo json_encode($listeBouteille);
	}

	/**
	 * Récupère les résultats de la recherche d'auto-complétion.
	 * @return json
	 */
	private function autocompleteBouteilleCellier()
	{
		$bte = new Bouteille();
		// var_dump(file_get_contents('php://input'));
		$body = json_decode(file_get_contents('php://input'));
		// var_dump($body);
		$listeBouteille = $bte->autocompleteCellier($body->nom);

		echo json_encode($listeBouteille);
	}

	/**
	 * Récupère les informations sur la bouteille à ajouter et déclenche la requete sql d'ajout.
	 * Si php://input est vide, affiche le formulaire d'ajout de bouteille
	 * @return mixted
	 */
	private function ajouterNouvelleBouteilleCellier()
	{
		$body = json_decode(file_get_contents('php://input'));


		if (!empty($body)) {
			// var_dump($body);
			$bte = new Bouteille();
			$resultat = $bte->ajouterBouteilleCellier($body);
			echo json_encode($resultat);
		} else {
			include("vues/entete.php");
			include("vues/ajouter.php");
			include("vues/pied.php");
		}
	}

	/**
	 * Récupère les informations sur la bouteille à modifier et déclenche la requete sql de modification.
	 * Si php://input est vide, affiche la page de modification de bouteille
	 * @return mixted
	 */
	private function modifierBouteilleCellier()
	{
		$body = json_decode(file_get_contents('php://input'));

		if (!empty($body)) {
			$bte = new Bouteille();
			$resultat = $bte->modifierInfoBouteilleCellier($body);
			echo json_encode($resultat);
		} else {
			$bte = new Bouteille();
			$data = $bte->getBouteilleCellier($_GET['id'], $_GET['cellier']);
			include("vues/entete.php");
			include("vues/modifier.php");
			include("vues/pied.php");
		}
	}

	/**
	 * Récupère les informations sur la bouteille dont la quantité doit être modifiée et déclenche la requete sql de modification de quantité avec -1.
	 * @return json
	 */
	private function boireBouteilleCellier()
	{
		$body = json_decode(file_get_contents('php://input'));

		$bte = new Bouteille();
		$resultat = $bte->modifierQuantiteBouteilleCellier($body->id, -1);
		echo json_encode($resultat);
	}

	/**
	 * Récupère les informations sur la bouteille dont la quantité doit être modifiée et déclenche la requete sql de modification de quantité avec +1.
	 * @return json
	 */
	private function ajouterBouteilleCellier()
	{
		$body = json_decode(file_get_contents('php://input'));

		$bte = new Bouteille();
		$resultat = $bte->modifierQuantiteBouteilleCellier($body->id, 1);
		echo json_encode($resultat);
	}
}
