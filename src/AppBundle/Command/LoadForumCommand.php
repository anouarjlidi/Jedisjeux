<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 19/02/2016
 * Time: 12:38
 */

namespace AppBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadForumCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:forum:load')
            ->setDescription('Loading forum')
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<comment>".$this->getDescription()."</comment>");
        $this->deletePosts();
        $this->deleteTopics();
        $this->loadTopics();
        $this->loadPosts();
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }

    public function deletePosts()
    {
        $query = <<<EOM
delete from jdj_post;
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }

    public function deleteTopics()
    {
        $query = <<<EOM
delete from jdj_topic;
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * {@inheritdoc}
     */
    public function loadTopics()
    {
        $query = <<<EOM
insert into jdj_topic (id, title, createdBy_id, createdAt)
select old.topic_id as id,
       old.topic_title as title,
       old.topic_poster as createdBy_id,
      FROM_UNIXTIME(old.topic_time) as createdAt
from jedisjeux.phpbb3_topics old
  inner join fos_user user
    on user.id = old.topic_poster
where forum_id = 51
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * {@inheritdoc}
     */
    public function loadPosts()
    {
        $query = <<<EOM
        insert into jdj_post(id, topic_id, createdBy_id, body, createdAt)
select old.post_id as id,
       old.topic_id as topic_id,
       old.poster_id as createdBy_id,
       concat ('<p>', replace(old.post_text, '\n\n', '</p><p>'), '</p>') as body,
       FROM_UNIXTIME(old.post_time) as createdAt
from jedisjeux.phpbb3_posts old
  inner join fos_user user
    on user.id = old.poster_id
  inner join jdj_topic topic
    on topic.id = old.topic_id;
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }
}