<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CartBundle\EventListener;

use Sylius\Bundle\ResourceBundle\EventListener\FlashListener as BaseFlashListener;
use Sylius\Bundle\CartBundle\SyliusCartEvents;

/**
 * Flash message listener.
 *
 * @author Joseph Bielawski <stloyd@gmail.com>
 */
class FlashListener extends BaseFlashListener
{
    /**
     * @var array
     */
    public $messages = array(
        SyliusCartEvents::CART_SAVE_COMPLETED   => 'sylius.cart.cart_save_completed',
        SyliusCartEvents::CART_CLEAR_COMPLETED  => 'sylius.cart.cart_clear_completed',

        SyliusCartEvents::ITEM_ADD_COMPLETED    => 'sylius.cart.item_add_completed',
        SyliusCartEvents::ITEM_REMOVE_COMPLETED => 'sylius.cart.item_remove_completed',

        SyliusCartEvents::ITEM_ADD_ERROR        => 'sylius.cart.item_add_error',
        SyliusCartEvents::ITEM_REMOVE_ERROR     => 'sylius.cart.item_remove_error'
    );

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            SyliusCartEvents::CART_CLEAR_COMPLETED  => 'addMessage',
            SyliusCartEvents::CART_SAVE_COMPLETED   => 'addMessage',

            SyliusCartEvents::ITEM_ADD_COMPLETED    => 'addMessage',
            SyliusCartEvents::ITEM_REMOVE_COMPLETED => 'addMessage',

            SyliusCartEvents::ITEM_ADD_ERROR        => 'addMessage',
            SyliusCartEvents::ITEM_REMOVE_ERROR     => 'addMessage',
        );
    }
}
