<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Guard;

use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductGuard
{
    /**
     * @var AuthorizationChecker
     */
    protected $authorizationChecker;

    /**
     * ArticleGuard constructor.
     *
     * @param AuthorizationChecker $authorizationChecker
     */
    public function __construct(AuthorizationChecker $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @return bool
     */
    public function canAskForPublication()
    {
        return $this->authorizationChecker->isGranted('ROLE_REVIEWER');
    }

    /**
     * @return bool
     */
    public function canPublish()
    {
        return $this->authorizationChecker->isGranted('ROLE_PUBLISHER');
    }
}
