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

use Sylius\Component\Resource\Model\ResourceInterface;

interface AwardInterface extends ResourceInterface
{
    /**
     * @return int
     */
    public function getMinAmount();

    /**
     * @param int $minAmount
     */
    public function setMinAmount($minAmount);

    /**
     * @return int
     */
    public function getMaxAmount();

    /**
     * @param int $maxAmount
     */
    public function setMaxAmount($maxAmount);

    /**
     * @return int
     */
    public function getAmount();

    /**
     * @param int $amount
     */
    public function setAmount($amount);

    /**
     * @return int
     */
    public function getReward();

    /**
     * @param int $reward
     */
    public function setReward($reward);

    /**
     * @return LevelInterface
     */
    public function getLevel();

    /**
     * @param LevelInterface $level
     */
    public function setLevel(LevelInterface $level);
}
