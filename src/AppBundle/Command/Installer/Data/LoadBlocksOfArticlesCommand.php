<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\Article;
use AppBundle\Entity\Block;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadBlocksOfArticlesCommand extends ContainerAwareCommand
{
    const BATCH_SIZE = 20;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:blocks-of-articles:load')
            ->setDescription('Load blocks of all articles')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command loads blocks of all articles.
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

        foreach ($this->getBlocks() as $key => $data) {
            $output->writeln(sprintf("Loading block <comment>%s</comment> of <comment>%s</comment> article", $data['code'], $data['article_title']));

            /** @var Article $article */
            $article = $this->getContainer()->get('app.repository.article')->find($data['article_id']);
            $block = $this->createOrReplaceBlock($data);
            $article
                ->addBlock($block);

            $this->getManager()->persist($block);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->getManager()->flush(); // Executes all updates.
                $this->getManager()->clear(); // Detaches all objects from Doctrine!
            }

            ++$i;
        }

        $this->getManager()->flush();
        $this->getManager()->clear();

        $output->writeln(sprintf("<info>%s</info>", "Blocks of all articles have been successfully loaded."));
    }

    /**
     * @param array $data
     *
     * @return Block
     */
    protected function createOrReplaceBlock(array $data)
    {
        /** @var Block $block */
        $block = $this->getRepository()->findOneBy(['code' => $data['code']]);

        if (null === $block) {
            $block = $this->getFactory()->createNew();
        }

        $block
            ->setCode($data['code'])
            ->setTitle($data['title'] ?: null)
            ->setBody($data['body'] ?: null);


        return $block;
    }

    /**
     * @return array
     */
    protected function getBlocks()
    {
        $query = <<<EOM
SELECT
  concat('block-', block.text_id) AS code,
  block.text_id                   AS id,
  block.text_titre                AS title,
  block.text                      AS body,
  CASE block.style
  WHEN 5
    THEN 'well'
  END                             AS class,
  CASE block.style
  WHEN 1
    THEN 'left'
  WHEN 2
    THEN 'right'
  WHEN 5
    THEN 'top'
  WHEN 6
    THEN 'top'
  END                             AS image_position,
  block.ordre                     AS position,
  image.img_nom                   AS image,
  imageElements.legende           AS image_label,
  article.id                      AS article_id,
  article.title                   AS article_title
FROM jedisjeux.jdj_article_text AS block
  INNER JOIN jedisjeux.jdj_article oldArticle
    ON oldArticle.article_id = block.article_id
  INNER JOIN jdj_article article
    ON article.code = concat('article-', oldArticle.article_id)
  LEFT JOIN jedisjeux.jdj_images image
    ON image.img_id = block.img_id
  LEFT JOIN jedisjeux.jdj_images_elements imageElements
    ON imageElements.img_id = image.img_id
       AND imageElements.elem_type = 'article'
       AND imageElements.elem_id = block.article_id
ORDER BY block.ordre
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
    }

    /**
     * @return FactoryInterface|object
     */
    protected function getFactory()
    {
        return $this->getContainer()->get('app.factory.block');
    }

    /**
     * @return EntityRepository|object
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('app.repository.block');
    }

    /**
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
