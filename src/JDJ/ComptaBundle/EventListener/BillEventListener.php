<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 04/06/2015
 * Time: 13:54
 */

namespace JDJ\ComptaBundle\EventListener;


use Doctrine\Common\EventSubscriber;
use JDJ\ComptaBundle\Entity\Bill;
use JDJ\ComptaBundle\Entity\Manager\BookEntryManager;
use JDJ\ComptaBundle\Entity\Manager\SubscriptionManager;
use JDJ\ComptaBundle\Event\BillEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class BillEventListener implements EventSubscriberInterface
{
    /**
     * @var BookEntryManager
     */
    private $bookEntryManager;

    /**
     * @var SubscriptionManager
     */
    private $subscriptionManager;


    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            BillEvents::BILL_CREATED => 'createBookEntry',
            BillEvents::BILL_CREATED => 'createSubscriptions',
        );
    }

    /**
     * Constructor
     *
     * @param BookEntryManager $bookEntryManager
     * @param SubscriptionManager $subscriptionManager
     */
    public function __construct(BookEntryManager $bookEntryManager, SubscriptionManager $subscriptionManager)
    {
        $this->bookEntryManager = $bookEntryManager;
        $this->subscriptionManager = $subscriptionManager;
    }

    private function isEventBillObject(GenericEvent $event)
    {
        $bill = $event->getSubject();

        if (!$bill instanceof Bill) {
            throw new UnexpectedTypeException(
                $bill,
                'JDJ\ComptaBundle\Entity\Bill'
            );
        }
    }

    /**
     * @param GenericEvent $event
     */
    public function createBookEntry(GenericEvent $event)
    {
        $this->isEventBillObject($event);
        $bill = $event->getSubject();
        $this->bookEntryManager->createFromBill($bill);
    }

    /**
     * @param GenericEvent $event
     */
    public function removeBookEntry(GenericEvent $event)
    {
        $this->isEventBillObject($event);
        $bill = $event->getSubject();
        $this->bookEntryManager->removeFromBill($bill);
    }

    /**
     * @param GenericEvent $event
     */
    public function createSubscriptions(GenericEvent $event)
    {
        $this->isEventBillObject($event);
        $bill = $event->getSubject();
        $this->subscriptionManager->createFromBill($bill);
    }

    public function updateSubscriptionStatus(GenericEvent $event)
    {
        $this->isEventBillObject($event);
        $bill = $event->getSubject();
        $this->subscriptionManager->updateStatusFromBill($bill);
    }
}