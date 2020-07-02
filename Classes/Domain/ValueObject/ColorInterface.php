<?php

declare(strict_types=1);

namespace PackageFactory\ColorHelper\Domain\ValueObject;

interface ColorInterface
{
    /**
     * @return RgbaColor
     */
    public function asRgba(): RgbaColor;

    /**
     * @return HslaColor
     */
    public function asHsla(): HslaColor;

    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return string
     */
    public function getHexString(): string;

    /**
     * @return string
     */
    public function getHslaString(): string;

    /**
     * @return string
     */
    public function getRgbaString(): string;

    /**
     * @param ColorInterface $color
     * @param int            $weight 0 ... 100
     *
     * @return ColorInterface
     */
    public function withMixedColor(self $color, int $weight): self;

    /**
     * @param float $delta
     *
     * @return ColorInterface
     */
    public function withAdjustedLightness(float $delta): self;

    /**
     * @param float $delta
     *
     * @return ColorInterface
     */
    public function withAdjustedSaturation(float $delta): self;

    /**
     * @param float $delta
     *
     * @return ColorInterface
     */
    public function withAdjustedHue(float $delta): self;

    /**
     * @param float $delta 0..100
     *
     * @return ColorInterface
     */
    public function withAdjustedAlpha(float $delta): self;
}
