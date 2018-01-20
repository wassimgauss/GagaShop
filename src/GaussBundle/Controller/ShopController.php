<?php

namespace GaussBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ShopController extends Controller
{
    public function indexAction(Request $request)
    {
        $page = $request->query->get('page');
        return $this->render('@Gauss/Shop/index.html.twig',array('page' => $page));
    }

    public function getProductAction(Request $request,$page){
        if(!$page)
            $request->query->set('page',1);
        $em    = $this->get('doctrine.orm.entity_manager');
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

        $page = $request->query->get('page');
        return $this->render('@Gauss/Shop/index-categ.html.twig',array('id_categ' => $id_categ, 'page' => $page));
    }

    public function getProductCategAction($id_categ, Request $request, $page){

        if(!$page)
            $request->query->set('page',1);
        $em    = $this->get('doctrine.orm.entity_manager');
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

    public function viewProductAction($id_product){
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
