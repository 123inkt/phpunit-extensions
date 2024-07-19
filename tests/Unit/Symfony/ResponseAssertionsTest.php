<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Unit\Symfony;

use DR\PHPUnitExtensions\Symfony\ResponseAssertions;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @dataProvider statusCodeProvider
     */
    public function testAssertStatusCode(int $statusCode, bool $shouldPass): void
    {
        $response = new Response('', 200);

        if ($shouldPass === false) {
            $this->expectException(AssertionFailedError::class);
        }
        self::assertStatusCode($response, $statusCode);
    }

    /**
     * @return array<int, array{int, bool}>
     */
    public static function statusCodeProvider(): array
    {
        return [
            [200, true],
            [404, false],
        ];
    }

    /**
     * @dataProvider responseMessageProvider
     */
    public function testAssertResponseMessage(string $messageContent, bool $shouldPass): void
    {
        $response = new Response($messageContent);
        $expectedMessage = 'This is a test message';

        if ($shouldPass === false) {
            $this->expectException(AssertionFailedError::class);
        }
        self::assertResponseMessage($response, $expectedMessage);
    }

    /**
     * @return array<int, array{string, bool}>
     */
    public static function responseMessageProvider(): array
    {
        return [
            ['This is a test message', true],
            ['Different message', false],
        ];
    }

    /**
     * @dataProvider assertResponseProvider
     */
    public function testAssertResponseDifferentCode(int $statusCode, ?string $messageContent, bool $shouldPass): void
    {
        $response = new Response('This is a test message', 200);

        if ($shouldPass === false) {
            $this->expectException(AssertionFailedError::class);
        }

        self::assertResponse($response, $statusCode, $messageContent);
    }

    /**
     * @return array<int, array{int, ?string, bool}>
     */
    public static function assertResponseProvider(): array
    {
        return [
            [200, 'This is a test message', true],
            [404, 'This is a test message', false],
        ];
    }

    /**
     * @dataProvider responseIsSuccessfulProvider
     */
    public function testAssertResponseIsSuccessful(int $statusCode, ?string $expectedMessage, bool $shouldPass): void
    {
        $response = new Response('Expected message', $statusCode);

        if ($shouldPass === false) {
            $this->expectException(AssertionFailedError::class);
        }

        self::assertResponseIsSuccessful($response, $expectedMessage);
    }

    /**
     * @return array<int, array{int, ?string, bool}>
     */
    public static function responseIsSuccessfulProvider(): array
    {
        return [
            [200, null, true],
            [200, 'Expected message', true],
            [404, null, false],
            [200, 'Unexpected message', false],
        ];
    }

    /**
     * @dataProvider responseIsRedirectProvider
     */
    public function testAssertResponseIsRedirect(int $statusCode, ?string $expectedMessage, bool $shouldPass): void
    {
        $response = new Response('Expected message', $statusCode);

        if ($shouldPass === false) {
            $this->expectException(AssertionFailedError::class);
        }
        self::assertResponseIsRedirect($response, $expectedMessage);
    }

    /**
     * @return array<int, array{int, ?string, bool}>
     */
    public static function responseIsRedirectProvider(): array
    {
        return [
            [302, null, true],
            [302, 'Expected message', true],
            [200, null, false],
            [302, 'Unexpected message', false],
        ];
    }

    /**
     * @dataProvider responseIsBadRequestProvider
     */
    public function testAssertResponseIsBadRequest(int $statusCode, ?string $expectedMessage, bool $shouldPass): void
    {
        $response = new Response('Expected message', $statusCode);

        if ($shouldPass === false) {
            $this->expectException(AssertionFailedError::class);
        }
        self::assertResponseIsBadRequest($response, $expectedMessage);
    }

    /**
     * @return array<int, array{int, ?string, bool}>
     */
    public static function responseIsBadRequestProvider(): array
    {
        return [
            [400, null, true],
            [400, 'Expected message', true],
            [200, null, false],
            [400, 'Unexpected message', false],
        ];
    }

    /**
     * @dataProvider responseIsServerErrorProvider
     */
    public function testAssertResponseIsServerError(int $statusCode, ?string $expectedMessage, bool $shouldPass): void
    {
        $response = new Response('Expected message', $statusCode);

        if ($shouldPass === false) {
            $this->expectException(AssertionFailedError::class);
        }
        self::assertResponseIsServerError($response, $expectedMessage);
    }

    /**
     * @return array<int, array{int, ?string, bool}>
     */
    public static function responseIsServerErrorProvider(): array
    {
        return [
            [500, null, true],
            [500, 'Expected message', true],
            [200, null, false],
            [500, 'Unexpected message', false],
        ];
    }
}
