<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Attribute\Builder;

use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Resource\Builder\AbstractBuilder;

class AttributeValueBuilder extends AbstractBuilder
{
    /**
     * @var AttributeValueInterface
     */
    private $attributeValue;

    /**
     * {@inheritdoc}
     */
    public function build(array $data)
    {
        if (!isset($data['value'])) {
            throw new \InvalidArgumentException('Value field must be set!');
        } else {
            $this->create($data['value']);

            unset($data['value']);
        }

        foreach ($data as $field => $value) {
            if ('attribute' === $field && $this->registry->has('attribute')) {
                $this->attributeValue->setAttribute($this->registry->get('attribute')->build(is_array($value) ? $value : array($value)));

                continue;
            }

            $this->setField($this->attributeValue, $field, $value);
        }

        $this->save($this->attributeValue);

        return $this->attributeValue;
    }

    /**
     * {@inheritdoc}
     */
    private function create($value, $persistAndFlush = false)
    {
        $this->attributeValue = $this->repository->createNew();
        $this->attributeValue->setValue($value);

        if ($persistAndFlush) {
            $this->save($this->attributeValue);
        }

        return $this->attributeValue;
    }
}
