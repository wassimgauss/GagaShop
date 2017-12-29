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
    
    public function getCategMenuAction(){
        $em= $this->getDoctrine()->getManager();
        $listCategory = $em->getRepository("GaussBundle:Category")->findAll();
        return $this->render('@Gauss/Default/layout/menu-home.html.twig',array('listCategory' => $listCategory));
    }

    public function contactAction()
    {
        return $this->render('@Gauss/Default/contact.html.twig');
    }

    public function aboutAction()
    {
        return $this->render('@Gauss/Default/about.html.twig');
    }
}
