<?php

namespace GaussBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Gauss/Default/index.html.twig');
    }
    public function getCollectionAction(){
        $em= $this->getDoctrine()->getManager();
        $listProduct = $em->getRepository("GaussBundle:Produit")->findBy(array(),array('id' => 'desc'),6);
        return $this->render('@Gauss/Default/layout/collection-2.html.twig',array('listProduct' => $listProduct));
    }
}
