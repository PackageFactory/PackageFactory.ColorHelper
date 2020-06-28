<?php

namespace PackageFactory\ColorHelper\Tests\Unit;

use PackageFactory\ColorHelper\Domain\ValueObject\ColorInterface;
use PackageFactory\ColorHelper\Domain\ValueObject\HslaColor;
use PackageFactory\ColorHelper\Domain\ValueObject\RgbaColor;
use PackageFactory\ColorHelper\Eel\ColorHelper;
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
    public function conversionOfRgbToHslWorks($colorFixture)
    {
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
    public function conversionOfHslToRgbWorks($colorFixture)
    {
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
    public function conversionOfRgbToHexbWorks($colorFixture)
    {
        $rgb = $colorFixture['rgb'];
        $hex = $colorFixture['hex'];
        $rgbColor = new RgbaColor($rgb[0], $rgb[1], $rgb[2]);
        self::assertEquals($hex, strtoupper($rgbColor->getHexString()));
    }



    public function conversionToHexStringWorksDataProvider():array
    {
        return [
            [new RgbaColor(255,0,0), '#ff0000'],
            [new RgbaColor(0,255,0), '#00ff00'],
            [new RgbaColor(0,0,255), '#0000ff'],
            [new RgbaColor(255,255,255,0), '#ffffff00'],
            [new RgbaColor(255,255,255,128), '#ffffff80'],
            [new RgbaColor(255,255,255,255), '#ffffff']
        ];
    }

    /**
     * @test
     * @dataProvider conversionToHexStringWorksDataProvider
     */
    public function conversionToHexStringWorks(ColorInterface $color, string $expectation)
    {
        self::assertEquals($color->getHexString(), $expectation);
    }

    public function conversionToRgbStringWorksDataProvider():array
    {
        return [
            [new RgbaColor(255,0,0), 'rgb(255, 0, 0)'],
            [new RgbaColor(0,255,0), 'rgb(0, 255, 0)'],
            [new RgbaColor(0,0,255), 'rgb(0, 0, 255)'],
            [new RgbaColor(255,255,255,0), 'rgba(255, 255, 255, 0)'],
            [new RgbaColor(255,255,255,128), 'rgba(255, 255, 255, 128)'],
            [new RgbaColor(255,255,255,255), 'rgb(255, 255, 255)']
        ];
    }

    /**
     * @test
     * @dataProvider conversionToRgbStringWorksDataProvider
     */
    public function conversionToRgbStringWorks(ColorInterface $color, string $expectation)
    {
        self::assertEquals($color->getRgbaString(), $expectation);
    }

    public function conversionToHslaStringWorksDataProvider():array
    {
        return [
            [new HslaColor(320,20,50), 'hsl(320, 20%, 50%)'],
            [new HslaColor(50,80,80), 'hsl(50, 80%, 80%)'],
            [new HslaColor(320,20,50,0), 'hsla(320, 20%, 50%, 0)'],
            [new HslaColor(320,20,50,128), 'hsla(320, 20%, 50%, 0.5)'],
            [new HslaColor(320,20,50,255), 'hsl(320, 20%, 50%)']
        ];
    }

    /**
     * @test
     * @dataProvider conversionToHslaStringWorksDataProvider
     */
    public function conversionToHslaStringWorks(ColorInterface $color, string $expectation)
    {
        self::assertEquals($color->getHslaString(), $expectation);
    }

    public function colorMixingWorksDataProvider():array
    {
        return [
            [new RgbaColor(0,0,0,0), new RgbaColor(255,255,255,255), 100, new RgbaColor(0,0,0,0)],
            [new RgbaColor(0,0,0,0), new RgbaColor(255,255,255,255), 75, new RgbaColor(64,64,64,64)],
            [new RgbaColor(0,0,0,0), new RgbaColor(255,255,255,255), 50, new RgbaColor(128,128,128,128)],
            [new RgbaColor(0,0,0,0), new RgbaColor(255,255,255,255), 25, new RgbaColor(191,191,191,191)],
            [new RgbaColor(0,0,0,0), new RgbaColor(255,255,255,255), 0, new RgbaColor(255,255,255,255)],
            [new RgbaColor(0,0,0,0), new RgbaColor(255,0,0,0), 50, new RgbaColor(128,0,0,0)],
            [new RgbaColor(0,0,0,0), new RgbaColor(0,255,0,0), 50, new RgbaColor(0,128,0,0)],
            [new RgbaColor(0,0,0,0), new RgbaColor(0,0,255,0), 50, new RgbaColor(0,0,128,0)],
            [new RgbaColor(0,0,0,0), new RgbaColor(0,0,0,255), 50, new RgbaColor(0,0,0,128)]
        ];
    }

    /**
     * @test
     * @dataProvider colorMixingWorksDataProvider
     */
    public function colorMixingWorks(ColorInterface $colorA, ColorInterface $colorB, $weight, ColorInterface $expectation)
    {
        $mixed = $colorA->withMixedColor($colorB, $weight)->asRgba();
        self::assertTrue($mixed->equals($expectation));
    }

    public function lightnessAdjustmentWorksDataProvider():array
    {
        return [
            [new HslaColor(0,0,50,0),10,new HslaColor(0,0,60,0)],
            [new HslaColor(0,0,50,0),-10,new HslaColor(0,0,40,0)],
            [new HslaColor(0,0,50,0),100,new HslaColor(0,0,100,0)],
            [new HslaColor(0,0,50,0),-100,new HslaColor(0,0,0,0)]
        ];
    }

    /**
     * @test
     * @dataProvider lightnessAdjustmentWorksDataProvider
     */
    public function lightnessAdjustmentWorks(ColorInterface $color, int $delta, ColorInterface $expectation)
    {
        $adjusted = $color->withAdjustedLightness($delta)->asHsla();
        self::assertTrue($adjusted->equals($expectation));
    }

    public function saturationAdjustmentWorksDataProvider():array
    {
        return [
            [new HslaColor(0,50,0,0),10,new HslaColor(0,60,0,0)],
            [new HslaColor(0,50,0,0),-10,new HslaColor(0,40,0,0)],
            [new HslaColor(0,50,0,0),100,new HslaColor(0,100,0,0)],
            [new HslaColor(0,50,0,0),-100,new HslaColor(0,0,0,0)]
        ];
    }

    /**
     * @test
     * @dataProvider saturationAdjustmentWorksDataProvider
     */
    public function saturationAdjustmentWorks(ColorInterface $color, int $delta, ColorInterface $expectation)
    {
        $adjusted = $color->withAdjustedSaturation($delta)->asHsla();
        self::assertTrue($adjusted->equals($expectation));
    }

    public function hueAdjustmentWorksDataProvider():array
    {
        return [
            [new HslaColor(100,0,0,0),10,new HslaColor(110,0,0,0)],
            [new HslaColor(100,0,0,0),-10,new HslaColor(90,0,0,0)],
            [new HslaColor(100,0,0,0),-150,new HslaColor(310,0,0,0)],
            [new HslaColor(100,0,0,0),+300,new HslaColor(40,0,0,0)]
        ];
    }

    /**
     * @test
     * @dataProvider hueAdjustmentWorksDataProvider
     */
    public function hueAdjustmentWorks(ColorInterface $color, int $delta, ColorInterface $expectation)
    {
        $adjustedColor = $color->withAdjustedHue($delta)->asHsla();
        self::assertTrue($adjustedColor->equals($expectation));
    }

    public function alphaAdjustmentWorksColorsDataProvider():array
    {
        return [
            [new HslaColor(0,0,0,50),10,new HslaColor(0,0,0,60)],
            [new HslaColor(0,0,0,50),-10,new HslaColor(0,0,0,40)],
            [new HslaColor(0,0,0,50),250,new HslaColor(0,0,0,255)],
            [new HslaColor(0,0,0,50),-100,new HslaColor(0,0,0,0)],
            [new RgbaColor(0,0,0,50),10,new RgbaColor(0,0,0,60)],
            [new RgbaColor(0,0,0,50),-10,new RgbaColor(0,0,0,40)],
            [new RgbaColor(0,0,0,50),250,new RgbaColor(0,0,0,255)],
            [new RgbaColor(0,0,0,50),-100,new RgbaColor(0,0,0,0)]
        ];
    }

    /**
     * @test
     * @dataProvider alphaAdjustmentWorksColorsDataProvider
     */
    public function alphaAdjustmentWorksColors(ColorInterface $color, int $delta, ColorInterface $expectation)
    {
        $adjustedColor = $color->withAdjustedAlpha($delta);
        self::assertTrue($adjustedColor->equals($expectation));
    }
}
