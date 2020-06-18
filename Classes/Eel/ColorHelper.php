<?php
namespace PackageFactory\ColorHelper\Eel;

use Neos\Eel\ProtectedContextAwareInterface;

class ColorHelper implements ProtectedContextAwareInterface
{

    /**
     * @param $red
     * @param $green
     * @param $blue
     * @return Color
     */
    public function rgb($red, $green, $blue): Color
    {
        return Color::createFromRgb($red, $green, $blue);
    }

    /**
     * @param $hue
     * @param $saturatiom
     * @param $lightness
     * @return Color
     */
    public function hsl($hue, $saturatiom, $lightness): Color
    {
        return Color::createFromHSL($hue, $saturatiom, $lightness);
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
