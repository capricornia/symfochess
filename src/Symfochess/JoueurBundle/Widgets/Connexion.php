<?php
//--- src/Symfochess/JoueurBundle/Widgets/Connexion.php

namespace Symfochess\JoueurBundle\Widgets;

use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Class Connexion
 * @package Symfochess\JoueurBundle\Widgets
 *
 *
 */
class Connexion
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

    public function afficheWidget()
    {
        if ($this->joueur) {
            return $this->afficheConnecte();
        } else {
            return $this->afficheDeconnecte();
        }
    }

    protected function afficheConnecte()
    {
        return ("Vous êtes connecté en tant que {$this->joueur->getUsername()}!! {$this->joueur->getPrenom()} {$this->joueur->getNom()}");
    }

    protected function afficheDeconnecte()
    {
        return ("Vous êtes déconnecté!!");
    }
}