<?php
namespace Symfochess\JoueurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invitation
 *
 * @ORM\Table("invitation")
 * @ORM\Entity(repositoryClass="Symfochess\JoueurBundle\Entity\InvitationRepository")
 */
class Invitation
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Symfochess\PartiesBundle\Entity\Partie")
     *
     */
    private $partie;

    /**
     * @ORM\ManyToOne(targetEntity="Symfochess\JoueurBundle\Entity\Joueur")
     * @ORM\JoinColumn(name="expediteur_id", referencedColumnName="id")
     *
     */
    private $expediteur;

    /**
     * @ORM\ManyToOne(targetEntity="Symfochess\JoueurBundle\Entity\Joueur")
     *
     */
    private $destinataire;

    /**
     * @var datetime dateEnvoi
     * @ORM\Column(name="date_envoi", type="datetime", nullable=true)
     */
    private $dateEnvoi;

    /**
     * @var datetime dateReponse
     * @ORM\Column(name="date_reponse", type="datetime", nullable=true)
     */
    private $dateReponse;

    /**
     * @var string etat
     * @ORM\Column(name="etat", type="string", length=5, nullable=true)
     *
     */
    private $etat;

    /**
     * @var string message
     * @ORM\Column(name="message", type="text", nullable=true)
     *
     */
    private $message;

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
     * Get dateEnvoi
     *
     * @return \DateTime
     */
    public function getDateEnvoi()
    {
        return $this->dateEnvoi;
    }

    /**
     * Set dateEnvoi
     *
     * @param \DateTime $dateEnvoi
     * @return Invitation
     */
    public function setDateEnvoi($dateEnvoi)
    {
        $this->dateEnvoi = $dateEnvoi;

        return $this;
    }

    /**
     * Get dateReponse
     *
     * @return \DateTime
     */
    public function getDateReponse()
    {
        return $this->dateReponse;
    }

    /**
     * Set dateReponse
     *
     * @param \DateTime $dateReponse
     * @return Invitation
     */
    public function setDateReponse($dateReponse)
    {
        $this->dateReponse = $dateReponse;

        return $this;
    }

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
     * Set etat
     *
     * @param string $etat
     * @return Invitation
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Invitation
     */
    public function setMessage($message)
    {
        $this->message = $message;

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
     * @return Invitation
     */
    public function setPartie(\Symfochess\PartiesBundle\Entity\Partie $partie = null)
    {
        $this->partie = $partie;

        return $this;
    }

    /**
     * Get expediteur
     *
     * @return \Symfochess\JoueurBundle\Entity\Joueur
     */
    public function getExpediteur()
    {
        return $this->expediteur;
    }

    /**
     * Set expediteur
     *
     * @param \Symfochess\JoueurBundle\Entity\Joueur $expediteur
     * @return Invitation
     */
    public function setExpediteur(\Symfochess\JoueurBundle\Entity\Joueur $expediteur = null)
    {
        $this->expediteur = $expediteur;

        return $this;
    }

    /**
     * Get destinataire
     *
     * @return \Symfochess\JoueurBundle\Entity\Joueur
     */
    public function getDestinataire()
    {
        return $this->destinataire;
    }

    /**
     * Set destinataire
     *
     * @param \Symfochess\JoueurBundle\Entity\Joueur $destinataire
     * @return Invitation
     */
    public function setDestinataire(\Symfochess\JoueurBundle\Entity\Joueur $destinataire = null)
    {
        $this->destinataire = $destinataire;

        return $this;
    }
}
