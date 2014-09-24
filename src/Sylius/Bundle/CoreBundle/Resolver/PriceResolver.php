<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Resolver;

use Sylius\Component\Cart\Model\CartItemInterface;
use Sylius\Component\Cart\Resolver\ItemResolverInterface;
use Sylius\Component\Pricing\Calculator\DelegatingCalculatorInterface;

class PriceResolver implements ItemResolverInterface
{
    private $priceCalculator;

    public function __construct(DelegatingCalculatorInterface $priceCalculator)
    {
        $this->priceCalculator = $priceCalculator;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(CartItemInterface $item, $data)
    {
        $cart    = $item->getOrder();
        $context = array('quantity' => $item->getQuantity());

        if (null !== $user = $cart->getUser()) {
            $context['groups'] = $user->getGroups()->toArray();
        }

        $item->setUnitPrice($this->priceCalculator->calculate($item->getVariant(), $context));
    }
}
