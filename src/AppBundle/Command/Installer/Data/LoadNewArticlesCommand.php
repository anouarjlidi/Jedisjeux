<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 31/01/16
 * Time: 18:58
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Command\LogMemoryUsageTrait;
use AppBundle\Document\BlockquoteBlock;
use AppBundle\Document\ArticleContent;
use AppBundle\Entity\Article;
use AppBundle\Entity\Taxon;
use AppBundle\Entity\Topic;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SlideshowBlock;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadNewArticlesCommand extends ContainerAwareCommand
{
    const BATCH_SIZE = 20;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:new-articles:load')
            ->setDescription('Load articles')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command loads all articles.
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $i = 0;

        foreach ($this->getArticles() as $key => $data) {
            $output->writeln(sprintf("Loading <comment>%s</comment> article", $data['title']));

            $article = $this->createOrReplaceArticle($data);

            if (null !== $data['mainTaxon']) {
                /** @var TaxonInterface $mainTaxon */
                $mainTaxon = $this->getContainer()->get('sylius.repository.taxon')->findOneBy(['code' => $data['mainTaxon']]);
                $article->setMainTaxon($mainTaxon);
            }

            if (null !== $data['product_id']) {
                /** @var ProductInterface $product */
                $product = $this->getContainer()->get('sylius.repository.product')->find($data['product_id']);

                $article
                    ->setProduct($product);
            }

            if (null !== $data['topic_id']) {
                /** @var Topic $topic */
                $topic = $this->getContainer()->get('app.repository.topic')->find($data['topic_id']);

                $article
                    ->setTopic($topic);
            }

            if (null !== $data['author_id']) {
                /** @var CustomerInterface $author */
                $author = $this->getContainer()->get('sylius.repository.customer')->find($data['author_id']);
                $article
                    ->setAuthor($author);
            }

            $this->getManager()->persist($article);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->getManager()->flush(); // Executes all updates.
                $this->getManager()->clear(); // Detaches all objects from Doctrine!
            }

            ++$i;
        }

        $this->getManager()->flush();
        $this->getManager()->clear();

        $output->writeln(sprintf("<info>%s</info>", "Articles have been successfully loaded."));
    }

    /**
     * @param array $data
     *
     * @return Article
     */
    protected function createOrReplaceArticle(array $data)
    {
        $code = sprintf('article-%s', $data['id']);

        /** @var Article $article */
        $article = $this->getRepository()->findOneBy(['code'=> $code]);

        if (null === $article) {
            $article = $this->getFactory()->createNew();
        }

        $article
            ->setCode($code)
            ->setName($data['name'])
            ->setTitle($data['title'])
            ->setViewCount($data['view_count'])
            ->setShortDescription($data['shortDescription'] ?: null)
            ->setPublishable($data['publishable'])
            ->setPublishStartDate(\DateTime::createFromFormat('Y-m-d H:i:s', $data['publishedAt']))
            ->setStatus($data['publishable'] ? Article::STATUS_PUBLISHED : Article::STATUS_NEW);


        if (null !== $data['mainImage'] && !empty($data['mainImage'])) {
            if (null === $mainImage = $article->getMainImage()) {
                $mainImage = $this->getContainer()->get('app.factory.article_image')->createNew();
                $article
                    ->setMainImage($mainImage);
            }

            $mainImage
                ->setPath($data['mainImage']);
        }

        return $article;
    }

    /**
     * @return array
     */
    protected function getArticles()
    {
        $query = <<<EOM
SELECT
  article.article_id                                                        AS id,
  concat(replace(article.titre_clean, ' ', '-'), '-a-', article.article_id) AS name,
  article.titre                                                             AS title,
  article.date                                                              AS publishedAt,
  article.intro                                                             AS shortDescription,
  article.photo                                                             AS mainImage,
  CASE article.type_article
  WHEN 'article'
    THEN NULL
  WHEN 'reportage'
    THEN 'report-articles'
  WHEN 'interview'
    THEN 'interviews'
  WHEN 'cdlb'
    THEN 'in-the-boxes'
  WHEN 'preview'
    THEN 'previews'
  ELSE NULL
  END                                                                       AS mainTaxon,
  article.valid                                                             AS publishable,
  product.id                                                                AS product_id,
  CASE WHEN article.topic_id = 5280 AND article.article_id <> 313
    THEN NULL
  ELSE topic.id END                                                         AS topic_id,
  user.customer_id                                                          AS author_id,
  article_view.view_count
FROM jedisjeux.jdj_article article
  INNER JOIN jedisjeux.jdj_v_article_view_count AS article_view
    ON article_view.id = article.article_id
  LEFT JOIN sylius_product_variant productVariant
    ON productVariant.code = concat('game-', article.game_id)
  LEFT JOIN sylius_product product
    ON product.id = productVariant.product_id
  LEFT JOIN jdj_topic topic
    ON topic.code = concat('topic-', article.topic_id)
  LEFT JOIN sylius_user user
    ON convert(user.username USING UTF8) = convert(article.auteur USING UTF8)
WHERE titre_clean != ''
ORDER BY article.date DESC
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
    }

    /**
     * @return FactoryInterface|object
     */
    protected function getFactory()
    {
        return $this->getContainer()->get('app.factory.article');
    }

    /**
     * @return EntityRepository|object
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('app.repository.article');
    }

    /**
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}