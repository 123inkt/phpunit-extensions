<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Renderer;

use Imagick;
use ImagickException;

/**
 * Renders the results IsSameImageConstraint difference to local html file. Can be enabled by setting the `PHPUNIT_EXTENSIONS_IMAGE_DIFF_OUTPUT_PATH`
 * Optionally an output url can be set which will be used to notify the user where to find the rendered diff.
 */
class ImageDiffRenderer implements ImageDiffRendererInterface
{
    private readonly ?string $outputPath;
    private readonly ?string $outputUrl;

    public function __construct(?string $outputPath = null, ?string $outputUrl = null)
    {
        $outputPath ??= $_SERVER['PHPUNIT_EXTENSIONS_IMAGE_DIFF_OUTPUT_PATH'] ?? null;
        assert(is_string($outputPath) || $outputPath === null, 'PHPUNIT_EXTENSIONS_IMAGE_DIFF_OUTPUT_PATH must be a string or null');
        $this->outputPath = $outputPath !== null ? rtrim($outputPath, '/\\') : null;

        $outputUrl ??= $_SERVER['PHPUNIT_EXTENSIONS_IMAGE_DIFF_OUTPUT_URL'] ?? null;
        assert(is_string($outputUrl) || $outputUrl === null, 'PHPUNIT_EXTENSIONS_IMAGE_DIFF_OUTPUT_URL must be a string or null');
        $this->outputUrl = $outputUrl !== null ? rtrim($outputUrl, '/') : null;
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

        $expected->setImageFormat('png');
        $actual->setImageFormat('png');
        $diff->setImageFormat('png');

        // render html
        $replaces = [
            '{{ expected }}' => base64_encode($expected->getImageBlob()),
            '{{ actual }}'   => base64_encode($actual->getImageBlob()),
            '{{ diff }}'     => base64_encode($diff->getImageBlob()),
        ];
        $html     = str_replace(array_keys($replaces), array_values($replaces), $this->createHtml());
        file_put_contents($this->outputPath . DIRECTORY_SEPARATOR . 'diff.html', $html);

        return 'View the differences at ' . ($this->outputUrl === null ? realpath($this->outputPath) : $this->outputUrl) . '/diff.html';
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
    <img src="data:image/png;base64,{{ expected }}" alt="expected">
</fieldset>
<fieldset>
    <legend>Actual</legend>
    <img src="data:image/png;base64,{{ actual }}" alt="actual">
</fieldset>
<fieldset>
    <legend>Difference</legend>
    <img src="data:image/png;base64,{{ diff }}" alt="diff">
</fieldset>
</body>
</html>
HTML;
    }
}
