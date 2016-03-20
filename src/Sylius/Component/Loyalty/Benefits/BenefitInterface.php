<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Loyalty\Benefits;

use Sylius\Component\Originator\Model\OriginAwareInterface;

interface BenefitInterface
{
    /**
     * @param OriginAwareInterface $subject
     */
    public function apply(OriginAwareInterface $subject);
}
