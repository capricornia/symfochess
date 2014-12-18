<?php

namespace Symfochess\JoueurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SymfochessJoueurBundle:Default:index.html.twig', array('name' => $name));
    }
}
