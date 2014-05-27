<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Resource\Builder;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

abstract class AbstractBuilder implements BuilderInterface
{
    /**
     * Object manager.
     *
     * @var ObjectManager
     */
    protected $manager;

    /**
     * Repository.
     *
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * Registry.
     *
     * @var ServiceRegistryInterface
     */
    protected $registry;

    /**
     * @var ClassMetadata
     */
    protected $metadata;

    public function __construct(ObjectManager $manager, RepositoryInterface $repository, ServiceRegistryInterface $registry)
    {
        $this->manager    = $manager;
        $this->repository = $repository;
        $this->registry   = $registry;
        $this->metadata   = $manager->getClassMetadata($repository->getClassName());
    }

    /**
     * @param string           $name
     * @param BuilderInterface $builder
     */
    public function register($name, BuilderInterface $builder)
    {
        $this->registry->register($name, $builder);
    }

    /**
     * @param object $object
     * @param bool   $flush
     *
     * @return object
     */
    protected function save($object, $flush = true)
    {
        $this->manager->persist($object);

        if ($flush) {
            $this->manager->flush($object);
        }

        return $object;
    }

    /**
     * @param object $object
     * @param string $field
     * @param mixed  $value
     *
     * @throws \InvalidArgumentException
     */
    protected function setField($object, $field, $value)
    {
        if (!$this->metadata->hasField($field)) {
            throw new \InvalidArgumentException(sprintf('Unknown field "%s" called on entity "%s"', $field, $this->repository->getClassName()));
        };

        call_user_func(array($object, 'set'.ucfirst($field)), $value);
    }
}
