<?php
namespace PackageFactory\ColorHelper\Eel;

use Neos\Eel\ProtectedContextAwareInterface;

class ColorBuilder implements ProtectedContextAwareInterface
{

    const PATTERN_HEX_LONG = '/^#?(?<red>[0-9abcdefABCDEF]{2})(?<green>[0-9abcdefABCDEF]{2})(?<blue>[0-9abcdefABCDEF]{2})(?:(?<alpha>[0-9abcdefABCDEF]{2}))?^$/u';

    const PATTERN_HEX_SHORT = '/#?(?<red>[0-9abcdefABCDEF]{1})(?<green>[0-9abcdefABCDEF]{1})(?<blue>[0-9abcdefABCDEF]{1})(?:(?<alpha>[0-9abcdefABCDEF]{1}))?$/u';

    const PATTERN_RGBA = '/^rgba?\\s*\\(\\s*(?<red>[0-9\\.]+%?)\\s*,?\\s*(?<green>[0-9\\.]+%?)\\s*,?\\s*(?<blue>[0-9\\.]+%?)\\s*(?:,?\\s*(?<alpha>[0-9\\.]+%?)\\s*)?\\)$/u';

    const PATTERN_HSLA = '/hsla?\\s*\\(\\s*(?<hue>[0-9\\.]+)\\s*,?\\s*(?<saturation>[0-9\\.]+%)\\s*,?\\s*(?<lightness>[0-9\\.]+%)\\s*(?:,?\\s*(?<alpha>[0-9\\.]+%?)\\s*)?\\)$/u';

    /**
     * @param $red 0-255
     * @param $green 0-255
     * @param $blue 0-255
     * @param $alpha 0-255
     * @return Color
     */
    public function rgb($red, $green, $blue, $alpha = 255): Color
    {
        return Color::createFromRgb($red, $green, $blue, $alpha);
    }

    /**
     * @param $hue 0-355
     * @param $saturatiom  0-100
     * @param $lightness 0-100
     * @param $alpha 0-100
     * @return Color
     */
    public function hsl($hue, $saturatiom, $lightness, $alpha = 100): Color
    {
        return Color::createFromHSL($hue, $saturatiom, $lightness, $alpha);
    }

    /**
     * @param $hex
     * @return Color
     */
    public function hex($hex): Color
    {
        return Color::createFromHex($hex);
    }

    /**
     * @param string $colorString
     * @return Color
     * @throws \Exception
     */
    public function css(string $colorString): Color
    {
        if (preg_match(self::PATTERN_HEX_SHORT, $colorString, $matches)) {
            $red = hexdec($matches['red'].$matches['red']);
            $green = hexdec($matches['green'].$matches['green']);
            $blue = hexdec($matches['blue'].$matches['blue']);
            $alpha = hexdec(isset($matches['alpha']) ? $matches['alpha'] . $matches['alpha'] : 1.0);
            return Color::createFromRgb($red, $green, $blue, $alpha);
        } elseif (preg_match(self::PATTERN_HEX_LONG, $colorString, $matches)) {
            $red = hexdec($matches['red']);
            $green = hexdec($matches['green']);
            $blue = hexdec($matches['blue']);
            $alpha = hexdec($matches['alpha'] ?? 1);
            return Color::createFromRgb($red, $green, $blue, $alpha);
        } elseif (preg_match(self::PATTERN_RGBA, $colorString, $matches)) {
            $red = $this->parseNumber($matches['red']);
            $green = $this->parseNumber($matches['red']);
            $blue = $this->parseNumber($matches['red']);
            $alpha = $this->parseNumber($matches['alpha'] ?? 1, 1);
            return Color::createFromRgb($red, $green, $blue, $alpha);
        } elseif (preg_match(self::PATTERN_HSLA, $colorString, $matches)) {
            $hue = $this->parseNumber($matches['hue'], 355);
            $saturation = $this->parseNumber($matches['saturation'], 100);
            $lightness = $this->parseNumber($matches['lightness'], 100);
            $alpha = $this->parseNumber($matches['alpha'] ?? 1, 1);
            return Color::createFromHSL($hue, $saturation, $lightness, $alpha);
        } else {
            return Color::createFromRgb(0,0,0,1);
        }
    }

    /**
     * @param string $value
     * @param int $max
     * @return float
     */
    protected function parseNumber(string $value, int $max = 255): float
    {
        if (substr($value, -1) == '%') {
            $number =  (int)(
            substr($value, 0, -1)
            );
            return $max * ($number / 100);
        } else {
            return (float) $value;
        }
    }

    /**
     * @param string $methodName
     * @return bool
     */
    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }

}
