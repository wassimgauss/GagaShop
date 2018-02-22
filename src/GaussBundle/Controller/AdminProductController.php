<?php

namespace GaussBundle\Controller;

use GaussBundle\Entity\AboIptv;
use GaussBundle\Entity\Category;
use GaussBundle\Entity\Produit;
use GaussBundle\Form\AboIptvType;
use GaussBundle\Form\CategoryType;
use GaussBundle\Form\ProduitIPTVType;
use GaussBundle\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminProductController extends Controller
{

    public function listProductAction()
    {
        $em= $this->getDoctrine()->getManager();
        $listProduct = $em->getRepository("GaussBundle:Produit")->findAll();
        return $this->render('@Gauss/Admin/Product/listProduct.html.twig',array('listProduct' => $listProduct));

    }

    public function addProductAction(Request $request)
    {
        $user = $this->getUser();
        $em= $this->getDoctrine()->getManager();
        $product = new Produit();
        $form = $this->get('form.factory')->createBuilder(ProduitType::class, $product)->getForm();
        if($request->isMethod('POST')) {
            $form->handleRequest($request);
            if($form->isValid()) {
                $product->setNameProductUrl(str_replace(" ","-",strtolower($product->getNameProduct())));
                $em->persist($product);
                $em->flush();
                return $this->redirectToRoute('adminpage');
            }
        }
        return $this->render('@Gauss/Admin/Product/addProduct.html.twig',array('form' => $form->createView()));
    }

    public function listIptvAction()
    {
        $em= $this->getDoctrine()->getManager();
        $listProduct = $em->getRepository("GaussBundle:Produit")->findBy(array('category' => 5));
        return $this->render('@Gauss/Admin/AboIptv/listAboIptv.html.twig',array('listProduct' => $listProduct));
    }

    public function addIptvAction(Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $product = new Produit();
        $form = $this->get('form.factory')->createBuilder(ProduitIPTVType::class, $product)->getForm();
        if($request->isMethod('POST')) {
            $form->handleRequest($request);
            if($form->isValid()) {
                $em->persist($product);
                $em->flush();
                return $this->redirectToRoute('adminpage');
            }
        }
        return $this->render('@Gauss/Admin/AboIptv/addAboIptv.html.twig',array('form' => $form->createView()));
    }

    
}
