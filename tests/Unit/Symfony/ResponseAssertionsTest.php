<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Unit\Symfony;

use DR\PHPUnitExtensions\Symfony\ResponseAssertions;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(ResponseAssertions::class)]
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

    #[TestWith([200, true])]
    #[TestWith([404, false])]
    public function testAssertStatusCode(int $statusCode, bool $shouldPass): void
    {
        $response = new Response('', 200);

        if ($shouldPass === false) {
            $this->expectException(AssertionFailedError::class);
        }
        self::assertStatusCode($response, $statusCode);
    }

    #[TestWith(['This is a test message', true])]
    #[TestWith(['Different message', false])]
    public function testAssertResponseMessage(string $messageContent, bool $shouldPass): void
    {
        $response        = new Response($messageContent);
        $expectedMessage = 'This is a test message';

        if ($shouldPass === false) {
            $this->expectException(AssertionFailedError::class);
        }
        self::assertResponseMessage($response, $expectedMessage);
    }

    #[TestWith([200, 'This is a test message', true])]
    #[TestWith([404, 'This is a test message', false])]
    public function testAssertResponseDifferentCode(int $statusCode, ?string $messageContent, bool $shouldPass): void
    {
        $response = new Response('This is a test message', 200);

        if ($shouldPass === false) {
            $this->expectException(AssertionFailedError::class);
        }

        self::assertResponse($response, $statusCode, $messageContent);
    }

    #[TestWith([200, null, true])]
    #[TestWith([200, 'Expected message', true])]
    #[TestWith([404, null, false])]
    #[TestWith([200, 'Unexpected message', false])]
    public function testAssertResponseIsSuccessful(int $statusCode, ?string $expectedMessage, bool $shouldPass): void
    {
        $response = new Response('Expected message', $statusCode);

        if ($shouldPass === false) {
            $this->expectException(AssertionFailedError::class);
        }

        self::assertResponseIsSuccessful($response, $expectedMessage);
    }

    #[TestWith([302, null, true])]
    #[TestWith([302, 'Expected message', true])]
    #[TestWith([200, null, false])]
    #[TestWith([302, 'Unexpected message', false])]
    public function testAssertResponseIsRedirect(int $statusCode, ?string $expectedMessage, bool $shouldPass): void
    {
        $response = new Response('Expected message', $statusCode);

        if ($shouldPass === false) {
            $this->expectException(AssertionFailedError::class);
        }
        self::assertResponseIsRedirect($response, $expectedMessage);
    }

    #[TestWith([400, null, true])]
    #[TestWith([400, 'Expected message', true])]
    #[TestWith([200, null, false])]
    #[TestWith([400, 'Unexpected message', false])]
    public function testAssertResponseIsClientError(int $statusCode, ?string $expectedMessage, bool $shouldPass): void
    {
        $response = new Response('Expected message', $statusCode);

        if ($shouldPass === false) {
            $this->expectException(AssertionFailedError::class);
        }
        self::assertResponseIsClientError($response, $expectedMessage);
    }

    #[TestWith([500, null, true])]
    #[TestWith([500, 'Expected message', true])]
    #[TestWith([200, null, false])]
    #[TestWith([500, 'Unexpected message', false])]
    public function testAssertResponseIsServerError(int $statusCode, ?string $expectedMessage, bool $shouldPass): void
    {
        $response = new Response('Expected message', $statusCode);

        if ($shouldPass === false) {
            $this->expectException(AssertionFailedError::class);
        }
        self::assertResponseIsServerError($response, $expectedMessage);
    }
}
