<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Component\Core\Promotion\Action;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Cart\Resolver\ItemResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Promotion\Model\PromotionInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Alexandre Bacco <alexandre.bacco@gmail.com>
 */
class AddProductActionSpec extends ObjectBehavior
{
    function let(
        RepositoryInterface $itemRepository,
        RepositoryInterface $variantRepository,
        ItemResolverInterface $restrictedZoneResolver,
        ItemResolverInterface $availabilityResolver
    ) {
        $this->beConstructedWith($itemRepository, $variantRepository, $restrictedZoneResolver, $availabilityResolver);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Component\Core\Promotion\Action\AddProductAction');
    }

    function it_implements_Sylius_promotion_action_interface()
    {
        $this->shouldImplement('Sylius\Component\Promotion\Action\PromotionActionInterface');
    }

    function it_adds_product_as_promotion(
        $itemRepository,
        $variantRepository,
        $restrictedZoneResolver,
        $availabilityResolver,
        OrderInterface $order,
        OrderItemInterface $item,
        ProductInterface $product,
        ProductVariantInterface $variant,
        PromotionInterface $promotion
    ) {
        $configuration = array('variant' => 500, 'quantity' => 2, 'price' => 0);

        $variantRepository->find($configuration['variant'])->willReturn($variant);

        $itemRepository->createNew()->willReturn($item);
        $item->setUnitPrice($configuration['price'])->willReturn($item);
        $item->setVariant($variant)->willReturn($item);
        $item->setQuantity($configuration['quantity'])->willReturn($item);
        $item->getProduct()->willReturn($product);

        $order->getItems()->willReturn(array());

        $restrictedZoneResolver->resolve($item, $product)->shouldBeCalled();
        $availabilityResolver->resolve($item, array())->shouldBeCalled();

        $order->addItem($item)->shouldBeCalled();

        $this->execute($order, $configuration, $promotion);
    }

    function it_does_not_add_product_if_exists(
        RepositoryInterface $variantRepository,
        RepositoryInterface $itemRepository,
        OrderInterface $order,
        OrderItemInterface $item,
        ProductVariantInterface $variant,
        PromotionInterface $promotion
    ) {
        $configuration = array('variant' => 500, 'quantity' => 2, 'price' => 1);

        $variantRepository->find($configuration['variant'])->willReturn($variant);

        $itemRepository->createNew()->willReturn($item);
        $item->setUnitPrice($configuration['price'])->willReturn($item);
        $item->setVariant($variant)->willReturn($item);
        $item->setQuantity($configuration['quantity'])->willReturn($item);
        $item->equals($item)->willReturn(true);

        $order->getItems()->willReturn(array($item));

        $order->addItem($item)->shouldNotBeCalled();

        $this->execute($order, $configuration, $promotion);
    }

    function it_reverts_product(
        RepositoryInterface $variantRepository,
        RepositoryInterface $itemRepository,
        OrderInterface $order,
        OrderItemInterface $item,
        ProductVariantInterface $variant,
        PromotionInterface $promotion
    ) {
        $configuration = array('variant' => 500, 'quantity' => 3, 'price' => 2);

        $variantRepository->find($configuration['variant'])->willReturn($variant);

        $itemRepository->createNew()->willReturn($item);
        $item->setUnitPrice($configuration['price'])->willReturn($item);
        $item->setVariant($variant)->willReturn($item);
        $item->setQuantity($configuration['quantity'])->willReturn($item);
        $item->equals($item)->willReturn(true);

        $order->getItems()->willReturn(array($item));

        $order->removeItem($item)->shouldBeCalled();

        $this->revert($order, $configuration, $promotion);
    }
}
