<?php

namespace GaussBundle\Controller;

use GaussBundle\Entity\Category;
use GaussBundle\Entity\Produit;
use GaussBundle\Form\CategoryType;
use GaussBundle\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminCategoryController extends Controller
{

    public function addCategoryAction(Request $request)
    {
        $user = $this->getUser();
        $em= $this->getDoctrine()->getManager();
        $category = new Category();
        $form = $this->get('form.factory')->createBuilder(CategoryType::class, $category)->getForm();
        if($request->isMethod('POST')) {
            $form->handleRequest($request);
            if($form->isValid()) {
                $em->persist($category);
                $em->flush();
                return $this->redirectToRoute('adminpage');
            }
        }
        return $this->render('@Gauss/Admin/Category/addCategory.html.twig',array('form' => $form->createView()));
    }

    public function listCategoryAction(Request $request)
    {
        $user = $this->getUser();
        $em= $this->getDoctrine()->getManager();
        $listCategory = $em->getRepository("GaussBundle:Category")->findAll();
        return $this->render('@Gauss/Admin/Category/listCategory.html.twig',array('listCategory' => $listCategory));
    }
    
}
