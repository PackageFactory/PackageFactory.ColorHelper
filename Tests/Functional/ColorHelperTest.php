<?php

namespace PackageFactory\ColorHelper\Tests\Functional;

use PHPUnit\Framework\TestCase;
use PackageFactory\ColorHelper\Eel\ColorBuilder;
use PackageFactory\ColorHelper\Eel\ColorHelper;

class ColorHelperTest extends TestCase
{
    private $builder;

    public function setUp(): void
    {
        $this->builder = new ColorBuilder();
    }

    public function colorsCanBeCreatedViaRgbFactoryMethodDataProvider():array
    {
        return [
            [100,0,255,null,'#6400ff'],
            [0,0,0,null,'#000000'],
            [100,0,255,128,'#6400ff80']
        ];
    }

    /**
     * @test
     * @dataProvider colorsCanBeCreatedViaRgbFactoryMethodDataProvider
     */
    public function colorsCanBeCreatedViaRgbFactoryMethod($red, $green, $blue, $alpha, $hex)
    {
        $this->assertEquals($hex,  $this->builder->rgb($red, $green, $blue, $alpha ?? 255)->__toString());
    }

    public function colorsCanBeCreatedViaHslFactoryMethodDataProvider():array
    {
        return [
            [100,25,75,1,'#cfcfaf'],
            [100,25,100,1,'#ffffff'],
            [100,25,0,1,'#000000']
        ];
    }

    /**
     * @test
     * @dataProvider colorsCanBeCreatedViaHslFactoryMethodDataProvider
     */
    public function colorsCanBeCreatedViaHslFactoryMethod($hue, $saturation, $lightness, $alpha, $hex)
    {
        $this->assertEquals($hex,  $this->builder->hsl($hue, $saturation, $lightness, $alpha ?? 1)->__toString());
    }

    public function colorsCanBeCreatedViaHexFactoryMethodDataProvider():array
    {
        return [
            ['#ae8','#aaee88'],
            ['#6400ff','#6400ff'],
            ['#FFAAEEDD','#ffaaeedd'],
            ['#ffeeaa88','#ffeeaa88']
        ];
    }

    /**
     * @test
     * @dataProvider colorsCanBeCreatedViaHexFactoryMethodDataProvider
     */
    public function colorsCanBeCreatedViaHexFactoryMethod($string, $hex)
    {
        $this->assertEquals($hex,  $this->builder->hex($string)->__toString());
    }

    public function colorsCanBeCreatedViaCssFactoryMethodDataProvider():array
    {
        return [
            ['#ae8','#aaee88'],
            ['#6400ff','#6400ff'],
            ['#FFAAEEDD','#ffaaeedd'],
            ['#ffeeaa88','#ffeeaa88'],
            ['rgb(128,128,128)','#808080'],
            ['rgba(128,128,128,255)','#808080'],
            ['hsl(66,100%,75%)','#ffff80'],
            ['hsl(66,100%,75%,1)','#ffff80']
        ];
    }

    /**
     * @test
     * @dataProvider colorsCanBeCreatedViaCssFactoryMethodDataProvider
     */
    public function colorsCanBeCreatedViaCssFactoryMethod($string, $hex)
    {
        $this->assertEquals($hex,  $this->builder->css($string)->__toString());
    }
}
