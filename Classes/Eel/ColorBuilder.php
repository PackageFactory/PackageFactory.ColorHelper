<?php

namespace PackageFactory\ColorHelper\Eel;

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\ColorHelper\Domain\ValueObject\HslaColor;
use PackageFactory\ColorHelper\Domain\ValueObject\RgbaColor;

class ColorBuilder implements ProtectedContextAwareInterface
{
    const PATTERN_HEX_LONG = '/^#(?<red>[0-9abcdef]{2})(?<green>[0-9abcdef]{2})(?<blue>[0-9abcdef]{2})(?:(?<alpha>[0-9abcdef]{2}))?$/';

    const PATTERN_HEX_SHORT = '/^#(?<red>[0-9abcdef]{1})(?<green>[0-9abcdef]{1})(?<blue>[0-9abcdef]{1})(?:(?<alpha>[0-9abcdef]{1}))?$/u';

    const PATTERN_RGBA = '/^rgba?\\s*\\(\\s*(?<red>[0-9\\.]+%?)\\s*,?\\s*(?<green>[0-9\\.]+%?)\\s*,?\\s*(?<blue>[0-9\\.]+%?)\\s*(?:,?\\s*(?<alpha>[0-9\\.]+%?)\\s*)?\\)$/u';

    const PATTERN_HSLA = '/^hsla?\\s*\\(\\s*(?<hue>[0-9\\.]+)\\s*,?\\s*(?<saturation>[0-9\\.]+%)\\s*,?\\s*(?<lightness>[0-9\\.]+%)\\s*(?:,?\\s*(?<alpha>[0-9\\.]+%?)\\s*)?\\)$/u';

    /**
     * @param float $red   0-255
     * @param float $green 0-255
     * @param float $blue  0-255
     * @param float $alpha 0-255
     *
     * @return ColorHelper
     */
    public function rgb(float $red, float $green, float $blue, float $alpha = 255): ColorHelper
    {
        return new ColorHelper(
            new RgbaColor($red, $green, $blue, $alpha)
        );
    }

    /**
     * @param float $hue        0-360
     * @param float $saturatiom 0-100
     * @param float $lightness  0-100
     * @param float $alpha      0-1
     *
     * @return ColorHelper
     */
    public function hsl(float $hue, float $saturatiom, float $lightness, float $alpha = 1): ColorHelper
    {
        return new ColorHelper(
            new HslaColor($hue, $saturatiom, $lightness, $alpha)
        );
    }

    /**
     * @param string $hex
     *
     * @return ColorHelper
     */
    public function hex(string $hex): ?ColorHelper
    {
        $hex = strtolower($hex);
        if (preg_match(self::PATTERN_HEX_SHORT, $hex, $matches)) {

            $red = hexdec($matches['red'].$matches['red']);
            $green = hexdec($matches['green'].$matches['green']);
            $blue = hexdec($matches['blue'].$matches['blue']);
            $alpha = isset($matches['alpha']) ? hexdec($matches['alpha'].$matches['alpha']) : 255;

            return new ColorHelper(
                new RgbaColor($red, $green, $blue, $alpha)
            );
        } elseif (preg_match(self::PATTERN_HEX_LONG, $hex, $matches)) {

            $red = hexdec($matches['red']);
            $green = hexdec($matches['green']);
            $blue = hexdec($matches['blue']);
            $alpha = isset($matches['alpha']) ? hexdec($matches['alpha']) : 255;

            return new ColorHelper(
                new RgbaColor($red, $green, $blue, $alpha)
            );
        }
        return null;
    }

    /**
     * @param string $colorString
     *
     * @throws \Exception
     *
     * @return ColorHelper
     */
    public function css(string $colorString): ?ColorHelper
    {
        $colorString = strtolower($colorString);
        if (preg_match(self::PATTERN_HEX_SHORT, $colorString, $matches)) {
            $red = hexdec($matches['red'].$matches['red']);
            $green = hexdec($matches['green'].$matches['green']);
            $blue = hexdec($matches['blue'].$matches['blue']);
            $alpha = isset($matches['alpha']) ? hexdec($matches['alpha'].$matches['alpha']) : 255;

            return new ColorHelper(
                new RgbaColor($red, $green, $blue, $alpha)
            );
        } elseif (preg_match(self::PATTERN_HEX_LONG, $colorString, $matches)) {
            $red = hexdec($matches['red']);
            $green = hexdec($matches['green']);
            $blue = hexdec($matches['blue']);
            $alpha = isset($matches['alpha']) ? hexdec($matches['alpha']) : 255;

            return new ColorHelper(
                new RgbaColor($red, $green, $blue, $alpha)
            );
        } elseif (preg_match(self::PATTERN_RGBA, $colorString, $matches)) {
            $red = $this->parseNumber($matches['red'], 255);
            $green = $this->parseNumber($matches['red'], 255);
            $blue = $this->parseNumber($matches['red'], 255);
            $alpha = isset($matches['alpha']) ? $this->parseNumber($matches['alpha'], 255) : 255;

            return new ColorHelper(
                new RgbaColor($red, $green, $blue, $alpha)
            );
        } elseif (preg_match(self::PATTERN_HSLA, $colorString, $matches)) {
            $hue = $this->parseNumber($matches['hue'], 360);
            $saturation = $this->parseNumber($matches['saturation'], 100);
            $lightness = $this->parseNumber($matches['lightness'], 100);
            $alpha = isset($matches['alpha']) ? $this->parseNumber($matches['alpha'], 1) : 1;

            return new ColorHelper(
                new HslaColor($hue, $saturation, $lightness, $alpha)
            );
        }

        return null;
    }

    /**
     * @param string $value
     * @param int    $max
     *
     * @return float
     */
    protected function parseNumber(string $value, int $max = 255, bool $circle = false): float
    {
        if (substr($value, -1) == '%') {
            $number = (int) (
                substr($value, 0, -1)
            );
            return $max * ($number / 100);
        } else {
            $value = (float) $value;
            if ($circle) {
                if ($value < 0) $value = $max + ($value % $max);
                if ($value > $max) $value = $value % $max;
            } else {
                if ($value < 0) $value = 0;
                if ($value > $max) $value = $max;
            }
            return $value;
        }
    }

    /**
     * @param string $methodName
     *
     * @return bool
     */
    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
