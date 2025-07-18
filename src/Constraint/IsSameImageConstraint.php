<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Constraint;

use Closure;
use Imagick;
use ImagickException;
use InvalidArgumentException;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use SplFileInfo;

/**
 * Asserts two images are equal using Imagick image compare.
 */
class IsSameImageConstraint extends Constraint
{
    private ?string $additionalInfo = null;

    /**
     * @param string|SplFileInfo|resource                                             $expectedImage
     * @param (Closure(Imagick $diff, Imagick $expected, Imagick $actual): void)|null $diffCallback a callback called after a difference was detected
     */
    public function __construct(private $expectedImage, private ?Closure $diffCallback = null)
    {
    }

    /**
     * @throws ExpectationFailedException
     */
    public function matches(mixed $other): bool
    {
        assert(is_string($other) || is_resource($other) || $other instanceof SplFileInfo);
        if (class_exists('Imagick') === false) {
            $this->fail($this->getFileName($other), "IsSameImageConstraint requires Imagick extension to be installed.");
        }

        try {
            $expected = $this->getImageObject($this->expectedImage);
            $actual   = $this->getImageObject($other);

            $expectedImageCount = $expected->getNumberImages();
            $actualImageCount   = $actual->getNumberImages();
            if ($expectedImageCount !== $actualImageCount) {
                $this->fail(
                    $this->getFileName($other),
                    "Expected an image count of " . $expectedImageCount . " but received count of " . $actualImageCount,
                );
            }

            $success = true;
            for ($index = 0; $index < $expectedImageCount; ++$index) {
                $expected->setIteratorIndex($index);
                $actual->setIteratorIndex($index);

                $success = $this->compareImages($expected, $actual);
                if ($success === false) {
                    break;
                }
            }
        } catch (ImagickException $e) {
            $this->fail($this->getFileName($other), "Image compare failed due to Imagick error: " . $e->getMessage());
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return "matches " . $this->getFileName($this->expectedImage);
    }

    /**
     * @inheritDoc
     */
    public function additionalFailureDescription(mixed $other): string
    {
        return $this->additionalInfo ?? parent::additionalFailureDescription($other);
    }

    /**
     * @param string|SplFileInfo|resource $data
     *
     * @throws ImagickException
     */
    private function getImageObject($data): Imagick
    {
        if ($data instanceof SplFileInfo) {
            // create resource from file
            $handle = fopen($data->getPathname(), 'rb');
            assert(is_resource($handle));
        } elseif (is_string($data)) {
            // create resource from data string
            $handle = fopen('php://memory', 'rb+');
            assert(is_resource($handle));
            fwrite($handle, $data);
            fseek($handle, 0);
        } else {
            $handle = $data;
        }

        $image = new Imagick();
        $image->readImageFile($handle);
        $image->resetIterator();

        return $image;
    }

    /**
     * @throws ImagickException
     */
    private function compareImages(Imagick $baseImage, Imagick $generatedImage): bool
    {
        [$imageDiff, $difference] = $baseImage->compareImages($generatedImage, 1);
        if ($difference === 0.0) {
            return true;
        }

        // call callback if present
        if ($this->diffCallback !== null) {
            ($this->diffCallback)($imageDiff, $baseImage, $generatedImage);
        }

        // report findings
        $this->additionalInfo = 'Imagick found a difference of ' . $difference . '.';

        return false;
    }

    /**
     * @param string|SplFileInfo|resource $data
     */
    private function getFileName($data): string
    {
        if ($data instanceof SplFileInfo) {
            return $data->getPathname();
        }

        if (is_resource($data)) {
            return stream_get_meta_data($data)['uri'] ?? throw new InvalidArgumentException('Input resource doesn\'t have stream uri');
        }

        return 'binary-data-stream';
    }
}
