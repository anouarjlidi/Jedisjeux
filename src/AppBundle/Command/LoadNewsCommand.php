<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 06/04/2016
 * Time: 09:04
 */

namespace AppBundle\Command;

use AppBundle\Document\ArticleContent;
use AppBundle\Document\SingleImageBlock;
use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ODM\PHPCR\DocumentRepository;
use PHPCR\Util\NodeHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadNewsCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:news:load')
            ->setDescription('Load news');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getNews() as $data) {
            $page = $this->createOrReplaceArticle($data);
            $blocks = [
                [
                    'id' => $data['id'],
                    'body' => $data['body'],
                    'image_position' => null,
                    'title' => null,
                    'class' => null,
                ]
            ];
            $this->populateBlocks($page, $blocks);
            $this->getManager()->persist($page);
            $this->getManager()->flush();
            $this->getManager()->clear();

        }
    }

    protected function getNews()
    {
        $query = <<<EOM
select      old.news_id as id,
            old.titre as title,
            replace(old.titre_clean, ' ', '-') as name,
            old.date as createdAt,
            old.text as body,
            old.photo as mainImage
from        jedisjeux.jdj_news old
WHERE       old.valid = 1
  AND       old.type_lien in (0, 1)
order by    old.date desc
limit       10
EOM;

        return $this->getDatabaseConnection()->fetchAll($query);
    }

    protected function populateBlocks(ArticleContent $page, array $blocks)
    {
        foreach ($blocks as $data) {
            $block = $this->createOrReplaceBlock($page, $data);
            $page->addChild($block);
            if (isset($data['image'])) {
                $this->createOrReplaceImagineBlock($block, $data);
            }
        }
    }

    /**
     * @param array $data
     * @return ArticleContent
     */
    protected function createOrReplaceArticle(array $data)
    {
        $article = $this->findPage($data['name']);

        if (null === $article) {
            $article = new ArticleContent();
            $article
                ->setParentDocument($this->getParent());

        }

        if (null !== $data['mainImage']) {
            $mainImage = $article->getMainImage();

            if (null === $mainImage) {
                $mainImage = new ImagineBlock();
            }


            $image = new Image();
            $image->setFileContent(file_get_contents($this->getImageOriginalPath($data['mainImage'])));

            $mainImage
                ->setParentDocument($article)
                ->setImage($image);

            // $this->getManager()->persist($mainImage);

            $article
                ->setMainImage($mainImage);
        }

        $article->setName($data['name']);
        $article->setTitle($data['title']);
        $article->setPublishable(true);

        return $article;
    }

    /**
     * @param ArticleContent $page
     * @param array $data
     * @return SingleImageBlock
     */
    protected function createOrReplaceBlock(ArticleContent $page, array $data)
    {
        $name = 'block'.$data['id'];

        $block = $this
            ->getSingleImageBlockRepository()
            ->findOneBy(array('name' => $name));

        if (null === $block) {
            $block = new SingleImageBlock();
            $block
                ->setParentDocument($page);
        }

        $block
            ->setImagePosition($data['image_position'])
            ->setTitle($data['title'])
            ->setBody($this->nl2p($data['body']))
            ->setName($name)
            ->setClass($data['class'] ?: null)
            ->setPublishable(true);

        return $block;
    }

    protected function createOrReplaceImagineBlock(SingleImageBlock $block, array $data)
    {
        $name = 'image'.$data['id'];

        if (false === $block->hasChildren()) {
            $imagineBlock = new ImagineBlock();
            $block
                ->addChild($imagineBlock);
        } else {
            /** @var ImagineBlock $imagineBlock */
            $imagineBlock = $block->getChildren()->first();
        }

        $image = new Image();
        $image->setFileContent(file_get_contents($this->getImageOriginalPath($data['image'])));
        $image->setName($data['image']);

        $imagineBlock
            ->setName($name)
            ->setParentDocument($block)
            ->setImage($image)
            ->setLabel($data['image_label']);

        $this->getManager()->persist($imagineBlock);

        return $imagineBlock;
    }

    /**
     * @param string $name
     * @return ArticleContent
     */
    protected function findPage($name)
    {
        $page = $this
            ->getRepository()
            ->findOneBy(array('name' => $name));

        return $page;
    }

    /**
     * @return Generic
     */
    protected function getParent()
    {
        $contentBasepath = '/cms/pages/articles';
        $parent = $this->getManager()->find(null, $contentBasepath);

        if (null === $parent) {
            $session = $this->getManager()->getPhpcrSession();
            NodeHelper::createPath($session, $contentBasepath);
            $parent = $this->getManager()->find(null, $contentBasepath);
        }

        return $parent;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getImageOriginalPath($path)
    {
        return "http://www.jedisjeux.net/img/800/".$path;
    }

    protected function nl2p($string)
    {
        return "<p>".str_replace("\n", "</p><p>", trim($string))."</p>";
    }

    /**
     * @return DocumentRepository
     */
    public function getRepository()
    {
        return $this->getContainer()->get('app.repository.article_content');
    }

    /**
     * @return DocumentRepository
     */
    public function getSingleImageBlockRepository()
    {
        return $this->getContainer()->get('app.repository.single_image_block');
    }

    /**
     * @return DocumentManager
     */
    public function getManager()
    {
        return $this->getContainer()->get('app.manager.article_content');
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}
