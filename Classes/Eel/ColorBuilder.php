<?php

namespace PackageFactory\ColorHelper\Eel;

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\ColorHelper\Domain\ValueObject\HslaColor;
use PackageFactory\ColorHelper\Domain\ValueObject\RgbaColor;

class ColorBuilder implements ProtectedContextAwareInterface
{
    const PATTERN_HEX_LONG = '/^#?(?<red>[0-9abcdefABCDEF]{2})(?<green>[0-9abcdefABCDEF]{2})(?<blue>[0-9abcdefABCDEF]{2})(?:(?<alpha>[0-9abcdefABCDEF]{2}))?^$/u';

    const PATTERN_HEX_SHORT = '/#?(?<red>[0-9abcdefABCDEF]{1})(?<green>[0-9abcdefABCDEF]{1})(?<blue>[0-9abcdefABCDEF]{1})(?:(?<alpha>[0-9abcdefABCDEF]{1}))?$/u';

    const PATTERN_RGBA = '/^rgba?\\s*\\(\\s*(?<red>[0-9\\.]+%?)\\s*,?\\s*(?<green>[0-9\\.]+%?)\\s*,?\\s*(?<blue>[0-9\\.]+%?)\\s*(?:,?\\s*(?<alpha>[0-9\\.]+%?)\\s*)?\\)$/u';

    const PATTERN_HSLA = '/hsla?\\s*\\(\\s*(?<hue>[0-9\\.]+)\\s*,?\\s*(?<saturation>[0-9\\.]+%)\\s*,?\\s*(?<lightness>[0-9\\.]+%)\\s*(?:,?\\s*(?<alpha>[0-9\\.]+%?)\\s*)?\\)$/u';

    /**
     * @param int $red   0-255
     * @param int $green 0-255
     * @param int $blue  0-255
     * @param int $alpha 0-255
     *
     * @return ColorHelper
     */
    public function rgb(int $red, int $green, int $blue, int $alpha = 255): ColorHelper
    {
        return new ColorHelper(
            new RgbaColor($red, $green, $blue, $alpha)
        );
    }

    /**
     * @param int $hue        0-355
     * @param int $saturatiom 0-100
     * @param int $lightness  0-100
     * @param int $alpha      0-100
     *
     * @return ColorHelper
     */
    public function hsl(int $hue, int $saturatiom, int $lightness, int $alpha = 100): ColorHelper
    {
        $alpha = (int) round($alpha / 100 * 255);

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
        if (preg_match(self::PATTERN_HEX_SHORT, $hex, $matches)) {
            $red = (int)hexdec($matches['red'].$matches['red']);
            $green = (int)hexdec($matches['green'].$matches['green']);
            $blue = (int)hexdec($matches['blue'].$matches['blue']);
            $alpha = (int)hexdec(isset($matches['alpha']) ? $matches['alpha'].$matches['alpha'] : "ff");

            return new ColorHelper(
                new RgbaColor($red, $green, $blue, $alpha)
            );
        } elseif (preg_match(self::PATTERN_HEX_LONG, $hex, $matches)) {
            $red = (int)hexdec($matches['red']);
            $green = (int)hexdec($matches['green']);
            $blue = (int)hexdec($matches['blue']);
            $alpha = (int)hexdec($matches['alpha'] ?? "ff");

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
        if (preg_match(self::PATTERN_HEX_SHORT, $colorString, $matches)) {
            $red = (int)hexdec($matches['red'].$matches['red']);
            $green = (int)hexdec($matches['green'].$matches['green']);
            $blue = (int)hexdec($matches['blue'].$matches['blue']);
            $alpha = (int)hexdec(isset($matches['alpha']) ? $matches['alpha'].$matches['alpha'] : "ff");

            return new ColorHelper(
                new RgbaColor($red, $green, $blue, $alpha)
            );
        } elseif (preg_match(self::PATTERN_HEX_LONG, $colorString, $matches)) {
            $red = (int)hexdec($matches['red']);
            $green = (int)hexdec($matches['green']);
            $blue = (int)hexdec($matches['blue']);
            $alpha = (int)hexdec($matches['alpha'] ? $matches['alpha'] : "ff");

            return new ColorHelper(
                new RgbaColor($red, $green, $blue, $alpha)
            );
        } elseif (preg_match(self::PATTERN_RGBA, $colorString, $matches)) {
            $red = (int) round($this->parseNumber($matches['red']));
            $green = (int) round($this->parseNumber($matches['red']));
            $blue = (int) round($this->parseNumber($matches['red']));
            $alpha = (int) round($this->parseNumber($matches['alpha'] ?? 255, 255));

            return new ColorHelper(
                new RgbaColor($red, $green, $blue, $alpha)
            );
        } elseif (preg_match(self::PATTERN_HSLA, $colorString, $matches)) {
            $hue = (int) round($this->parseNumber($matches['hue'], 355));
            $saturation = (int) round($this->parseNumber($matches['saturation'], 100));
            $lightness = (int) round($this->parseNumber($matches['lightness'], 100));
            $alpha = (int) round($this->parseNumber($matches['alpha'] ?? 1, 1) * 255);

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
    protected function parseNumber(string $value, int $max = 255): float
    {
        if (substr($value, -1) == '%') {
            $number = (int) (
                substr($value, 0, -1)
            );

            return $max * ($number / 100);
        } else {
            return (float) $value;
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
