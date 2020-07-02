<?php

namespace PackageFactory\ColorHelper\Tests\Unit;

use PackageFactory\ColorHelper\Domain\ValueObject\ColorInterface;
use PackageFactory\ColorHelper\Domain\ValueObject\HslaColor;
use PackageFactory\ColorHelper\Domain\ValueObject\RgbaColor;

class ColorTest extends AbstractColorTest
{
    /**
     * @test
     * @dataProvider rgbSpectrumDataProvider
     */
    public function conversionOfRgbToHslAndBackWorks(int $r, int $g, int $b)
    {
        $original = new RgbaColor($r, $g, $b);
        $converted = $original->asHsla()->asRgba();
        self::assertSimilarColor($original, $converted);
    }

    /**
     * @test
     * @dataProvider hlsSpectrumDataProvider
     */
    public function conversionOfHslToRgbAndBackWorks(int $h, int $l, int $s)
    {
        $original = new HslaColor($h, $l, $s);
        $converted = $original->asRgba()->asHsla();
        self::assertSimilarColor($original, $converted);
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
        $expectation = new HslaColor($hsl[0], $hsl[1], $hsl[2]);
        self::assertSimilarColor($hslColor, $expectation);
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
        $expectation = new RgbaColor($rgb[0], $rgb[1], $rgb[2]);
        self::assertSimilarColor($expectation, $rgbColor);
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

    public function conversionToHexStringWorksDataProvider(): array
    {
        return [
            [new RgbaColor(255, 0, 0), '#ff0000'],
            [new RgbaColor(0, 255, 0), '#00ff00'],
            [new RgbaColor(0, 0, 255), '#0000ff'],
            [new RgbaColor(255, 255, 255, 0), '#ffffff00'],
            [new RgbaColor(255, 255, 255, 128), '#ffffff80'],
            [new RgbaColor(255, 255, 255, 255), '#ffffff'],
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

    public function conversionToRgbStringWorksDataProvider(): array
    {
        return [
            [new RgbaColor(255, 0, 0), 'rgb(255, 0, 0)'],
            [new RgbaColor(0, 255, 0), 'rgb(0, 255, 0)'],
            [new RgbaColor(0, 0, 255), 'rgb(0, 0, 255)'],
            [new RgbaColor(255, 255, 255, 0), 'rgba(255, 255, 255, 0)'],
            [new RgbaColor(255, 255, 255, 128), 'rgba(255, 255, 255, 128)'],
            [new RgbaColor(255, 255, 255, 255), 'rgb(255, 255, 255)'],
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

    public function conversionToHslaStringWorksDataProvider(): array
    {
        return [
            [new HslaColor(320, 20, 50), 'hsl(320, 20%, 50%)'],
            [new HslaColor(50, 80, 80), 'hsl(50, 80%, 80%)'],
            [new HslaColor(320, 20, 50, 0), 'hsla(320, 20%, 50%, 0)'],
            [new HslaColor(320, 20, 50, 0.5), 'hsla(320, 20%, 50%, 0.5)'],
            [new HslaColor(320, 20, 50, 1), 'hsl(320, 20%, 50%)'],
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

    public function colorMixingWorksDataProvider(): array
    {
        return [
            [new RgbaColor(0, 0, 0, 0), new RgbaColor(255, 255, 255, 255), 100, new RgbaColor(0, 0, 0, 0)],
            [new RgbaColor(0, 0, 0, 0), new RgbaColor(255, 255, 255, 255), 75, new RgbaColor(64, 64, 64, 64)],
            [new RgbaColor(0, 0, 0, 0), new RgbaColor(255, 255, 255, 255), 50, new RgbaColor(128, 128, 128, 128)],
            [new RgbaColor(0, 0, 0, 0), new RgbaColor(255, 255, 255, 255), 25, new RgbaColor(191, 191, 191, 191)],
            [new RgbaColor(0, 0, 0, 0), new RgbaColor(255, 255, 255, 255), 0, new RgbaColor(255, 255, 255, 255)],
            [new RgbaColor(0, 0, 0, 0), new RgbaColor(255, 0, 0, 0), 50, new RgbaColor(128, 0, 0, 0)],
            [new RgbaColor(0, 0, 0, 0), new RgbaColor(0, 255, 0, 0), 50, new RgbaColor(0, 128, 0, 0)],
            [new RgbaColor(0, 0, 0, 0), new RgbaColor(0, 0, 255, 0), 50, new RgbaColor(0, 0, 128, 0)],
            [new RgbaColor(0, 0, 0, 0), new RgbaColor(0, 0, 0, 255), 50, new RgbaColor(0, 0, 0, 128)],
        ];
    }

    /**
     * @test
     * @dataProvider colorMixingWorksDataProvider
     */
    public function colorMixingWorks(ColorInterface $colorA, ColorInterface $colorB, $weight, ColorInterface $expectation)
    {
        $mixed = $colorA->withMixedColor($colorB, $weight)->asRgba();
        self::assertSimilarColor($mixed, $expectation);
    }

    public function lightnessAdjustmentWorksDataProvider(): array
    {
        return [
            [new HslaColor(0, 0, 50, 0), 10, new HslaColor(0, 0, 60, 0)],
            [new HslaColor(0, 0, 50, 0), -10, new HslaColor(0, 0, 40, 0)],
            [new HslaColor(0, 0, 50, 0), 100, new HslaColor(0, 0, 100, 0)],
            [new HslaColor(0, 0, 50, 0), -100, new HslaColor(0, 0, 0, 0)],
        ];
    }

    /**
     * @test
     * @dataProvider lightnessAdjustmentWorksDataProvider
     */
    public function lightnessAdjustmentWorks(ColorInterface $color, int $delta, ColorInterface $expectation)
    {
        $adjusted = $color->withAdjustedLightness($delta)->asHsla();
        self::assertSimilarColor($adjusted, $expectation);
    }

    public function saturationAdjustmentWorksDataProvider(): array
    {
        return [
            [new HslaColor(0, 50, 0, 0), 10, new HslaColor(0, 60, 0, 0)],
            [new HslaColor(0, 50, 0, 0), -10, new HslaColor(0, 40, 0, 0)],
            [new HslaColor(0, 50, 0, 0), 100, new HslaColor(0, 100, 0, 0)],
            [new HslaColor(0, 50, 0, 0), -100, new HslaColor(0, 0, 0, 0)],
        ];
    }

    /**
     * @test
     * @dataProvider saturationAdjustmentWorksDataProvider
     */
    public function saturationAdjustmentWorks(ColorInterface $color, int $delta, ColorInterface $expectation)
    {
        $adjusted = $color->withAdjustedSaturation($delta)->asHsla();
        self::assertSimilarColor($adjusted, $expectation);
    }

    public function hueAdjustmentWorksDataProvider(): array
    {
        return [
            [new HslaColor(100, 0, 0, 0), 10, new HslaColor(110, 0, 0, 0)],
            [new HslaColor(100, 0, 0, 0), -10, new HslaColor(90, 0, 0, 0)],
            [new HslaColor(100, 0, 0, 0), -150, new HslaColor(310, 0, 0, 0)],
            [new HslaColor(100, 0, 0, 0), +300, new HslaColor(40, 0, 0, 0)],
        ];
    }

    /**
     * @test
     * @dataProvider hueAdjustmentWorksDataProvider
     */
    public function hueAdjustmentWorks(ColorInterface $color, int $delta, ColorInterface $expectation)
    {
        $adjustedColor = $color->withAdjustedHue($delta)->asHsla();
        self::assertSimilarColor($adjustedColor, $expectation);
    }

    public function alphaAdjustmentWorksColorsDataProvider(): array
    {
        return [
            [new HslaColor(0, 0, 0, 0.5), 10, new HslaColor(0, 0, 0, 0.6)],
            [new HslaColor(0, 0, 0, 0.5), -10, new HslaColor(0, 0, 0, 0.4)],
            [new HslaColor(0, 0, 0, 0.5), 250, new HslaColor(0, 0, 0, 1)],
            [new HslaColor(0, 0, 0, 0.5), -100, new HslaColor(0, 0, 0, 0)],

            [new RgbaColor(0, 0, 0, 128), 25, new RgbaColor(0, 0, 0, 192)],
            [new RgbaColor(0, 0, 0, 128), -25, new RgbaColor(0, 0, 0, 64)],
            [new RgbaColor(0, 0, 0, 128), 250, new RgbaColor(0, 0, 0, 255)],
            [new RgbaColor(0, 0, 0, 128), -250, new RgbaColor(0, 0, 0, 0)],
        ];
    }

    /**
     * @test
     * @dataProvider alphaAdjustmentWorksColorsDataProvider
     */
    public function alphaAdjustmentWorksColors(ColorInterface $color, int $delta, ColorInterface $expectation)
    {
        $adjustedColor = $color->withAdjustedAlpha($delta);
        self::assertSimilarColor($adjustedColor, $expectation);
    }
}
