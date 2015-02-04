<?php

namespace SoftwareDesk\BikeTraderAPIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SoftwareDeskBikeTraderAPIBundle:Default:index.html.twig', array('name' => $name));
    }
}
