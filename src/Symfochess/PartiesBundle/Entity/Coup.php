<?php

namespace Symfochess\PartiesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coup
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Symfochess\PartiesBundle\Entity\CoupRepository")
 */
class Coup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Symfochess\PartiesBundle\Entity\Partie", cascade={"remove", "persist"})
     *
     */
    private $partie;

    /**
     * @ORM\ManyToOne(targetEntity="\Symfochess\JoueurBundle\Entity\Joueur", cascade={"remove", "persist"})
     *
     */
    private $joueur;

    /**
     * @var string
     *
     * @ORM\Column(name="piece", type="string", length=5)
     *
     */
    private $piece;

    /**
     * @var string
     *
     * @ORM\Column(name="couleur", type="string", length=1)
     *
     */
    private $couleur;

    /**
     * @var string
     *
     * @ORM\Column(name="origine", type="string", length=2)
     *
     */
    private $origine;

    /**
     * @var string
     *
     * @ORM\Column(name="destination", type="string", length=2)
     *
     */
    private $destination;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get piece
     *
     * @return string
     */
    public function getPiece()
    {
        return $this->piece;
    }

    /**
     * Set piece
     *
     * @param string $piece
     * @return Coup
     */
    public function setPiece($piece)
    {
        $this->piece = $piece;

        return $this;
    }

    /**
     * Get couleur
     *
     * @return string
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * Set couleur
     *
     * @param string $couleur
     * @return Coup
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * Get origine
     *
     * @return string
     */
    public function getOrigine()
    {
        return $this->origine;
    }

    /**
     * Set origine
     *
     * @param string $origine
     * @return Coup
     */
    public function setOrigine($origine)
    {
        $this->origine = $origine;

        return $this;
    }

    /**
     * Get destination
     *
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set destination
     *
     * @param string $destination
     * @return Coup
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get partie
     *
     * @return \Symfochess\PartiesBundle\Entity\Partie
     */
    public function getPartie()
    {
        return $this->partie;
    }

    /**
     * Set partie
     *
     * @param \Symfochess\PartiesBundle\Entity\Partie $partie
     * @return Coup
     */
    public function setPartie(\Symfochess\PartiesBundle\Entity\Partie $partie = null)
    {
        $this->partie = $partie;

        return $this;
    }

    /**
     * Get joueur
     *
     * @return \Symfochess\JoueurBundle\Entity\Joueur
     */
    public function getJoueur()
    {
        return $this->joueur;
    }

    /**
     * Set joueur
     *
     * @param \Symfochess\JoueurBundle\Entity\Joueur $joueur
     * @return Coup
     */
    public function setJoueur(\Symfochess\JoueurBundle\Entity\Joueur $joueur = null)
    {
        $this->joueur = $joueur;

        return $this;
    }
}
