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

    setPiece: function (piece) {
        this.piece = piece;
    },
    setCouleur: function (couleur) {
        this.couleur = couleur;
    },

    setCoordonneesOrigine: function (x, y) {
        this.coordDepart = {x: x, y: y};
    },

    initDeplacement: function (objJqueryPiece) {
        this.coefCouleur['b'] = 1;
        this.coefCouleur['n'] = -1;
        // console.log("initDeplacement", objJqueryPiece);
        //--- On sauvegarde la pièce en cours de jeu
        this.piece_en_cours = objJqueryPiece;
        this.case_depart = objJqueryPiece.parent();
        var coordonneesDepart = this.case_depart.attr('name').split("_");
        //console.log(coordonneesDepart);
        this.setCoordonneesOrigine(coordonneesDepart[1], coordonneesDepart[2]);
        console.log(typeof(this), this);
        console.log('tableauEchiquier', tableauEchiquier);
        console.log('this.coordDepart', this.coordDepart);
        console.log('tableauEchiquier[this.coordDepart.x][this.coordDepart.y]', tableauEchiquier[this.coordDepart.x][this.coordDepart.y]);
        this.piece = tableauEchiquier[this.coordDepart.x][this.coordDepart.y].piece;
        this.couleur = tableauEchiquier[this.coordDepart.x][this.coordDepart.y].couleur;
    },
    deposePiece: function (objCaseArrivee) {
        console.log("Droppable : ", objCaseArrivee.attr('name'));
        gestionDeplacements.case_arrivee = objCaseArrivee;
        var coordonneesArrivee = objCaseArrivee.attr('name').split("_");
        this.coordArrivee = new Coordonnees(coordonneesArrivee[1], coordonneesArrivee[2]);
        console.log("arrivee : ", this.coordArrivee);
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
            console.log("Il y a une piece dans case d'arrivee", this.coordArrivee.y - this.coordDepart.y, this.coordArrivee.x - this.coordDepart.x);
            //--- Il y avait une pièce dans la case d'arrivée => le déplacement doit être diagonal
            if (Math.abs(this.coordArrivee.y - this.coordDepart.y) != 1 || Math.abs(this.coordArrivee.x - this.coordDepart.x) != 1) {
                return new Retour(false, "&#9817; Vous ne pouvez prendre une pièce que sur une case adjacente en diagonale");
            }
        } else {
            console.log("Il y n'y a pas une piece dans case d'arrivee");
            //--- Il y avait une pièce dans la case d'arrivée => La pièce a dû avancer d'une case en ligne droite
            if (Math.abs(this.coordArrivee.x - this.coordDepart.x) != 0) {
                return new Retour(false, "&#9817;Vous ne pouvez avancer qu'en ligne droite!!");
            }
        }
        console.log("Piece dans case d'arrivee : ", this.coordArrivee.x, this.coordArrivee.y, tableauEchiquier[this.coordArrivee.x][this.coordArrivee.y], typeof(tableauEchiquier[this.coordArrivee.x][this.coordArrivee.y]));
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
        if (Math.abs(this.coordArrivee.x - this.coordDepart.x) != 0 && !(Math.abs(this.coordArrivee.y - this.coordDepart.y) == 0 || Math.abs(this.coordArrivee.x - this.coordDepart.x) != Math.abs(this.coordArrivee.y - this.coordDepart.y)) || Math.abs(this.coordArrivee.x - this.coordDepart.x) > 1 ||
            Math.abs(this.coordArrivee.y - this.coordDepart.y) != 0 && !(Math.abs(this.coordArrivee.x - this.coordDepart.x) == 0 || Math.abs(this.coordArrivee.x - this.coordDepart.x) != Math.abs(this.coordArrivee.y - this.coordDepart.y)) || Math.abs(this.coordArrivee.x - this.coordDepart.x) > 1) {
            return new Retour(false, "&#9812;La ne peut avancer qu'en ligne droite dans n'importe quelle direction!!");
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
        var nomLignes = new Array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H');
        //--- On construit le nom de la case à contrôler
        var nomCase = 'case' + nomLignes[y] + x;
        //---
    },

    afficherSacesPossibles: function (piece, x, y) {

    },

    estPremierCoupPion: function (piece) {

    }

};

function Coordonnees(x, y) {
    this.x = x;
    this.y = y;
    this.setX = function (x) {
        this.x = x;
    };
    this.getX = function () {
        return this.x;
    }
    this.setY = function (y) {
        this.y = y;
    }
    this.getY = function () {
        return this.y
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

