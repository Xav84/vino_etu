<?php

/**
 * 
 *
 *
 * @author Jonathan Martel
 * @version 1.0
 *
 *
 *
 */
class SAQ extends Modele
{

	const DUPLICATION = 'duplication';
	const ERREURDB = 'erreurdb';
	const INSERE = 'Nouvelle bouteille insérée';

	private static $_webpage;
	private static $_status;
	private $stmt;

	public function __construct()
	{
		parent::__construct();
		if (!($this->stmt = $this->_db->prepare("INSERT INTO vino__bouteille(nom, fk_type_id, image, code_saq, pays, description, prix_saq, url_saq, url_img, format) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))) {
			echo "Echec de la préparation : (" . $this->_db->errno . ") " . $this->_db->error;
		}
	}

	// MODIF XAVIER

	/**
	 * getProduits
	 * Cette méthode retourne le nombre de bouteilles importées. 
	 * @param int $nombre désigne le nombre de bouteilles par page.
	 * @param int $page le numéro de la page ou se trouve les bouteilles.
	 * @return int $i le nombre de bouteilles importées.
	 */

	public function getProduits($nombre, $page)
	{
		$s = curl_init();
		switch ($nombre) {
			case 24:
				$url = "https://www.saq.com/fr/produits/vin/vin-rouge?p=2&product_list_limit=24&product_list_order=name_asc";
				break;
			case 48:
				$url = "https://www.saq.com/fr/produits/vin/vin-rouge?p=2&product_list_limit=48&product_list_order=name_asc";
				break;
			case 96:
				$url = "https://www.saq.com/fr/produits/vin/vin-rouge?p=2&product_list_limit=96&product_list_order=name_asc";
				break;
			default:
				$url = "https://www.saq.com/fr/produits/vin/vin-rouge?p=2&product_list_limit=24&product_list_order=name_asc";
				break;
		}

		curl_setopt($s, CURLOPT_URL, $url);
		curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($s, CURLOPT_FOLLOWLOCATION, 1);

		self::$_webpage = curl_exec($s);
		self::$_status = curl_getinfo($s, CURLINFO_HTTP_CODE);
		curl_close($s);

		$doc = new DOMDocument(); //instanciation de la classe DOMDocument()
		$doc->recover = true;
		$doc->strictErrorChecking = false;

		@$doc->loadHTML(file_get_contents($url)); //chargement du code html de la page web

		//@$doc -> loadHTML(self::$_webpage);
		$elements = $doc->getElementsByTagName("li"); //Création de l'objet contenant la liste des 
		//éléments li
		$i = 0;

		echo "<ul class='listeImportation'>";
		echo "<strong>Nombre de bouteille à importer :" . $nombre . "<br>";
		echo "<br>";
		foreach ($elements as $key => $noeud) {
			//var_dump($noeud -> getAttribute('class')) ;

			//tester si la classe "product-item existe
			if (strpos($noeud->getAttribute('class'), "product-item") !== false) {

				//echo $this->get_inner_html($noeud);
				$info = self::recupereInfo($noeud);

				echo "<li>" . $info->nom;
				$retour = $this->ajouteProduit($info);
				echo "<br>Code de retour : " . $retour->raison . "<br>";
				if ($retour->succes == false) {
					echo "<br>";
				} else {
					$i++;
				}
			}
		}

		return $i;
	}
	// FIN MODIFS

	/**
	 * get_inner_html
	 * Cette méthode sauvegarde la collection d'éléments dans une chaine de caractéres.
	 * @param object $node tableau de données des bouteilles.
	 * @return string $innerHTML la collection d'éléments.
	 */

	private function get_inner_html($node)
	{
		$innerHTML = '';
		$children = $node->childNodes; //récuperer la collection d'éléments
		foreach ($children as $child) {
			$innerHTML .= $child->ownerDocument->saveXML($child); //sauvegarde la liste dans une chaine de caractéres
		}

		return $innerHTML;
	}


	/**
	 * nettoyerEspace
	 * Cette méthode enléve les espace en trop dans la chaine
	 * @param string $chaine informations des bouteilles.
	 * @return string retourne une chaine sans les espaces en trop.
	 */
	private static function nettoyerEspace($chaine)

	{
		return preg_replace('/\s+/', ' ', $chaine);
	}


	/**
	 * recupereInfo
	 * Cette méthode parcour le DOM et récupére les infos spécifiques pour chaque bouteille.
	 * @param object $noeud Tableau des éléments dans le DOM.
	 * @return object $info Tableau contenant les informations des bouteilles récupérées.
	 */

	private static function recupereInfo($noeud)
	{

		$info = new stdClass(); //déclaration d'un objet vide
		$info->img = $noeud->getElementsByTagName("img")->item(0)->getAttribute('src'); //TODO : Nettoyer le lien
		$info->img = preg_replace('/https:/', '', $info->img);

		$a_titre = $noeud->getElementsByTagName("a")->item(0);

		$info->url = $a_titre->getAttribute('href');
		//Tableau contenant tous les formats des bouteilles de la SAQ.
		$tab = array(
			"1 L", "1,5 L", "2,25 L", "250 ml", "3 L", "375 ml", "4 L", "4,5 L", "500 ml", "5 L", "6 L",
			"750 ml"
		);

		$info->nom = self::nettoyerEspace(trim($a_titre->textContent));	//TODO : Retirer le format de la bouteille du titre.
		//Rechercher l'existance d'un format dans le titre d'une bouteille et le remplacer par une chaine vide.
		foreach ($tab as $format) {
			$format_b = $format;
			if (stripos($info->nom, $format_b) !== false) {
				//var_dump($format_b);

				$info->nom = preg_replace("/$format_b/", ' ', $info->nom);
			}
		}

		// Type, format et pays
		$aElements = $noeud->getElementsByTagName("strong");
		foreach ($aElements as $node) {
			if ($node->getAttribute('class') == 'product product-item-identity-format') {
				$info->desc = new stdClass();
				$info->desc->texte = $node->textContent;
				$info->desc->texte = self::nettoyerEspace($info->desc->texte);
				$aDesc = explode("|", $info->desc->texte); // Type, Format, Pays
				if (count($aDesc) == 3) {

					$info->desc->type = trim($aDesc[0]);
					$info->desc->format = trim($aDesc[1]);
					$info->desc->pays = trim($aDesc[2]);
				}

				$info->desc->texte = trim($info->desc->texte);
				//Enlever le "|" de la chaine de description d'une bouteille.
				$info->desc->texte = preg_replace("/\|/", " ", $info->desc->texte);
			}
		}

		//Code SAQ
		$aElements = $noeud->getElementsByTagName("div");
		foreach ($aElements as $node) {
			if ($node->getAttribute('class') == 'saq-code') {
				if (preg_match("/\d+/", $node->textContent, $aRes)) {
					$info->desc->code_SAQ = trim($aRes[0]);
				}
			}
		}

		$aElements = $noeud->getElementsByTagName("span");
		foreach ($aElements as $node) {
			if ($node->getAttribute('class') == 'price') {
				$info->prix = trim($node->textContent);
				//Remplacer la virgule contenu dans le prix par un point 
				//Pour l'insertion compléte du prix dans la table.
				$info->prix = preg_replace("/,/", ".", "$info->prix");
			}
		}
		// var_dump($info);
		return $info;
	}

	/**
	 * ajouteProduit
	 * Cette méthode Permet d'insérer les boouteilles dans la table vino__bouteille
	 * @param object $bte Tableau des données de la bouteille a inserer dans la table.
	 * @return object $retour retourne un message sur l'état de l'importation des bouteilles.
	 */

	private function ajouteProduit($bte)
	{
		$retour = new stdClass();
		$retour->succes = false;
		$retour->raison = '';


		// Récupère le type
		$rows = $this->_db->query("select id from vino__type where type = '" . $bte->desc->type . "'");

		if ($rows->num_rows == 1) {
			$type = $rows->fetch_assoc();
			//var_dump($type);
			$type = $type['id'];


			$rows = $this->_db->query("select id from vino__bouteille where code_saq = '" . $bte->desc->code_SAQ . "'");
			if ($rows->num_rows < 1) {
				//changement du type du prix de integer a double
				$this->stmt->bind_param("sissssdsss", $bte->nom, $type, $bte->img, $bte->desc->code_SAQ, $bte->desc->pays, $bte->desc->texte, $bte->prix, $bte->url, $bte->img, $bte->desc->format);
				$retour->succes = $this->stmt->execute();
				$retour->raison = self::INSERE;


				//var_dump($this->stmt);
			} else {
				$retour->succes = false;
				$retour->raison = self::DUPLICATION;
			}
		} else {
			$retour->succes = false;
			$retour->raison = self::ERREURDB;
		}
		return $retour;
	}
}
