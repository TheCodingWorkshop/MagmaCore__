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

use MagmaCore\Test\Calculate;
use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryTestCase;

final class SessionTest extends TestCase
{


    public function setUp(): void
    {
        $this->calculate = new Calculate();
    }

    public function test_areaOfSquare_WhenCalledWithLength2_Return4()
    {
        $length = 2;

        $response = $this->calculate->areaOfSquare($length);
        $this->assertTrue(is_int($response));
        $this->assertEquals(4, $response);
    }

    public function test_areaOfSquare_WhenCalledWithLength6_Return36()
    {
        $length = 6;

        $response = $this->calculate->areaOfSquare($length);

        $this->assertTrue(is_int($response));
        $this->assertEquals(36, $response);
    }

    public function test_areaOfSquare_WhenCalledWithoutLength_ThrowAnException()
    {
        $this->expectException('ArgumentCountError');
        $this->expectExceptionMessage('Too few arguments to function');

        $this->calculate->areaOfSquare();
    }
}
