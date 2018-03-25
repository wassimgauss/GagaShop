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
        $nom_prod = $request->query->get('product_name');
        if($session->get('last_route')['name'] === "shoppage_category"){
            $parms = $session->get('last_route')['params'];
            foreach ($parms as $p)
                $param = $p;
            return $this->render('@Gauss/Shop/index.html.twig',array('page' => $page, 'price' => $price,'param' => $param, 'nom_prod' => $nom_prod));
        }
        return $this->render('@Gauss/Shop/index.html.twig',array('page' => $page, 'price' => $price, 'nom_prod' => $nom_prod, 'param' => ''));
    }
    
    public function getProductAction(Request $request, $page, $price, $param, $nom_prod)
    {
        if(!$page)
        $request->query->set('page',1);
        $em = $this->get('doctrine.orm.entity_manager');
        $clause_req ="";
        if($param) {
            $catg = $em->getRepository("GaussBundle:Category")->findOneBy(array('nomUrl' => $param));
            if($catg !=null) {
                $id_categ = $catg->getId();
                $clause_req= "AND a.category = ".$id_categ;
            }
        }
        if($price != null) {
            $value = explode(',',$price);
            $min = $value[0];
            $max = $value[1];
            $dql   = "select a from GaussBundle:Produit a where a.currentPrice BETWEEN ".$min."AND ".$max." ".$clause_req;
        }
        else if($nom_prod != null)
            $dql   = "select a from GaussBundle:Produit a where a.category != 5 AND a.nameProduct Like '%".$nom_prod." %'";
        else
            $dql   = "select a from GaussBundle:Produit a where a.category != 5 ORDER BY a.currentPrice DESC";
        $query = $em->createQuery($dql);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->get('page',$page)/*page number*/,
            6/*limit per page*/
        );
        return $this->render('@Gauss/Shop/layout/shop-body.html.twig',array('pagination' => $pagination));
    }

    public function indexCategAction(Request $request, $nom_categ)
    {
        $session = $request->getSession();
        $local = $session->get('_local');
        $request->setLocale($local);
        $request->setDefaultLocale($local);
        $this->get('translator')->setLocale($local);

        $page = $request->query->get('page');
        $price = $request->query->get('price');
        return $this->render('@Gauss/Shop/index-categ.html.twig',array('nom_categ' => $nom_categ, 'page' => $page, 'price' => $price));
    }

    public function getProductCategAction($nom_categ, Request $request, $page, $price)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $catg = $em->getRepository("GaussBundle:Category")->findOneBy(array('nomUrl' => $nom_categ));
        if($catg != null)
            $id_categ = $catg->getId();
        else {
            $pagination = null;
            return $this->render('@Gauss/Shop/layout/shop-body.html.twig',array('pagination' => $pagination));
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
            return $this->redirect($this->generateUrl('homepage_404'));
        }
    }
    
}
