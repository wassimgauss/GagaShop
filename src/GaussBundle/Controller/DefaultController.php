<?php

namespace GaussBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $local = $session->get('_local');
        $request->setLocale($local);
        $request->setDefaultLocale($local);
        $this->get('translator')->setLocale($local);
        return $this->render('@Gauss/Default/index.html.twig');
    }

    public function setLocalAction($lang, Request $request)
    {
        $session = $request->getSession();
        $request->setLocale($lang);
        $request->setDefaultLocale($lang);
        $this->get('translator')->setLocale($lang);
        $session->set('_local',$lang);
        if(!$session->get('last_route')['name'])
            $session->get('last_route')['name'] ="homepage";
        $parm_routes = $session->get('last_route')['params'];
        return $this->redirect($this->generateUrl($session->get('last_route')['name'],$parm_routes));

    }

    public function getCollectionAction()
    {
        $em= $this->getDoctrine()->getManager();
        //$listProduct = $em->getRepository("GaussBundle:Produit")->findBy(array(),array('id' => 'desc'),6);
        $listProduct = $em->getRepository("GaussBundle:Produit")->getCollection();
        $listiptvs = $em->getRepository("GaussBundle:Produit")->findBy(array('category' => 5),array('id' => 'desc'),3);
        return $this->render('@Gauss/Default/layout/collection-2.html.twig',array('listProduct' => $listProduct, 'listIptvs' => $listiptvs));
    }
    
    public function getCategMenuAction()
    {
        $em= $this->getDoctrine()->getManager();
        $listCategory = $em->getRepository("GaussBundle:Category")->findAll();
        $data = array();
        foreach ($listCategory as $value) {
            $listProduct = $em->getRepository("GaussBundle:Produit")->findBy(array('category' => $value));
            array_push($data,(count($listProduct)));
        }
        $names_url = array();
        foreach ($listCategory as $value) {
            array_push($names_url,str_replace(" ","-",strtolower($value->getNom())));
        }
        return $this->render('@Gauss/Default/layout/menu-home.html.twig',array('listCategory' => $listCategory,'names_url' => $names_url,'count' => $data));
    }

    public function contactAction(Request $request)
    {
        $session = $request->getSession();
        $local = $session->get('_local');
        $request->setLocale($local);
        $request->setDefaultLocale($local);
        $this->get('translator')->setLocale($local);
        return $this->render('@Gauss/Default/contact.html.twig');
    }

    public function aboutAction(Request $request)
    {
        $session = $request->getSession();
        $local = $session->get('_local');
        $request->setLocale($local);
        $request->setDefaultLocale($local);
        $this->get('translator')->setLocale($local);
        return $this->render('@Gauss/Default/about.html.twig');
    }

    public function notFoundAction(Request $request)
    {
        $session = $request->getSession();
        $local = $session->get('_local');
        $request->setLocale($local);
        $request->setDefaultLocale($local);
        $this->get('translator')->setLocale($local);
        return $this->render('@Gauss/Default/404.html.twig');
    }
}
