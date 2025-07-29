<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Unit\Renderer;

use DR\PHPUnitExtensions\Renderer\ImageDiffRenderer;
use Imagick;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

#[CoversClass(ImageDiffRenderer::class)]
#[RequiresPhpExtension('imagick')]
class ImageDiffRendererTest extends TestCase
{
    private string $path;
    private ImageDiffRenderer $renderer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->path     = vfsStream::setup()->url();
        $this->renderer = new ImageDiffRenderer($this->path . '/pdf');
    }

    public function testRender(): void
    {
        $diff = $this->createMock(Imagick::class);
        $diff->expects(static::once())->method('setImageFormat')->with('png');
        $diff->expects(static::once())->method('getImageBlob')->willReturn('diff');

        $expected = $this->createMock(Imagick::class);
        $expected->expects(static::once())->method('setImageFormat')->with('png');
        $expected->expects(static::once())->method('getImageBlob')->willReturn('expected');

        $actual = $this->createMock(Imagick::class);
        $actual->expects(static::once())->method('setImageFormat')->with('png');
        $actual->expects(static::once())->method('getImageBlob')->willReturn('actual');

        $result = $this->renderer->render($diff, $expected, $actual);
        static::assertSame('View the differences at ' . $this->path . '/pdf/diff.html', $result);
        static::assertFileExists($this->path . '/pdf/diff.html');
    }

    public function testRenderShouldSkipOnAbsentOutputPath(): void
    {
        $diff = $this->createMock(Imagick::class);
        $diff->expects(static::never())->method('setImageFormat');

        $expected = $this->createMock(Imagick::class);
        $expected->expects(static::never())->method('setImageFormat');

        $actual = $this->createMock(Imagick::class);
        $actual->expects(static::never())->method('setImageFormat');

        $result = (new ImageDiffRenderer())->render($diff, $expected, $actual);
        static::assertNull($result);
        static::assertFileDoesNotExist($this->path . '/pdf/diff.html');
    }

    public function testRenderShouldWithOutputUrl(): void
    {
        $diff = $this->createMock(Imagick::class);
        $diff->expects(static::once())->method('setImageFormat')->with('png');
        $diff->expects(static::once())->method('getImageBlob')->willReturn('diff');

        $expected = $this->createMock(Imagick::class);
        $expected->expects(static::once())->method('setImageFormat')->with('png');
        $expected->expects(static::once())->method('getImageBlob')->willReturn('expected');

        $actual = $this->createMock(Imagick::class);
        $actual->expects(static::once())->method('setImageFormat')->with('png');
        $actual->expects(static::once())->method('getImageBlob')->willReturn('actual');

        $result = (new ImageDiffRenderer($this->path . '/pdf', 'https://example.com/'))->render($diff, $expected, $actual);
        static::assertSame('View the differences at https://example.com/diff.html', $result);
    }
}
