<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\SettingsBundle\Manager;

use Doctrine\Common\Cache\Cache;
use Sylius\Bundle\SettingsBundle\Model\Settings;
use Sylius\Bundle\SettingsBundle\Schema\SchemaRegistryInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilder;
use Sylius\Bundle\SettingsBundle\Storage\StorageInterface;

/**
 * Settings manager.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class SettingsManager implements SettingsManagerInterface
{
    /**
     * Schema registry.
     *
     * @var SchemaRegistryInterface
     */
    protected $schemaRegistry;

    /**
     * Parameter storage.
     *
     * @var StorageInterface
     */
    protected $storage;

    /**
     * Cache.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * Runtime cache for resolved parameters
     *
     * @var Settings[]
     */
    protected $resolvedSettings = array();

    /**
     * Validator instance
     *
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * Event dispatcher
     *
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * Constructor.
     *
     * @param SchemaRegistryInterface $schemaRegistry
     * @param StorageInterface        $storage
     * @param Cache                   $cache
     */
    public function __construct(SchemaRegistryInterface $schemaRegistry, StorageInterface $storage, Cache $cache)
    {
        $this->schemaRegistry = $schemaRegistry;
        $this->storage = $storage;
        $this->cache = $cache;
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function loadSettings($namespace)
    {
        if (isset($this->resolvedSettings[$namespace])) {
            return $this->resolvedSettings[$namespace];
        }

        if ($this->cache->contains($namespace)) {
            $parameters = $this->cache->fetch($namespace);
        } else {
            $parameters = $this->getParameters($namespace);
        }

        $settingsBuilder = new SettingsBuilder();

        $schema = $this->schemaRegistry->getSchema($namespace);
        $schema->buildSettings($settingsBuilder);

        $parameters = $this->transformParameters($settingsBuilder, $parameters);
        $parameters = $settingsBuilder->resolve($parameters);

        return $this->resolvedSettings[$namespace] = new Settings($parameters);
    }

    /**
     * {@inheritdoc}
     * @throws ValidatorException
     */
    public function saveSettings($namespace, Settings $settings)
    {
        $schema = $this->schemaRegistry->getSchema($namespace);

        $settingsBuilder = new SettingsBuilder();
        $schema->buildSettings($settingsBuilder);

        $parameters = $settingsBuilder->resolve($settings->getParameters());

        foreach (array_keys($parameters) as $parameter) {
            $transformer = $settingsBuilder->getTransformer($parameter);
            if ($transformer) {
                $parameters[$parameter] = $transformer->transform($parameters[$parameter]);
            }
        }

        if (isset($this->resolvedSettings[$namespace])) {
            $transformedParameters = $this->transformParameters($settingsBuilder, $parameters);
            $this->resolvedSettings[$namespace]->setParameters($transformedParameters);
        }

        $settings->setParameters($parameters);

        $this->storage->save($namespace, $settings);

        $this->cache->save($namespace, $parameters);
    }

    /**
     * Load parameter from database.
     *
     * @param string $namespace
     *
     * @return array
     */
    private function getParameters($namespace)
    {
        $settings = $this->storage->load($namespace);

        return $settings->getParameters();
    }

    private function transformParameters(SettingsBuilder $settingsBuilder, array $parameters)
    {
        $transformedParameters = $parameters;

        foreach ($settingsBuilder->getTransformers() as $parameter => $transformer) {
            if (array_key_exists($parameter, $parameters)) {
                $transformedParameters[$parameter] = $transformer->reverseTransform($parameters[$parameter]);
            }
        }

        return $transformedParameters;
    }
}
