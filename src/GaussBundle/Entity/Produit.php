<?php

namespace GaussBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="GaussBundle\Repository\ProduitRepository")
 */
class Produit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @ORM\ManyToOne(targetEntity="GaussBundle\Entity\Category", inversedBy="produits")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;
    
    /**
     *
     * @ORM\OneToOne(targetEntity="MultimediaBundle\Entity\Image", cascade={"persist", "remove"})
     *
     */
    private $image;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nameProduct", type="string", length=255)
     */
    private $nameProduct;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionProduct", type="string", length=855)
     */
    private $descriptionProduct;

    /**
     * @var int
     *
     * @ORM\Column(name="currentPrice", type="integer")
     */
    private $currentPrice;

    /**
     * @var int
     *
     * @ORM\Column(name="otherPrice", type="integer", nullable=true)
     */
    private $otherPrice;

    /**
     * @var int
     *
     * @ORM\Column(name="statusProduct", type="integer")
     */
    private $statusProduct;

    /**
     * @var string
     *
     * @ORM\Column(name="codeProduct", type="string", length=255)
     */
    private $codeProduct;

    /**
     * @var int
     *
     * @ORM\Column(name="classement", type="integer", nullable=true)
     */
    private $classement;

    /**
     * @return int
     */
    public function getClassement()
    {
        return $this->classement;
    }

    /**
     * @param int $classement
     */
    public function setClassement($classement)
    {
        $this->classement = $classement;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nameProduct
     *
     * @param string $nameProduct
     *
     * @return Produit
     */
    public function setNameProduct($nameProduct)
    {
        $this->nameProduct = $nameProduct;

        return $this;
    }

    /**
     * Get nameProduct
     *
     * @return string
     */
    public function getNameProduct()
    {
        return $this->nameProduct;
    }

    /**
     * Set descriptionProduct
     *
     * @param string $descriptionProduct
     *
     * @return Produit
     */
    public function setDescriptionProduct($descriptionProduct)
    {
        $this->descriptionProduct = $descriptionProduct;

        return $this;
    }

    /**
     * Get descriptionProduct
     *
     * @return string
     */
    public function getDescriptionProduct()
    {
        return $this->descriptionProduct;
    }

    /**
     * Set currentPrice
     *
     * @param integer $currentPrice
     *
     * @return Produit
     */
    public function setCurrentPrice($currentPrice)
    {
        $this->currentPrice = $currentPrice;

        return $this;
    }

    /**
     * Get currentPrice
     *
     * @return int
     */
    public function getCurrentPrice()
    {
        return $this->currentPrice;
    }

    /**
     * Set otherPrice
     *
     * @param integer $otherPrice
     *
     * @return Produit
     */
    public function setOtherPrice($otherPrice)
    {
        $this->otherPrice = $otherPrice;

        return $this;
    }

    /**
     * Get otherPrice
     *
     * @return int
     */
    public function getOtherPrice()
    {
        return $this->otherPrice;
    }

    /**
     * Set statusProduct
     *
     * @param integer $statusProduct
     *
     * @return Produit
     */
    public function setStatusProduct($statusProduct)
    {
        $this->statusProduct = $statusProduct;

        return $this;
    }

    /**
     * Get statusProduct
     *
     * @return int
     */
    public function getStatusProduct()
    {
        return $this->statusProduct;
    }

    /**
     * Set codeProduct
     *
     * @param string $codeProduct
     *
     * @return Produit
     */
    public function setCodeProduct($codeProduct)
    {
        $this->codeProduct = $codeProduct;

        return $this;
    }

    /**
     * Get codeProduct
     *
     * @return string
     */
    public function getCodeProduct()
    {
        return $this->codeProduct;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }


}

