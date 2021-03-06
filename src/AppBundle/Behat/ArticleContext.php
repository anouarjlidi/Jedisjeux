<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat;

use AppBundle\Entity\Article;
use Behat\Gherkin\Node\TableNode;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleContext extends DefaultContext
{
    /**
     * @Given /^there are articles:$/
     * @Given /^there are following articles:$/
     * @Given /^the following articles exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreArticles(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            /** @var TaxonInterface $taxon */
            $taxon = $this->getRepository('taxon')->findOneBySlug($data['taxon'], $this->getContainer()->getParameter('locale'));

            /** @var CustomerInterface $author */
            $author = $this->getRepository('customer')->findOneBy(['email' => $data['author']]);

            if (null === $taxon) {
                throw new \InvalidArgumentException(
                    sprintf('Taxon with permalink "%s" was not found.', $data['taxon'])
                );
            }

            /** @var Article $article */
            $article = $this->getFactory('article', 'app')->createNew();
            $article->setCode(isset($data['code']) ? $data['code'] : $this->faker->postcode);
            $article->setTitle(isset($data['title']) ? $data['title'] : $this->faker->text(20));
            $article->setPublishable(true);
            $article->setStatus(isset($data['status']) ? $data['status'] : Article::STATUS_PUBLISHED);
            $article->setMainTaxon($taxon);
            $article->setAuthor($author);

            $manager->persist($article);
        }

        $manager->flush();
    }

    /**
     * @When I am on :articleTitle article page
     *
     * @param string $articleTitle
     */
    public function iAmOnArticlePage($articleTitle)
    {
        /** @var Article $article */
        $article = $this->findOneBy('article', ['title' => $articleTitle], 'app');

        $this->visitPath(sprintf('/article/%s', $article->getSlug()));
    }

    /**
     * @Then :articleTitle article should have :statusName status
     *
     * @param string $articleTitle
     * @param string $status
     */
    public function articleHasFollowingStatus($articleTitle, $status)
    {
        /** @var Article $article */
        $article = $this->findOneBy('article', ['title' => $articleTitle], 'app');

        Assert::eq($status, $article->getStatus(), sprintf("%s article has %s status but it should have %s status", $article->getTitle(), $article->getStatus(), $status));
    }
}