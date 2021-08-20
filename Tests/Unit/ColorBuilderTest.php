<?php

namespace PackageFactory\ColorHelper\Tests\Unit;

use PackageFactory\ColorHelper\Domain\ValueObject\ColorInterface;
use PackageFactory\ColorHelper\Domain\ValueObject\HslaColor;
use PackageFactory\ColorHelper\Domain\ValueObject\RgbaColor;
use PackageFactory\ColorHelper\Eel\ColorBuilder;

class ColorBuilderTest extends AbstractColorTest
{
    private $builder;

    public function setUp(): void
    {
        $this->builder = new ColorBuilder();
    }

    public function colorsCanBeCreatedViaRgbFactoryMethodDataProvider(): array
    {
        return [
            [100, 0, 255, null, new RgbaColor(100, 0, 255)],
            [0, 0, 0, null, new RgbaColor(0, 0, 0)],
            [100, 0, 255, 50, new RgbaColor(100, 0, 255, 50)],
            [100, 0, 255, 100, new RgbaColor(100, 0, 255, 100)],
        ];
    }

    /**
     * @test
     * @dataProvider colorsCanBeCreatedViaRgbFactoryMethodDataProvider
     */
    public function colorsCanBeCreatedViaRgbFactoryMethod(float $red, float $green, float $blue, ?float $alpha = null, ColorInterface $expectation)
    {
        $color = $this->builder->rgba($red, $green, $blue, $alpha ?? 100)->getColor();
        $this->assertSameColor($expectation, $color);
    }

    public function colorsCanBeCreatedViaHslFactoryMethodDataProvider(): array
    {
        return [
            [100, 25, 75, null, new HslaColor(100, 25, 75, 100)],
            [100, 25, 100, 100, new HslaColor(100, 25, 100, 100)],
            [100, 25, 0, 100, new HslaColor(0, 0, 0, 100)],
        ];
    }

    /**
     * @test
     * @dataProvider colorsCanBeCreatedViaHslFactoryMethodDataProvider
     */
    public function colorsCanBeCreatedViaHslFactoryMethod(float $hue, float $saturation, float $lightness, ?float $alpha = null, ColorInterface $expectation)
    {
        $color = $this->builder->hsla($hue, $saturation, $lightness, $alpha ?? 100)->getColor();
        $this->assertSameColor($expectation, $color);
    }

    public function colorsCanBeCreatedViaHexFactoryMethodDataProvider(): array
    {
        return [
            ['#ae8', '#aaee88'],
            ['#6400ff', '#6400ff'],
            ['#FFAAEEDD', '#ffaaeedd'],
            ['#ffeeaa88', '#ffeeaa88'],
        ];
    }

    /**
     * @test
     * @dataProvider colorsCanBeCreatedViaHexFactoryMethodDataProvider
     */
    public function colorsCanBeCreatedViaHexFactoryMethod($hexString, $hex)
    {
        $expectation = $this->builder->hex($hex)->getColor();
        $color = $this->builder->hex($hexString)->getColor();
        $this->assertSameColor($expectation, $color);
    }

    public function colorsCanBeCreatedViaCssFactoryMethodDataProvider(): array
    {
        return [
            ['#ae8', '#aaee88'],
            ['#6400ff', '#6400ff'],
            ['#FFAAEEDD', '#ffaaeedd'],
            ['#ffeeaa88', '#ffeeaa88'],
            ['rgb(128,128,128)', '#808080'],
            ['rgba(128,128,128,100%)', '#808080'],
            ['rgba(128,128,128,1)', '#808080'],
            ['hsl(66,100%,75%)', '#f2ff80'],
            ['hsl(66,100%,75%,1)', '#f2ff80'],
            ['hsl(66,100%,75%,100%)', '#f2ff80'],
        ];
    }

    /**
     * @test
     * @dataProvider colorsCanBeCreatedViaCssFactoryMethodDataProvider
     */
    public function colorsCanBeCreatedViaCssFactoryMethod($cssColor, $hex)
    {
        $expectation = $this->builder->hex($hex)->getColor();
        $color = $this->builder->css($cssColor)->getColor();
        $this->assertSimilarColor($expectation, $color);
    }
}
