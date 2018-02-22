<?php

namespace GaussBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use GaussBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ShopController extends Controller
{
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $local = $session->get('_local');
        $request->setLocale($local);
        $request->setDefaultLocale($local);
        $this->get('translator')->setLocale($local);
        $page = $request->query->get('page');
        $price = $request->query->get('price');
        return $this->render('@Gauss/Shop/index.html.twig',array('page' => $page, 'price' => $price));
    }
    
    public function getProductAction(Request $request, $page, $price)
    {
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

    public function indexCategAction(Request $request, $id_categ, $nom_categ)
    {
        $session = $request->getSession();
        $local = $session->get('_local');
        $request->setLocale($local);
        $request->setDefaultLocale($local);
        $this->get('translator')->setLocale($local);
        $page = $request->query->get('page');
        $price = $request->query->get('price');
        return $this->render('@Gauss/Shop/index-categ.html.twig',array('id_categ' => $id_categ,'nom_categ' => str_replace(" ","-",strtolower($nom_categ)), 'page' => $page, 'price' => $price));
    }

    public function getProductCategAction($id_categ, $nom_categ, Request $request, $page, $price)
    {
        $session = $request->getSession();
        $em    = $this->get('doctrine.orm.entity_manager');
        $category = $em->getRepository("GaussBundle:Category")->find(array("id" => $id_categ));
        if(strcmp($nom_categ,str_replace(" ","-",strtolower($category->getNom())))){
            $pagination = null;
            return $this->render('@Gauss/Shop/layout/shop-body.html.twig',array('pagination' => $pagination));
            exit();
        }
        if(!$page)
            $request->query->set('page',1);
        if($price != null) {
            $value = explode(',',$price);
            $min = $value[0];
            $max = $value[1];
            $dql   = "select a from GaussBundle:Produit a where a.category = ".$id_categ." AND a.currentPrice BETWEEN ".$min."AND ".$max;
        }
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

    public function getCategoryAction()
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
        return $this->render('@Gauss/Shop/layout/widget-categories.html.twig',array('listCategory' => $listCategory, 'count' => $data,'names_url' => $names_url,));
    }

    public function getTopSellerAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listProductTop = $em->getRepository("GaussBundle:Produit")->findBy(array(),array('classement' => 'desc'), 4);
        return $this->render('@Gauss/Shop/layout/widget-top-seller.html.twig',array('listProductTop' => $listProductTop));
    }

    public function viewProductAction($nom_product, Request $request)
    {
        $session = $request->getSession();
        $local = $session->get('_local');
        $request->setLocale($local);
        $request->setDefaultLocale($local);
        $this->get('translator')->setLocale($local);
        $em = $this->getDoctrine()->getManager();
        $allproduct= $em->getRepository("GaussBundle:Produit")->findBy(array(),array('statusProduct' => 'desc'),10);
        $product = $em->getRepository("GaussBundle:Produit")->findOneBy(array('nameProductUrl' => $nom_product));
        $count=0;
        $all_id = array();
        if($product != null){
            //$query = $em->createQuery('select count(p.id) from GaussBundle:Produit p WHERE p.category = 1');
            //$count = $query->getResult();
            $counts = $em->getRepository("GaussBundle:Produit")->getProductCateg($product->getCategory());
            foreach ($counts as $c){
                $count++;
                array_push($all_id,$c->getId());
            }
            $session->set('id_product',$product->getId());
            $current_key = array_search($product->getId(),$all_id);
                if($current_key == $count-1 ){
                    $suiv_id = $all_id[0];
                }
                else {
                    $suiv_id =$all_id[$current_key +1];
                }
                if($current_key == 0){
                    $prev_id =$all_id[$count-1];
                }
                else {
                    $prev_id = $all_id[$current_key -1];
                }
            $product_prev = $em->getRepository("GaussBundle:Produit")->findOneBy(array('id' => $prev_id));
            $product_suiv = $em->getRepository("GaussBundle:Produit")->findOneBy(array('id' => $suiv_id));
            $prev = $product_prev->getNameProductUrl();
            $suiv = $product_suiv->getNameProductUrl();
            return $this->render('@Gauss/Shop/viewProduct.html.twig',array('product' => $product,
                'allProdcut' => $allproduct, 'prev' =>$prev, 'suiv' => $suiv));
        }
        else {
            var_dump($nom_product);
            return $this->redirect($this->generateUrl('homepage_404'));
        }
    }
    
}
