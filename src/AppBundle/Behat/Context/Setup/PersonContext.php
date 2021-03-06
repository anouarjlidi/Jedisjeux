<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Setup;

use AppBundle\Behat\Service\SharedStorageInterface;
use AppBundle\Entity\Person;
use AppBundle\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PersonContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var ExampleFactoryInterface
     */
    protected $personFactory;

    /**
     * @var EntityRepository
     */
    protected $personRepository;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param ExampleFactoryInterface $personFactory
     * @param EntityRepository $personRepository
     */
    public function __construct(SharedStorageInterface $sharedStorage, ExampleFactoryInterface $personFactory, EntityRepository $personRepository)
    {
        $this->sharedStorage = $sharedStorage;
        $this->personFactory = $personFactory;
        $this->personRepository = $personRepository;
    }

    /**
     * @Given there is person with first name :firstName and last name :lastName
     *
     * @param string $firstName
     * @param string $lastName
     */
    public function thereIsPersonWithFirstNameAndLastName($firstName, $lastName)
    {
        /** @var Person $person */
        $person = $this->personFactory->create([
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);

        $this->personRepository->add($person);
        $this->sharedStorage->set('person', $person);
    }
}
