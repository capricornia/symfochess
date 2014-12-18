<?php
//--- src/Symfochess/JoueurBundle/Widgets/ListeParties.php

namespace Symfochess\JoueurBundle\Widgets;

use Symfony\Component\Security\Core\SecurityContextInterface;

class ListeParties
{

    /**
     * Joueur actuellement connecté
     * @var \Symfochess\JoueurBundle\Entity\Joueur
     */
    private $joueur;

    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
        $this->joueur = $this->securityContext->getToken()->getUser();
    }

    public function listeParties()
    {
        $parties = $this->joueur->getParties();
        $retour = "";
        foreach ($parties as $partie) {
            $retour .= "<hr>{$partie->getTitre()}</ht>";
        }
        return $retour;
    }

}