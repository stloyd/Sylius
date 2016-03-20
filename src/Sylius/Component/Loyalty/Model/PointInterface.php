<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Loyalty\Model;

use Sylius\Component\Originator\Model\OriginAwareInterface;

interface PointInterface extends OriginAwareInterface
{
    /**
     * @return int
     */
    public function getAmount();

    /**
     * @param int $amount
     */
    public function setAmount($amount);

    /**
     * @return AwardInterface
     */
    public function getAward();

    /**
     * @param AwardInterface $award
     */
    public function setAward(AwardInterface $award);
}
