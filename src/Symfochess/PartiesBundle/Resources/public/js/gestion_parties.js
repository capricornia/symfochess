$(document).ready(function () {
    $(".case").droppable({
        accept: ".imgpion",
        drop: function () {
            console.log("Droppable : ", $(this).attr('name'));
            gestionDeplacements.case_arrivee = $(this);
            var coordonneesArrivee = $(this).attr('name').split("_");
            console.log("arrivee : ", coordonneesArrivee);
            var y_arrivee = coordonneesArrivee[1];
            var x_arrivee = coordonneesArrivee[2];
            //--- Y a-t-il une pièce à cet endroit?
            console.log("Enfants de la case d'arrivée : ", gestionDeplacements.case_arrivee.children());
            gestionDeplacements.checkDeplacement(x_arrivee, y_arrivee);
            //--- On place la pièce comme il faut
            gestionDeplacements.setPiece(gestionDeplacements.piece_en_cours.attr('name'));
            gestionDeplacements.piece_en_cours.detach().appendTo($(this));
            gestionDeplacements.piece_en_cours.css('top', 0);
            gestionDeplacements.piece_en_cours.css('left', 0);
        }
    });
    $(".imgpion").draggable({
        start: function () {
            //--- On sauvegarde la pièce en cours de jeu
            gestionDeplacements.piece_en_cours = $(this);
            gestionDeplacements.case_depart = $(this).parent();
            var coordonneesDepart = $(this).attr('name').split("_");
            console.log(coordonneesDepart);
            gestionDeplacements.setCoordonneesOrigine(coordonneesDepart[1], coordonneesDepart[2]);
        }
    });
});

//--- Déplacement des pièces
var gestionDeplacements = {
    //--- Si premier déplacement : on peut avancer de deux cases en ligne droite
    piece: null,
    couleur: null,
    coord1: null,
    piece_en_cours: null,
    case_depart: null,
    case_arrivee: null,

    setPiece: function (piece) {
        this.piece = piece;
    },
    setCouleur: function (couleur) {
        this.couleur = couleur;
    },

    setCoordonneesOrigine: function (x, y) {
        this.coord1 = {x: x, y: y};
    },
    checkDeplacement: function (x, y) {
        switch (this.piece) {
            case 'P':
                this.checkDeplacementPion(x, y);
                break;
            default :
                return false;
        }
    },
    checkDeplacementPion: function (x, y) {
        //--- Premier déplacement?
        if ((this.couleur == 'B' && this.y == 2) || (this.couleur == 'N' && this.y == 7)) {
            //--- Le joueur peut avancer d'une ou deux cases
            if (Math.abs(y - this.coord1.x) > 2) {
                return false;
            }
        } else {
            //--- Le joueur peut avancer d'une cases
            if (Math.abs(y - this.coord1.x) > 1) {
                return false;
            }
            //--- Le joueur avance en ligne droite sauf s'il a pris une autre pièce
            if (tableauEchiquier[x][y]) {
                console.log("Il y a une piece dans case d'arrivee");
            } else {
                console.log("Il y n'y a pas une piece dans case d'arrivee");
            }
            console.log("Piece dans case d'arrivee : ", x, y, tableauEchiquier[x][y], typeof(tableauEchiquier[x][y]));
            //if(tableauEchiquier[x][y])

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

var piece = {
    jqueryObjet: null,
    type: null,
    couleur: null,
    coordonnees: null,
    setJqueryObject: function (obj) {
        this.jqueryObjet = obj
    }
}