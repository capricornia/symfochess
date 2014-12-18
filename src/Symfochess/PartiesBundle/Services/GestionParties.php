<?php

namespace Symfochess\PartiesBundle\Services;

//--- On a besoin de ceci pour accéder à l'utilisateur logué
//-- use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Swift_Mailer;
use Symfochess\JoueurBundle\Entity\Invitation;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class GestionParties
 * @package Symfochess\PartiesBundle\Services
 *
 */
class GestionParties
{

    /**
     * @var \Symfochess\JoueurBundle\Entity\Joueur $joueur
     *
     */
    private $joueur;

    /**
     * @var EngineInterface $twig
     */
    private $twig;

    /**
     * @var Swift_Mailer $mailer
     */
    private $mailer;

    /**
     * @var EntityManager $em
     */
    private $em;

    public function __construct(EngineInterface $twig, \Swift_Mailer $mailer, SecurityContext $securityContext, EntityManager $entityManager)
    {
        $this->securityContext = $securityContext;
        $this->joueur = $this->securityContext->getToken()->getUser();
        $this->twig = $twig; //$container->get('templating');
        $this->mailer = $mailer;
        $this->em = $entityManager;
    }

    public function envoiInvitations(\Symfochess\PartiesBundle\Entity\Partie $partie)
    {
        $adversaires = $partie->getAdversaires();
        foreach ($adversaires as $joueur) {
            //--- Si ce n'est pas l'utilisateur courant
            if (!$joueur->isEqualToJoueur($this->joueur)) {

                //--- On l'écrit en base
                $invitation = new Invitation();
                $invitation->setEtat('AV');
                $invitation->setDestinataire($joueur);
                $invitation->setExpediteur($this->joueur);
                $invitation->setPartie($partie);

                $invitation->setDateEnvoi(new \DateTime());
                $this->em->persist($invitation);
                $this->em->flush();

                //--- On génère le texte de l'invitation
                $texteEmail = $this->twig->render('SymfochessPartiesBundle:invitations:mailinvitation.html.twig', array('joueur' => $joueur, 'partie' => $partie, 'invitation' => $invitation));

                $message = \Swift_Message::newInstance()
                    ->setSubject("Invitation à affronter {$this->joueur->getUsername()} sur Symfochess!!")
                    ->setFrom('admin@symfochess.com', 'Roi de Symfochess')
                    ->setTo($joueur->getEmail())
                    ->setBody($texteEmail);
                //--- On l'envoie
                $this->mailer->send($message);
                $invitation->setMessage($texteEmail);
                $this->em->persist($invitation);
                $this->em->flush();
            }
        }

    }

    /**
     * @param $id
     * identifiant de l'invitation
     *
     * Cette fonction met à jour l'état d'une invitation suite au clic du lien dans le mail d'invitation
     */
    public function accepterInvitations($id)
    {
        //--- On met à jour l'état de l'invitation
        $invitation = $this->em->getRepository('SymfochessJoueurBundle:Invitation')->find($id);
        $invitation->setEtat('AC');
        $invitation->setDateReponse(new \DateTime());
        $this->em->persist($invitation);
        $this->em->flush();

        //--- On récupère les joueurs de l'invitation.


        //--- On envoie un mail pour prévenir le joueur à l'origine de la partie
        //--- On génère le texte de l'invitation
        $texteEmail = $this->twig->render('SymfochessPartiesBundle:invitations:mailinvitationacceptee.html.twig', array('invitation' => $invitation));

        $message = \Swift_Message::newInstance()
            ->setSubject(" {$invitation->getDestinataire()->getUsername()} a répondu à votre invitation sur Symfochess!!")
            ->setFrom('admin@symfochess.com', 'Roi de Symfochess')
            ->setTo($invitation->getExpediteur()->getEmail())
            ->setBody($texteEmail);
        //--- On l'envoie
        $this->mailer->send($message);
        $this->checkToutesInvitationsAcceptees($invitation->getPartie());
    }

    public function checkToutesInvitationsAcceptees(\Symfochess\PartiesBundle\Entity\Partie $partie)
    {
        //--- On récupère le nombre d'invitations non validées
        $invitationsNonAceptees = $this->em->getRepository('SymfochessPartiesBundle:Partie')->compteInvitationsNonAcceptees($partie);
        //--- S'il n'y en a plus, on change l'état de la partie => elle est prête à commencer
        if ($invitationsNonAceptees === 0) {
            //--- On change l'état de la partie
            $partie->setEtat('PR');
            $this->em->persist($partie);
            $this->em->flush();

            //--- on envoie une notification au joueurs qui a créé la partie
            $texteEmail = $this->twig->render('SymfochessPartiesBundle:parties:partiepretetextemail.html.twig', array('partie' => $partie));

            $message = \Swift_Message::newInstance()
                ->setContentType('text/html')
                ->setSubject("Une partie est prête à démarrer sur Symfochess!!")
                ->setFrom('admin@symfochess.com', 'Roi de Symfochess')
                ->setTo($partie->getJoueur()->getEmail())
                ->setBody($texteEmail);
            //--- On l'envoie
            $this->mailer->send($message);

        }
    }

    /**
     *
     */
    public function piecesOrigine()
    {
        $listePieces = array(
            //--- Les blancs - pièces
            'A1' => new \Symfochess\PartiesBundle\Entity\Piece('T', 'B', 1, 1),
            'B1' => new \Symfochess\PartiesBundle\Entity\Piece('C', 'B', 2, 1),
            'C1' => new \Symfochess\PartiesBundle\Entity\Piece('F', 'B', 3, 1),
            'D1' => new \Symfochess\PartiesBundle\Entity\Piece('R', 'B', 4, 1),
            'E1' => new \Symfochess\PartiesBundle\Entity\Piece('D', 'B', 5, 1),
            'F1' => new \Symfochess\PartiesBundle\Entity\Piece('F', 'B', 6, 1),
            'G1' => new \Symfochess\PartiesBundle\Entity\Piece('C', 'B', 7, 1),
            'H1' => new \Symfochess\PartiesBundle\Entity\Piece('T', 'B', 8, 1),
            //--- Les blancs - pions
            'A2' => new \Symfochess\PartiesBundle\Entity\Piece('P', 'B', 1, 2),
            'B2' => new \Symfochess\PartiesBundle\Entity\Piece('P', 'B', 2, 2),
            'C2' => new \Symfochess\PartiesBundle\Entity\Piece('P', 'B', 3, 2),
            'D2' => new \Symfochess\PartiesBundle\Entity\Piece('P', 'B', 4, 2),
            'E2' => new \Symfochess\PartiesBundle\Entity\Piece('P', 'B', 5, 2),
            'F2' => new \Symfochess\PartiesBundle\Entity\Piece('P', 'B', 6, 2),
            'G2' => new \Symfochess\PartiesBundle\Entity\Piece('P', 'B', 7, 2),
            'H2' => new \Symfochess\PartiesBundle\Entity\Piece('P', 'B', 8, 2),

            //--- Les noirs - pièces
            'A8' => new \Symfochess\PartiesBundle\Entity\Piece('T', 'N', 1, 8),
            'B8' => new \Symfochess\PartiesBundle\Entity\Piece('C', 'N', 2, 8),
            'C8' => new \Symfochess\PartiesBundle\Entity\Piece('F', 'N', 3, 8),
            'D8' => new \Symfochess\PartiesBundle\Entity\Piece('R', 'N', 4, 8),
            'E8' => new \Symfochess\PartiesBundle\Entity\Piece('D', 'N', 5, 8),
            'F8' => new \Symfochess\PartiesBundle\Entity\Piece('F', 'N', 6, 8),
            'G8' => new \Symfochess\PartiesBundle\Entity\Piece('C', 'N', 7, 8),
            'H8' => new \Symfochess\PartiesBundle\Entity\Piece('T', 'N', 8, 8),
            //--- Les noirs - pions
            'A7' => new \Symfochess\PartiesBundle\Entity\Piece('P', 'N', 1, 7),
            'B7' => new \Symfochess\PartiesBundle\Entity\Piece('P', 'N', 2, 7),
            'C7' => new \Symfochess\PartiesBundle\Entity\Piece('P', 'N', 3, 7),
            'D7' => new \Symfochess\PartiesBundle\Entity\Piece('P', 'N', 4, 7),
            'E7' => new \Symfochess\PartiesBundle\Entity\Piece('P', 'N', 5, 7),
            'F7' => new \Symfochess\PartiesBundle\Entity\Piece('P', 'N', 6, 7),
            'G7' => new \Symfochess\PartiesBundle\Entity\Piece('P', 'N', 7, 7),
            'H7' => new \Symfochess\PartiesBundle\Entity\Piece('P', 'N', 8, 7),
        );
        return $listePieces;
    }
}