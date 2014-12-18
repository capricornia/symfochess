<?php

namespace Symfochess\PartiesBundle\Controller;

use Symfochess\PartiesBundle\Entity\Partie;
use Symfochess\PartiesBundle\Form\Type\PartieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PartiesController extends Controller
{
    public function nouvellePartieAction(Request $request)
    {
        //--- Formulaire avec le choix de l'adversaire
        $partie = new Partie();
        $partie->setDate(new \DateTime());
        $partie->setEtat("CR");
        $partie->setJoueur($this->getUser());
        $formulaire = $this->get('form.factory')->create(new PartieType(), $partie);

        $formulaire->handleRequest($request);

        if ($formulaire->isValid()) {
//            echo "<pre>";var_dump($partie->getJoueurs());echo "</pre>";
            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            foreach ($partie->getJoueurs() as $joueur) {
                //$partie->addJoueur($joueur);
                $joueur->addPartie($partie);
                $em->persist($joueur);
            }
            $em->persist($partie);
            $em->flush();
            //--- Envoi des invitations
            $this->get('symfochess_parties.gestion')->envoiInvitations($partie);


            return new Response('Partie enregistré!!');
        } else {
            return $this->render('SymfochessJoueurBundle:Joueur:nouveau.html.twig', array('formulaire' => $formulaire->createView()));
        }
    }

    public function enregistrerPartieAction()
    {
        $request = $this->get('request');
        var_dump($request);
        //--- Vzlidation et enregistrement de la partie avec état "attente de réponse"

        //--- Envoi de l'invitation

        //--- Message de confirmation
        return new Response("Création d'une nouvelle partie");
    }

    public function accepterInvitationAction($id)
    {
        $this->get('symfochess_parties.gestion')->accepterInvitations($id);
        return new Response("Merci !!");
    }

    public function infosAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("SymfochessPartiesBundle:Partie");
        $partie = $repo->find($id);
        $invitations = $repo->getInvitations($partie);

        return $this->render('SymfochessPartiesBundle:parties:parties_infos.html.twig', array('partie' => $partie, 'invitations' => $invitations));
    }

    public function commencerAction($id)
    {
        //--- On va chercher la partie
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("SymfochessPartiesBundle:Partie");
        $partie = $repo->find($id);

        //--- Affichage de l'écran de démarrage de la partie
        return $this->render('SymfochessPartiesBundle:parties:parties_demarrer.html.twig', array('partie' => $partie));
    }

    public function jouerAction($id)
    {
        //--- On va chercher la partie
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("SymfochessPartiesBundle:Partie");
        $partie = $repo->find($id);

        $listePieces = $this->get('symfochess_parties.gestion')->piecesOrigine();

        //--- On vérifie si on a déjà un coup

        //--- Affichage de l'écran de jeu
        return $this->render('SymfochessPartiesBundle:parties:echiquier.html.twig', array('partie' => $partie, 'pieces' => $listePieces));
    }
}
