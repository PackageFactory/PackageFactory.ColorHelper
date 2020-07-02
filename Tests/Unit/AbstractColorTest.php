<?php
namespace PackageFactory\ColorHelper\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PackageFactory\ColorHelper\Domain\ValueObject\ColorInterface;
use PackageFactory\ColorHelper\Domain\ValueObject\HslaColor;
use PackageFactory\ColorHelper\Domain\ValueObject\RgbaColor;
use Symfony\Component\Yaml\Yaml;

abstract class AbstractColorTest extends TestCase
{
    /**
     * Return a set of colors as hls rgb and hex
     * @return array
     */
    public function getColorFixtures(): array
    {
        $yaml = Yaml::parseFile(__DIR__.'/Colors.yaml');
        array_walk(
            $yaml,
            function ($item, $name) {
                $item['name'] = $name;
            }
        );
        $yaml = array_values($yaml);
        $yaml = array_map(
            function ($item) {
                return [$item];
            },
            $yaml
        );

        return $yaml;
    }

    /**
     * Create a full range of rgb values to test the full color spectrum
     * @return array
     */
    public function rgbSpectrumDataProvider():array
    {
        $interval = 20;
        $testArgumentSets = [];
        for ($r = 0; $r < 256; $r+=$interval) {
            for ($g = 0; $g < 256; $g+=$interval) {
                for ($b = 0; $b < 256; $b+=$interval) {
                    $testArgumentSets[] = [$r,$g,$b];
                }
            }
        }
        return $testArgumentSets;
    }

    /**
     * Create a full range of hsl values to test the full color spectrum
     * @return array
     */
    public function hlsSpectrumDataProvider():array
    {
        $interval = 20;
        $start = 5;

        $testArgumentSets = [];
        for ($h = $start; $h < 360; $h+=$interval) {
            for ($l =$start; $l < 100; $l+=$interval) {
                for ($s = $start; $s < 100; $s+=$interval) {
                    $testArgumentSets[] = [$h, $l, $s];
                }
            }
        }

        // test the extremes
        $testArgumentSets[] = [0,0,0];
        $testArgumentSets[] = [0,0,100];

        return $testArgumentSets;
    }


    /**
     * @param ColorInterface $expected
     * @param ColorInterface $color
     */
    protected function assertSimilarColor(ColorInterface $expected, ColorInterface $color, string $message = null) {
        $this->addToAssertionCount(1);
        if ($color->isSimilarTo($expected) == false) {
            if ($this->expectecColor instanceof RgbaColor) {
                $this->fail( $message ?? $color->getRgbaString() . ' is not similar to ' . $expected->getRgbaString());
            } elseif ($this->expectecColor instanceof HslaColor) {
                $this->fail( $message ?? $color->getHslaString() . ' is not similar to ' . $expected->getHslaString());
            }
        }
    }

    /**
     * @param ColorInterface $expected
     * @param ColorInterface $color
     */
    protected function assertSameColor(ColorInterface $expected, ColorInterface $color, string $message = null) {
        $this->addToAssertionCount(1);
        if (get_class($expected) != get_class($color)) {
            $this->fail( $message ?? get_class($expected) . ' is not the same as ' . get_class($color));
        } elseif ($color->equals($expected) == false) {
            if ($this->expectecColor instanceof RgbaColor) {
                $this->fail( $message ?? $color->getRgbaString() . ' is not similar to ' . $expected->getRgbaString());
            } elseif ($this->expectecColor instanceof HslaColor) {
                $this->fail( $message ?? $color->getHslaString() . ' is not similar to ' . $expected->getHslaString());
            }
        }
    }
}
