<?php

namespace GaussBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ShopController extends Controller
{
    public function indexAction(Request $request) {
        $session = $request->getSession();
        $local = $session->get('_local');
        $request->setLocale($local);
        $request->setDefaultLocale($local);
        $this->get('translator')->setLocale($local);
        $page = $request->query->get('page');
        $price = $request->query->get('price');
        return $this->render('@Gauss/Shop/index.html.twig',array('page' => $page, 'price' => $price));
    }
    
    public function getProductAction(Request $request, $page, $price) {
        if(!$page)
        $request->query->set('page',1);
        $em = $this->get('doctrine.orm.entity_manager');
        if($price != null) {
            $value = explode(',',$price);
            $min = $value[0];
            $max = $value[1];
            $dql   = "select a from GaussBundle:Produit a where a.currentPrice BETWEEN ".$min."AND ".$max;
        }
        else
            $dql   = "select a from GaussBundle:Produit a where a.category != 5";
        $query = $em->createQuery($dql);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->get('page',$page)/*page number*/,
            6/*limit per page*/
        );

        return $this->render('@Gauss/Shop/layout/shop-body.html.twig',array('pagination' => $pagination));
    }

    public function indexCategAction(Request $request, $id_categ){

        $session = $request->getSession();
        $local = $session->get('_local');
        $request->setLocale($local);
        $request->setDefaultLocale($local);
        $this->get('translator')->setLocale($local);
        $page = $request->query->get('page');
        $price = $request->query->get('price');
        return $this->render('@Gauss/Shop/index-categ.html.twig',array('id_categ' => $id_categ, 'page' => $page, 'price' => $price));
    }

    public function getProductCategAction($id_categ, Request $request, $page, $price){

        if(!$page)
            $request->query->set('page',1);
        $em    = $this->get('doctrine.orm.entity_manager');
        if($price != null) {
            $value = explode(',',$price);
            $min = $value[0];
            $max = $value[1];
            $dql   = "select a from GaussBundle:Produit a where a.category = ".$id_categ." AND a.currentPrice BETWEEN ".$min."AND ".$max;
        }
        else
        $dql   = "select a from GaussBundle:Produit a where a.category = ".$id_categ;
        $query = $em->createQuery($dql);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->get('page',$page)/*page number*/,
            6/*limit per page*/
        );
        return $this->render('@Gauss/Shop/layout/shop-body.html.twig',array('pagination' => $pagination));
    }

    public function getCategoryAction(){
        $em= $this->getDoctrine()->getManager();
        $listCategory = $em->getRepository("GaussBundle:Category")->findAll();
        $data = array();
        foreach ($listCategory as $value) {
            $listProduct = $em->getRepository("GaussBundle:Produit")->findBy(array('category' => $value));
            array_push($data,(count($listProduct)));
        }
        return $this->render('@Gauss/Shop/layout/widget-categories.html.twig',array('listCategory' => $listCategory, 'count' => $data));
    }

    public function getTopSellerAction(){
        $em = $this->getDoctrine()->getManager();
        $listProductTop = $em->getRepository("GaussBundle:Produit")->findBy(array(),array('classement' => 'desc'), 4);
        return $this->render('@Gauss/Shop/layout/widget-top-seller.html.twig',array('listProductTop' => $listProductTop));
    }

    public function viewProductAction($id_product, Request $request){
        $session = $request->getSession();
        $session->set('id_product',$id_product);
        $local = $session->get('_local');
        $request->setLocale($local);
        $request->setDefaultLocale($local);
        $this->get('translator')->setLocale($local);
        $em = $this->getDoctrine()->getManager();
        $allproduct= $em->getRepository("GaussBundle:Produit")->findBy(array(),array('statusProduct' => 'desc'));
        $product = $em->getRepository("GaussBundle:Produit")->findOneBy(array('id' => $id_product));
        if($product != null){
            $query = $em->createQuery('select count(p.id) from GaussBundle:Produit p');
            $count = $query->getResult();
            return $this->render('@Gauss/Shop/viewProduct.html.twig',array('product' => $product, 'allProdcut' => $allproduct, 'count' => $count));

        }
        else {
            return $this->redirect($this->generateUrl('homepage_404'));
        }
    }
    
}
