<?php

namespace Symfochess\PartiesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SymfochessPartiesBundle:Default:index.html.twig', array('name' => $name));
    }

}
