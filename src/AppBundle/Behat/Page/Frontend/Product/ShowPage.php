<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Page\Frontend\Product;

use AppBundle\Behat\Page\SymfonyPage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ShowPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'sylius_product_show';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getElement('name')->getText();
    }

    /**
     * {@inheritdoc}
     */
    public function getMechanisms()
    {
        $mechanismsParagraph = $this->getElement('mechanisms');

        return $mechanismsParagraph->findAll('css', 'a');
    }

    /**
     * {@inheritdoc}
     */
    public function getThemes()
    {
        $mechanismsParagraph = $this->getElement('themes');

        return $mechanismsParagraph->findAll('css', 'a');
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'name' => 'h2 span',
            'mechanisms' => '#product-mechanisms',
            'themes' => '#product-themes',
        ]);
    }
}
