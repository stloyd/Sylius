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
use Sylius\Component\Cart\Resolver\ItemResolvingException;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Registry\ServiceRegistry;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class ChainableItemResolver extends ServiceRegistry implements ItemResolverInterface
{
    /**
     * Product repository.
     *
     * @var RepositoryInterface
     */
    protected $productRepository;

    /**
     * Form factory.
     *
     * @var FormFactoryInterface
     */
    protected $formFactory;

    public function __construct(RepositoryInterface $productRepository, FormFactoryInterface $formFactory)
    {
        parent::__construct('Sylius\Component\Cart\Resolver\ItemResolverInterface');

        $this->productRepository = $productRepository;
        $this->formFactory       = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(CartItemInterface $item, $data)
    {
        /** @var $product ProductInterface */
        if (!$product = $this->productRepository->find($this->resolveItemIdentifier($data))) {
            throw new ItemResolvingException('Requested product was not found.');
        }

        $this->resolveProductVariant($item, $product, $data);

        /** @var $resolver ItemResolverInterface */
        foreach ($this->services as $resolver) {
            $resolver->resolve($item, $product);
        }

        return $item;
    }

    /**
     * Here we resolve the item identifier that is going to be added into the cart.
     *
     * @param mixed $request
     *
     * @return mixed
     *
     * @throws ItemResolvingException
     */
    protected function resolveItemIdentifier($request)
    {
        if (!$request instanceof Request) {
            throw new ItemResolvingException('Invalid request data.');
        }

        if (!$request->isMethod('POST') && !$request->isMethod('PUT')) {
            throw new ItemResolvingException('Invalid request method.');
        }

        /*
         * We're getting here product id via query but you can easily override route
         * pattern and use attributes, which are available through request object.
         */
        if (!$id = $request->get('id')) {
            throw new ItemResolvingException('Error while trying to add item to cart.');
        }

        return $id;
    }

    /**
     * @param CartItemInterface $item
     * @param ProductInterface  $product
     * @param mixed             $data
     *
     * @throws ItemResolvingException
     */
    protected function resolveProductVariant(CartItemInterface $item, ProductInterface $product, $data)
    {
        // We use forms to easily set the quantity and pick variant but you can do here whatever is required to create the item.
        $form = $this->formFactory->create('sylius_cart_item', $item, array('product' => $product));
        $form->submit($data);

        // If our product has no variants, we simply set the master variant of it.
        if (!$product->hasVariants()) {
            $item->setVariant($product->getMasterVariant());
        }

        // If all is ok with form, quantity and other stuff, simply return the item.
        if (!$form->isValid() || null === $item->getVariant()) {
            throw new ItemResolvingException('Submitted form is invalid.');
        }
    }
}
