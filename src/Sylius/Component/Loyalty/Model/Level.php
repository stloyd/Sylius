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

use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Resource\Model\TimestampableTrait;

class Level implements LevelInterface
{
    use TimestampableTrait;

    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var ArrayCollection|AwardInterface[]
     */
    protected $awards;

    public function __construct()
    {
        $this->awards = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getAwards()
    {
        return $this->awards;
    }

    public function addAward(AwardInterface $award)
    {
        if (!$this->awards->contains($award)) {
            $this->awards->add($award);
            $award->setLevel($this);
        }
    }

    public function removeAward(AwardInterface $award)
    {
        if ($this->awards->contains($award)) {
            $this->awards->removeElement($award);
        }
    }
}
