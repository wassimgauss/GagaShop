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
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $myfav = $em->getRepository("GaussBundle:Favoris")->findBy(array('idUser' => $user->getId()));
        $listProduct = array();
        foreach ($myfav as $item) {
            $product = $em->getRepository("GaussBundle:Produit")->findOneBy(array('id' => $item->getIdProduct()));
            array_push($listProduct,$product);
        }
        return $this->render('@Gauss/Shop/wish-list.html.twig',array('listproduct' => $listProduct));
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
            throw new EntityNotFoundException();
        }
      if($session->get('last_route')['name'] === "shoppage"){
            return $this->redirect($this->generateUrl('shoppage'));
        }
        elseif ($session->get('last_route')['name'] === "adminpage_view_product") {
            return $this->redirect($this->generateUrl('adminpage_view_product',array('id_product' => $id_product)));
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
            throw new EntityNotFoundException();
        }
        return $this->redirect($this->generateUrl('shoppage_wishlist'));
    }

}
