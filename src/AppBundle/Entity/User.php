<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\User\Model\User as BaseUser;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table("sylius_user")
 */
class User extends BaseUser
{
    /**
     * @var CustomerInterface
     *
     * @ORM\OneToOne(targetEntity="Sylius\Component\Customer\Model\CustomerInterface")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $customer;
}