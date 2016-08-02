<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 08/03/2016
 * Time: 10:12
 */

namespace AppBundle\Entity;

use AppBundle\Document\ArticleContent;
use AppBundle\Model\Identifiable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Review\Model\ReviewableInterface;
use Sylius\Component\Review\Model\ReviewInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\User\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_article")
 */
class Article implements ResourceInterface, ReviewableInterface
{
    use Identifiable;

    /**
     * @var integer
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $documentId;

    /**
     * @var ArticleContent
     */
    protected $document;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $publishStartDate;

    /**
     * @var TaxonInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Taxonomy\Model\TaxonInterface")
     */
    protected $mainTaxon;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $publishEndDate;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $publishable = true;

    /**
     * @var ProductInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Product\Model\ProductInterface")
     */
    protected $product;

    /**
     * @var CustomerInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\User\Model\CustomerInterface")
     */
    protected $author;

    /**
     * @var Topic
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Topic", inversedBy="article")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $topic;

    /**
     * @var ArrayCollection|ArticleReview[]
     *
     * @ORM\OneToMany(targetEntity="ArticleReview", mappedBy="reviewSubject")
     */
    protected $reviews;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $averageRating = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $materialRating = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $rulesRating = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $lifetimeRating = 0;

    /**
     * Article constructor.
     */
    public function __construct()
    {
        $this->publishable = false;
        $this->reviews = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getDocumentId()
    {
        return $this->documentId;
    }

    /**
     * @param int $documentId
     *
     * @return $this
     */
    public function setDocumentId($documentId)
    {
        $this->documentId = $documentId;

        return $this;
    }

    /**
     * @return ArticleContent
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param ArticleContent $document
     *
     * @return $this
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param ProductInterface $product
     *
     * @return $this
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return CustomerInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param CustomerInterface $author
     *
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param Topic $topic
     *
     * @return $this
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return ArticleReview[]|ArrayCollection
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @param ReviewInterface $review
     *
     * @return $this
     */
    public function addReview(ReviewInterface $review)
    {
        if (!$this->reviews->contains($review)) {
            $review->setReviewSubject($this);
            $this->reviews->add($review);
        }

        return $this;
    }

    /**
     * @param ReviewInterface $review
     *
     * @return $this
     */
    public function removeReview(ReviewInterface $review)
    {
        $this->reviews->remove($review);

        return $this;
    }

    /**
     * @return float
     */
    public function getAverageRating()
    {
        return $this->averageRating;
    }

    /**
     * @param float $averageRating
     *
     * @return $this
     */
    public function setAverageRating($averageRating)
    {
        $this->averageRating = $averageRating;

        return $this;
    }

    /**
     * @return float
     */
    public function getMaterialRating()
    {
        return $this->materialRating;
    }

    /**
     * @param float $materialRating
     *
     * @return $this
     */
    public function setMaterialRating($materialRating)
    {
        $this->materialRating = $materialRating;

        return $this;
    }

    /**
     * @return float
     */
    public function getRulesRating()
    {
        return $this->rulesRating;
    }

    /**
     * @param float $rulesRating
     *
     * @return $this
     */
    public function setRulesRating($rulesRating)
    {
        $this->rulesRating = $rulesRating;

        return $this;
    }

    /**
     * @return float
     */
    public function getLifetimeRating()
    {
        return $this->lifetimeRating;
    }

    /**
     * @param float $lifetimeRating
     *
     * @return $this
     */
    public function setLifetimeRating($lifetimeRating)
    {
        $this->lifetimeRating = $lifetimeRating;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getPublishStartDate()
    {
        return $this->publishStartDate;
    }

    /**
     * @param \DateTime|null $publishStartDate
     *
     * @return $this
     */
    public function setPublishStartDate($publishStartDate)
    {
        $this->publishStartDate = $publishStartDate;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getPublishEndDate()
    {
        return $this->publishEndDate;
    }

    /**
     * @param \DateTime|null $publishEndDate
     *
     * @return $this
     */
    public function setPublishEndDate($publishEndDate)
    {
        $this->publishEndDate = $publishEndDate;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isPublishable()
    {
        return $this->publishable;
    }

    /**
     * @param boolean $publishable
     *
     * @return $this
     */
    public function setPublishable($publishable)
    {
        $this->publishable = $publishable;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getDocument()->getTitle();
    }

    /**
     * @return TaxonInterface
     */
    public function getMainTaxon()
    {
        return $this->mainTaxon;
    }

    /**
     * @param TaxonInterface $mainTaxon
     *
     * @return $this
     */
    public function setMainTaxon($mainTaxon)
    {
        $this->mainTaxon = $mainTaxon;

        return $this;
    }
}
