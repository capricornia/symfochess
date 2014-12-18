<?php
// src/Symfochess/SecurityBundle/Entity/Groupe.php

namespace Symfochess\SecurityBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Groupe implements RoleInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=30)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=20, unique=true)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity="\Symfochess\JoueurBundle\Entity\Joueur", mappedBy="groupes")
     */
    private $joueurs;

    public function __construct()
    {
        $this->joueurs = new ArrayCollection();
    }

// ... getters and setters for each property

    /**
     * @see RoleInterface
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Groupe
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Groupe
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Add joueurs
     *
     * @param \Symfochess\JoueurBundle\Entity\Joueur $joueurs
     * @return Groupe
     */
    public function addJoueur(\Symfochess\JoueurBundle\Entity\Joueur $joueurs)
    {
        $this->joueurs[] = $joueurs;

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
     * Get joueurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJoueurs()
    {
        return $this->joueurs;
    }
}
