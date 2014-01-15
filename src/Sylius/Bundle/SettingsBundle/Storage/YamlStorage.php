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
use Symfony\Component\Yaml\Yaml;

class YamlStorage implements StorageInterface
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * @param string $dir
     */
    public function __construct($dir)
    {
        $this->filename = $dir.'settings.yml';
    }

    /**
     * {@inheritdoc}
     */
    public function load($namespace)
    {
        return new Settings($this->parse($namespace));
    }

    /**
     * {@inheritdoc}
     */
    public function save($namespace, Settings $settings)
    {
        $this->dump($namespace, $settings->getParameters());
    }

    /**
     * @param string $namespace
     *
     * @return array
     */
    protected function parse($namespace)
    {
        $data = Yaml::parse($this->filename);
        if (empty($data) || !isset($data[$namespace])) {
            return array();
        }

        $parameters = array();
        foreach ($data[$namespace] as $section => $value) {
            $parameters['sylius.'.$section] = $value;
        }

        return $parameters;
    }

    /**
     * @param string $namespace
     * @param array  $data
     *
     * @throws \RuntimeException
     */
    protected function dump($namespace, array $data)
    {
        $currentData = Yaml::parse($this->filename);

        $parameters = array();
        foreach ($data as $section => $value) {
            $parameters[str_replace('sylius.', '', $section)] = $value;
        }

        $currentData[$namespace] = $parameters;

        $this->saveFile($this->filename, Yaml::dump($currentData, 2));
    }

    /**
     * @param string $filename
     * @param string $yaml
     *
     * @throws \RuntimeException
     */
    private function saveFile($filename, $yaml)
    {
        if (!stream_is_local($filename)) {
            throw new \RuntimeException(sprintf('Failed to write to %s.', $filename));
        }

        if (!file_exists($filename)) {
            throw new \RuntimeException(sprintf('Requested %s not exists.', $filename));
        }

        if (false === file_put_contents($filename, $yaml)) {
            throw new \RuntimeException(sprintf('Failed to write to %s.', $filename));
        }
    }
}
