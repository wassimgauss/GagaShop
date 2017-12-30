<?php

namespace GaussBundle\Controller;

use GaussBundle\Entity\Produit;
use GaussBundle\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Gauss/Admin/index.html.twig');
    }
    
    public function listUserAction(){

        $listUsers =array();
        $em = $this->getDoctrine()->getManager();
        $listUsersAll = $em->getRepository('UserBundle:User')->findAll();
        foreach ($listUsersAll as $user){
            foreach ($user->getRoles() as $role) {
                if($role == "ROLE_USER")
                    array_push($listUsers,$user);
            }
        }

        return $this->render('@Gauss/Admin/Users/listUsers.html.twig',array('listUsers' => $listUsers));
    }

    public function enableAction($id_user){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("UserBundle:User")->find($id_user);
        if($user != null) {
            $user->setEnabled(1);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('adminpage_list_users');
        }
        else {
            return $this->redirectToRoute("homepage_404");
        }
    }

    public function disableAction($id_user){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("UserBundle:User")->find($id_user);
        if($user != null) {
            $user->setEnabled(0);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('adminpage_list_users');
        }
        else {
            return $this->redirectToRoute("homepage_404");
        }
    }
    
    
}
