<?php

namespace GaussBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IptvController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listProduct = $em->getRepository("GaussBundle:Produit")->findBy(array('category' => 5));
        return $this->render('@Gauss/Shop/aboiptv.html.twig',array('listIptv' => $listProduct));
    }

    public function centerAction(){

        return $this->render('@Gauss/Default/center.html.twig');
    }
    
}
