<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Unit\Symfony;

use DR\PHPUnitExtensions\Symfony\ResponseAssertions;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @covers \DR\PHPUnitExtensions\Symfony\ResponseAssertions
 */
class ResponseAssertionsTest extends TestCase
{
    use ResponseAssertions;

    public function testAssertJsonResponse(): void
    {
        $expected = ['foo' => 'bar'];
        $response = new JsonResponse($expected);

        self::assertJsonResponse($expected, $response);
    }

    public function testAssertJsonResponseFails(): void
    {
        $expected = ['foo' => 'bar'];
        $response = new JsonResponse(['bar' => 'foo']);

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that two arrays are identical.');
        self::assertJsonResponse($expected, $response);
    }

    public function testAssertJsonResponseFalse(): void
    {
        $expected = ['foo' => 'bar'];
        $response = new JsonResponse(false);

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that false is identical to Array');
        self::assertJsonResponse($expected, $response);
    }
}
