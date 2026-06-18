<?php

namespace Efati\ModuleGenerator\Tests\Support;

use Efati\ModuleGenerator\Support\SwaggerConfigManager;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SwaggerConfigManagerTest extends TestCase
{
    public function testPaletteUsesProvidedBaseColor(): void
    {
        $palette = SwaggerConfigManager::generateColorPalette('#8b5cf6');

        $this->assertSame('#8b5cf6', $palette[500]);
        $this->assertNotSame($palette[50], $palette[900]);
    }

    public function testPaletteRejectsInvalidHexColor(): void
    {
        $this->expectException(InvalidArgumentException::class);

        SwaggerConfigManager::generateColorPalette('not-a-color');
    }
}
