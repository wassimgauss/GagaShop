<?php

namespace GaussBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AboIptv
 *
 * @ORM\Table(name="abo_iptv")
 * @ORM\Entity(repositoryClass="GaussBundle\Repository\AboIptvRepository")
 */
class AboIptv
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

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
     * @var string
     *
     * @ORM\Column(name="duree", type="string", length=255)
     */
    private $duree;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="classement", type="integer", nullable=true)
     */
    private $classement;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return AboIptv
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return AboIptv
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set currentPrice
     *
     * @param string $currentPrice
     *
     * @return AboIptv
     */
    public function setCurrentPrice($currentPrice)
    {
        $this->currentPrice = $currentPrice;

        return $this;
    }

    /**
     * Get currentPrice
     *
     * @return string
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
     * @return AboIptv
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
     * Set duree
     *
     * @param string $duree
     *
     * @return AboIptv
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return string
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return AboIptv
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set classement
     *
     * @param integer $classement
     *
     * @return AboIptv
     */
    public function setClassement($classement)
    {
        $this->classement = $classement;

        return $this;
    }

    /**
     * Get classement
     *
     * @return int
     */
    public function getClassement()
    {
        return $this->classement;
    }
}

