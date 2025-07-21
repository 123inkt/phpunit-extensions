<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Renderer;

use Imagick;
use ImagickException;

/**
 * Renders the results IsSameImageConstraint difference to local html file. Can be enabled by setting the `PHPUNIT_EXTENSIONS_IMAGE_DIFF_OUTPUT_PATH`
 */
class ImageDiffRenderer implements ImageDiffRendererInterface
{
    private readonly ?string $outputPath;

    public function __construct(?string $outputPath = null)
    {
        $this->outputPath = $outputPath ?? $_SERVER['PHPUNIT_EXTENSIONS_IMAGE_DIFF_OUTPUT_PATH'] ?? null;
    }

    /**
     * @throws ImagickException
     */
    public function render(Imagick $diff, Imagick $expected, Imagick $actual): ?string
    {
        if ($this->outputPath === null) {
            return null;
        }

        // create directory
        if (is_dir($this->outputPath) === false) {
            mkdir($this->outputPath, 0777, true);
        }

        // write images and html
        $diff->writeImage($this->outputPath . '/diff.png');
        $expected->writeImage($this->outputPath . '/expected.png');
        $actual->writeImage($this->outputPath . '/actual.png');
        file_put_contents($this->outputPath . '/index.html', $this->createHtml());

        return 'View the difference at ' . $this->outputPath . '/index.html';
    }

    private function createHtml(): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        fieldset {
            float: left;
        }

        fieldset > img {
            display: block;
            max-width: 400px;
        }
    </style>
    <title>IsSameImageConstraint output</title>
</head>
<body>
<fieldset>
    <legend>Expected</legend>
    <img src="expected.png" alt="expected">
</fieldset>
<fieldset>
    <legend>Actual</legend>
    <img src="actual.png" alt="actual">
</fieldset>
<fieldset>
    <legend>Difference</legend>
    <img src="diff.png" alt="diff">
</fieldset>
</body>
</html>
HTML;
    }
}
