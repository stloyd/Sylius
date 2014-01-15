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

use Sylius\Bundle\SettingsBundle\Model\Settings;

interface StorageInterface
{
    /**
     * Load settings from given namespace.
     *
     * @param string $namespace
     *
     * @return Settings
     */
    public function load($namespace);

    /**
     * Save settings under given namespace.
     *
     * @param string   $namespace
     * @param Settings $settings
     */
    public function save($namespace, Settings $settings);
} 