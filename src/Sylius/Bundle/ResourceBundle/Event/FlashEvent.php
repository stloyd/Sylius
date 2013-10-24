<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ResourceBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Flash message event.
 *
 * @author Joseph Bielawski <stloyd@gmail.com>
 */
class FlashEvent extends Event
{
    const TYPE_ERROR   = 'error';
    const TYPE_WARNING = 'warning';
    const TYPE_INFO    = 'info';
    const TYPE_SUCCESS = 'success';

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @var string
     */
    protected $type;

    /**
     * @param null|string $message
     */
    public function __constructor($message = null)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessageParameters()
    {
        return $this->parameters;
    }

    public function setMessageParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type = self::TYPE_SUCCESS)
    {
        $this->type = $type;
    }
}
