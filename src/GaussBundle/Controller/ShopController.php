<?php

namespace GaussBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ShopController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Gauss/Shop/index.html.twig');
    }

    public function indexCategAction($id_categ){

        return $this->render('@Gauss/Shop/index-categ.html.twig',array('id_categ' => $id_categ));
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

    public function getProductCategAction($id_categ){
        $em = $this->getDoctrine()->getManager();
        $listProduct = $em->getRepository("GaussBundle:Produit")->findBy(array('category' => $id_categ));
        return $this->render('@Gauss/Shop/layout/shop-body.html.twig',array('listProduct' => $listProduct));
    }

    public function getProductAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        //$listProduct = $em->getRepository("GaussBundle:Produit")->findAll();
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "select a from GaussBundle:Produit a where a.category != 5";
        $query = $em->createQuery($dql);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );

        return $this->render('@Gauss/Shop/layout/shop-body.html.twig',array('pagination' => $pagination));
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
