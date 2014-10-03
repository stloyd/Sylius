<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\MoneyBundle\ExchangeRate\Updater;

use Sylius\Bundle\MoneyBundle\ExchangeRate\Provider\ProviderFactory;
use Sylius\Bundle\MoneyBundle\ExchangeRate\Provider\ProviderInterface;
use Sylius\Component\Currency\Converter\CurrencyConverterInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * Updates exchange rates using external exchange rate providers.
 *
 * @author Ivan Đurđevac <djurdjevac@gmail.com>
 */
class DatabaseUpdater implements UpdaterInterface
{
    /**
     * Currency repository
     *
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var ProviderInterface
     */
    private $provider;

    /**
     * @var CurrencyConverterInterface
     */
    private $converter;

    /**
     * Create Updater with provider.
     *
     * @param ProviderFactory            $providerFactory
     * @param CurrencyConverterInterface $converter
     * @param RepositoryInterface        $repository
     */
    public function __construct(ProviderFactory $providerFactory, CurrencyConverterInterface $converter, RepositoryInterface $repository)
    {
        $this->provider   = $providerFactory->createProvider();
        $this->converter  = $converter;
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function updateRate($code)
    {
        /** @var $currency CurrencyInterface */
        $currency = $this->repository->findOneBy(array('code' => $code));

        $this->fetchRate($currency);
    }

    /**
     * {@inheritdoc}
     */
    public function updateAllRates()
    {
        foreach ($this->repository->findAll() as $exchangeRate) {
            $this->fetchRate($exchangeRate);
        }
    }

    /**
     * Fetch rate from external services.
     *
     * @param CurrencyInterface $currency
     */
    private function fetchRate(CurrencyInterface $currency)
    {
        /** @var $baseCurrency CurrencyInterface */
        $baseCurrency = $this->repository->findOneBy(array('base' => true));
        if ($baseCurrency->getCode() !== $currency->getCode()) {
            $currency->setExchangeRate($this->provider->getRate($baseCurrency, $currency->getCode()));
        }
    }
}
