<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Component\Loyalty\Model;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Loyalty\Model\LevelInterface;

class LevelSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Component\Loyalty\Model\Level');
    }

    function it_should_be_sylius_loyalty_level()
    {
        $this->shouldImplement(LevelInterface::class);
    }

    function it_should_not_have_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    function it_should_not_name_type_by_default()
    {
        $this->getName()->shouldReturn(null);
    }

    function its_name_should_be_mutable()
    {
        $this->setName('test_level');
        $this->getName()->shouldReturn('test_level');
    }

    function it_should_not_description_type_by_default()
    {
        $this->getDescription()->shouldReturn(null);
    }

    function its_description_should_be_mutable()
    {
        $this->setDescription('test_level_desc');
        $this->getDescription()->shouldReturn('test_level_desc');
    }
}
