<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Page\Frontend\Topic;

use AppBundle\Behat\Page\SymfonyPage;

class UpdatePage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'app_frontend_topic_update';
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title)
    {
        $this->getElement('title')->setValue($title);
    }

    /**
     * @param string|null $comment
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function setComment(?string $comment)
    {
        $this->getElement('comment')->setValue($comment);
    }

    public function submit()
    {
        $this->getDocument()->pressButton('Save changes');
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'comment' => '#app_topic_mainPost_body',
            'title' => '#app_topic_title',
        ]);
    }
}
