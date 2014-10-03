<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CurrencyBundle\Controller;

use Sylius\Bundle\MoneyBundle\ExchangeRate\Provider\ProviderException;
use Sylius\Bundle\MoneyBundle\ExchangeRate\Updater\UpdaterInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class CurrencyController extends ResourceController
{
    public function changeAction(Request $request, $currency)
    {
        $this->getCurrencyContext()->setCurrency($currency);

        return $this->redirect($request->headers->get('referer'));
    }

    public function updateAllRatesAction(Request $request)
    {
        $flashBag = $request->getSession()->getFlashBag();
        try {
            if ($this->getExchangeRateUpdater()->updateAllRates()) {
                $message = $this->get('translator')->trans('sylius.exchange_rate.update.success', array(), 'flashes');
                $flashBag->add('success', $message);
            } else {
                $message = $this->get('translator')->trans('sylius.exchange_rate.update.error', array(), 'flashes');
                $flashBag->add('error', $message);
            }
        } catch (ProviderException $exception) {
            $message = $this->get('translator')->trans('sylius.exchange_rate.update.provider_exception', array(), 'flashes');
            $flashBag->add('error', $message);
        }

        return $this->redirect($this->generateUrl('sylius_backend_exchange_rate_index'));
    }

    /**
     * @return CurrencyContextInterface
     */
    protected function getCurrencyContext()
    {
        return $this->get('sylius.context.currency');
    }

    /**
     * @return UpdaterInterface
     */
    protected function getExchangeRateUpdater()
    {
        return $this->get('sylius.exchange_rate.updater');
    }
}
