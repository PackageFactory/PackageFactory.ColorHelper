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
     * @param ColorInterface $color
     *
     * @return bool
     */
    public function equals(self $color): bool;

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
     * @param int $delta
     *
     * @return ColorInterface
     */
    public function withAdjustedLightness(int $delta): self;

    /**
     * @param int $delta
     *
     * @return ColorInterface
     */
    public function withAdjustedSaturation(int $delta): self;

    /**
     * @param int $delta
     *
     * @return ColorInterface
     */
    public function withAdjustedHue(int $delta): self;

    /**
     * @param int $delta
     *
     * @return ColorInterface
     */
    public function withAdjustedAlpha(int $delta): self;
}
