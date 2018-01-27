<?php

namespace GaussBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
use GaussBundle\Entity\Favoris;
use GaussBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class WishController extends Controller
{
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $local = $session->get('_local');
        $request->setLocale($local);
        $request->setDefaultLocale($local);
        $this->get('translator')->setLocale($local);
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if($user != null) {
            $myfav = $em->getRepository("GaussBundle:Favoris")->findBy(array('idUser' => $user->getId()));
            $listProduct = array();
            $listIptv = array();
            foreach ($myfav as $item) {
                $product = $em->getRepository("GaussBundle:Produit")->findOneBy(array('id' => $item->getIdProduct()));
                array_push($listProduct,$product);
            }
            return $this->render('@Gauss/Shop/wish-list.html.twig',array('listproduct' => $listProduct));
        }
        else {
            return $this->redirectToRoute('fos_user_security_login');
        }
    }

    public function addToWishAction($id_product, Request $request){

        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $user = $this->getUser();
        $favoris = new Favoris();
        $product = $em->getRepository("GaussBundle:Produit")->findOneBy(array('id' => $id_product));
        if($product != null && $user != null ){
            $favoris->setIdUser($user->getId());
            $favoris->setIdProduct($id_product);
            $em->persist($favoris);
            $em->flush();
        }
        else {
            return $this->redirect($this->generateUrl('homepage_404'));
        }
        sleep(2);
      if($session->get('last_route')['name'] === "shoppage"){
            return $this->redirect($this->generateUrl('shoppage'));
        }
        elseif ($session->get('last_route')['name'] === "adminpage_view_product") {
            return $this->redirect($this->generateUrl('adminpage_view_product',array('id_product' => $id_product)));
        }
      elseif ($session->get('last_route')['name'] === "shoppage_list_aboIptv") {
          return $this->redirect($this->generateUrl('shoppage_list_aboIptv'));
      }
        else {
            return $this->redirect($this->generateUrl('shoppage_wishlist'));
        }
    }

    public function deleteFromWishAction($id_product, Request $request){

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $myfav = $em->getRepository("GaussBundle:Favoris")->findOneBy(array('idUser' => $user->getId(),'idProduct' => $id_product));
        if($myfav != null) {
            $em->remove($myfav);
            $em->flush();
        }
        else {
            return $this->redirect($this->generateUrl('homepage_404'));
        }
        return $this->redirect($this->generateUrl('shoppage_wishlist'));
    }

    
    
    
}
