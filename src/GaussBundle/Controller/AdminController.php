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
    
    
}
