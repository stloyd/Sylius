<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\CartBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\CartBundle\SyliusCartEvents;

/**
 * @author Joseph Bielawski <stloyd@gmail.com>
 */
class FlashListenerSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\HttpFoundation\Session\Session  $session
     * @param Symfony\Component\Translation\TranslatorInterface $translator
     */
    function let($session, $translator)
    {
        $this->beConstructedWith($session, $translator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\CartBundle\EventListener\FlashListener');
    }

    /**
     * @param Sylius\Bundle\ResourceBundle\Event\FlashEvent $event
     * @param Symfony\Component\HttpFoundation\Session\Flash\FlashBag $flashBag
     */
    function it_should_add_a_custom_error_flash_message_from_event($session, $translator, $event, $flashBag)
    {
        $message = 'This is an error message';
        $type    = 'error';

        $event
            ->getMessage()
            ->shouldBeCalled()
            ->willReturn($message)
        ;

        $event
            ->getType()
            ->shouldBeCalled()
            ->willReturn($type)
        ;

        $event
            ->getMessageParameters()
            ->shouldBeCalled()
            ->willReturn(array())
        ;

        $session
            ->getFlashBag()
            ->shouldBeCalled()
            ->willReturn($flashBag)
        ;

        $translator
            ->trans(Argument::cetera())
            ->shouldBeCalled()
            ->willReturn($message)
        ;

        $flashBag
            ->add($type, $message)
            ->shouldBeCalled()
        ;

        $this->addMessage($event);
    }

    /**
     * @param Sylius\Bundle\ResourceBundle\Event\FlashEvent $event
     * @param Symfony\Component\HttpFoundation\Session\Flash\FlashBag $flashBag
     */
    function it_should_add_a_custom_success_flash_message_from_event($session, $translator, $event, $flashBag)
    {
        $message = 'This is an success message';
        $type    = 'success';

        $event
            ->getType()
            ->shouldBeCalled()
            ->willReturn($type)
        ;

        $event
            ->getMessage()
            ->shouldBeCalled()
            ->willReturn($message)
        ;

        $event
            ->getMessageParameters()
            ->shouldBeCalled()
            ->willReturn(array())
        ;

        $session
            ->getFlashBag()
            ->shouldBeCalled()
            ->willReturn($flashBag)
        ;

        $translator
            ->trans(Argument::cetera())
            ->shouldBeCalled()
            ->willReturn($message)
        ;

        $flashBag
            ->add($type, $message)
            ->shouldBeCalled()
        ;

        $this->addMessage($event);
    }

    /**
     * @param Sylius\Bundle\ResourceBundle\Event\FlashEvent $event
     * @param Symfony\Component\HttpFoundation\Session\Flash\FlashBag $flashBag
     */
    function it_should_have_a_default_error_flash_message_for_event_name($session, $translator, $event, $flashBag)
    {
        $type     = 'error';
        $messages = array(SyliusCartEvents::ITEM_ADD_ERROR => 'Error occurred while adding item to cart.');
        $this->messages = $messages;

        $event
            ->getType()
            ->shouldBeCalled()
            ->willReturn($type)
        ;

        $event
            ->getMessage()
            ->shouldBeCalled()
            ->willReturn(null)
        ;

        $event
            ->getName()
            ->shouldBeCalled()
            ->willReturn(SyliusCartEvents::ITEM_ADD_ERROR)
        ;

        $event
            ->getMessageParameters()
            ->shouldBeCalled()
            ->willReturn(array())
        ;

        $session
            ->getFlashBag()
            ->shouldBeCalled()
            ->willReturn($flashBag)
        ;

        $translator
            ->trans(Argument::cetera())
            ->shouldBeCalled()
            ->willReturn($messages[SyliusCartEvents::ITEM_ADD_ERROR])
        ;

        $flashBag
            ->add($type, $messages[SyliusCartEvents::ITEM_ADD_ERROR])
            ->shouldBeCalled()
        ;

        $this->addMessage($event);
    }

    /**
     * @param Sylius\Bundle\ResourceBundle\Event\FlashEvent $event
     * @param Symfony\Component\HttpFoundation\Session\Flash\FlashBag $flashBag
     */
    function it_should_have_a_default_success_flash_message_for_event_name($session, $translator, $event, $flashBag)
    {
        $type     = 'success';
        $messages = array(SyliusCartEvents::ITEM_ADD_COMPLETED => 'The cart has been successfully updated.');
        $this->messages = $messages;

        $event
            ->getType()
            ->shouldBeCalled()
            ->willReturn($type)
        ;

        $event
            ->getMessage()
            ->shouldBeCalled()
            ->willReturn(null)
        ;

        $event
            ->getName()
            ->shouldBeCalled()
            ->willReturn(SyliusCartEvents::ITEM_ADD_COMPLETED)
        ;

        $event
            ->getMessageParameters()
            ->shouldBeCalled()
            ->willReturn(array())
        ;

        $session
            ->getFlashBag()
            ->shouldBeCalled()
            ->willReturn($flashBag)
        ;

        $translator
            ->trans(Argument::cetera())
            ->shouldBeCalled()
            ->willReturn($messages[SyliusCartEvents::ITEM_ADD_COMPLETED])
        ;

        $flashBag
            ->add($type, $messages[SyliusCartEvents::ITEM_ADD_COMPLETED])
            ->shouldBeCalled()
        ;

        $this->addMessage($event);
    }
}
