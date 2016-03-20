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

class Award implements AwardInterface
{
    protected $id;

    /**
     * @var int
     */
    protected $minAmount;

    /**
     * @var int
     */
    protected $maxAmount;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var int
     */
    protected $reward;

    /**
     * @var LevelInterface
     */
    protected $level;

    public function getId()
    {
        return $this->id;
    }

    public function getMinAmount()
    {
        return $this->minAmount;
    }

    public function setMinAmount($minAmount)
    {
        $this->minAmount = $minAmount;
    }

    public function getMaxAmount()
    {
        return $this->maxAmount;
    }

    public function setMaxAmount($maxAmount)
    {
        $this->maxAmount = $maxAmount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getReward()
    {
        return $this->reward;
    }

    public function setReward($reward)
    {
        $this->reward = $reward;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setLevel(LevelInterface $level)
    {
        $this->level = $level;
    }
}
