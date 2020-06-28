<?php

namespace PackageFactory\ColorHelper\Eel;

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\ColorHelper\Domain\ValueObject\ColorInterface;

class ColorHelper implements ProtectedContextAwareInterface
{
    /**
     * Default adjustments for color manipulations.
     */
    const DEFAULT_ADJUSTMENT = 10;

    /**
     * @var ColorInterface
     */
    private $color;

    public function __construct(ColorInterface $color)
    {
        $this->color = $color;
    }

    /**
     * @return ColorInterface
     */
    public function getColor(): ColorInterface
    {
        return $this->color;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->color->getHexString();
    }

    /**
     * @return string
     */
    public function rgb(): string
    {
        return $this->color->getRgbaString();
    }

    /**
     * @return string
     */
    public function hsl(): string
    {
        return $this->color->getHslaString();
    }

    /**
     * @return string
     */
    public function hex(): string
    {
        return $this->color->getHexString();
    }

    /**
     * @param ColorHelper $color
     * @param int $weight
     * @return ColorHelper
     */
    public function mix(self $color, int $weight = 50): self
    {
        return new self($this->color->withMixedColor($color->getColor(), $weight));
    }

    /**
     * @param int $amount between 0 and 100
     *
     * @return ColorHelper
     */
    public function lighten(int $amount = self::DEFAULT_ADJUSTMENT): self
    {
        return new self($this->color->withAdjustedLightness($amount));
    }

    /**
     * @param int $amount between 0 and 100
     *
     * @return ColorHelper
     */
    public function darken(int $amount = self::DEFAULT_ADJUSTMENT): self
    {
        return new self($this->color->withAdjustedLightness(-1 * $amount));
    }

    /**
     * Adjust the value by rotating the hue angle of a color in either direction.
     *
     * @param int $amount degrees to rotate the color
     *
     * @return ColorHelper
     */
    public function spin(int $amount): self
    {
        return new self($this->color->withAdjustedHue($amount));
    }

    /**
     * @param int $amount to saturate the color
     *
     * @return ColorHelper
     */
    public function saturate(int $amount = self::DEFAULT_ADJUSTMENT): self
    {
        return new self($this->color->withAdjustedSaturation($amount));
    }

    /**
     * @param int $amount to desaturate the color
     *
     * @return ColorHelper
     */
    public function desaturate(int $amount = self::DEFAULT_ADJUSTMENT): self
    {
        return new self($this->color->withAdjustedSaturation(-1 * $amount));
    }

    /**
     * @param int $amount to desaturate the color
     *
     * @return ColorHelper
     */
    public function fadein(int $amount = self::DEFAULT_ADJUSTMENT): self
    {
        return new self($this->color->withAdjustedAlpha($amount));
    }

    /**
     * @param int $amount to desaturate the color
     *
     * @return ColorHelper
     */
    public function fadeout(int $amount = self::DEFAULT_ADJUSTMENT): self
    {
        return new self($this->color->withAdjustedAlpha(-1 * $amount));
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
