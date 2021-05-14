<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MagmaCore\Tests\Unit\Session;

use MagmaCore\Test\Math;
use MagmaCore\Test\Calculate;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;

final class MathTest extends MockeryTestCase
{

    public function setUp(): void
    {
        $this->calculate = m::mock(Calculate::class);
        $this->math = new Math($this->calculate);
    }

    public function test_getArea_WhenCalledWithLength2_Return4()
    {
        $this->calculate->shouldReceive('areaOfSquare')
            ->andReturn(4)
            ->once();

        $length = 2;

        $response = $this->math->getArea($length);

        $this->assertTrue(is_int($response));
        $this->assertEquals(4, $response);
    }

}
