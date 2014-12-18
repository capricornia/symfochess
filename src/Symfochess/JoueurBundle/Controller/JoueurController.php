<?php

namespace Symfochess\JoueurBundle\Controller;

use Symfochess\JoueurBundle\Entity\Joueur;
use Symfochess\JoueurBundle\Form\Type\JoueurType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// use Symfochess\PartiesBundle\Form\Type\PartieType;

class JoueurController extends Controller
{
    public function bienvenueAction($message)
    {
        return new Response("Bienvenue sur l'interface de gestion des joueurs.<br />" . $message);
    }

    public function AjouterJoueurAction($id = null)
    {
        $nouveauJoueur = new Joueur();
        if ($id != null) {
            $nouveauJoueur->find($id);
        }
        $nouveauJoueur->setPrenom('Eric');
        $nouveauJoueur->setNom('Cartman');
        $nouveauJoueur->setRang(666);

        //--- On va chercher l'"entity manager (em)"
        $em = $this->getDoctrine()->getManager();
        $em->persist($nouveauJoueur);
        $em->flush();
        return new Response('OK, enregistré!!');
    }

    public function AfficherJoueurAction($id)
    {
        $joueur = $this->getDoctrine()
            ->getRepository('SymfochessJoueurBundle:Joueur')
            ->find($id);
        if (!$joueur) {
            return new Response('Joueur non trouvé :-(');
        } else {
            return new Response('Joueur trouvé :-)<br /><pre>' . print_r($joueur, true) . "</pre>");
        }
    }

    public function NouveauJoueurAction(Request $request, $id = null)
    {
        if ($id == null) {
            $joueur = new Joueur();
        } else {
            $joueur = $this->getDoctrine()->getRepository('SymfochessJoueurBundle:Joueur')->find($id);
        }


        $formulaire = $this->get('form.factory')->create(new JoueurType(), $joueur);
        /*
        $this->createFormBuilder($joueur)
        ->add('id', 'hidden')
        ->add('prenom', 'text', array('label' => 'Prénom'))
        ->add('nom', 'text', array('label' => 'Nom'))
        ->add('rang', 'integer', array('label' => 'Rang'))
        ->add('username', 'text', array('label' => 'Pseudo'))
        ->add('password', 'text', array('label' => 'Mot de passe'))
        ->add('Valider', 'submit')
        ->getForm();
*/
        $formulaire->handleRequest($request);

        if ($formulaire->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($joueur);
            $em->flush();
            return new Response('Utilisateur enregistré!!');
        } else {
            return $this->render('SymfochessJoueurBundle:Joueur:nouveau.html.twig', array('formulaire' => $formulaire->createView()));
        }
    }

    /**
     *
     * Affichage de l'accueil du joueur authentifié
     * => Informations personnelles, parties, créer partie
     *
     * @param Request $request
     *
     *
     */
    public function tableauDeBordAction(Request $request)
    {

        return $this->render('SymfochessJoueurBundle:Joueur:tableaudebord.html.twig');
        //--- Informations sur le joueur
        $joueur = $this->getUser();

        return new Response('Utilisateur ' . get_class($joueur) . ' : <pre>' . $joueur->getUsername() . " ({$joueur->getPrenom()} {$joueur->getNom()} )");
    }

    public function testserviceAction()
    {
        $affichage = $this->get('joueur.widgetconnexion')->afficheWidget();

        return new Response("Petit test {$affichage}");
    }

    public function listePartiesAction()
    {
        $joueur = $this->getUser();
        $listeParties = $joueur->getParties();
        return $this->render("SymfochessJoueurBundle:Joueur:listeParties.html.twig", array('parties' => $listeParties));
    }
}
