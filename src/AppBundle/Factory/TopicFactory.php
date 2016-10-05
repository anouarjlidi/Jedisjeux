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

use AppBundle\Entity\GamePlay;
use AppBundle\Entity\Topic;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\User\Context\CustomerContextInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicFactory extends Factory
{
    /**
     * @var CustomerContextInterface
     */
    protected $customerContext;

    /**
     * @var EntityRepository
     */
    protected $gamePlayRepository;

    /**
     * @param CustomerContextInterface $customerContext
     */
    public function setCustomerContext(CustomerContextInterface $customerContext)
    {
        $this->customerContext = $customerContext;
    }

    /**
     * @param EntityRepository $gamePlayRepository
     */
    public function setGamePlayRepository($gamePlayRepository)
    {
        $this->gamePlayRepository = $gamePlayRepository;
    }

    /**
     * @return Topic
     */
    public function createNew()
    {
        /** @var Topic $topic */
        $topic = parent::createNew();
        $topic->setAuthor($this->customerContext->getCustomer());

        return $topic;
    }

    /**
     * @param int $gamePlayId
     *
     * @return Topic
     */
    public function createForGamePlay($gamePlayId)
    {
        /** @var GamePlay $gamePlay */
        $gamePlay = $this->gamePlayRepository->find($gamePlayId);

        if (null === $gamePlay) {
            throw new \InvalidArgumentException(sprintf('Requested gameplay does not exist with id "%s".', $gamePlayId));
        }

        /** @var Topic $topic */
        $topic = $this->createNew();

        $gamePlay
            ->setTopic($topic);

        $topic
            ->setTitle("Partie de ".(string)$gamePlay->getProduct())
            ->setAuthor($gamePlay->getAuthor());

        return $topic;
    }
}
