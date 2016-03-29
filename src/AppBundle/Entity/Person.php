<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Person
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonRepository")
 * @ORM\Table(name="jdj_person", indexes={@ORM\Index(name="search_idx", columns={"slug"})})
 */
class Person implements ResourceInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"firstName","lastName"}, separator="-")
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @var ProductInterface[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Product", mappedBy="designers", cascade={"persist", "merge"})
     */
    private $designerProducts;

    /**
     * @var ProductInterface[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Product", mappedBy="artists", cascade={"persist", "merge"})
     */
    private $artistProducts;

    /**
     * @var ProductInterface[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Product", mappedBy="publishers", cascade={"persist", "merge"})
     */
    private $publisherProducts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->designerProducts = new ArrayCollection();
        $this->artistProducts = new ArrayCollection();
        $this->publisherProducts = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getImage()
    {
        return null;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return ProductInterface[]|Collection
     */
    public function getPublisherProducts()
    {
        return $this->publisherProducts;
    }

    /**
     * @param ProductInterface[]|Collection $publisherProducts
     *
     * @return $this
     */
    public function setPublisherProducts($publisherProducts)
    {
        $this->publisherProducts = $publisherProducts;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param string $website
     *
     * @return $this
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * @return ProductInterface[]|Collection
     */
    public function getDesignerProducts()
    {
        return $this->designerProducts;
    }

    /**
     * @param ProductInterface[]|Collection $designerProducts
     *
     * @return $this
     */
    public function setDesignerProducts($designerProducts)
    {
        $this->designerProducts = $designerProducts;

        return $this;
    }

    /**
     * @return ProductInterface[]|Collection
     */
    public function getArtistProducts()
    {
        return $this->artistProducts;
    }

    /**
     * @param ProductInterface[]|Collection $artistProducts
     *
     * @return $this
     */
    public function setArtistProducts($artistProducts)
    {
        $this->artistProducts = $artistProducts;

        return $this;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Personne
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Personne
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Convert Entity To String
     *
     * @return string
     */
    public function __toString()
    {
        /**
         * firstname is not mandatory, thus we trim concatenation
         */
        return trim($this->getFirstName() . ' ' . $this->getLastName());
    }
}
