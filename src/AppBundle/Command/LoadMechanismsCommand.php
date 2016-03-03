<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 21/12/2015
 * Time: 13:00
 */

namespace AppBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Taxonomy\Model\TaxonomyInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadMechanismsCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:mechanisms:load')
            ->setDescription('Load mechanisms');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $taxonomy = $this->createOrReplaceTaxonomy();
        foreach ($this->getRows() as $data) {
            $taxon = $this->createOrReplaceTaxon($data, $taxonomy);
            $this->getManager()->persist($taxon);
        }

        $this->getManager()->flush();
    }

    protected function createOrReplaceTaxonomy()
    {
        /** @var TaxonomyInterface $taxonomy */
        $taxonomy = $this->getContainer()
            ->get('sylius.repository.taxonomy')
            ->findOneBy(array('name' => 'mechanisms'));

        if (null === $taxonomy) {
            $taxonomy = $this->getContainer()
                ->get('sylius.factory.taxonomy')
                ->createNew();
        }

        $taxonomy->setCode('mechanisms');
        $taxonomy->setName('mechanisms');

        /** @var EntityManager $manager */
        $manager = $this->getContainer()
            ->get('sylius.manager.taxonomy');

        $manager->persist($taxonomy);
        $manager->flush();

        return $taxonomy;
    }

    /**
     * @param array $data
     * @param TaxonomyInterface $taxonomy
     * @return TaxonInterface
     */
    protected function createOrReplaceTaxon(array $data, TaxonomyInterface $taxonomy)
    {
        $locale = $this->getContainer()->getParameter('locale');

        /** @var TaxonInterface $taxon */
        $taxon = $this->getRepository()->findOneBy(array('name' => $data['name']));

        if (null === $taxon) {
            $taxon = $this->getFactory()->createNew();
            $taxon->setCurrentLocale($locale);
            $taxon->setFallbackLocale($locale);
        }

        $taxon->setCode('mechanism-'.$data['id']);
        $taxon->setName($data['name']);
        $taxon->setDescription(isset($data['description']) ? $data['description'] : null);

        //$taxon->setParent($taxonomy->getRoot());
        $taxonomy->addTaxon($taxon);

        return $taxon;

    }

    /**
     * @return string
     */
    public function getYAMLFileName()
    {
        return $this->getContainer()->get('kernel')->getRootDir() . '/Resources/initialData/mechanisms.yml';
    }

   /**
     * Parse YAML File
     *
     * @return mixed
     */
    public function getRows()
    {
        $yaml = new Parser();
        return $yaml->parse(file_get_contents($this->getYAMLFileName()));
    }

    /**
     * @return Factory
     */
    public function getFactory()
    {
        return $this->getContainer()->get('sylius.factory.taxon');
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->getContainer()->get('sylius.repository.taxon');
    }

    /**
     * @return EntityManager
     */
    public function getManager()
    {
        return $this->getContainer()->get('sylius.manager.taxon');
    }
}