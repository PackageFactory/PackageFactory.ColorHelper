<?php
namespace PackageFactory\ColorHelper\Eel;

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\ColorHelper\Domain\ValueObject\ColorInterface;

class ColorHelper implements ProtectedContextAwareInterface
{

    /**
     * Default adjustments for color manipulations
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

    public function getColor(): ColorInterface
    {
        return $this->color;
    }

    public function __toString(): string
    {
        return $this->color->getHexString();
    }

    public function rgb(): string
    {
        return $this->color->getRgbaString();
    }

    public function hsl(): string
    {
        return $this->color->getHslaString();
    }

    public function hex(): string
    {
        return $this->color->getHexString();
    }

    /**
     * @param ColorHelper $color
     * @param int $weight between 0 and 100
     * @return string
     */
    public function mix(ColorHelper $color, int $weight = 50): self
    {
        return new self ($this->color->withMixedColor($color->getColor(), $weight));
    }

    /**
     * @param int $amount between 0 and 100
     * @return string
     */
    public function lighten(int $amount = self::DEFAULT_ADJUSTMENT ): self
    {
        return new self ($this->color->withAdjustedLightness($amount));
    }

    /**
     * @param int $amount between 0 and 100
     * @return string
     */
    public function darken(int $amount = self::DEFAULT_ADJUSTMENT ): self
    {
        return new self ($this->color->withAdjustedLightness(-1 * $amount));
    }


    /**
     * Adjust the value by rotating the hue angle of a color in either direction.
     *
     * @param int $amount degrees to rotate the color
     * @return string
     */
    public function spin(int $amount): self
    {
        return new self ($this->color->withAdjustedHue($amount));
    }

    /**
     * @param int $amount to saturate the color
     * @return string
     */
    public function saturate(int $amount = self::DEFAULT_ADJUSTMENT): self
    {
        return new self ($this->color->withAdjustedSaturation($amount));
    }

    /**
     * @param int $amount to desaturate the color
     * @return string
     */
    public function desaturate(int $amount = self::DEFAULT_ADJUSTMENT): self
    {
        return new self ($this->color->withAdjustedSaturation(-1 * $amount));
    }

    /**
     * @param int $amount to desaturate the color
     * @return string
     */
    public function fadein(int $amount = self::DEFAULT_ADJUSTMENT): self
    {
        return new self ($this->color->withAdjustedAlpha($amount));
    }

    /**
     * @param int $amount to desaturate the color
     * @return string
     */
    public function fadeout(int $amount = self::DEFAULT_ADJUSTMENT): self
    {
        return new self ($this->color->withAdjustedAlpha(-1 * $amount));
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
