<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Page\Backend;

use AppBundle\Behat\Page\SymfonyPage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DashboardPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'app_backend_dashboard';
    }
}
