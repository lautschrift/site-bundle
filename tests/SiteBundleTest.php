<?php

/*
 * This file is part of [package name].
 *
 * (c) John Doe
 *
 * @license LGPL-3.0-or-later
 */

namespace Lautschrift\SiteBundle\Tests;

use Lautschrift\SiteBundle\SiteBundle;
use PHPUnit\Framework\TestCase;

class SiteBundleTest extends TestCase
{
    public function testCanBeInstantiated()
    {
        $bundle = new SiteBundle();

        $this->assertInstanceOf('Lautschrift\SiteBundle\SiteBundle', $bundle);
    }
}
