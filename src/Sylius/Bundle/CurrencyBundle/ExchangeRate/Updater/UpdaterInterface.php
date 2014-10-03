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

/**
 * UpdaterInterface responsibility is to update exchange rate value.
 *
 * @author Ivan Djurdjevac <djurdjevac@gmail.com>
 */
interface UpdaterInterface
{
    /**
     * Update currency rate.
     *
     * @param string $code
     */
    public function updateRate($code);

    /**
     * Update all currencies in system.
     */
    public function updateAllRates();
}
