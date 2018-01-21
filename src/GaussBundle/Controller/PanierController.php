<?php

namespace GaussBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PanierController extends Controller
{
    
    public function viewCartAction(Request $request){

        $session = $request->getSession();
        $local = $session->get('_local');
        $request->setLocale($local);
        $request->setDefaultLocale($local);
        $this->get('translator')->setLocale($local);
        $em = $this->getDoctrine()->getManager();
        if(!$session->has('panier'))
            $session->set('panier',array());
        $listproduct = array();
        foreach (array_keys($session->get('panier')) as $key ){
           $product = $em->getRepository("GaussBundle:Produit")->findOneBy(array('id' => intval($key)));
           array_push($listproduct,$product);
        }
        return $this->render('@Gauss/Shop/cart.html.twig',array('listproduct' => $listproduct,'panier' =>$session->get('panier')));
    }

    public function addToCartAction($id_product, Request $request){

        $session = $request->getSession();
        if(!$session->has('panier'))
            $session->set('panier',array());
        $panier = $session->get('panier');
        if(array_key_exists($id_product, $panier)) {
            if($request->query->get('qte') != null)
                $panier[$id_product] = $request->query->get('qte');

        } else {
            if($request->query->get('qte') != null) {
                $panier[$id_product] = $request->query->get('qte');
            }
            else {
                $panier[$id_product] =1;
            }
        }
        $session->set('panier',$panier);
        sleep(2);
        if($session->get('last_route')['name'] === "shoppage"){
            return $this->redirect($this->generateUrl('shoppage'));
        }
        elseif ($session->get('last_route')['name'] === "adminpage_view_product") {
            return $this->redirect($this->generateUrl('adminpage_view_product',array('id_product' => $id_product)));
        }
        elseif ($session->get('last_route')['name'] === "shoppage_wishlist"){
            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $myfav = $em->getRepository("GaussBundle:Favoris")->findOneBy(array('idUser' => $user->getId(),'idProduct' => $id_product));
            if($myfav != null) {
                $em->remove($myfav);
                $em->flush();
            }
            else {
                return $this->redirect($this->generateUrl('homepage_404'));
            }
            return $this->redirect($this->generateUrl('adminpage_view_cart'));
        }
        else {
            return $this->redirect($this->generateUrl('adminpage_view_cart'));
        }
    }

    public function deleteFromCartAction($id_product, Request $request){

        $session = $request->getSession();
        $panier = $session->get('panier');
        if(array_key_exists($id_product, $panier)) {
            unset($panier[$id_product]);
        }
        $panier = $session->set('panier',$panier);
        return $this->redirect($this->generateUrl('adminpage_view_cart'));
    }


    public function getProductAction(Request $request){

        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        if(!$session->has('panier'))
            $session->set('panier',array());
        //print_r(array_keys($session->get('panier')));
        $listproduct = array();
        foreach (array_keys($session->get('panier')) as $key ){
            $product = $em->getRepository("GaussBundle:Produit")->findOneBy(array('id' => intval($key)));
            array_push($listproduct,$product);
        }
        return $this->render('@Gauss/Default/layout/navbar-panier.html.twig',array('listproduct' => $listproduct,'panier' =>$session->get('panier')));
    }
}
