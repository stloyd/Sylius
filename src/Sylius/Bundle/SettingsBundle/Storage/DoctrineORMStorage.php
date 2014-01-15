<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\SettingsBundle\Storage;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\SettingsBundle\Model\ParameterInterface;
use Sylius\Bundle\SettingsBundle\Model\Settings;

class DoctrineORMStorage implements StorageInterface
{
    /**
     * Object manager.
     *
     * @var ObjectManager
     */
    protected $parameterManager;

    /**
     * @param ObjectManager $parameterManager
     */
    public function __construct(ObjectManager $parameterManager)
    {
        $this->parameterManager = $parameterManager;
    }

    /**
     * {@inheritdoc}
     */
    public function load($namespace)
    {
        return new Settings(array());
    }

    /**
     * {@inheritdoc}
     */
    public function save($namespace, Settings $settings)
    {
        /* @var $persistedParameters ParameterInterface[] */
        $persistedParameters = $this->parameterManager->findBy(array('namespace' => $namespace));

        /* @var $persistedParametersMap ParameterInterface[] */
        $persistedParametersMap = array();

        foreach ($persistedParameters as $parameter) {
            $persistedParametersMap[$parameter->getName()] = $parameter;
        }

        foreach ($settings->getParameters() as $name => $value) {
            if (isset($persistedParametersMap[$name])) {
                $persistedParametersMap[$name]->setValue($value);
            } else {
                /* @var $parameter ParameterInterface */
                $parameter = $this->parameterManager->createNew();
                $parameter->setNamespace($namespace);
                $parameter->setName($name);
                $parameter->setValue($value);

                $this->parameterManager->persist($parameter);
            }
        }

        $this->parameterManager->flush();
    }
} 