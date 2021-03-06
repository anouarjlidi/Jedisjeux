<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 24/03/16
 * Time: 08:37
 */

namespace AppBundle\Command\Installer\Data;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadImagesOfGamePlaysCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:images-of-game-plays:load')
            ->setDescription('Loading images of game plays');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));
        $this->deleteGamePlaysImages();
        $this->insertGamePlaysImages();
    }

    protected function deleteGamePlaysImages()
    {
        $query = <<<EOM
DELETE image
FROM jdj_game_play gamePlay
  INNER JOIN jedisjeux.jdj_parties old
    ON concat('game-play-', old.partie_id) = gamePlay.code
  INNER JOIN jdj_game_play_image image
    ON image.game_play_id = gamePlay.id;
EOM;
        $this->getDatabaseConnection()->executeQuery($query);
    }

    protected function insertGamePlaysImages()
    {
        $query = <<<EOM
insert into jdj_game_play_image(id, game_play_id, path, description)
select    old.img_id, gamePlay.id as game_play_id, img_nom as path, ie.legende as description
from        jedisjeux.jdj_images old
  inner join  jedisjeux.jdj_images_elements ie
    on ie.img_id = old.img_id
       and ie.elem_type = 'partie'
  inner join  jdj_game_play gamePlay
    on gamePlay.code = concat('game-play-', ie.elem_id);
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}