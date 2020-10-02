/**
 * @file Script contenant les fonctions de base
 * @author Jonathan Martel (jmartel@cmaisonneuve.qc.ca)
 * @version 0.1
 * @update 2019-01-21
 * @license Creative Commons BY-NC 3.0 (Licence Creative Commons Attribution - Pas d’utilisation commerciale 3.0 non transposé)
 * @license http://creativecommons.org/licenses/by-nc/3.0/deed.fr
 *
 */

//const BaseURL = "https://jmartel.webdev.cmaisonneuve.qc.ca/n61/vino/";

//const BaseURL = "http://localhost/vino_etu/";
const BaseURL = document.baseURI;

console.log(BaseURL);
window.addEventListener("load", function () {
  console.log("load");

  let bouteille = {
    nom: document.querySelector(".nom_bouteille"),
    nomBtlCellier: document.querySelector(".nom_bouteille_cellier"),
    nomBtlCatalogue: document.querySelector(".nom_bouteille_catalogue"),
    millesime: document.querySelector("[name='millesime']"),
    quantite: document.querySelector("[name='quantite']"),
    date_achat: document.querySelector("[name='date_achat']"),
    code: document.querySelector("[name='code_saq']"),
    prix: document.querySelector("[name='prix']"),
    prix_saq: document.querySelector("[name='prix_saq']"),
    garde_jusqua: document.querySelector("[name='garde_jusqua']"),
    notes: document.querySelector("[name='notes']"),
    pays: document.querySelector("[name='pays']"),
    type: document.querySelector("[name='type']"),
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
        {
          method: "POST",
          body: JSON.stringify(param),
        }
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
        {
          method: "POST",
          body: JSON.stringify(param),
        }
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
      let nom = inputNomBouteille.value;
      liste.innerHTML = "";
      if (nom) {
        let requete = new Request(
          BaseURL + "index.php?requete=autocompleteBouteille",
          {
            method: "POST",
            body: '{"nom": "' + nom + '"}',
          }
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
            response.forEach(function (element) {
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
      if (evt.target.tagName == "LI") {
        // touver la date du jour pour ensuite pré-remplir le champs date_achat
        let dateDuJour = new Date().toISOString().substring(0, 10);
        document.querySelector("[name='date_achat']").value = dateDuJour;
        // console.log("DATE", dateDuJour);

        bouteille.nom.dataset.id = evt.target.dataset.id;
        bouteille.nom.innerHTML = evt.target.innerHTML;

        //insertion du prix récupéré du catalogue de la saq dans le formulaire
        document.getElementById("prix_bouteille").value =
          evt.target.dataset.prix;
        bouteille.prix.innerHTML = evt.target.dataset.prix;
        //insertion du code saq dans le formulaire
        bouteille.code.innerHTML = evt.target.dataset.code;
        liste.innerHTML = "";
        inputNomBouteille.value = "";
        document.getElementById("messageSAQ").innerHTML = "";
        /* Vérification si la bouteille se trouve déja dans le cellier
         *   Si oui un message en informe l'usager
         */
        var paramSAQ = {
          id_cellier: bouteille.nom.dataset.id_cellier,
          id_bouteille: bouteille.nom.dataset.id,
          code_saq: document.querySelector("[name='code_saq']").innerHTML,
        };
        // console.log("PARAM SAQ", paramSAQ);
        let requete = new Request(BaseURL + "index.php?requete=infoCodeSaq", {
          method: "POST",
          body: JSON.stringify(paramSAQ),
        });
        // console.log("REQUETE", requete);
        fetch(requete)
          .then((response) => {
            if (response.status === 200) {
              return response.json();
            } else {
              throw new Error("Erreur");
            }
          })
          .then((response) => {
            //affichage du message si la bouteille se trouve déja dans le cellier
            if (response.data !== null) {
              document.getElementById("messageSAQ").innerHTML =
                "Cette bouteille est déja dans votre cellier.";
            }
          })
          .catch((error) => {
            document.getElementById("messageSAQ").innerHTML = "";
            console.error(error);
          });
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
          prix: bouteille.prix.value,
          quantite: bouteille.quantite.value,
          millesime: bouteille.millesime.value,
        };
        console.log(param);
        let requete = new Request(
          BaseURL + "index.php?requete=ajouterNouvelleBouteilleCellier",
          {
            method: "POST",
            body: JSON.stringify(param),
          }
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
            console.log("response2", response);
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

  /*
   *
   *
   * Autocompletion de la recherche dans le catalogue
   *
   *
   * */
  let listeCatalogue = document.querySelector(".listeAutoCompleteCatalogue");

  let inputNomBouteilleCatalogue = document.querySelector(
    "[name='nom_bouteille_catalogue']"
  );
  if (inputNomBouteilleCatalogue) {
    inputNomBouteilleCatalogue.addEventListener("keyup", function (evt) {
      // console.log(evt);
      let nom = inputNomBouteilleCatalogue.value;
      listeCatalogue.innerHTML = "";
      if (nom) {
        let requete = new Request(
          BaseURL + "index.php?requete=autocompleteBouteilleCatalogue",
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
              //Affichage des résultats de recherche d'auto-complétion pour la recherche dans le catalogue:
              listeCatalogue.innerHTML +=
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

  //Insertion du nom de la bouteille cliqué dans le champ de recherche du catalogue:
  if (listeCatalogue) {
    listeCatalogue.addEventListener("click", function (evt) {
      console.dir(evt.target);
      if (evt.target.tagName == "LI") {
        bouteille.nomBtlCatalogue.dataset.id = evt.target.dataset.id;
        bouteille.nomBtlCatalogue.value = evt.target.innerText;
        listeCatalogue.innerHTML = "";
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
        {
          method: "POST",
          body: JSON.stringify(param),
        }
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

  //Comportement du bouton "modifier la bouteille" de la page modifierCatalogue :
  let btnModifierCatalogue = document.querySelector(
    "[name='modifierBouteilleCatalogue']"
  );
  if (btnModifierCatalogue) {
    btnModifierCatalogue.addEventListener("click", function (evt) {
      console.log("click btn modifier");
      var paramCatalogue = {
        id: bouteille.nom.dataset.id,
        nom: bouteille.nom.value,
        prix_saq: bouteille.prix_saq.value,
        pays: bouteille.pays.value,
        type: bouteille.type.value,
      };
      console.log(paramCatalogue);
      let requete = new Request(
        BaseURL + "index.php?requete=modifierBouteilleCatalogue",
        {
          method: "POST",
          body: JSON.stringify(paramCatalogue),
        }
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
            document.querySelector(".pays").innerHTML =
              response.erreurs.pays || "";
            document.querySelector(".prix").innerHTML =
              response.erreurs.prix || "";
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
        .querySelector(".retour_catalogue")
        .addEventListener("click", (_) => {
          window.location.href = `${BaseURL}?requete=afficherCatalogue`;
        });
    });
  }

  // Comportement du bouton "valider_authentification" du la page d'authentification :
  let btnAuthentification = document.querySelector(
    "[name='validerAuthentification']"
  );
  if (btnAuthentification) {
    btnAuthentification.addEventListener("click", function (evt) {
      document.querySelector(".erreur").innerHTML = "";
      var param = {
        courriel: document.querySelector("[name='courriel']").value,
        mdp: document.querySelector("[name='mdp']").value,
      };

      let requete = new Request(
        BaseURL + "index.php?requete=authentification",
        {
          method: "POST",
          body: JSON.stringify(param),
        }
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
          //la requête à fonctionnée, redirection vers la page du cellier de l'utilisateur connecté :
          if (response.data !== null) {
            window.location =
              BaseURL +
              "index.php?requete=afficherCellier&id_utilisateur=" +
              response.data.id_utilisateur;

            //la requete à fonctionnée mais n'a rien retournée
          } else if (response.data == null && response.erreurs == null) {
            document.querySelector(".identifiants_inconnus").innerHTML =
              "Aucun compte utlisateur lié aux identifiants renseignés";

            //il y a des erreurs de validation du formulaire :
          } else if (response.erreurs !== null) {
            document.querySelector(".courriel").innerHTML =
              response.erreurs.courriel || "";
            document.querySelector(".mdp").innerHTML =
              response.erreurs.mdp || "";
          }
        })
        .catch((error) => {
          console.error(error);
        });
    });
  }

  //Comportement du bouton "créer compte" de la page creeCompte.php :
  let btnCreerCompte = document.querySelector(".confirmerCompte");
  if (btnCreerCompte) {
    btnCreerCompte.addEventListener("click", function (evt) {
      evt.preventDefault();
      document.querySelector(".prenom").innerHTML = "";
      document.querySelector(".nom").innerHTML = "";
      document.querySelector(".courriel").innerHTML = "";
      document.querySelector(".mdp").innerHTML = "";
      var utilisateur = {
        prenom: document.querySelector("[name='prenom']").value,
        nom: document.querySelector("[name='nom']").value,
        courriel: document.querySelector("[name='courriel']").value,
        mdp: document.querySelector("[name='mdp']").value,
      };
      let requete = new Request(BaseURL + "index.php?requete=creerCompte", {
        method: "POST",
        body: JSON.stringify(utilisateur),
      });

      fetch(requete)
        .then((response) => {
          if (response.status === 200) {
            return response.json();
          } else {
            throw new Error("Erreur");
          }
        })
        .then((response) => {
          //si la création de compte s'est bien passée, authentification directe du nouvel utilisateur :
          if (response.data === true) {
            var param = {
              courriel: response.email,
              mdp: response.mdp,
            };
            let requete2 = new Request(
              BaseURL + "index.php?requete=authentification",
              { method: "POST", body: JSON.stringify(param) }
            );
            return fetch(requete2);

            //affichage des erreurs renvoyées par la vérification des données du formulaire :
          } else if (response.erreurs != null) {
            document.querySelector(".prenom").innerHTML =
              response.erreurs.prenom || "";
            document.querySelector(".nom").innerHTML =
              response.erreurs.nom || "";
            document.querySelector(".courriel").innerHTML =
              response.erreurs.courriel || "";
            document.querySelector(".mdp").innerHTML =
              response.erreurs.mdp || "";

            //affichage d'une erreur si le courriel est déjà dans la base de données :
          } else if (response.existant != null) {
            document.querySelector(".resultat").innerHTML =
              "Il existe déjà un compte utilisateur lié à ce courriel" || "";
          }
          console.log(response);
        })
        .then((response2) => {
          //si l'authentification du nouvel utilisateur s'est bien passée, redirection vers la page d'accueil (son cellier)
          if (response2.status === 200) {
            window.location.href = BaseURL;
          } else {
            throw new Error("Erreur");
          }
        })
        .catch((error) => {
          console.error(error);
        });
    });
  }
  /* Comportement du bouton "supprimer" sur la page de cellier */
  document.querySelectorAll(".btnSupprimer").forEach(function (element) {
    element.addEventListener("click", function (evt) {
      console.log("btn supprimer");

      let id = evt.target.parentElement.dataset.id;
      let requete = new Request(
        BaseURL + "index.php?requete=supprimerBouteille",
        {
          method: "DELETE",
          body: '{"id": ' + id + "}",
        }
      );
      // Ajouter une confirmation pour la suppression
      let resultat = confirm("Voulez vraiment supprimer cette bouteille?");
      if (resultat === true) {
        fetch(requete)
          .then((response) => {
            if (response.status === 200) {
              console.log(response.status);
              console.log("suppression effectuée");
              window.location.href = BaseURL;
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
      } else {
        window.location.href = BaseURL;
        console.log("suppression non effectuée");
        evt.preventDefault();
      }
    });
  });

  //Évenement lié à la bulle info sur la page ajouter et modifier une bouteille
  // Ajoute la classe apparait pour rendre la bulle visible

  let bulle = document.querySelector(".remplir_Champs");
  if (bulle) {
    bulle.addEventListener("click", function () {
      let info = document.getElementById("fenetre_info");
      info.classList.toggle("apparait");
    });
  }
});
