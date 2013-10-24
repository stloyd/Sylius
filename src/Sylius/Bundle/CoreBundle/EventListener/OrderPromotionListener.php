<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\EventListener;

use Sylius\Bundle\CoreBundle\Model\OrderInterface;
use Sylius\Bundle\PromotionsBundle\Processor\PromotionProcessorInterface;
use Sylius\Bundle\PromotionsBundle\SyliusPromotionEvents;
use Sylius\Bundle\ResourceBundle\Event\FlashEvent;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Order promotion listener.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class OrderPromotionListener
{
    /**
     * Order promotion processor.
     *
     * @var PromotionProcessorInterface
     */
    protected $promotionProcessor;

    /**
     * Constructor.
     *
     * @param PromotionProcessorInterface $promotionProcessor
     */
    public function __construct(PromotionProcessorInterface $promotionProcessor)
    {
        $this->promotionProcessor = $promotionProcessor;
    }

    /**
     * Get the order from event and run the promotion processor on it.
     *
     * @param GenericEvent $event
     *
     * @throws \InvalidArgumentException
     */
    public function processOrderPromotion(GenericEvent $event)
    {
        $order = $event->getSubject();

        if (!$order instanceof OrderInterface) {
            throw new \InvalidArgumentException(
                'Order promotion listener requires event subject to be instance of "Sylius\Bundle\CoreBundle\Model\OrderInterface"'
            );
        }

        // remove former promotion adjustments as they are calculated each time the cart changes
        $order->removePromotionAdjustments();
        $this->promotionProcessor->process($order);
    }

    /**
     * Handle coupons added by the user in his cart.
     *
     * @param GenericEvent $event
     */
    public function handleCouponPromotion(GenericEvent $event)
    {
        if (SyliusPromotionEvents::COUPON_ELIGIBLE === $event->getName()) {
            $name    = 'sylius.promotion_coupon.flash.eligible';
            $message = 'sylius.promotion_coupon.eligible';
        } elseif (SyliusPromotionEvents::COUPON_NOT_ELIGIBLE === $event->getName()) {
            $name    = 'sylius.promotion_coupon.flash.not_eligible';
            $message = 'sylius.promotion_coupon.not_eligible';
        } else {
            $name    = 'sylius.promotion_coupon.flash.invalid';
            $message = 'sylius.promotion_coupon.invalid';
        }

        $event->getDispatcher()->dispatch($name, new FlashEvent($message));
    }
}
