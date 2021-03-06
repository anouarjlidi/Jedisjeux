<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Ui\Frontend;

use AppBundle\Behat\Page\Frontend\Post\CreatePage;
use AppBundle\Behat\Page\Frontend\Post\IndexPage;
use AppBundle\Behat\Page\Frontend\Post\UpdatePage;
use AppBundle\Behat\Service\SharedStorage;
use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

class PostContext implements Context
{
    /**
     * @var SharedStorage
     */
    private $sharedStorage;

    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @var CreatePage
     */
    private $createPage;

    /**
     * @var UpdatePage
     */
    private $updatePage;

    /**
     * @param SharedStorage $sharedStorage
     * @param IndexPage $indexPage
     * @param CreatePage $createPage
     * @param UpdatePage $updatePage
     */
    public function __construct(
        SharedStorage $sharedStorage,
        IndexPage $indexPage,
        CreatePage $createPage,
        UpdatePage $updatePage
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
    }

    /**
     * @Given /^I want to reply to (this topic)$/
     */
    public function iWantToReplyToTopic(Topic $topic)
    {
        $this->createPage->open(['topicId' => $topic->getId()]);
    }

    /**
     * @Given /^I want to change (this post)$/
     */
    public function iWantToChangePost(Post $post)
    {
        $this->updatePage->open(['id' => $post->getId()]);
    }

    /**
     * @When /^I want to remove (this post)$/
     */
    public function iWantToRemovePost(Post $post)
    {
        $this->indexPage->open(['topicId' => $post->getTopic()->getId()]);

        $button = $this->indexPage->getRemoveButtonFromPostWithComment($post->getBody());
        Assert::notNull($button, 'Remove button was not found for this post');

        $button->press();
    }

    /**
     * @When I leave a comment :comment
     * @When I do not leave any comment
     */
    public function iLeaveAComment($comment = null)
    {
        $this->createPage->setComment($comment);
    }

    /**
     * @When I change my comment as :comment
     */
    public function iChangeMyCommentAs($comment)
    {
        $this->updatePage->setComment($comment);
    }

    /**
     * @When I add it
     * @When I try to add it
     */
    public function iAddIt()
    {
        $this->createPage->submit();
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->submit();
    }

    /**
     * @Then I should be notified that the :elementName is required
     */
    public function iShouldBeNotifiedThatCommentIsRequired($elementName)
    {
        Assert::true($this->createPage->checkValidationMessageFor($elementName, 'This value should not be blank.'));
    }

    /**
     * @Then this post should not be added
     */
    public function thisPostShouldNotBeAdded()
    {
        /** @var Topic $topic */
        $topic = $this->sharedStorage->get('topic');
        Assert::notNull($topic);

        $this->indexPage->open(['topicId' => $topic->getId()]);

        Assert::same($this->indexPage->countItems(), 0);
    }
}
