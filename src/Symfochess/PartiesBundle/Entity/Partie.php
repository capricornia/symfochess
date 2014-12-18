<?php

namespace Symfochess\PartiesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Partie
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Symfochess\PartiesBundle\Entity\PartieRepository")
 */
class Partie
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
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=100)
     */
    private $titre;
    /**
     * @var date
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var datetime
     *
     * @ORM\Column(name="debut", type="datetime", nullable=true)
     */
    private $debut;

    /**
     * @var datetime
     *
     * @ORM\Column(name="fin", type="datetime", nullable=true)
     */
    private $fin;

    /**
     * @var text
     *
     * @ORM\Column(name="nom", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="\Symfochess\JoueurBundle\Entity\Joueur", cascade={"remove", "persist"})
     *
     */
    private $joueur;

    /**
     * @ORM\ManyToMany(targetEntity="\Symfochess\JoueurBundle\Entity\Joueur", mappedBy="parties", cascade={"remove", "persist"})
     *
     */
    private $adversaires;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=5)
     *
     */
    private $etat;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->joueurs = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Partie
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Partie
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get debut
     *
     * @return \DateTime
     */
    public function getDebut()
    {
        return $this->debut;
    }

    /**
     * Set debut
     *
     * @param \DateTime $debut
     * @return Partie
     */
    public function setDebut($debut)
    {
        $this->debut = $debut;

        return $this;
    }

    /**
     * Get fin
     *
     * @return \DateTime
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * Set fin
     *
     * @param \DateTime $fin
     * @return Partie
     */
    public function setFin($fin)
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Partie
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Add joueurs
     *
     * @param \Symfochess\JoueurBundle\Entity\Joueur $joueurs
     * @return Partie
     */
    public function addJoueur(\Symfochess\JoueurBundle\Entity\Joueur $joueurs)
    {
        $this->joueurs[] = $joueurs;
        foreach ($this->joueurs as $joueur) {
            $joueur->addPartie($this);
        }

        return $this;
    }

    /**
     * Remove joueurs
     *
     * @param \Symfochess\JoueurBundle\Entity\Joueur $joueurs
     */
    public function removeJoueur(\Symfochess\JoueurBundle\Entity\Joueur $joueurs)
    {
        $this->joueurs->removeElement($joueurs);
    }

    /**
     * add joueurs
     *
     * @param \Symfochess\JoueurBundle\Entity\Joueur $joueurs
     *//*
    public function addJoueurs(ArrayCollection $joueurs)
    {
        foreach($this->joueurs as $joueur){

        }
    }
*/

    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Get joueurs
     *
     * @return \Doctrine\Common\Collections\Collection
     *
     * public function getJoueurs()
     * {
     * return $this->joueurs;
     * }
     *
     * /**
     * Set etat
     *
     * @param string $etat
     * @return Partie
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

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
     * @return Partie
     */
    public function setJoueur(\Symfochess\JoueurBundle\Entity\Joueur $joueur = null)
    {
        $this->joueur = $joueur;

        return $this;
    }

    /**
     * Add adversaires
     *
     * @param \Symfochess\JoueurBundle\Entity\Joueur $adversaires
     * @return Partie
     */
    public function addAdversaire(\Symfochess\JoueurBundle\Entity\Joueur $adversaires)
    {
        $this->adversaires[] = $adversaires;

        return $this;
    }

    /**
     * Remove adversaires
     *
     * @param \Symfochess\JoueurBundle\Entity\Joueur $adversaires
     */
    public function removeAdversaire(\Symfochess\JoueurBundle\Entity\Joueur $adversaires)
    {
        $this->adversaires->removeElement($adversaires);
    }

    /**
     * Get adversaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdversaires()
    {
        return $this->adversaires;
    }
}
