<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Unit\Symfony;

use DR\PHPUnitExtensions\Symfony\ResponseAssertions;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[CoversTrait(ResponseAssertions::class)]
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

    #[DataProvider('statusCodeProvider')]
    public function testAssertStatusCode(int $statusCode, bool $shouldPass): void
    {
        $response = new Response('', Response::HTTP_OK);

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
            [Response::HTTP_OK, true],
            [Response::HTTP_NOT_FOUND, false],
        ];
    }

    #[DataProvider('responseContentProvider')]
    public function testAssertResponseContent(string $messageContent, bool $shouldPass): void
    {
        $response        = new Response($messageContent);
        $expectedMessage = 'This is a test message';

        if ($shouldPass === false) {
            $this->expectException(AssertionFailedError::class);
        }
        self::assertResponseContent($response, $expectedMessage);
    }

    /**
     * @return array<int, array{string, bool}>
     */
    public static function responseContentProvider(): array
    {
        return [
            ['This is a test message', true],
            ['Different message', false],
        ];
    }

    #[DataProvider('assertResponseProvider')]
    public function testAssertResponseDifferentCode(int $statusCode, ?string $messageContent, bool $shouldPass): void
    {
        $response = new Response('This is a test message', Response::HTTP_OK);

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
            [Response::HTTP_OK, 'This is a test message', true],
            [Response::HTTP_NOT_FOUND, 'This is a test message', false],
        ];
    }

    #[DataProvider('responseIsSuccessfulProvider')]
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
            [Response::HTTP_OK, null, true],
            [Response::HTTP_OK, 'Expected message', true],
            [Response::HTTP_NOT_FOUND, null, false],
            [Response::HTTP_OK, 'Unexpected message', false],
        ];
    }

    #[DataProvider('responseIsRedirectionProvider')]
    public function testAssertResponseIsRedirection(int $statusCode, ?string $expectedRedirectionUrl, bool $shouldPass): void
    {
        $response = new Response('', $statusCode);
        $response->headers->set('Location', 'http://expected.com');

        if ($shouldPass === false) {
            $this->expectException(AssertionFailedError::class);
        }
        self::assertResponseIsRedirection($response, $expectedRedirectionUrl);
    }

    /**
     * @return array<int, array{int, ?string, bool}>
     */
    public static function responseIsRedirectionProvider(): array
    {
        return [
            [Response::HTTP_MOVED_PERMANENTLY, null, true],
            [Response::HTTP_MOVED_PERMANENTLY, 'http://expected.com', true],
            [Response::HTTP_OK, null, false],
            [Response::HTTP_MOVED_PERMANENTLY, 'http://unexpected.com', false],
        ];
    }

    #[DataProvider('responseIsClientErrorProvider')]
    public function testAssertResponseIsClientError(int $statusCode, ?string $expectedMessage, bool $shouldPass): void
    {
        $response = new Response('Expected message', $statusCode);

        if ($shouldPass === false) {
            $this->expectException(AssertionFailedError::class);
        }
        self::assertResponseIsClientError($response, $expectedMessage);
    }

    /**
     * @return array<int, array{int, ?string, bool}>
     */
    public static function responseIsClientErrorProvider(): array
    {
        return [
            [Response::HTTP_BAD_REQUEST, null, true],
            [Response::HTTP_BAD_REQUEST, 'Expected message', true],
            [Response::HTTP_OK, null, false],
            [Response::HTTP_BAD_REQUEST, 'Unexpected message', false],
        ];
    }

    #[DataProvider('responseIsServerErrorProvider')]
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
            [Response::HTTP_INTERNAL_SERVER_ERROR, null, true],
            [Response::HTTP_INTERNAL_SERVER_ERROR, 'Expected message', true],
            [Response::HTTP_OK, null, false],
            [Response::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected message', false],
        ];
    }
}
