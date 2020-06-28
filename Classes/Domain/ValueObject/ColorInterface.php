<?php
declare(strict_types=1);

namespace PackageFactory\ColorHelper\Domain\ValueObject;

interface ColorInterface {

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
     * @return bool
     */
    public function equals(ColorInterface $color): bool;

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
     * @param int $weight 0 ... 100
     * @return ColorInterface
     */
    public function withMixedColor(ColorInterface $color, int $weight): ColorInterface;

    /**
     * @param int $delta
     * @return ColorInterface
     */
    public function withAdjustedLightness(int $delta): ColorInterface;

    /**
     * @param int $delta
     * @return ColorInterface
     */
    public function withAdjustedSaturation(int $delta): ColorInterface;

    /**
     * @param int $delta
     * @return ColorInterface
     */
    public function withAdjustedHue(int $delta): ColorInterface;

    /**
     * @param int $delta
     * @return ColorInterface
     */
    public function withAdjustedAlpha(int $delta): ColorInterface;
}
