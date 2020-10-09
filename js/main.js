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
        note_degustation: document.querySelector("[name='note_degustation']")
    };

    /* Comportement du bouton "boire" sur la page de cellier :*/
    document.querySelectorAll(".btnBoire").forEach(function (element) {
        element.addEventListener("click", function (evt) {
            let param = {
                id_bouteille: evt.target.parentElement.dataset.id_bouteille,
                id_cellier: evt.target.parentElement.dataset.id_cellier,
            }
            let requete = new Request(
                BaseURL + "index.php?requete=boireBouteilleCellier", {
                method: "POST",
                body: JSON.stringify(param)
            }
            );

            fetch(requete)
                .then((response) => {
                    if (response.status === 200) {
                        //récupérer la quantité affichée de bouteille dans le cellier et soustraire 1
                        let quantite = element.parentElement.parentElement.querySelector(".quantite").firstElementChild;
                        if (parseInt(quantite.innerHTML) - 1 > 0) quantite.innerHTML = parseInt(quantite.innerHTML) - 1;
                        else quantite.innerHTML = 0;
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
            }
            let requete = new Request(
                BaseURL + "index.php?requete=ajouterBouteilleCellier", {
                method: "POST",
                body: JSON.stringify(param)
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
                    if (response.data == true && document.querySelector(".msg_sql") != null) {
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
    let liste = document.querySelector(".listeAutoComplete");

    //fonctionnement de l'auto-complétion de l'ajout de bouteille au cellier :
    if (inputNomBouteille) {
        inputNomBouteille.addEventListener("keyup", function (evt) {
            let nom = inputNomBouteille.value;
            liste.innerHTML = "";
            if (nom) {
                let requete = new Request(
                    BaseURL + "index.php?requete=autocompleteBouteille", {
                    method: "POST",
                    body: '{"nom": "' + nom + '"}'
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
            // touver la date du jour pour ensuite pré-remplir le champs date_achat
            let dateDuJour = new Date().toISOString().substring(0, 10);
            document.querySelector("[name='date_achat']").value = dateDuJour;
            if (evt.target.tagName == "LI") {
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
                let requete = new Request(BaseURL + "index.php?requete=infoCodeSaq", {
                    method: "POST",
                    body: JSON.stringify(paramSAQ),
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

                let requete = new Request(
                    BaseURL + "index.php?requete=ajouterNouvelleBouteilleCellier", {
                    method: "POST",
                    body: JSON.stringify(param)
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
                            /* vider les spans d'erreur */
                            document.querySelectorAll('.erreur').forEach(element => {
                                element.innerHTML = " ";
                            })
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
            var param = {
                id_cellier: bouteille.nom.dataset.id_cellier,
                id_bouteille: bouteille.nom.dataset.id,
                date_achat: bouteille.date_achat.value,
                garde_jusqua: bouteille.garde_jusqua.value,
                notes: bouteille.notes.value,
                prix: bouteille.prix.value,
                quantite: bouteille.quantite.value,
                millesime: bouteille.millesime.value,
                note_degustation: bouteille.note_degustation.value
            };
            let requete = new Request(
                BaseURL + "index.php?requete=modifierBouteilleCellier", {
                method: "POST",
                body: JSON.stringify(param)
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
                        document.querySelector(".evaluation").innerHTML =
                            response.erreurs.note_degustation || "";
                    }
                    if (response.data == true) {
                        modal.style.display = "block";
                        /* vider les spans d'erreurs */
                        document.querySelectorAll('.erreur').forEach(element => {
                            element.innerHTML = " ";
                        })
                        document.querySelector(".msg_sql").innerHTML =
                            "Modification effectué";
                    } else {
                        document.querySelector(".msg_sql").innerHTML =
                            "Erreur de modification";
                    }
                })

                .catch((error) => {
                    console.error(error);

                })
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
            var paramCatalogue = {
                id: bouteille.nom.dataset.id,
                nom: bouteille.nom.value,
                prix_saq: bouteille.prix_saq.value,
                pays: bouteille.pays.value,
                type: bouteille.type.value,
            };
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
                        document.querySelector(".pays").innerHTML =
                            response.erreurs.pays || "";
                        document.querySelector(".prix").innerHTML =
                            response.erreurs.prix || "";
                    }
                    if (response.data == true) {
                        modal.style.display = "block";
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
    let btnAuthentification = document.querySelector("[name='validerAuthentification']");
    if (btnAuthentification) {
        btnAuthentification.addEventListener("click", function (evt) {
            document.querySelector(".erreur").innerHTML = '';
            var param = {
                courriel: document.querySelector("[name='courriel']").value,
                mdp: document.querySelector("[name='mdp']").value
            };

            let requete = new Request(
                BaseURL + "index.php?requete=authentification", {
                method: "POST",
                body: JSON.stringify(param)
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
                    //la requête à fonctionnée et l'utilisateur n'est pas administrateur, redirection vers la page du cellier de l'utilisateur connecté :
                    if (response.data && response.data.type_utilisateur == 2) {
                        window.location = BaseURL + "index.php?requete=afficherCellier&id_utilisateur=" + response.data.id_utilisateur;

                        //la requête à fonctionnée et l'utilisateur est administrateur, redirection vers la page du cellier de l'utilisateur connecté :
                    } else if (response.data && response.data.type_utilisateur == 1) {
                        window.location = BaseURL + "index.php?requete=afficherInterfaceAdmin";

                        //la requete à fonctionnée mais n'a rien retournée
                    } else if (response.data == null && response.erreurs == null) {
                        document.querySelector(".identifiants_inconnus").innerHTML = "Aucun compte utlisateur lié aux identifiants renseignés";

                        //il y a des erreurs de validation du formulaire :
                    } else if (response.erreurs !== null) {
                        document.querySelector(".courriel").innerHTML = response.erreurs.courriel || "";
                        document.querySelector(".mdp").innerHTML = response.erreurs.mdp || "";
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
            document.querySelector('.prenom').innerHTML = '';
            document.querySelector('.nom').innerHTML = '';
            document.querySelector('.courriel').innerHTML = '';
            document.querySelector('.mdp').innerHTML = '';
            var utilisateur = {
                prenom: document.querySelector("[name='prenom']").value,
                nom: document.querySelector("[name='nom']").value,
                courriel: document.querySelector("[name='courriel']").value,
                mdp: document.querySelector("[name='mdp']").value,
            };
            let requete = new Request(
                BaseURL + "index.php?requete=creerCompte",
                { method: "POST", body: JSON.stringify(utilisateur) }
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
                    //si la création de compte s'est bien passée, authentification directe du nouvel utilisateur :
                    if (response.data === true) {
                        var param = {
                            courriel: response.email,
                            mdp: response.mdp
                        };
                        let requete2 = new Request(
                            BaseURL + "index.php?requete=authentification",
                            { method: "POST", body: JSON.stringify(param) });
                        return fetch(requete2)
                            .then((response2) => {
                                //si l'authentification du nouvel utilisateur s'est bien passée, redirection vers la page d'accueil (son cellier)
                                if (response2.status === 200) {
                                    window.location.href = BaseURL;
                                } else {
                                    throw new Error("Erreur");
                                }
                            })

                        //affichage des erreurs renvoyées par la vérification des données du formulaire :
                    } else if (response.erreurs != null) {
                        document.querySelector('.prenom').innerHTML = response.erreurs.prenom || '';
                        document.querySelector('.nom').innerHTML = response.erreurs.nom || '';
                        document.querySelector('.courriel').innerHTML = response.erreurs.courriel || '';
                        document.querySelector('.mdp').innerHTML = response.erreurs.mdp || '';

                        //affichage d'une erreur si le courriel est déjà dans la base de données :
                    } else if (response.existant != null) {
                        document.querySelector('.resultat').innerHTML = "Il existe déjà un compte utilisateur lié à ce courriel" || '';
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

            evt.preventDefault();

            let id_bouteille = evt.target.dataset.id_bouteille;
            let id_cellier = evt.target.dataset.id_cellier;
            let modal_confirmation = document.querySelector(".modal_confirmation");

            modal_confirmation.style.display = "block";
            document.querySelector(".msg_confirmation").innerHTML = "Voulez-vous vraiment supprimer cette bouteille de votre cellier?";

            let btnConfirmer = document.querySelector(".confirmer");
            let btnAnnuler = document.querySelector(".annuler");

            /* Comportement du bouton 'confirmer' du modal */
            btnConfirmer.addEventListener("click", _ => {
                let requete = new Request(
                    BaseURL + "index.php?requete=supprimerBouteille&id=" + id_bouteille + "&cellier=" + id_cellier, {
                    method: "DELETE"
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
                        if (response == true) {
                            window.location = BaseURL;
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            });

            /* Comportement du bouton 'annuler' du modal */
            btnAnnuler.addEventListener("click", _ => {
                modal_confirmation.style.display = "none";
            });
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


    //comportement de l'icone de compte utilisateur :
    let btnCompte = document.querySelector('.fa-user-circle');
    if (btnCompte) {
        btnCompte.addEventListener('mouseover', evt => {
            evt.target.style.cursor = "pointer";
        });

        btnCompte.addEventListener('click', evt => {
            window.location = "index.php?requete=modifierCompteUtilisateur";
            let requete = new Request(BaseURL + "index.php?requete=modifierCompteUtilisateur");
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
                })
                .catch((error) => {
                    console.error(error);
                });
        });
    }

    //comportement du bouton de modification de l'intitulé du cellier de l'utilisateur connecté :
    let btnModifierIntituleCellier = document.querySelector('.modifierIntituleCellier');
    if (btnModifierIntituleCellier) {
        btnModifierIntituleCellier.addEventListener('click', evt => {
            document.querySelector('.intitule').innerHTML = '';
            let param = {
                modifierIntituleCellier: true, //pour orienter la route du controler vers la bonne requete SQL
                id_cellier: document.querySelector('.compteUtilisateur').dataset.id_cellier,
                nouvelIntitule: document.querySelector("[name='nouvelIntitule']").value,
            }

            let requete = new Request(BaseURL + "index.php?requete=modifierCompteUtilisateur", {
                method: "POST",
                body: JSON.stringify(param),
            });

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

                    /* affichage du modal confirmant la modification : */
                    if (response.data === true) {
                        modal.style.display = "block";
                        document.querySelector(".msg_sql").innerHTML = "Modification effectué";

                        //remplacer la valeur dans le champ "Intitulé" et vider le champ "Nouvel intitulé"
                        document.querySelector("[name='ancienIntitule']").value = param.nouvelIntitule;
                        document.querySelector("[name='nouvelIntitule']").value = '';

                    } else {
                        document.querySelector(".msg_sql").innerHTML = "Erreur de modification";
                    }
                })
                .catch((error) => {
                    console.error(error);
                });

            /* Cacher modal, retour à la page de gestion de compte : */
            document
                .querySelector(".retourGestionCompteUtilisateur")
                .addEventListener("click", _ => {
                    modal.style.display = "none";
                });

        });
    }

    //comportement du bouton de modification de l'email par l'utilisateur connecté :
    let btnModifierEmailUtilisateur = document.querySelector('.modifierEmailUtilisateur');
    if (btnModifierEmailUtilisateur) {
        btnModifierEmailUtilisateur.addEventListener('click', evt => {
            document.querySelector('.courriel').innerHTML = '';
            let param = {
                modifierEmail: true, //pour orienter la route du controler vers la bonne requete SQL
                ancienEmail: document.querySelector("[name='ancienEmail']").value,
                nouvelEmail: document.querySelector("[name='nouvelEmail']").value,
            }

            let requete = new Request(BaseURL + "index.php?requete=modifierCompteUtilisateur", {
                method: "POST",
                body: JSON.stringify(param),
            });

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
                    /* affichage des erreurs de formulaire s'il y en a: */
                    if (response.erreurs !== null) {
                        document.querySelector('.courriel').innerHTML = response.erreurs.existant || response.erreurs.courriel || '';
                    }

                    /* affichage du modal confirmant la modification : */
                    if (response.data === true) {
                        modal.style.display = "block";
                        document.querySelector(".msg_sql").innerHTML = "Modification effectué";

                        //remplacer la valeur dans le champ "Courriel" et vider le champ "Nouveau courriel"
                        document.querySelector("[name='ancienEmail']").value = response.nouvelEmail;
                        document.querySelector("[name='nouvelEmail']").value = '';

                    } else {
                        document.querySelector(".msg_sql").innerHTML = "Erreur de modification";
                    }
                })
                .catch((error) => {
                    console.error(error);
                });

            /* Cacher modal, retour à la page de gestion de compte : */
            document
                .querySelector(".retourGestionCompteUtilisateur")
                .addEventListener("click", _ => {
                    modal.style.display = "none";
                });

        });
    }


    //comportement du bouton de modification du mot de passe par l'utilisateur connecté :
    let btnModifierMdpUtilisateur = document.querySelector('.modifierMdpUtilisateur');
    if (btnModifierMdpUtilisateur) {
        btnModifierMdpUtilisateur.addEventListener('click', evt => {
            document.querySelector('.ancienMdp').innerHTML = '';
            document.querySelector('.nouveauMdp').innerHTML = '';
            document.querySelector('.confirmationMdp').innerHTML = '';
            let param = {
                modifierMdp: true, //pour orienter la route du controler vers la bonne requete SQL
                ancienEmail: document.querySelector("[name='ancienEmail']").value,
                ancienMdp: document.querySelector("[name='ancienMdp']").value,
                nouveauMdp: document.querySelector("[name='nouveauMdp']").value,
                confirmationMdp: document.querySelector("[name='confirmationMdp']").value,
            }

            let requete = new Request(BaseURL + "index.php?requete=modifierCompteUtilisateur", {
                method: "POST",
                body: JSON.stringify(param),
            });

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
                    /* affichage des erreurs de formulaire s'il y en a: */
                    if (response.erreurs !== null) {
                        document.querySelector('.ancienMdp').innerHTML = response.erreurs.mauvaisMdp || '';
                        document.querySelector('.nouveauMdp').innerHTML = response.erreurs.mdpInvalide || '';
                        document.querySelector('.confirmationMdp').innerHTML = response.erreurs.mdpDifferent || '';
                    }

                    /* affichage du modal confirmant la modification : */
                    if (response.data === true) {
                        modal.style.display = "block";
                        document.querySelector(".msg_sql").innerHTML = "Modification effectué";

                        //vider les champs :
                        document.querySelector("[name='ancienMdp']").value = '';
                        document.querySelector("[name='nouveauMdp']").value = '';
                        document.querySelector("[name='confirmationMdp']").value = '';
                        document.querySelector('.ancienMdp').innerHTML = '';
                        document.querySelector('.nouveauMdp').innerHTML = '';
                        document.querySelector('.confirmationMdp').innerHTML = '';

                    } else {
                        document.querySelector(".msg_sql").innerHTML = "Erreur de modification";
                    }

                })
                .catch((error) => {
                    console.error(error);
                });

            /* Cacher modal, retour à la page de gestion de compte : */
            document
                .querySelector(".retourGestionCompteUtilisateur")
                .addEventListener("click", _ => {
                    modal.style.display = "none";
                });

        });
    }

    //comportement des icones de visibilité/invisibilité du mot de passe :
    let btnMdpVisible = document.querySelectorAll(".fa-eye");
    let btnMdpInvisible = document.querySelectorAll(".fa-eye-slash");

    if (btnMdpVisible.length > 0 && btnMdpInvisible.length > 0) {
        btnMdpVisible.forEach(element => {
            element.addEventListener('click', evt => {
                evt.target.previousElementSibling.type = "password";
                evt.target.style.display = "none";
                evt.target.nextElementSibling.style.display = "inline";
            });
        });
    }

    if (btnMdpVisible.length > 0 && btnMdpInvisible.length > 0) {
        btnMdpInvisible.forEach(element => {
            element.addEventListener('click', evt => {
                evt.target.previousElementSibling.previousElementSibling.type = "text";
                evt.target.style.display = "none";
                evt.target.previousElementSibling.style.display = "inline";
            });
        });
    }

    //comportement du bouton de suppression du compte de l'utilisateur connecté :
    let btnSupprimerCompte = document.querySelector("[name='supprimerCompte']");
    if (btnSupprimerCompte) {
        btnSupprimerCompte.addEventListener('click', evt => {
            //Affichage du modal :
            let modal_confirmation = document.querySelector(".modal_confirmation");
            modal_confirmation.style.display = "block";
            document.querySelector(".msg_confirmation").innerHTML = "Voulez-vous vraiment supprimer votre compte ? <br> La suppression sera irréversible et toutes vos données seront perdues.";

            let btnConfirmer = document.querySelector(".confirmer");
            let btnAnnuler = document.querySelector(".annuler");

            /* Comportement du bouton 'confirmer' du modal */
            btnConfirmer.addEventListener("click", _ => {
                let param = {
                    supprimerCompte: true, //pour orienter la route du controler vers la bonne requete SQL
                    id_utilisateur: evt.target.dataset.id_utilisateur,
                    id_cellier: evt.target.dataset.id_cellier,
                    type_utilisateur: evt.target.dataset.type_utilisateur
                }

                let requete = new Request(BaseURL + "index.php?requete=modifierCompteUtilisateur", {
                    method: "POST",
                    body: JSON.stringify(param),
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
                        if (response.data === true) window.location.href = BaseURL + "index.php?requete=authentification";
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            });

            /* Comportement du bouton 'annuler' du modal */
            btnAnnuler.addEventListener("click", _ => {
                modal_confirmation.style.display = "none";
            });
        });
    }

    //comportement du bouton de suppression du compte de l'utilisateur connecté :
    let btnSupprimerCompteAdmin = document.querySelectorAll(".btnSupprimerCompteAdmin");
    if (btnSupprimerCompteAdmin.length > 0) {
        btnSupprimerCompteAdmin.forEach(element => {
            element.addEventListener('click', evt => {
                //Affichage du modal :
                let modal_confirmation = document.querySelector(".modal_confirmation");
                modal_confirmation.style.display = "block";
                document.querySelector(".msg_confirmation").innerHTML = "Voulez-vous vraiment supprimer ce compte ? <br> La suppression sera irréversible et toutes les données seront perdues.";

                let btnConfirmer = document.querySelector(".confirmer");
                let btnAnnuler = document.querySelector(".annuler");

                /* Comportement du bouton 'confirmer' du modal */
                btnConfirmer.addEventListener("click", _ => {
                    let param = {
                        supprimerCompte: true, //pour orienter la route du controler vers la bonne requete SQL
                        id_utilisateur: evt.target.dataset.id_utilisateur,
                        id_cellier: evt.target.dataset.id_cellier,
                        type_utilisateur: evt.target.dataset.type_utilisateur
                    }

                    let requete = new Request(BaseURL + "index.php?requete=modifierCompteUtilisateur", {
                        method: "POST",
                        body: JSON.stringify(param),
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
                            if (response.data === true) window.location.href = BaseURL + "index.php?requete=afficherInterfaceAdmin";
                        })
                        .catch((error) => {
                            console.error(error);
                        });
                });

                /* Comportement du bouton 'annuler' du modal */
                btnAnnuler.addEventListener("click", _ => {
                    modal_confirmation.style.display = "none";
                });
            });
        });
    }


    //comportement du bouton de suppression d'une bouteille du catalogue :
    let btnSupprimerCatalogue = document.querySelectorAll('.btnSupprimerCatalogue');

    btnSupprimerCatalogue.forEach(function (element) {
        element.addEventListener('click', evt => {
            let id_bouteille_catalogue = evt.target.parentElement.dataset.id_bouteille;
            let modal_confirmation = document.querySelector(".modal_confirmation");

            modal_confirmation.style.display = "block";
            document.querySelector(".msg_confirmation").innerHTML = "Voulez-vous vraiment supprimer cette bouteille du catalogue? <br> Cette suppression affectera également les bouteilles contenues dans les celliers des utilisateurs.";

            let btnConfirmer = document.querySelector(".confirmer");
            let btnAnnuler = document.querySelector(".annuler");

            btnConfirmer.addEventListener("click", _ => {
                let requete = new Request(
                    BaseURL + "?requete=supprimerBouteilleCatalogue&id=" + id_bouteille_catalogue, {
                    method: "DELETE"
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
                        if (response.data == true) {
                            window.location = BaseURL + "?requete=afficherCatalogue";
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            });

            /* Comportement du bouton 'annuler' du modal */
            btnAnnuler.addEventListener("click", _ => {
                modal_confirmation.style.display = "none";
            });
        });
    });


    //Comportement du bouton ajouter une bouteille au cellier depuis la page catalogue :
    document.querySelectorAll("[name='ajouterC']").forEach(function (element, index) {
        if (element) {
            element.addEventListener("click", function (evt) {
                console.log("ajouté")
                let aujourdhui = new Date();
                let date = aujourdhui.getFullYear() + '-' + (aujourdhui.getMonth() + 1) + '-' + aujourdhui.getDate();
                var param = {
                    id_cellier: evt.target.parentElement.dataset.id_cellier,
                    id_bouteille: evt.target.parentElement.dataset.id_bouteille,
                    date_achat: date,
                    // prix: document.querySelector('.prix').childNodes[1].nodeValue,
                    prix: (document.querySelectorAll('.prix')[index].childNodes[1].nodeValue).slice(0, 6)
                }
                console.log(param);
                let requete = new Request(
                    BaseURL + "index.php?requete=ajouterNouvelleBouteilleCellierDepuisleCatalogue", {
                    method: "POST",
                    body: JSON.stringify(param)
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
                        if (response.data == true) {
                            modal.style.display = "block";
                            document.querySelector(".msg_sql").innerHTML =
                                "Bouteille ajoutée";

                        } else {
                            document.querySelector(".msg_sql").innerHTML =
                                "Bouteille non ajoutée";
                        }
                    })


                    .catch((error) => {
                        console.error(error);
                    });

                document
                    .querySelector(".retour_cellier")
                    .addEventListener("click", (_) => {
                        window.location.href = BaseURL + '?requete=afficherCatalogue';
                    });

            })

        }
    })

    /* affichage du modal d'info des bouteilles du cellier */
    let btnModalBouteilleCellier = document.querySelectorAll('.cellier .bouteille');
    if (btnModalBouteilleCellier.length > 0) {
        btnModalBouteilleCellier.forEach(element => {
            /* affichage des modals d'information sur les bouteilles du cellier : */
            element.addEventListener('click', evt => {
                evt.currentTarget.querySelector('.modal_affichage_bouteille').style.display = "inline";
            })
        })
    }

    /* fermer le modal */
    let btnFermerModal = document.querySelectorAll('.contenu_modal_description .btnFermerModal')
    if (btnFermerModal.length > 0) {
        btnFermerModal.forEach(element => {
            element.addEventListener('mouseover', evt => {
                evt.target.style.cursor = "pointer";
            });

            element.addEventListener('click', evt => {
                window.location.href = BaseURL;
            })
        });
    }

    let typeVin = document.querySelectorAll(" .contenu_modal_description .type");

    if (typeVin.length > 0) {
        typeVin.forEach(element => {
            if (element.parentElement.querySelector('.type').textContent.trim() == "Vin blanc") {
                element.parentElement.parentElement.parentElement.parentElement.parentElement.querySelector(".pastille_qte").style.backgroundColor = "rgb(253, 241, 184)";
                element.parentElement.parentElement.parentElement.parentElement.parentElement.querySelector(".pastille_qte").style.color = "black";
            }
        })
    }


    //    Ajout d'étoiles pour les notes
    document.querySelectorAll("[type='hidden']").forEach(function (element, index) {
        let etoiles_1 = document.querySelectorAll(".etoile_1");
        let etoiles_2 = document.querySelectorAll(".etoile_2");
        let etoiles_3 = document.querySelectorAll(".etoile_3");
        let etoiles_4 = document.querySelectorAll(".etoile_4");
        let etoiles_5 = document.querySelectorAll(".etoile_5");

        switch (+element.value) {
            case 1:
                etoiles_1[index].classList.remove("unchecked");
                etoiles_1[index].classList.add("checked");
                etoiles_2[index].classList.add("unchecked");
                etoiles_2[index].classList.remove("checked");
                etoiles_3[index].classList.add("unchecked");
                etoiles_3[index].classList.remove("checked");
                etoiles_4[index].classList.add("unchecked");
                etoiles_4[index].classList.remove("checked");
                etoiles_5[index].classList.add("unchecked");
                etoiles_5[index].classList.remove("checked");
                break;
            case 2:
                etoiles_1[index].classList.remove("unchecked");
                etoiles_1[index].classList.add("checked");
                etoiles_2[index].classList.remove("unchecked");
                etoiles_2[index].classList.add("checked");
                etoiles_3[index].classList.add("unchecked");
                etoiles_3[index].classList.remove("checked");
                etoiles_4[index].classList.add("unchecked");
                etoiles_4[index].classList.remove("checked");
                etoiles_5[index].classList.add("unchecked");
                etoiles_5[index].classList.remove("checked");

                break;
            case 3:
                etoiles_1[index].classList.remove("unchecked");
                etoiles_1[index].classList.add("checked");
                etoiles_2[index].classList.remove("unchecked");
                etoiles_2[index].classList.add("checked");
                etoiles_3[index].classList.remove("unchecked");
                etoiles_3[index].classList.add("checked");
                etoiles_4[index].classList.remove("checked");
                etoiles_4[index].classList.add("unchecked");
                etoiles_5[index].classList.remove("checked");
                etoiles_5[index].classList.add("unchecked");
                break;
            case 4:
                etoiles_1[index].classList.remove("unchecked");
                etoiles_1[index].classList.add("checked");
                etoiles_2[index].classList.remove("unchecked");
                etoiles_2[index].classList.add("checked");
                etoiles_3[index].classList.remove("unchecked");
                etoiles_3[index].classList.add("checked");
                etoiles_4[index].classList.remove("unchecked");
                etoiles_4[index].classList.add("checked");
                etoiles_5[index].classList.remove("checked");
                etoiles_5[index].classList.add("unchecked");
                break;
            case 5:
                etoiles_1[index].classList.remove("unchecked");
                etoiles_1[index].classList.add("checked");
                etoiles_2[index].classList.remove("unchecked");
                etoiles_2[index].classList.add("checked");
                etoiles_3[index].classList.remove("unchecked");
                etoiles_3[index].classList.add("checked");
                etoiles_4[index].classList.remove("unchecked");
                etoiles_4[index].classList.add("checked");
                etoiles_4[index].classList.remove("unchecked");
                etoiles_5[index].classList.add("checked");
                etoiles_5[index].classList.remove("unchecked");

                break;
        }
    })
})
