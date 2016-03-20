<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Loyalty\Processor;

use Sylius\Component\Originator\Model\OriginAwareInterface;

interface LoyaltyProcessorInterface
{
    /**
     * @param OriginAwareInterface $subject
     */
    public function process(OriginAwareInterface $subject);
}
