<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Page\Backend\Redirection;

use AppBundle\Behat\Page\Backend\Crud\CreatePage as BaseCreatePage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CreatePage extends BaseCreatePage
{
    /**
     * @param string $source
     */
    public function specifySource($source)
    {
        $this->getElement('source')->setValue($source);
    }

    /**
     * @param string $destination
     */
    public function specifyDestination($destination)
    {
        $this->getElement('destination')->setValue($destination);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'source' => '#app_redirection_source',
            'destination' => '#app_redirection_destination',
        ]);
    }
}
