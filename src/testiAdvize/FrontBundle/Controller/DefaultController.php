<?php

namespace testiAdvize\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('testiAdvizeFrontBundle:Default:index.html.twig', array('name' => $name));
    }
}
