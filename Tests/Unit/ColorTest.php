<?php

namespace PackageFactory\ColorHelper\Tests\Unit;

use PackageFactory\ColorHelper\Domain\ValueObject\HslaColor;
use PackageFactory\ColorHelper\Domain\ValueObject\RgbaColor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class ColorTest extends TestCase
{
    /**
     * @return array
     */
    public function getColorFixtures ():array
    {
        $yaml = Yaml::parseFile(__DIR__ . '/Colors.yaml');
        array_walk(
            $yaml,
            function($item, $name)
            {
                $item['name'] = $name;
            }
        );
        $yaml = array_values($yaml);
        $yaml = array_map(
            function($item) {
                return [$item];
            },
            $yaml
        );
        return $yaml;
    }

    /**
     * @test
     * @dataProvider getColorFixtures
     */
    public function conversionOfRgbToHslWorks($colorFixture) {
        $rgb = $colorFixture['rgb'];
        $hsl = $colorFixture['hsl'];
        $rgbColor = new RgbaColor($rgb[0], $rgb[1], $rgb[2]);
        $hslColor = $rgbColor->asHsla();
        self::assertEquals($hsl[0], $hslColor->getHue());
        self::assertEquals($hsl[1], $hslColor->getSaturation());
        self::assertEquals($hsl[2], $hslColor->getLightness());
    }

    /**
     * @test
     * @dataProvider getColorFixtures
     */
    public function conversionOfHslToRgbWorks($colorFixture) {
        $rgb = $colorFixture['rgb'];
        $hsl = $colorFixture['hsl'];
        $hslColor = new HslaColor($hsl[0], $hsl[1], $hsl[2]);
        $rgbColor = $hslColor->asRgba();
        self::assertEquals($rgb[0], $rgbColor->getRed());
        self::assertEquals($rgb[1], $rgbColor->getGreen());
        self::assertEquals($rgb[2], $rgbColor->getBlue());
    }

    /**
     * @test
     * @dataProvider getColorFixtures
     */
    public function conversionOfRgbToHexbWorks($colorFixture) {
        $rgb = $colorFixture['rgb'];
        $hex = $colorFixture['hex'];
        $rgbColor = new RgbaColor($rgb[0], $rgb[1], $rgb[2]);
        self::assertEquals($hex, strtoupper($rgbColor->getHex()));
    }
}
