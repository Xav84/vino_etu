<?php

/**
 * Class Bouteille
 * Cette classe possède les fonctions de gestion des bouteilles dans le cellier et des bouteilles dans le catalogue complet.
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2019-01-21
 * @license Creative Commons BY-NC 3.0 (Licence Creative Commons Attribution - Pas d’utilisation commerciale 3.0 non transposé)
 * @license http://creativecommons.org/licenses/by-nc/3.0/deed.fr
 * 
 */
class Bouteille extends Modele
{
	const BOUTEILLE = 'vino__bouteille'; //table contenant la liste de bouteille du catalogue
	const CELLIER = 'vino__cellier'; //table contenant la liste des celliers et les informations les concernant (à quel utilisateur il appartient apr exemple)
	const TYPE = 'vino__type'; //table contenant la liste des types de vin (rouge, blanc, etc.)
	const CELLIER_BOUTEILLE = 'cellier__bouteille'; //table contenant la liste des bouteilles du celliers avec leurs informations

	private $erreurs = []; //tableau pour récupérer les erreurs lors de la vérifications des données

	/**
	 * Cette méthode retourne la liste des bouteilles contenues dans la base de données
	 * 
	 * @return Array $rows contenant toutes les bouteilles.
	 */
	public function getListeBouteille()
	{

		$rows = array();
		$res = $this->_db->query('SELECT
											b.id,
											b.nom,
											b.image,
											b.code_saq,
											b.prix_saq,
											b.url_saq,
											b.pays,
											b.format,
											t.type
										FROM vino__bouteille b
										INNER JOIN ' . self::TYPE . ' t ON t.id = b.fk_type_id');
		if ($res->num_rows) {
			while ($row = $res->fetch_assoc()) {
				$rows[] = $row;
			}
		}
		return $rows;
	}


	/**
	 * Cette méthode retourne la liste des bouteilles contenues dans un cellier
	 * 
	 * @return Array $rows contenant toutes les bouteilles.
	 */
	//clause WHERE de getListeBouteilleCellier() pour fin de test seulement 
	public function getListeBouteilleCellier($id_utilisateur = '')
	{
		$rows = array();
		$requete = 'SELECT
								c.vino__cellier_id,
								c.vino__bouteille_id,
								c.date_achat,
								c.garde_jusqua,
								c.notes,
								c.prix,
								c.quantite,
								c.millesime,
								vc.id,
								vc.fk_id_utilisateur,
								b.id,
								b.nom,
								b.image,
								b.code_saq,
								b.url_saq,
								b.pays,
								b.description,
								t.type
							FROM cellier__bouteille c
							INNER JOIN ' . self::BOUTEILLE . ' b ON c.vino__bouteille_id = b.id
							INNER JOIN ' . self::CELLIER . ' vc ON c.vino__cellier_id = vc.id
							INNER JOIN ' . self::TYPE . ' t ON t.id = b.fk_type_id
                            WHERE vc.fk_id_utilisateur =' . $id_utilisateur;


		if (($res = $this->_db->query($requete)) ==     true) {
			if ($res->num_rows) {
				while ($row = $res->fetch_assoc()) {
					$row['nom'] = trim($row['nom']);
					$rows[] = $row;
				}
			}
		} else {
			throw new Exception("Erreur de requête sur la base de donnée", 1);
			//$this->_db->error;
		}

		return $rows;
	}

	/**
	 * Cette méthode retourne la liste des bouteilles contenues dans un cellier filtré par le tri
	 * 
	 * @return Array $rows contenant toutes les bouteilles.
	 */
	//clause WHERE de getListeBouteilleCellier() pour fin de test seulement 
	public function getListeBouteilleCellierTri($type, $ordre, $cellier)
	{

		$rows = array();
		$requete = 'SELECT
								c.vino__cellier_id,
								c.vino__bouteille_id,
								c.date_achat,
								c.garde_jusqua,
								c.notes,
								c.prix,
								c.quantite,
								c.millesime,
								vc.id,
								vc.fk_id_utilisateur,
								b.id,
								b.nom,
								b.image,
								b.code_saq,
								b.url_saq,
								b.pays,
								b.description,
								t.type
							FROM cellier__bouteille c
							INNER JOIN ' . self::BOUTEILLE . ' b ON c.vino__bouteille_id = b.id
							INNER JOIN ' . self::CELLIER . ' vc ON c.vino__cellier_id = vc.id
							INNER JOIN ' . self::TYPE . ' t ON t.id = b.fk_type_id
							WHERE c.vino__cellier_id = ' . $cellier . ' ORDER BY ' . "$type $ordre" . '';

		if (($res = $this->_db->query($requete)) ==     true) {
			if ($res->num_rows) {
				while ($row = $res->fetch_assoc()) {
					$row['nom'] = trim($row['nom']);
					$rows[] = $row;
				}
			}
		} else {
			var_dump($type, $ordre);
			throw new Exception("Erreur de requête sur la base de donnée", 1);
			//$this->_db->error;
		}



		return $rows;
	}

	/**
	 * Cette méthode retourne la liste des bouteilles contenues dans le catalogue filtré par le tri
	 * 
	 * @return Array $rows contenant toutes les bouteilles.
	 */
	public function getListeBouteilleCatalogueTri($type, $ordre)
	{

		$rows = array();
		$requete = 'SELECT
								
								b.id,
								b.nom,
								b.image,
								b.code_saq,
								b.url_saq,
								b.prix_saq,
								b.pays,
								b.format,
								t.type
							FROM ' . self::BOUTEILLE . ' b
							INNER JOIN ' . self::TYPE . ' t ON t.id = b.fk_type_id
							ORDER BY ' . "$type $ordre" . '';

		if (($res = $this->_db->query($requete)) ==     true) {
			if ($res->num_rows) {
				while ($row = $res->fetch_assoc()) {
					$row['nom'] = trim($row['nom']);
					$rows[] = $row;
				}
			}
		} else {

			throw new Exception("Erreur de requête sur la base de donnée", 1);
			//$this->_db->error;
		}
		return $rows;
	}

	// MODIFS XAVIER

	/**
	 * Cette méthode retourne la bouteille contenue dans un cellier 
	 * 
	 * @return Array $rows contenant toutes les bouteilles.
	 */
	//clause WHERE de getListeBouteilleCellier() pour fin de test seulement 
	public function getRechercheBouteilleCellier($recherche, $cellier)
	{
		// var_dump($recherche);
		// exit;
		$rows = array();
		$recherche = $this->_db->real_escape_string($recherche); //Protéger les caractères spéciaux de la chaine $recherche pour la requête SQL
		$requete = 'SELECT
								c.vino__cellier_id,
								c.vino__bouteille_id,
								c.date_achat,
								c.garde_jusqua,
								c.notes,
								c.prix,
								c.quantite,
								c.millesime,
								vc.id,
								vc.fk_id_utilisateur,
								b.id,
								b.nom,
								b.image,
								b.code_saq,
								b.url_saq,
								b.pays,
								b.description,
								t.type
							FROM cellier__bouteille c
							INNER JOIN ' . self::BOUTEILLE . ' b ON c.vino__bouteille_id = b.id
							INNER JOIN ' . self::CELLIER . ' vc ON c.vino__cellier_id = vc.id
							INNER JOIN ' . self::TYPE . ' t ON t.id = b.fk_type_id
							WHERE LOWER(b.nom) like LOWER("%' . $recherche . '%") AND c.vino__cellier_id=' . $cellier . '
							OR LOWER(b.pays) like LOWER("%' . $recherche . '%") AND c.vino__cellier_id=' . $cellier . '
							OR  c.millesime like ("%' . $recherche . '%") AND c.vino__cellier_id=' . $cellier . '
							OR  LOWER(type) like LOWER("%' . $recherche . '%") AND c.vino__cellier_id=' . $cellier;

		if (($res = $this->_db->query($requete)) ==     true) {
			if ($res->num_rows) {
				while ($row = $res->fetch_assoc()) {
					$row['nom'] = trim($row['nom']);
					$rows[] = $row;
				}
			}
		} else {
			echo $requete;
			throw new Exception("Erreur de requête sur la base de donnée", 1);
			//$this->_db->error;
		}



		return $rows;
	}

	/**
	 * Cette méthode retourne la bouteille contenue dans le catalogue 
	 * 
	 * @return Array $rows contenant toutes les bouteilles.
	 */
	public function getRechercheBouteilleCatalogue($recherche)
	{
		// var_dump($recherche);
		// exit;
		$rows = array();
		$requete = 'SELECT
								b.id,
								b.nom,
								b.image,
								b.code_saq,
								b.url_saq,
								b.prix_saq,
								b.pays,
								b.format,
								t.type
							FROM ' . self::BOUTEILLE . ' b
							INNER JOIN ' . self::TYPE . ' t ON t.id = b.fk_type_id
							WHERE LOWER(b.nom) like LOWER("%' . $recherche . '%") 
							OR LOWER(b.pays) like LOWER("%' . $recherche . '%")
							OR b.format like ("%' . $recherche . '%") 
							OR b.code_saq like ("%' . $recherche . '%")
							OR LOWER(t.type) like LOWER("%' . $recherche . '%")';


		if (($res = $this->_db->query($requete)) ==     true) {
			if ($res->num_rows) {
				while ($row = $res->fetch_assoc()) {
					$row['nom'] = trim($row['nom']);
					$rows[] = $row;
				}
			}
		} else {

			throw new Exception("Erreur de requête sur la base de donnée", 1);
			//$this->_db->error;
		}
		return $rows;
	}

	/**
	 * Cette méthode retourne une bouteille contenue dans un cellier 
	 * 
	 * @return Array $rows contenant la bouteille.
	 */
	public function getBouteilleCellier($id, $cellier)

	{

		$rows = array();
		$requete = 'SELECT
                                c.vino__cellier_id,
                                c.vino__bouteille_id,
                                c.date_achat,
                                c.garde_jusqua,
                                c.notes,
                                c.prix,
                                c.quantite,
                                c.millesime,
                                b.nom,
                                b.id
                            FROM cellier__bouteille c
                            INNER JOIN ' . self::BOUTEILLE . ' b ON c.vino__bouteille_id = b.id
                            WHERE c.vino__cellier_id=' . $cellier . ' AND c.vino__bouteille_id =' . $id;

		if (($res = $this->_db->query($requete)) ==     true) {
			if ($res->num_rows) {
				while ($row = $res->fetch_assoc()) {
					$row['nom'] = trim($row['nom']);
					$rows[] = $row;
				}
			}
		} else {
			throw new Exception("Erreur de requête sur la base de donnée", 1);
			//$this->_db->error;
		}



		return $rows;
	}


	/**
	 * Cette méthode retourne un code SAQ d une bouteile contenue dans un cellier 
	 * 
	 * @return Array $rows contenant la bouteille.
	 */
	public function trouverCodeSaq($data)
	{
		$response = ["data" => null];
		$requete = 'SELECT
								c.vino__cellier_id,
								c.vino__bouteille_id,
								b.code_saq,
								b.id
							
							FROM ' . self::CELLIER_BOUTEILLE . ' c
							INNER JOIN ' . self::BOUTEILLE . ' b ON c.vino__bouteille_id = b.id
							WHERE b.code_saq =' . $data->code_saq . ' AND c.vino__cellier_id=' . $data->id_cellier;

		if (($res = $this->_db->query($requete)) ==     true) {
			if ($res->num_rows) {
				$row = $res->fetch_assoc();
				$response["data"] = true;
			}
		} else {
			throw new Exception("Erreur de requête sur la base de donnée", 1);
			//$this->_db->error;
		}
		return $response;
	}


	/**
	 * Cette méthode permet de retourner les résultats de recherche pour la fonction d'autocomplete de l'ajout des bouteilles dans le cellier
	 * 
	 * @param string $nom La chaine de caractère à rechercher
	 * @param integer $nb_resultat Le nombre de résultat maximal à retourner.
	 * 
	 * @throws Exception Erreur de requête sur la base de données 
	 * 
	 * @return array id et nom de la bouteille trouvée dans le catalogue
	 */
	public function autocomplete($nom, $nb_resultat = 10)
	{

		$rows = array();
		$nom = $this->_db->real_escape_string($nom);
		$nom = preg_replace("/\*/", "%", $nom);

		//echo $nom;
		$requete = 'SELECT 
							b.id, 
							b.nom, 
							b.code_saq, 
							b.prix_saq 
							FROM ' . self::BOUTEILLE . ' b 
							WHERE LOWER(nom) like LOWER("%' . $nom . '%")
							LIMIT 0,' . $nb_resultat;
		//var_dump($requete);
		if (($res = $this->_db->query($requete)) ==     true) {
			if ($res->num_rows) {
				while ($row = $res->fetch_assoc()) {
					$row['nom'] = trim($row['nom']);
					$rows[] = $row;
				}
			}
		} else {
			throw new Exception("Erreur de requête sur la base de données", 1);
		}
		//var_dump($rows);
		return $rows;
	}

	/**
	 * Cette méthode permet de retourner les résultats de recherche pour la fonction d'autocomplete de recherche de bouteilles dans le cellier
	 * 
	 * @param string $nom La chaine de caractère à rechercher
	 * @param integer $nb_resultat Le nombre de résultat maximal à retourner.
	 * 
	 * @throws Exception Erreur de requête sur la base de données 
	 * 
	 * @return array id et nom de la bouteille trouvée dans le cellier
	 */
	public function autocompleteCellier($nom, $cellier, $nb_resultat = 10)
	{

		$rows = array();
		$nom = $this->_db->real_escape_string($nom);
		$nom = preg_replace("/\*/", "%", $nom);

		//echo $nom;
		$requete = 'SELECT 
							c.vino__cellier_id,
							c.vino__bouteille_id, 
							b.nom 
							FROM ' . self::CELLIER_BOUTEILLE . ' c 
							INNER JOIN ' . self::BOUTEILLE . ' b ON c.vino__bouteille_id = b.id
							WHERE LOWER(nom) like LOWER("%' . $nom . '%") AND c.vino__cellier_id= ' . $cellier . ' LIMIT 0,' . $nb_resultat;
		//var_dump($requete);
		if (($res = $this->_db->query($requete)) ==     true) {
			if ($res->num_rows) {
				while ($row = $res->fetch_assoc()) {
					$row['nom'] = trim($row['nom']);
					$rows[] = $row;
				}
			}
		} else {
			throw new Exception("Erreur de requête sur la base de données", 1);
		}


		//var_dump($rows);
		return $rows;
	}

	/**
	 * Cette méthode permet de retourner les résultats de recherche pour la fonction d'autocomplete de recherche de bouteilles dans le catalogue
	 * 
	 * @param string $nom La chaine de caractère à rechercher
	 * @param integer $nb_resultat Le nombre de résultat maximal à retourner.
	 * 
	 * @throws Exception Erreur de requête sur la base de données 
	 * 
	 * @return array id et nom de la bouteille trouvée dans le catalogue
	 */
	public function autocompleteCatalogue($nom, $nb_resultat = 10)
	{

		$rows = array();
		$nom = $this->_db->real_escape_string($nom);
		$nom = preg_replace("/\*/", "%", $nom);

		//echo $nom;
		$requete = 'SELECT 
							id, 
							nom,
							pays,
							code_saq 
							FROM ' . self::BOUTEILLE . '
							WHERE LOWER(nom) like LOWER("%' . $nom . '%") LIMIT 0,' . $nb_resultat;
		//var_dump($requete);
		if (($res = $this->_db->query($requete)) ==     true) {
			if ($res->num_rows) {
				while ($row = $res->fetch_assoc()) {
					$row['nom'] = trim($row['nom']);
					$rows[] = $row;
				}
			}
		} else {
			throw new Exception("Erreur de requête sur la base de données", 1);
		}
		//var_dump($rows);
		return $rows;
	}


	/**
	 * Cette méthode modifie une ou des bouteilles au cellier
	 * 
	 * @param Object $data Tableau des données représentants la bouteille.
	 * 
	 * @return Boolean Succès ou échec de l'ajout.
	 */
	public function modifierInfoBouteilleCellier($data)
	{
		//TODO : Valider les données.
		// var_dump($data);	
		$reponse = ['erreurs' => null, 'data' => null];

		//Validation des données :
		/* date : */
		if (!preg_match('/^((19||20)\d{2}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))$/', $data->date_achat) || $data->date_achat > date('Y-m-d')) {
			$this->erreurs['date_achat'] = "La date doit être au format AAAA-MM-JJ et comprise entre 1900 et aujourd'hui.";
		}

		/* garde_jusqu'à */

		if ($data->garde_jusqua !== '' && !preg_match('/^\d{4}$/', $data->garde_jusqua)) {

			$this->erreurs['garde_jusqua'] = "Veuillez entrer l'année jusqu'à laquelle vous pouvez garder cette bouteille.";
		}

		/* validation notes : */

		if ($data->notes !== '' && !preg_match('/^.{1,200}$/', $data->notes)) {

			$this->erreurs['notes'] = "Veuillez entrer un commentaire sur le vin de 200 caractères au maximum.";
		}

		/* validation prix */
		if (!preg_match('/^(0|[1-9][0-9]*)(\.[0-9]{2})?$/', $data->prix)) {
			$this->erreurs['prix'] = "Veuillez entrer le prix au format suivant 12.34";
		}

		/* validation quantité */
		if (!preg_match('/^\d+$/', $data->quantite)) {
			$this->erreurs['quantite'] = "Veuillez entrer la quantité en chiffre.";
		}

		/* millesime */
		if ($data->millesime !== "" && !preg_match('/^\d{4}$/', $data->millesime)) {
			$this->erreurs['millesime'] = "Veuillez entrer une année à 4 chiffres.";
		}
		if ($data->millesime !== "" && $data->millesime > date('Y')) {
			$this->erreurs['millesime'] = "Veuillez entrer une année inférieur ou égale à l'année en cours.";
		}

		if (empty($this->erreurs)) {

			$requete = "UPDATE 	cellier__bouteille SET 
								date_achat = '$data->date_achat',
								garde_jusqua = '$data->garde_jusqua',
								notes = '$data->notes',
								prix = '$data->prix',
								quantite = '$data->quantite',
								millesime = '$data->millesime'
								WHERE vino__bouteille_id = '$data->id_bouteille' 
								AND vino__cellier_id = '$data->id_cellier'";

			$reponse['data'] = $this->_db->query($requete);
		} else {
			$reponse['erreurs'] = $this->erreurs;
		}

		return $reponse;
	}

	/**
	 * Cette méthode ajoute une ou des bouteilles au cellier
	 * 
	 * @param Object $data Tableau des données représentants la bouteille.
	 * 
	 * @return Array contenant la retour de la requete SQL ($reponse['data']) et les éventuelles erreurs de formulaire $reponse['erreurs].
	 */
	public function ajouterBouteilleCellier($data)
	{
		$reponse = ['erreurs' => null, 'data' => null];

		//Validation des données :
		/* date : */
		if (!preg_match('/^((19||20)\d{2}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))$/', $data->date_achat) || $data->date_achat > date('Y-m-d')) {
			$this->erreurs['date_achat'] = "La date doit être au format AAAA-MM-JJ et comprise entre 1900 et aujourd'hui.";
		}

		/* garde_jusqu'à */

		if ($data->garde_jusqua !== '' && !preg_match('/^\d{4}$/', $data->garde_jusqua)) {

			$this->erreurs['garde_jusqua'] = "Veuillez entrer l'année jusqu'à laquelle vous pouvez garder cette bouteille.";
		}

		/* validation notes : */

		if ($data->notes !== '' && !preg_match('/^.{1,200}$/', $data->notes)) {

			$this->erreurs['notes'] = "Veuillez entrer un commentaire sur le vin de 200 caractères au maximum.";
		}

		/* validation prix */
		if (!preg_match('/^(0|[1-9][0-9]*)(\.[0-9]{2})?$/', $data->prix)) {
			$this->erreurs['prix'] = "Veuillez entrer le prix au format suivant 12.34";
		}

		/* validation quantité */
		if (!preg_match('/^\d+$/', $data->quantite)) {
			$this->erreurs['quantite'] = "Veuillez entrer la quantité en chiffre.";
		}

		/* millesime */
		if ($data->millesime !== "" && !preg_match('/^\d{4}$/', $data->millesime)) {
			$this->erreurs['millesime'] = "Veuillez entrer une année à 4 chiffres.";
		}
		if ($data->millesime !== "" && $data->millesime > date('Y')) {
			$this->erreurs['millesime'] = "Veuillez entrer une année inférieur ou égale à l'année en cours.";
		}

		if (empty($this->erreurs)) {
			//requete pour vérifier si la bouteille à ajouter n'est pas déjà au cellier :
			$sql = "SELECT 	c.vino__cellier_id, 
							c.vino__bouteille_id,
							c.millesime,
							b.nom,
							b.id
							FROM " . self::CELLIER_BOUTEILLE . " c
							INNER JOIN vino__bouteille b ON c.vino__bouteille_id = b.id
							WHERE c.vino__cellier_id = " . $data->id_cellier . " AND c.vino__bouteille_id =" . "'$data->id_bouteille'" . " AND c.millesime =" . $data->millesime;


			//Si la bouteille est déjà au cellier et meme millesime, on incrémente seulement sa quantité, 
			// sinon on créé un nouvelle référence au cellier avec le millesime different :
			if ($this->_db->query($sql)->num_rows > 0) {
				$reponse['data'] = $this->modifierQuantiteBouteilleCellier($data->id_bouteille, $data->id_cellier, $data->quantite);
			} else {

				$requete = "INSERT INTO " . self::CELLIER_BOUTEILLE . " (vino__cellier_id,vino__bouteille_id,date_achat,garde_jusqua,notes,prix,quantite,millesime) VALUES (?,?,?,?,?,?,?,?)";
				$stmt = $this->_db->prepare($requete);
				$stmt->bind_param('iisisdii', $data->id_cellier, $data->id_bouteille, $data->date_achat, $data->garde_jusqua, $data->notes, $data->prix, $data->quantite, $data->millesime);
				$reponse['data'] = $stmt->execute();
			}
		} else {
			$reponse['erreurs'] = $this->erreurs;
		}

		return $reponse;
	}


	/**
	 * Cette méthode change la quantité d'une bouteille en particulier dans le cellier
	 * 
	 * @param int $id id de la bouteille
	 * @param int $nombre Nombre de bouteille a ajouter ou retirer
	 * 
	 * @return Boolean Succès ou échec de l'ajout.
	 */
	public function modifierQuantiteBouteilleCellier($id_bouteille, $id_cellier, $nombre)
	{

		/* validation du nombre de bouteilles */
		if (preg_match('/^[-+]?\d*$/', $nombre)) {

			$requete = "UPDATE " . self::CELLIER_BOUTEILLE . " SET quantite = GREATEST(quantite + " . $nombre . ", 0) WHERE vino__bouteille_id = " . $id_bouteille . " AND vino__cellier_id = $id_cellier";
			//echo $requete;
			$res = $this->_db->query($requete);

			return $res;
		}
	}

	/**
	 * Cette méthode supprime une bouteille selon l'id dans le cellier
	 * 
	 * @param int $id id de la bouteille
	 * 
	 * @return Boolean Succès ou échec de la suppression.
	 */
	public function supprimerBouteilleCellier($id, $cellier)
	{

		$requete = "DELETE FROM " . self::CELLIER_BOUTEILLE . " WHERE vino__bouteille_id =" . $id . " AND vino__cellier_id =" . $cellier;

		if ($stmt = $this->_db->prepare($requete)) {
			$stmt->execute();
			return true;
		} else {
			//   var_dump($this->_db->error);

			return false;
		}
	}
}
