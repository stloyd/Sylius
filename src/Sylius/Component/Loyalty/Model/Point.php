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

class Point implements PointInterface
{
    protected $id;

    /**
     * @var int
     */
    protected $amount = 0;

    /**
     * @var int
     */
    protected $originId;

    /**
     * @var string
     */
    protected $originType;

    /**
     * @var AwardInterface
     */
    protected $award;

    public function getId()
    {
        return $this->id;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getOriginId()
    {
        return $this->originId;
    }

    public function setOriginId($originId)
    {
        $this->originId = $originId;
    }

    public function getOriginType()
    {
        return $this->originType;
    }

    public function setOriginType($originType)
    {
        $this->originType = $originType;
    }

    public function getAward()
    {
        return $this->award;
    }

    public function setAward(AwardInterface $award)
    {
        $this->award = $award;
    }
}
