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

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Resource\Builder\AbstractBuilder;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class AttributeBuilder extends AbstractBuilder
{
    /**
     * @var AttributeInterface
     */
    private $attribute;

    public function __construct(ObjectManager $manager, RepositoryInterface $repository)
    {
        $this->manager    = $manager;
        $this->repository = $repository;
        $this->metadata   = $manager->getClassMetadata($repository->getClassName());
    }

    /**
     * {@inheritdoc}
     */
    public function build(array $data)
    {
        if (!isset($data['name'])) {
            throw new \InvalidArgumentException('Name field must be set!');
        } else {
            $this->attribute = $this->create($data['name']);

            unset($data['name']);
        }

        foreach ($data as $field => $value) {
            $this->setField($this->attribute, $field, $value);
        }

        $this->save($this->attribute);

        return $this->attribute;
    }

    /**
     * {@inheritdoc}
     */
    private function create($name, $persistAndFlush = false)
    {
        $this->attribute = $this->repository->createNew();
        $this->attribute->setName($name);

        if ($persistAndFlush) {
            $this->save($this->attribute);
        }

        return $this->attribute;
    }
}
