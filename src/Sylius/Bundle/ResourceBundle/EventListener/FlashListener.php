<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ResourceBundle\EventListener;

use Sylius\Bundle\ResourceBundle\Event\FlashEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Flash message listener.
 *
 * @author Joseph Bielawski <stloyd@gmail.com>
 */
class FlashListener implements EventSubscriberInterface
{
    /**
     * @var array
     */
    public $messages = array();

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param SessionInterface    $session
     * @param TranslatorInterface $translator
     */
    public function __construct(SessionInterface $session, TranslatorInterface $translator)
    {
        $this->session    = $session;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array();
    }

    /**
     * @param FlashEvent $event
     */
    public function addErrorFlash(FlashEvent $event)
    {
        $this->addFlashMessage('error', $event);
    }

    /**
     * @param FlashEvent $event
     */
    public function addSuccessFlash(FlashEvent $event)
    {
        $this->addFlashMessage('success', $event);
    }

    /**
     * @param string     $type
     * @param FlashEvent $event
     */
    protected function addFlashMessage($type, FlashEvent $event)
    {
        $event->stopPropagation();

        $this->session->getBag('flashes')->add($type, $this->translator->trans($event->getSubject() ?: $this->messages[$event->getName()], $event->getArguments(), $event->getDomain()));
    }
}
