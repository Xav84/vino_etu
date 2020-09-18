/**
 * @file Script contenant les fonctions de base
 * @author Jonathan Martel (jmartel@cmaisonneuve.qc.ca)
 * @version 0.1
 * @update 2019-01-21
 * @license Creative Commons BY-NC 3.0 (Licence Creative Commons Attribution - Pas d’utilisation commerciale 3.0 non transposé)
 * @license http://creativecommons.org/licenses/by-nc/3.0/deed.fr
 *
 */

const BaseURL = "http://localhost/Projet_final/Git/projets/vino_etu/";
//const BaseURL = document.baseURI;
console.log(BaseURL);
window.addEventListener("load", function () {
  console.log("load");

  let bouteille = {
    nom: document.querySelector(".nom_bouteille"),
    nomBtlCellier: document.querySelector(".nom_bouteille_cellier"),
    millesime: document.querySelector("[name='millesime']"),
    quantite: document.querySelector("[name='quantite']"),
    date_achat: document.querySelector("[name='date_achat']"),
    code: document.querySelector("[name='code_saq']"),
    prix: document.querySelector("[name='prix']"),
    garde_jusqua: document.querySelector("[name='garde_jusqua']"),
    notes: document.querySelector("[name='notes']"),
  };

  /* Comportement du bouton "boire" sur la page de cellier :*/
  document.querySelectorAll(".btnBoire").forEach(function (element) {
    element.addEventListener("click", function (evt) {
      let param = {
        id_bouteille: evt.target.parentElement.dataset.id_bouteille,
        id_cellier: evt.target.parentElement.dataset.id_cellier,
      };
      let requete = new Request(
        BaseURL + "index.php?requete=boireBouteilleCellier",
        { method: "POST", body: JSON.stringify(param) }
      );

      fetch(requete)
        .then((response) => {
          if (response.status === 200) {
            //récupérer la quantité affichée de bouteille dans le cellier et soustraire 1
            element.parentElement.parentElement.querySelector(
              ".quantite"
            ).firstElementChild.innerHTML =
              parseInt(
                element.parentElement.parentElement.querySelector(".quantite")
                  .firstElementChild.innerHTML
              ) - 1;
            return response.json();
          } else {
            throw new Error("Erreur");
          }
        })
        .then((response) => {
          console.debug(response);
        })
        .catch((error) => {
          console.error(error);
        });
      evt.preventDefault();
    });
  });

  /* Comportement du bouton "Ajouter" sur la page de cellier : */
  document.querySelectorAll(".btnAjouter").forEach(function (element) {
    element.addEventListener("click", function (evt) {
      let param = {
        id_bouteille: evt.target.parentElement.dataset.id_bouteille,
        id_cellier: evt.target.parentElement.dataset.id_cellier,
      };
      let requete = new Request(
        BaseURL + "index.php?requete=ajouterBouteilleCellier",
        { method: "POST", body: JSON.stringify(param) }
      );

      fetch(requete)
        .then((response) => {
          if (response.status === 200) {
            //récupérer la quantité affichée de bouteille dans le cellier et ajouter 1
            element.parentElement.parentElement.querySelector(
              ".quantite"
            ).firstElementChild.innerHTML =
              parseInt(
                element.parentElement.parentElement.querySelector(".quantite")
                  .firstElementChild.innerHTML
              ) + 1;
            return response.json();
          } else {
            throw new Error("Erreur");
          }
        })
        .then((response) => {
          if (
            response.data == true &&
            document.querySelector(".msg_sql") != null
          ) {
            console.log(document.querySelector(".msg_sql"));
            document.querySelector(".msg_sql").innerHTML =
              "Modification effectué";
          } else if (document.querySelector(".msg_sql") != null) {
            document.querySelector(".msg_sql").innerHTML =
              "Erreur de modification";
          }
        })
        .catch((error) => {
          console.error(error);
        });
      evt.preventDefault();
    });
  });

  /* comportement du formulaire d'ajout d'une nouvelle bouteille au cellier : */
  let inputNomBouteille = document.querySelector("[name='nom_bouteille']");
  // console.log(inputNomBouteille);
  let liste = document.querySelector(".listeAutoComplete");

  //fonctionnement de l'auto-complétion de l'ajout de bouteille au cellier :
  if (inputNomBouteille) {
    inputNomBouteille.addEventListener("keyup", function (evt) {
      console.log(evt);
      let nom = inputNomBouteille.value;
      liste.innerHTML = "";
      if (nom) {
        let requete = new Request(
          BaseURL + "index.php?requete=autocompleteBouteille",
          { method: "POST", body: '{"nom": "' + nom + '"}' }
        );
        fetch(requete)
          .then((response) => {
            if (response.status === 200) {
              return response.json();
            } else {
              throw new Error("Erreur");
            }
          })
          .then((response) => {
            console.log(response);

            response.forEach(function (element) {
              console.log(element);
              liste.innerHTML +=
                "<li data-id='" +
                element.id +
                "'data-prix ='" +
                element.prix_saq +
                "'data-code ='" +
                element.code_saq +
                "'data-format ='" +
                element.format +
                "'>" +
                element.nom +
                "</li>";
            });
          })
          .catch((error) => {
            console.error(error);
          });
      }
    });

    //Insertion du nom cliqué :
    liste.addEventListener("click", function (evt) {
      console.dir(evt.target);
      if (evt.target.tagName == "LI") {
        bouteille.nom.dataset.id = evt.target.dataset.id;
        bouteille.nom.innerHTML = evt.target.innerHTML;
        // bouteille.prix.dataset.prix = evt.target.dataset.prix;
        bouteille.prix.innerHTML = evt.target.dataset.prix + "$";
        // bouteille.code_saq.dataset.code_saq = evt.target.dataset.code_saq;
        bouteille.code.innerHTML = evt.target.dataset.code;

        liste.innerHTML = "";
        inputNomBouteille.value = "";
      }
    });

    //Comportement du bouton "ajouter la bouteille" du formulaire d'ajout de bouteille au cellier :
    let btnAjouter = document.querySelector("[name='ajouterBouteilleCellier']");
    if (btnAjouter) {
      btnAjouter.addEventListener("click", function (evt) {
        var param = {
          id_cellier: bouteille.nom.dataset.id_cellier,
          id_bouteille: bouteille.nom.dataset.id,
          date_achat: bouteille.date_achat.value,
          garde_jusqua: bouteille.garde_jusqua.value,
          notes: bouteille.notes.value,
          prix: bouteille.prix.innerHTML.slice(0, -1),
          code: bouteille.code.innerHTML,
          quantite: bouteille.quantite.value,
          millesime: bouteille.millesime.value,
        };
        console.log(param);
        let requete = new Request(
          BaseURL + "index.php?requete=ajouterNouvelleBouteilleCellier",
          { method: "POST", body: JSON.stringify(param) }
        );

        let modal = document.querySelector(".modal");

        fetch(requete)
          .then((response) => {
            if (response.status === 200) {
              return response.json();
            } else {
              throw new Error("Erreur");
            }
          })
          .then((response) => {
            if (response.data == null) {
              document.querySelector(".millesime").innerHTML =
                response.erreurs.millesime || "";
              document.querySelector(".date_achat").innerHTML =
                response.erreurs.date_achat || "";
              document.querySelector(".garde_jusqua").innerHTML =
                response.erreurs.garde_jusqua || "";
              document.querySelector(".notes").innerHTML =
                response.erreurs.notes || "";
              document.querySelector(".prix").innerHTML =
                response.erreurs.prix || "";
              document.querySelector(".quantite").innerHTML =
                response.erreurs.quantite || "";
            }
            if (response.data == true) {
              modal.style.display = "block";
              console.log(document.querySelector(".msg_sql"));
              document.querySelector(".msg_sql").innerHTML = "Ajout effectué";
            } else {
              document.querySelector(".msg_sql").innerHTML = "Erreur d'ajout";
            }
          })
          .catch((error) => {
            console.error(error);
          });
        //redirect vers le cellier
        document
          .querySelector(".retour_cellier")
          .addEventListener("click", (_) => {
            window.location.href = BaseURL;
          });
      });
    }
  }

  /*
   *
   *
   * Autocompletion de la recherche dans le cellier
   *
   *
   * */
  let listeCellier = document.querySelector(".listeAutoCompleteCellier");

  let inputNomBouteilleCellier = document.querySelector(
    "[name='nom_bouteille_cellier']"
  );
  if (inputNomBouteilleCellier) {
    inputNomBouteilleCellier.addEventListener("keyup", function (evt) {
      // console.log(evt);
      let nom = inputNomBouteilleCellier.value;
      listeCellier.innerHTML = "";
      if (nom) {
        let requete = new Request(
          BaseURL + "index.php?requete=autocompleteBouteilleCellier",
          { method: "POST", body: '{"nom": "' + nom + '"}' }
        );
        fetch(requete)
          .then((response) => {
            if (response.status === 200) {
              return response.json();
            } else {
              throw new Error("Erreur");
            }
          })
          .then((response) => {
            // console.log(response);

            response.forEach(function (element) {
              //Affichage des résultats de recherche d'auto-complétion pour la recherche dans le cellier:
              listeCellier.innerHTML +=
                "<li data-id='" +
                element.vino__bouteille_id +
                "'>" +
                element.nom +
                "</li>";
            });
          })
          .catch((error) => {
            console.error(error);
          });
      }
    });
  }
  //Insertion du nom de la bouteille cliqué dans le champ de recherche du cellier:
  if (listeCellier) {
    listeCellier.addEventListener("click", function (evt) {
      console.dir(evt.target);
      if (evt.target.tagName == "LI") {
        bouteille.nomBtlCellier.dataset.id = evt.target.dataset.id;
        bouteille.nomBtlCellier.value = evt.target.innerText;
        listeCellier.innerHTML = "";
        // inputNomBouteilleCellier.value = "";
      }
    });
  }

  //Comportement du bouton "modifier la bouteille" de la page modifier :
  let btnModifier = document.querySelector("[name='modifierBouteilleCellier']");
  if (btnModifier) {
    btnModifier.addEventListener("click", function (evt) {
      console.log("click btn modifier");
      var param = {
        id_cellier: bouteille.nom.dataset.id_cellier,
        id_bouteille: bouteille.nom.dataset.id,
        date_achat: bouteille.date_achat.value,
        garde_jusqua: bouteille.garde_jusqua.value,
        notes: bouteille.notes.value,
        prix: bouteille.prix.value,
        quantite: bouteille.quantite.value,
        millesime: bouteille.millesime.value,
      };
      let requete = new Request(
        BaseURL + "index.php?requete=modifierBouteilleCellier",
        { method: "POST", body: JSON.stringify(param) }
      );

      let modal = document.querySelector(".modal");

      fetch(requete)
        .then((response) => {
          if (response.status === 200) {
            return response.json();
          } else {
            throw new Error("Erreur");
          }
        })
        .then((response) => {
          if (response.data == null) {
            console.log(response);
            document.querySelector(".millesime").innerHTML =
              response.erreurs.millesime || "";
            document.querySelector(".date_achat").innerHTML =
              response.erreurs.date_achat || "";
            document.querySelector(".garde_jusqua").innerHTML =
              response.erreurs.garde_jusqua || "";
            document.querySelector(".notes").innerHTML =
              response.erreurs.notes || "";
            document.querySelector(".prix").innerHTML =
              response.erreurs.prix || "";
            document.querySelector(".quantite").innerHTML =
              response.erreurs.quantite || "";
          }
          if (response.data == true) {
            modal.style.display = "block";
            console.log(document.querySelector(".msg_sql"));
            document.querySelector(".msg_sql").innerHTML =
              "Modification effectué";
          } else {
            document.querySelector(".msg_sql").innerHTML =
              "Erreur de modification";
          }
        })
        .catch((error) => {
          console.error(error);
        });
      document
        .querySelector(".retour_cellier")
        .addEventListener("click", (_) => {
          window.location.href = BaseURL;
        });
    });
  }

  // Comportement du bouton "valider_authentification" du la page d'authentification :
  let btnAuthentification = document.querySelector(
    "[name='validerAuthentification']"
  );
  console.log(btnAuthentification);
  if (btnAuthentification) {
    btnAuthentification.addEventListener("click", function (evt) {
      document.querySelector(".erreur").innerHTML = "";
      var param = {
        courriel: document.querySelector("[name='courriel']").value,
        mdp: document.querySelector("[name='mdp']").value,
      };
      let requete = new Request(
        BaseURL + "index.php?requete=authentification",
        { method: "POST", body: JSON.stringify(param) }
      );

      fetch(requete)
        .then((response) => {
          if (response.status === 200) {
            return response.json();
          } else {
            throw new Error("Erreur");
          }
        })
        .then((response) => {
          if (response.data !== null) {
            //la requête à fonctionnée, redirection vers la page du cellier de l'utilisateur connecté :
            window.location =
              BaseURL +
              "index.php?requete=afficherCellier&id_utilisateur=" +
              response.data.id_utilisateur;
            console.log("aller vers le cellier");
          } else if (response.data == null && response.erreurs == null) {
            //la requete à focntionnée mais n'a rien retournée
            document.querySelector(".identifiants_inconnus").innerHTML =
              "Aucun compte utlisateur lié aux identifiants renseignés";
          } else if (response.erreurs !== null) {
            document.querySelector(".courriel").innerHTML =
              response.erreurs.courriel || "";
          }
        })
        .catch((error) => {
          console.error(error);
        });
    });
  }
});
