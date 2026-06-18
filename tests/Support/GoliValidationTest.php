<?php

namespace Efati\ModuleGenerator\Tests\Support;

use Efati\ModuleGenerator\Support\Goli;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class GoliValidationTest extends TestCase
{
    public function testRejectsInvalidJalaliMonthLength(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Goli::goliToGregorian(1403, 7, 31);
    }

    public function testRejectsEsfandThirtyInNonLeapYear(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Goli::goliToGregorian(1402, 12, 30);
    }

    public function testRejectsInvalidGregorianDate(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Goli::gregorianToGoli(2024, 2, 31);
    }

    public function testRejectsInvalidTime(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Goli::create(1403, 1, 1, 24, 0, 0);
    }
}
