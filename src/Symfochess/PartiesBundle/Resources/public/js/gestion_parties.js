$(document).ready(function () {
    $(".case").droppable({
        accept: ".imgpion",
        drop: function () {
            gestionDeplacements.deposePiece($(this))
        }
    });
    $(".imgpion").draggable({
        start: function () {
            gestionDeplacements.initDeplacement($(this));
        }
    });
});

//--- Déplacement des pièces
var gestionDeplacements = {
    //--- Si premier déplacement : on peut avancer de deux cases en ligne droite
    piece: null,
    couleur: null,
    coordDepart: null,
    coordArrivee: null,
    piece_en_cours: null,
    case_depart: null,
    case_arrivee: null,
    coefCouleur: Array(),
    coefficientPiece: null,
    tabColonnes: Array(null, 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'),

    setPiece: function (piece) {
        this.piece = piece;
    },
    setCouleur: function (couleur) {
        this.couleur = couleur;
    },

    setCoordonneesOrigine: function (x, y) {
        this.coordDepart = new Coordonnees(x, y);
    },

    initDeplacement: function (objJqueryPiece) {
        this.coefCouleur['B'] = 1;
        this.coefCouleur['N'] = -1;
        // console.log("initDeplacement", objJqueryPiece);
        //--- On sauvegarde la pièce en cours de jeu
        this.piece_en_cours = objJqueryPiece;
        this.case_depart = objJqueryPiece.parent();
        var coordonneesDepart = this.case_depart.attr('name').split("_");
        //console.log(coordonneesDepart);
        this.setCoordonneesOrigine(coordonneesDepart[1], coordonneesDepart[2]);
        //console.log(typeof(this), this);
        //console.log('tableauEchiquier', tableauEchiquier);
        //console.log('this.coordDepart', this.coordDepart);
        //console.log('tableauEchiquier[this.coordDepart.x][this.coordDepart.y]', tableauEchiquier[this.coordDepart.x][this.coordDepart.y]);
        this.piece = tableauEchiquier[this.coordDepart.x][this.coordDepart.y].piece;
        this.couleur = tableauEchiquier[this.coordDepart.x][this.coordDepart.y].couleur;
        this.coefficientPiece = this.coefCouleur[this.couleur];
        if ($("#avecAide").is(':checked')) {
            this.afficherCacesPossibles();
        }
    },
    deposePiece: function (objCaseArrivee) {
        //--- On supprime l'aide
        $(".possible").removeClass('possible');

        //console.log("Droppable : ", objCaseArrivee.attr('name'));
        gestionDeplacements.case_arrivee = objCaseArrivee;
        var coordonneesArrivee = objCaseArrivee.attr('name').split("_");
        this.coordArrivee = new Coordonnees(coordonneesArrivee[1], coordonneesArrivee[2]);
        //console.log("arrivee : ", this.coordArrivee);
        //--- Si on n'a pas déplacé la pièce, on s'arrête là.
        if (this.coordDepart.equals(this.coordArrivee)) {
            return;
        }

        //var x_arrivee = coordonneesArrivee[1];
        //var y_arrivee = coordonneesArrivee[2];
        //--- Y a-t-il une pièce à cet endroit?
        console.log("Enfants de la case d'arrivée : ", gestionDeplacements.case_arrivee.children());
        var retourVerifDeplacement = new Retour();
        retourVerifDeplacement = gestionDeplacements.checkDeplacement();
        if (retourVerifDeplacement.getEtat()) {
            //--- On place la pièce comme il faut
            gestionDeplacements.setPiece(gestionDeplacements.piece_en_cours.attr('name'));
            gestionDeplacements.piece_en_cours.detach().appendTo(objCaseArrivee);
            gestionDeplacements.piece_en_cours.css('top', 0);
            gestionDeplacements.piece_en_cours.css('left', 0);
            //--- On met à jour le tableau du plateau
            //--- On clone la pièce
            tableauEchiquier[this.coordArrivee.x][this.coordArrivee.y] = tableauEchiquier[this.coordDepart.x][this.coordDepart.y];
            //--- Et on la supprime de la case de départ
            tableauEchiquier[this.coordDepart.x][this.coordDepart.y] = null;
        } else {
            //--- Message d'erreur
            $("#dialog").html(retourVerifDeplacement.getMessage()).dialog({
                modal: true,
                buttons: {
                    "Ah d'accord!!": function () {
                        $(this).dialog("close");
                    }
                }
            });
            //alert(retourVerifDeplacement.getMessage());

            //--- On replace la pièce dans sa position d'origine'
            gestionDeplacements.piece_en_cours.css('top', 0);
            gestionDeplacements.piece_en_cours.css('left', 0);

        }
    },

    checkDeplacement: function () {
        switch (this.piece) {
            case 'P':
                return this.checkDeplacementPion();
                break;
            case 'R':
                return this.checkDeplacementRoi();
                break;
            case 'D':
                return this.checkDeplacementDame();
                break;
            case 'F':
                return this.checkDeplacementFou();
                break;
            case 'C':
                return this.checkDeplacementCavalier();
                break;
            case 'T':
                return this.checkDeplacementTour();
                break;
            default :
                return new Retour(false, "Déplacement non encore géré... :-(");
        }
    },
    checkDeplacementPion: function () {
        //--- Premier déplacement?
        if ((this.couleur == 'B' && this.coordDepart.y == 2) || (this.couleur == 'N' && this.coordDepart.y == 7)) {
            //--- Le joueur peut avancer d'une ou deux cases
            if (Math.abs(this.coordArrivee.y - this.coordDepart.y) > 2) {
                return new Retour(false, "&#9817;Vous ne pouvez pas avancer de " + Math.abs(this.coordArrivee.y - this.coordDepart.y) + " cases (premier déplacement : 2 cases maximum)");
            }
        } else {
            //--- Le joueur peut avancer d'une cases
            if (Math.abs(this.coordArrivee.y - this.coordDepart.y) > 1) {
                return new Retour(false, "&#9817;Vous ne pouvez pas avancer de " + Math.abs(this.coordArrivee.y - this.coordDepart.y) + " cases (1 case maximum)");
            }
        }
        //--- Le joueur avance en ligne droite sauf s'il a pris une autre pièce
        if (tableauEchiquier[this.coordArrivee.x][this.coordArrivee.y] != null) {
            //console.log("Il y a une piece dans case d'arrivee", this.coordArrivee.y - this.coordDepart.y, this.coordArrivee.x - this.coordDepart.x);
            //--- Il y avait une pièce dans la case d'arrivée => le déplacement doit être diagonal
            if (Math.abs(this.coordArrivee.y - this.coordDepart.y) != 1 || Math.abs(this.coordArrivee.x - this.coordDepart.x) != 1) {
                return new Retour(false, "&#9817; Vous ne pouvez prendre une pièce que sur une case adjacente en diagonale");
            }
        } else {
            //console.log("Il y n'y a pas une piece dans case d'arrivee");
            //--- Il y avait une pièce dans la case d'arrivée => La pièce a dû avancer d'une case en ligne droite
            if (Math.abs(this.coordArrivee.x - this.coordDepart.x) != 0) {
                return new Retour(false, "&#9817;Vous ne pouvez avancer qu'en ligne droite!!");
            }
        }
        //console.log("Piece dans case d'arrivee : ", this.coordArrivee.x, this.coordArrivee.y, tableauEchiquier[this.coordArrivee.x][this.coordArrivee.y], typeof(tableauEchiquier[this.coordArrivee.x][this.coordArrivee.y]));
        //if(tableauEchiquier[x][y])

        return new Retour(true, "&#9817;Déplacement autorisé!!");
    },

    /**
     *
     * Vérification de la validité de déplacement du roi (une case dans n'importe quelle direction)
     *
     */
    checkDeplacementRoi: function () {
        if (Math.abs(this.coordArrivee.y - this.coordDepart.y) > 1 || Math.abs(this.coordArrivee.x - this.coordDepart.x) > 1) {
            return new Retour(false, "&#9812;Le roi ne peut avancer que d'une case dans n'importe quelle direction!!");
        } else {
            //--- Gestion roque
            return new Retour(true, "&#9817;Déplacement autorisé!!");
        }
    },

    /**
     * Vérification dez déplacements de la dame (toute direction en ligne droite)
     *
     */
    checkDeplacementDame: function () {
        console.log("checkDeplacementDame", Math.abs(this.coordArrivee.x - this.coordDepart.x), Math.abs(this.coordArrivee.y - this.coordDepart.y), (Math.abs(this.coordArrivee.x - this.coordDepart.x) == 0 || Math.abs(this.coordArrivee.x - this.coordDepart.x) == Math.abs(this.coordArrivee.y - this.coordDepart.y)) == false);
        //--- La coordonnée horizontale a changé
        if (Math.abs(this.coordArrivee.x - this.coordDepart.x) != 0 &&
                //--- Le déplacement vertical n'est pas nul ou égal à celui horizontal
            (Math.abs(this.coordArrivee.y - this.coordDepart.y) == 0 || Math.abs(this.coordArrivee.x - this.coordDepart.x) == Math.abs(this.coordArrivee.y - this.coordDepart.y)) == false /*|| Math.abs(this.coordArrivee.x - this.coordDepart.x) > 1*/ ||
                //--- La coordonnée verticale a changé
            Math.abs(this.coordArrivee.y - this.coordDepart.y) != 0 &&
                //--- Le déplacement horizontal n'est pas nul ou égal à celui vertical
            (Math.abs(this.coordArrivee.x - this.coordDepart.x) == 0 || Math.abs(this.coordArrivee.x - this.coordDepart.x) == Math.abs(this.coordArrivee.y - this.coordDepart.y)) == false /*|| Math.abs(this.coordArrivee.x - this.coordDepart.x) > 1*/) {
            return new Retour(false, "&#9812;La ne peut avancer qu'en ligne droite dans n'importe quelle direction!!");
        } else {
            //--- Gestion roque
            return new Retour(true, "&#9817;Déplacement autorisé!!");
        }

    },

    /**
     * Vérification dez déplacements du fou (toute direction en diagonale, ne peut pas passer par dessus une autre pièce)
     *
     */
    checkDeplacementFou: function () {
        //--- On a juste à vérifier
        if (Math.abs(this.coordArrivee.y - this.coordDepart.y) != Math.abs(this.coordArrivee.x - this.coordDepart.x)) {
            return new Retour(false, "&#9812;Le fou ne peut avancer qu'en diagonale!!");
        } else {
            //--- Gestion roque
            return new Retour(true, "&#9817;Déplacement autorisé!!");
        }
    },

    /**
     * Vérification dez déplacements du cavalier : une case dans une direction et deux dans l'autre
     *
     */
    checkDeplacementCavalier: function () {
        if ((Math.abs(this.coordArrivee.y - this.coordDepart.y) == 1 && Math.abs(this.coordArrivee.x - this.coordDepart.x) != 2) ||
            (Math.abs(this.coordArrivee.y - this.coordDepart.y) == 2 && Math.abs(this.coordArrivee.x - this.coordDepart.x) != 1) ||
            Math.abs(this.coordArrivee.y - this.coordDepart.y) > 2
        ) {
            return new Retour(false, "&#9816;Le cavalier avance d'une case dans une direction et deux dans l'autre!!");
        } else {
            //--- Gestion roque
            return new Retour(true, "&#9817;Déplacement autorisé!!");
        }
    },

    /**
     * Vérification dez déplacements d'une tour : en ligne droite horizontalement ou verticalement
     *
     */
    checkDeplacementTour: function () {
        //--- On a juste à vérifier qu'on ne s'est pas déplacé dans deux directions.
        if ((Math.abs(this.coordArrivee.y - this.coordDepart.y) != 0 && Math.abs(this.coordArrivee.x - this.coordDepart.x) != 0)
        ) {
            return new Retour(false, "&#9814;La tour n'avance qu'en ligne droite!!");
        } else {
            //--- Gestion roque
            return new Retour(true, "&#9817;Déplacement autorisé!!");
        }
    },

    /**
     *
     * Fonction pour vérifier qu'il y a une pièce à ces coordonnées
     *
     * @param x
     * @param y
     */
    caseVide: function (x, y) {
        return (tableauEchiquier[x][y] == null);
    },
    peutPrendre: function (x, y) {
        var retour = false;
        console.log("peutPrendre", tableauEchiquier[this.coordDepart.getX()][this.coordDepart.getY()].couleur, tableauEchiquier[x][y].couleur);
        if (tableauEchiquier[x][y].couleur != tableauEchiquier[this.coordDepart.getX()][this.coordDepart.getY()].couleur) {
            retour = true;
        }
        console.log("retour", retour);
        return retour;
    },

    afficherCacesPossibles: function () {
        switch (this.piece) {
            case 'P':
                this.afficherCacesPossiblesPion();
                break;
            case 'R':
                this.afficherDeplacementRoi();
                break;
            case 'D':
                this.afficherDeplacementDame();
                break;
            case 'F':
                this.afficherDeplacementFou();
                break;
            case 'C':
                this.afficherDeplacementCavalier();
                break;
            case 'T':
                this.afficherDeplacementTour();
                break;
            default :
                return new Retour(false, "Déplacement non encore géré... :-(");

        }
    },

    afficherCacesPossiblesPion: function () {
        //--- Case de la pièce (annulation action)
        $("div[name=case_" + this.coordDepart.x + "_" + this.coordDepart.y + "]").addClass('possible');
        //--- Avancer d'une case si elle n'est pas occupée
        $("div[name=case_" + this.coordDepart.x + "_" + (this.coordDepart.y + 1) + "]").addClass('possible');
        var case1 = parseInt(this.coordDepart.y) + this.coefficientPiece;
        console.log("div[name=case_" + this.coordDepart.x + "_" + case1 + "]", $("div[name=case_" + this.coordDepart.x + "_" + case1 + "]"));
        $("div[name=case_" + this.coordDepart.x + "_" + case1 + "]").addClass('possible');
        //--- Premier déplacement : en ligne droite d'une ou deux cases
        if (this.estPremierCoupPion()) {
            case1 += this.coefficientPiece;
            $("div[name=case_" + this.coordDepart.x + "_" + case1 + "]").addClass('possible');
        } else {

        }
    },

    affichePoint: function (x_coef, y_coef) {
        var x_courant = this.coordDepart.getX() + x_coef;
        var y_courant = this.coordDepart.getY() + y_coef;

        if (x_courant >= 1 && y_courant >= 1 && x_courant <= 8 && y_courant <= 8) {
            var nomCase = "case" + this.tabColonnes[x_courant] + y_courant;
            if (this.caseVide(x_courant, y_courant)) {
                $("#" + nomCase).addClass('possible');
            } else {
                if (this.peutPrendre(x_courant, y_courant) == true) {
                    $("#" + nomCase).addClass('possible');
                }
            }
        }
    },

    afficheLigne: function (x_coef, y_coef) {
        var x_courant = this.coordDepart.getX() + x_coef;
        var y_courant = this.coordDepart.getY() + y_coef;
        var caseVide = true;

        while (x_courant >= 1 && y_courant >= 1 && x_courant <= 8 && y_courant <= 8 && caseVide != false) {
            var nomCase = "case" + this.tabColonnes[x_courant] + y_courant;
            if (this.caseVide(x_courant, y_courant)) {
                $("#" + nomCase).addClass('possible');
            } else {
                if (this.peutPrendre(x_courant, y_courant) == true) {
                    $("#" + nomCase).addClass('possible');
                }
                caseVide = false;
            }
            x_courant += x_coef;
            y_courant += y_coef;
        }
    },


    afficherDeplacementsDiagonale: function () {
        //--- Diagonales en avant et en arrière jusqu'à ce qu'on rencontre une pièce ou le bord de l'écran.
        console.log('afficherDeplacementsDiagonale');
        //--- Toutes les diagonales où il n'y a pas de pièce
        //--- En haut à droite
        this.afficheLigne(1, 1);
        //--- En haut à gauche
        this.afficheLigne(-1, 1);
        //--- En bas à droite
        this.afficheLigne(1, -1);
        //--- En bas à gauche
        this.afficheLigne(-1, -1);
    },

    afficherDeplacementsHorizontauxVerticaux: function () {
        //--- horizontales et verticales jusqu'à ce qu'on rencontre une pièce ou le bord de l'écran.
        console.log('afficherDeplacementsHorizontauxVerticaux');
        //--- Toutes les diagonales où il n'y a pas de pièce
        //--- à droite
        this.afficheLigne(1, 0);
        //--- à gauche
        this.afficheLigne(-1, 0);
        //--- En haut
        this.afficheLigne(0, 1);
        //--- En bas
        this.afficheLigne(0, -1);
    },

    afficherDeplacementRoi: function () {
        console.log('afficherDeplacementRoi');
        //--- Les coordonnées min et max possibles d'arrivée
        var min_x = Math.max(1, this.coordDepart.getX() - 1);
        var max_x = Math.min(8, this.coordDepart.getX() + 1);
        var min_y = Math.max(1, this.coordDepart.getY() - 1);
        var max_y = Math.min(8, this.coordDepart.getY() + 1);
        console.log('Cases max : ', {'min_x': min_x, 'max_x': max_x}, {'min_y': min_y, 'max_y': max_y});
        //--- Un dans toutes les directions où il n'y a pas de pièce
        for (var i = min_x; i <= max_x && i <= 8; i++) {
            for (var j = min_y; j <= max_y && j <= 8; j++) {
                var nomCase = "case" + this.tabColonnes[i] + j;
                console.log("Roi : teste ", nomCase);
                if (this.caseVide(i, j) || this.peutPrendre(i, j)) {
                    $("#" + nomCase).addClass('possible');
                }
            }
        }
    },
    afficherDeplacementDame: function () {
        console.log('afficherDeplacementDame');
        this.afficherDeplacementsDiagonale();
        this.afficherDeplacementsHorizontauxVerticaux();
    },
    afficherDeplacementFou: function () {
        this.afficherDeplacementsDiagonale();
    },
    afficherDeplacementCavalier: function () {
        //--- Vecteur (1, 2) dans toutes les directions
        this.affichePoint(-2, 1);
        this.affichePoint(-2, -1);
        this.affichePoint(-1, 2);
        this.affichePoint(-1, -2);
        this.affichePoint(1, 2);
        this.affichePoint(1, -2);
        this.affichePoint(2, 1);
        this.affichePoint(2, -1);
    },
    afficherDeplacementTour: function () {
        this.afficherDeplacementsHorizontauxVerticaux();
    },
    estPremierCoupPion: function () {
        switch (this.couleur) {
            case 'B': //--- Pour les blance : ligne 2
                if (this.coordDepart.getY() == 2) {
                    return true;
                } else {
                    return false;
                }
                break;
            case 'N': //--- Pour les noirs : ligne 7
                if (this.coordDepart.getY() == 7) {
                    return true;
                } else {
                    return false;
                }
                break;
            default :
                return false;
        }
    }

};

function Coordonnees(x, y) {
    this.x = x;
    this.y = y;
    this.setX = function (x) {
        this.x = parseInt(x);
    };
    this.getX = function () {
        return parseInt(this.x);
    }
    this.setY = function (y) {
        this.y = parseInt(y);
    }
    this.getY = function () {
        return parseInt(this.y);
    }
    this.equals = function (coord2) {
        return (this.getX() == coord2.getX() && this.getY() == coord2.getY())
    }
}

function Retour(etat, message) {
    this.etat = etat;
    this.message = message;

    console.log(this);

    this.setEtat = function (etat) {
        this.etat = etat;
    }
    this.getEtat = function () {
        return this.etat;
    }

    this.setMessage = function (message) {
        this.message = message;
    }

    this.getMessage = function () {
        return this.message;
    }
}

var Piece;
Piece = {
    jqueryObjet: null,
    type: null,
    couleur: null,
    coordonnees: null,
    setJqueryObjet: function (obj) {
        this.jqueryObjet = obj
    },
    getJqueryObjet: function () {
        return this.jqueryObjet;
    },
    setType: function (type) {
        this.type = type;
    },
    getType: function () {
        return this.type;
    },
    setCouleur: function (couleur) {
        this.couleur = couleur;
    },
    getCouleur: function () {
        return this.couleur;
    },
    setCoordonnees: function (x, y) {
        this.coordonnees = new Coordonnees(x, y);
    }
};

var deplaacement = {
    piece: null,
    coordonneesDeplacement: null,

    setPiece: function (piece) {
        this.piece = piece;
    }
}

