<?php

namespace Efati\ModuleGenerator\Tests\Support;

use Efati\ModuleGenerator\Support\Verta;
use PHPUnit\Framework\TestCase;

class VertaCompatibilityTest extends TestCase
{
    public function testFactoriesReturnVertaInstances(): void
    {
        $this->assertInstanceOf(Verta::class, Verta::now());
        $this->assertInstanceOf(Verta::class, Verta::parse('1403-01-01'));
        $this->assertInstanceOf(Verta::class, Verta::create(1403, 1, 1));
    }

    public function testLegacyAliasesDelegateToGoliApi(): void
    {
        $verta = Verta::create(1403, 1, 1, 12, 30, 45);

        $this->assertSame('1403-01-01', $verta->toJalaliDateString());
        $this->assertSame('1403-01-01 12:30:45', $verta->toJalaliDateTimeString());
        $this->assertSame(
            Verta::goliToGregorian(1403, 1, 1),
            Verta::jalaliToGregorian(1403, 1, 1)
        );
        $this->assertSame(
            Verta::gregorianToGoli(2024, 3, 20),
            Verta::gregorianToJalali(2024, 3, 20)
        );
    }
}
