<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Product\Builder;

use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Builder\AbstractBuilder;

/**
 * Product builder with fluent interface.
 *
 * Usage example:
 *
 * <code>
 * <?php
 * $product = $this->get('sylius.product_builder')->build(array(
 *     'name'        => 'Github mug',
 *     'description' => "Coffee. Tea. Coke. Water. Let's face it — humans need to drink liquids",
 *     'price'       => 1200,
 *     'attribute'   => array(
 *         'value'     => 'collection',
 *         'attribute' => '2013',
 *     ),
 * ));
 * </code>
 *
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class ProductBuilder extends AbstractBuilder
{
    /**
     * Currently built product.
     *
     * @var ProductInterface
     */
    private $product;

    /**
     * {@inheritdoc}
     */
    public function build(array $data)
    {
        if (!isset($data['name'])) {
            throw new \InvalidArgumentException('Name field must be set!');
        } else {
            $this->create($data['name']);

            unset($data['name']);
        }

        foreach ($data as $field => $value) {
            if ('attribute' === $field && $this->registry->has('attribute_value')) {
                $this->product->addAttribute($this->registry->get('attribute_value')->build($value));

                continue;
            }

            $this->setField($this->product, $field, $value);
        }

        $this->save($this->product);

        return $this->product;
    }

    /**
     * {@inheritdoc}
     */
    private function create($name, $persistAndFlush = false)
    {
        $this->product = $this->repository->createNew();
        $this->product->setName($name);

        if ($persistAndFlush) {
            $this->save($this->product);
        }

        return $this->product;
    }
}
