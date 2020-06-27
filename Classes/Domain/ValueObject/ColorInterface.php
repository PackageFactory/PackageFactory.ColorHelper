<?php
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
}
