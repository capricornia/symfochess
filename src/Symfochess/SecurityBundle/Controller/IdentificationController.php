<?php

namespace Symfochess\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

class IdentificationController extends Controller
{
    public function connexionAction(Request $request)
    {
        $session = $request->getSession();
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        }
        return $this->render('SymfochessSecurityBundle:Identification:connexion.html.twig', array(
                'last_username' => $session->get(SecurityContextInterface::LAST_USERNAME),
                'erreurs' => $error)
        );
    }

}
