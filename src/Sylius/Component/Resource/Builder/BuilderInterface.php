<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Resource\Builder;

interface BuilderInterface
{
    /**
     * @param array $data
     *
     * @return object
     *
     * @throws \InvalidArgumentException
     */
    public function build(array $data);
}
