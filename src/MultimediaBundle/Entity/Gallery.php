<?php

namespace MultimediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Gallery
 *
 * @ORM\Table(name="gallery")
 * @ORM\Entity(repositoryClass="MultimediaBundle\Repository\GalleryRepository")
 */
class Gallery
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
     * @ORM\Column(name="nomGallery", type="string", length=255, nullable=true)
     */
    private $nomGallery;

    
    private $mesImage;

    /**
     * Gallery constructor.
     * @param $mesImage
     */
    public function __construct($mesImage)
    {
        $this->mesImage = new ArrayCollection();
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
     * Set nomGallery
     *
     * @param string $nomGallery
     *
     * @return Gallery
     */
    public function setNomGallery($nomGallery)
    {
        $this->nomGallery = $nomGallery;

        return $this;
    }

    /**
     * Get nomGallery
     *
     * @return string
     */
    public function getNomGallery()
    {
        return $this->nomGallery;
    }
}

