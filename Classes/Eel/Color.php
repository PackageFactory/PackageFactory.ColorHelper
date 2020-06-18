<?php
namespace PackageFactory\ColorHelper\Eel;

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\ColorHelper\Library\Mexitek\PHPColors\Color as ColorLibrary;

class Color implements ProtectedContextAwareInterface
{

    /**
     * Default adjustments for color manipulations
     */
    const DEFAULT_ADJUST = 10;

    private $red;
    private $green;
    private $blue;

    private $hue;
    private $saturation;
    private $lightness;

    /**
     * @param int $hue
     * @param int $saturation
     * @param int $lightness
     * @return Color
     * @throws \Exception
     */
    public static function createFromHSL(int $hue, int $saturation, int $lightness): self
    {
        $hue = $hue % 360;
        if ($hue < 0) {
            $hue += 360;
        }

        $saturation = self::limitNumber($saturation, 0, 100);
        $lightness = self::limitNumber($lightness, 0, 100);

        $hsl = [
            'H' => $hue,
            'S' => $saturation / 100,
            'L' => $lightness / 100
        ];
        $hex = ColorLibrary::hslToHex($hsl);
        $rgb = ColorLibrary::hexToRgb($hex);

        $color = new static();
        $color->red = $rgb['R'];
        $color->green = $rgb['G'];
        $color->blue = $rgb['B'];
        $color->hue = $hue;
        $color->saturation = $saturation;
        $color->lightness = $lightness;

        return $color;
    }

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     * @return Color
     * @throws \Exception
     */
    public static function createFromRgb(int $red, int $green, int $blue): self
    {
        $red = self::limitNumber($red, 0, 255);
        $green = self::limitNumber($green, 0, 255);
        $blue = self::limitNumber($blue, 0, 255);

        $rgb = [
            'R' => $red,
            'G' => $green,
            'B' => $blue
        ];
        $hex = ColorLibrary::rgbToHex($rgb);
        $hsl = ColorLibrary::hexToHsl($hex);

        $color = new static();
        $color->red = $rgb['R'];
        $color->green = $rgb['G'];
        $color->blue = $rgb['B'];
        $color->hue = round($hsl['H']);
        $color->saturation = round($hsl['S'] * 100);
        $color->lightness = round($hsl['L'] * 100);

        return $color;
    }

    /**
     * @param string $hex
     * @return Color
     */
    public static function createFromHex(string $hex): self
    {
        $hsl = ColorLibrary::hexToHsl($hex);
        $rgb = ColorLibrary::hexToRgb($hex);

        $color = new static();
        $color->red = $rgb['R'];
        $color->green = $rgb['G'];
        $color->blue = $rgb['B'];
        $color->hue = round($hsl['H']);
        $color->saturation = round($hsl['S'] * 100);
        $color->lightness = round($hsl['L'] * 100);

        return $color;
    }

    public function __toString()
    {
        return '#' . ColorLibrary::rgbToHex([
            'R' => $this->red,
            'G' => $this->green,
            'B' => $this->blue
        ]);
    }

    public function getRgb(): array
    {
        return [$this->red, $this->green, $this->blue];
    }

    public function getHsl(): array
    {
        return [$this->hue, $this->saturation, $this->lightness];
    }

    public function getHex(): array
    {
        return $this->__toString();
    }

    /**
     * @param Color $colorA
     * @param Color $colorB
     * @param int $weight between 0 and 100
     * @return string
     */
    public function mix(Color $color, int $weight = 50): Color
    {
        list($red, $green, $blue) = $color->getRgb();
        $weight = $this->limitNumber($weight, 0, 100);
        $factorA = $weight / 100;
        $factorB = 1 - $factorA;

        return self::createFromRgb(
            round(($this->red * $factorA) + ($red * $factorB)),
            round(($this->green * $factorA) + ($green * $factorB)),
            round(($this->blue * $factorA) + ($blue * $factorB)),
        );
    }

    /**
     * @param int $amount between 0 and 100
     * @return string
     */
    public function lighten(int $amount = self::DEFAULT_ADJUST ): self
    {
        return self::createFromHSL(
            $this->hue,
            $this->saturation,
            $this->lightness + $amount
        );
    }

    /**
     * @param int $amount between 0 and 100
     * @return string
     */
    public function darken(int $amount = self::DEFAULT_ADJUST ): self
    {
        return self::createFromHSL(
            $this->hue,
            $this->saturation,
           $this->lightness - $amount
        );
    }


    /**
     * Adjust the value by rotating the hue angle of a color in either direction.
     *
     * @param string $color
     * @param int $amount degrees to rotate the color
     * @return string
     */
    public function spin(int $amount): self
    {
        return self::createFromHSL(
            $this->hue + $amount,
            $this->saturation,
            $this->lightness
        );
    }

    /**
     * @param int $amount to saturate the color
     * @return string
     */
    public function saturate(int $amount = self::DEFAULT_ADJUST): self
    {
        return self::createFromHSL(
           $this->hue,
            self::limitNumber($this->saturation + $amount, 0, 100),
           $this->lightness
        );
    }

    /**
     * @param string $color
     * @param int $amount to desaturate the color
     * @return string
     */
    public function desaturate(int $amount = self::DEFAULT_ADJUST): self
    {
        return self::createFromHSL(
            $this->hue,
            self::limitNumber($this->saturation - $amount, 0, 100),
            $this->lightness
        );
    }

    /**
     * @param int|float|double $value
     * @param int $min
     * @param int $max
     * @return int
     */
    protected static function limitNumber($value, int $min = 0, int $max = 1) {
        if ($value < $min) {
            return $min;
        } elseif ($value > $max) {
            return $max;
        } else {
            return $value;
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
