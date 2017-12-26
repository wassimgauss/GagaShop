<?php

namespace GaussBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PanierController extends Controller
{
    
    public function viewCartAction(Request $request){

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
        return $this->redirect($this->generateUrl('adminpage_view_cart'));
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

}
