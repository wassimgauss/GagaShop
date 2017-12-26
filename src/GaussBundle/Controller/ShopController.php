<?php

namespace GaussBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShopController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Gauss/Shop/index.html.twig');
    }

    public function getCategoryAction(){
        //$user = $this->getUser();
        $em= $this->getDoctrine()->getManager();
        $listCategory = $em->getRepository("GaussBundle:Category")->findAll();
        return $this->render('@Gauss/Shop/layout/widget-categories.html.twig',array('listCategory' => $listCategory));
    }

    public function getProductAction(){
        $em = $this->getDoctrine()->getManager();
        $listProduct = $em->getRepository("GaussBundle:Produit")->findAll();
        return $this->render('@Gauss/Shop/layout/shop-body.html.twig',array('listProduct' => $listProduct));
    }

    public function getTopSellerAction(){
        $em = $this->getDoctrine()->getManager();
        $listProductTop = $em->getRepository("GaussBundle:Produit")->findBy(array(),array('classement' => 'desc'), 3);
        return $this->render('@Gauss/Shop/layout/widget-top-seller.html.twig',array('listProductTop' => $listProductTop));
    }

    public function viewProductAction($id_product){
        $em = $this->getDoctrine()->getManager();
        $allproduct= $em->getRepository("GaussBundle:Produit")->findBy(array(),array('statusProduct' => 'desc'));
        $product = $em->getRepository("GaussBundle:Produit")->findOneBy(array('id' => $id_product));
        return $this->render('@Gauss/Shop/viewProduct.html.twig',array('product' => $product, 'allProdcut' => $allproduct));
    }
    
}
