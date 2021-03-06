<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Factory;

use AppBundle\Entity\Person;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductVariant;
use AppBundle\Entity\ProductVariantImage;
use AppBundle\Utils\BggProduct;
use Doctrine\ORM\EntityRepository;
use Gedmo\Sluggable\Util\Urlizer;
use Sylius\Component\Product\Factory\ProductFactory as BaseProductFactory;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductFactory extends BaseProductFactory
{
    /**
     * @var EntityRepository
     */
    protected $personRepository;

    /**
     * @var FactoryInterface
     */
    protected $productVariantImageFactory;

    /**
     * @var SlugGeneratorInterface
     */
    protected $slugGenerator;

    /**
     * @param EntityRepository $personRepository
     */
    public function setPersonRepository(EntityRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * @param FactoryInterface $productVariantImageFactory
     */
    public function setProductVariantImageFactory(FactoryInterface $productVariantImageFactory)
    {
        $this->productVariantImageFactory = $productVariantImageFactory;
    }

    /**
     * @param SlugGeneratorInterface $slugGenerator
     */
    public function setSlugGenerator(SlugGeneratorInterface $slugGenerator)
    {
        $this->slugGenerator = $slugGenerator;
    }

    /**
     * @param string $bggPath
     *
     * @return Product
     */
    public function createFromBgg($bggPath)
    {
        /** @var Product $product */
        $product = parent::createWithVariant();

        $bggProduct = new BggProduct($bggPath);

        $product->setName($bggProduct->getName());
        $product->setSlug($this->slugGenerator->generate($product->getName()));
        $product->setDescription($bggProduct->getDescription());

        if (null !== $releasedAtYear = $bggProduct->getReleasedAtYear()) {
            $releasedAt = \DateTime::createFromFormat('Y-m-d', $releasedAtYear . '-01-01');

            if (false !== $releasedAt) {
                $firstVariant = $product->getFirstVariant();

                $firstVariant->setReleasedAt($releasedAt);
                $firstVariant->setReleasedAtPrecision(ProductVariant::RELEASED_AT_PRECISION_ON_YEAR);
            }
        }

        $product->setMinAge($bggProduct->getAge());
        $product->setMinDuration($bggProduct->getMinDuration());
        $product->setMaxDuration($bggProduct->getMaxDuration());
        $product->setMinPlayerCount($bggProduct->getNbJoueursMin());
        $product->setMaxPlayerCount($bggProduct->getNbJoueursMax());

        foreach ($bggProduct->getDesigners() as $fullName) {
            $designer = $this->getPersonByFullName($fullName);

            if (null === $designer) {
                continue;
            }

            $product->getFirstVariant()
                ->addDesigner($designer);
        }

        foreach ($bggProduct->getArtists() as $fullName) {
            $artist = $this->getPersonByFullName($fullName);

            if (null === $artist) {
                continue;
            }

            $product->getFirstVariant()
                ->addArtist($artist);
        }

        foreach ($bggProduct->getPublishers() as $fullName) {
            $publisher = $this->getPersonByFullName($fullName);

            if (null === $publisher) {
                continue;
            }

            $product->getFirstVariant()
                ->addPublisher($publisher);
        }

        /** @var ProductVariantImage $mainImage */
        $mainImage = $this->productVariantImageFactory->createNew();
        $mainImage->setMain(true);
        $mainImage->setPath(basename($bggProduct->getImagePath()));
        file_put_contents($mainImage->getAbsolutePath(), file_get_contents($bggProduct->getImagePath()));

        $product->getFirstVariant()->addImage($mainImage);

        return $product;
    }

    /**
     * @param string $fullName
     *
     * @return Person
     */
    protected function getPersonByFullName($fullName)
    {
        $fullName = strtolower($fullName);
        $slug = Urlizer::urlize($fullName, '-');

        /** @var Person $person */
        $person = $this->personRepository->findOneBy(['slug' => $slug]);

        if (null === $person) {
            return null;
        }

        return $person;
    }
}
