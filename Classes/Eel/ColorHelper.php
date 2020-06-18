<?php
namespace PackageFactory\ColorHelper\Eel;

use Neos\Eel\ProtectedContextAwareInterface;

class ColorHelper implements ProtectedContextAwareInterface
{

    /**
     * @param $red
     * @param $green
     * @param $blue
     * @param $alpha
     * @return Color
     */
    public function rgb($red, $green, $blue, $alpha = 1): Color
    {
        return Color::createFromRgb($red, $green, $blue, $alpha);
    }

    /**
     * @param $hue
     * @param $saturatiom
     * @param $lightness
     * @param $alpha
     * @return Color
     */
    public function hsl($hue, $saturatiom, $lightness, $alpha = 1): Color
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
     * @param string $methodName
     * @return bool
     */
    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }

}
