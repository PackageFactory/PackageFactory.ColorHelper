<?php

declare(strict_types=1);

namespace PackageFactory\ColorHelper\Domain\ValueObject;

abstract class AbstractColor implements ColorInterface
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getHexString();
    }

    /**
     * @return string
     */
    public function getHexString(): string
    {
        $rgba = $this->asRgba();
        if ($rgba->getAlpha() == 255) {
            return '#'
                .str_pad(dechex((int)round($rgba->getRed())), 2, '0')
                .str_pad(dechex((int)round($rgba->getGreen())), 2, '0')
                .str_pad(dechex((int)round($rgba->getBlue())), 2, '0');
        } else {
            return '#'
                .str_pad(dechex((int)round($rgba->getRed())), 2, '0')
                .str_pad(dechex((int)round($rgba->getGreen())), 2, '0')
                .str_pad(dechex((int)round($rgba->getBlue())), 2, '0')
                .str_pad(dechex((int)round($rgba->getAlpha())), 2, '0');
        }
    }

    /**
     * @return string
     */
    public function getHslaString(): string
    {
        $hslaColor = $this->asHsla();
        if ($hslaColor->getAlpha() == 1) {
            return sprintf('hsl(%s, %s%%, %s%%)', round($hslaColor->getHue()), round($hslaColor->getSaturation()), round($hslaColor->getLightness()));
        } else {
            return sprintf('hsla(%s, %s%%, %s%%, %s)', round($hslaColor->getHue()), round($hslaColor->getSaturation()), round($hslaColor->getLightness()), round($hslaColor->getAlpha(), 2));
        }
    }

    /**
     * @return string
     */
    public function getRgbaString(): string
    {
        $rgbColor = $this->asRgba();
        if ($rgbColor->getAlpha() == 255) {
            return sprintf('rgb(%s, %s, %s)', round($rgbColor->getRed()), round($rgbColor->getGreen()), round($rgbColor->getBlue()));
        } else {
            return sprintf('rgba(%s, %s, %s, %s)', round($rgbColor->getRed()), round($rgbColor->getGreen()), round($rgbColor->getBlue()), $rgbColor->getAlpha());
        }
    }

    /**
     * @param ColorInterface $color
     *
     * @return bool
     */
    public function equals(ColorInterface $color): bool
    {
        return $this->getHexString() == $color->getHexString();
    }

    /**
     * @param ColorInterface $color
     * @param int            $weight
     *
     * @return RgbaColor
     */
    public function withMixedColor(ColorInterface $color, int $weight = 50): ColorInterface
    {
        if ($weight < 0 || $weight > 100) {
            throw new \InvalidArgumentException('argument weight has to be an integer between 0 and 100, '.$weight.' was given.');
        }

        $factorA = $weight / 100;
        $factorB = 1 - $factorA;

        $rgbaColorA = $this->asRgba();
        $rgbaColorB = $color->asRgba();

        return new RgbaColor(
            (int) round(($rgbaColorA->getRed() * $factorA) + ($rgbaColorB->getRed() * $factorB)),
            (int) round(($rgbaColorA->getGreen() * $factorA) + ($rgbaColorB->getGreen() * $factorB)),
            (int) round(($rgbaColorA->getBlue() * $factorA) + ($rgbaColorB->getBlue() * $factorB)),
            (int) round(($rgbaColorA->getAlpha() * $factorA) + ($rgbaColorB->getAlpha() * $factorB))
        );
    }

    /**
     * @param float $delta
     *
     * @return ColorInterface
     */
    public function withAdjustedLightness(float $delta): ColorInterface
    {
        $hslaColor = $this->asHsla();
        $lightness = $hslaColor->getLightness() + $delta;
        if ($lightness < 0) {
            $lightness = 0;
        }
        if ($lightness > 100) {
            $lightness = 100;
        }

        return new HslaColor(
            $hslaColor->getHue(),
            $hslaColor->getSaturation(),
            $lightness,
            $hslaColor->getAlpha()
        );
    }

    /**
     * @param float $delta
     *
     * @return ColorInterface
     */
    public function withAdjustedSaturation(float $delta): ColorInterface
    {
        $hslaColor = $this->asHsla();
        $saturation = $hslaColor->getSaturation() + $delta;
        if ($saturation < 0) {
            $saturation = 0;
        }
        if ($saturation > 100) {
            $saturation = 100;
        }

        return new HslaColor(
            $hslaColor->getHue(),
            $saturation,
            $hslaColor->getLightness(),
            $hslaColor->getAlpha()
        );
    }

    /**
     * @param float $delta
     *
     * @return ColorInterface
     */
    public function withAdjustedHue(float $delta): ColorInterface
    {
        $hslaColor = $this->asHsla();
        $hue = ($hslaColor->getHue() + $delta) % 360;
        if ($hue < 0) {
            $hue += 360;
        }

        return new HslaColor(
            $hue,
            $hslaColor->getSaturation(),
            $hslaColor->getLightness(),
            $hslaColor->getAlpha()
        );
    }
}
