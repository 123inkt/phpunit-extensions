<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Renderer;

use Imagick;

interface ImageDiffRendererInterface
{
    /**
     * @param Imagick $diff     the resulting image that contains the differences
     * @param Imagick $expected the image of the expected result
     * @param Imagick $actual   the image of the actual result
     *
     * @return string|null if a string, will be added to the additional info of the failing assertion message.
     */
    public function render(Imagick $diff, Imagick $expected, Imagick $actual): ?string;
}
