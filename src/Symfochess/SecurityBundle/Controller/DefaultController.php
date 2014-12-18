<?php

namespace Symfochess\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SymfochessSecurityBundle:Default:index.html.twig', array('name' => $name));
    }
}
