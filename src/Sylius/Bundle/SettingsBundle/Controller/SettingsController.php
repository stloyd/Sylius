<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\SettingsBundle\Controller;

use Sylius\Bundle\ResourceBundle\Event\FlashEvent;
use Sylius\Bundle\SettingsBundle\Form\Factory\SettingsFormFactoryInterface;
use Sylius\Bundle\SettingsBundle\Manager\SettingsManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Settings controller.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class SettingsController extends Controller
{
    /**
     * Edit configuration with given namespace.
     *
     * @param Request $request
     * @param string  $namespace
     *
     * @return Response
     */
    public function updateAction(Request $request, $namespace)
    {
        $manager = $this->getSettingsManager();
        $settings = $manager->loadSettings($namespace);

        $form = $this
            ->getSettingsFormFactory()
            ->create($namespace)
        ;

        $form->setData($settings);

        if ($request->isMethod('POST') && $form->bind($request)->isValid()) {
            $manager->saveSettings($namespace, $form->getData());

            $this->get('event_dispatcher')->dispatch('sylius.settings.flash.update', new FlashEvent('sylius.settings.update'));

            if ($request->headers->has('referer')) {
                return $this->redirect($request->headers->get('referer'));
            }
        }

        return $this->render($request->attributes->get('template'), array(
            'settings' => $settings,
            'form'     => $form->createView()
        ));
    }

    /**
     * Get settings manager.
     *
     * @return SettingsManagerInterface
     */
    protected function getSettingsManager()
    {
        return $this->get('sylius.settings.manager');
    }

    /**
     * Get settings form factory.
     *
     * @return SettingsFormFactoryInterface
     */
    protected function getSettingsFormFactory()
    {
        return $this->get('sylius.settings.form_factory');
    }
}
