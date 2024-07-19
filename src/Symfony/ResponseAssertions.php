<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Symfony;

use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResponseAssertions
{
    /**
     * @param mixed[] $expected
     */
    protected static function assertJsonResponse(array $expected, JsonResponse $response): void
    {
        Assert::assertNotFalse($response->getContent());

        $content = json_decode($response->getContent(), true);
        Assert::assertSame($expected, $content);
    }

    protected static function assertResponse(Response $response, int $expectedStatusCode, ?string $expectedMessage = null): void
    {
        self::assertStatusCode($response, $expectedStatusCode);

        if ($expectedMessage !== null) {
            self::assertResponseMessage($response, $expectedMessage);
        }
    }

    protected static function assertResponseIsSuccessful(Response $response, ?string $expectedMessage = null): void
    {
        self::assertResponse($response, Response::HTTP_OK, $expectedMessage);
    }

    protected static function assertResponseIsRedirect(Response $response, ?string $expectedMessage = null): void
    {
        self::assertResponse($response, Response::HTTP_MOVED_PERMANENTLY, $expectedMessage);
    }

    protected static function assertResponseIsBadRequest(Response $response, ?string $expectedMessage = null): void
    {
        self::assertResponse($response, Response::HTTP_BAD_REQUEST, $expectedMessage);
    }

    protected static function assertResponseIsServerError(Response $response, ?string $expectedMessage = null): void
    {
        self::assertResponse($response, Response::HTTP_INTERNAL_SERVER_ERROR, $expectedMessage);
    }

    private static function assertStatusCode(Response $response, int $expectedStatusCode): void
    {
        Assert::assertSame(
            $expectedStatusCode,
            $response->getStatusCode(),
            sprintf('Expected status code %d but got %d.', $expectedStatusCode, $response->getStatusCode())
        );
    }

    private static function assertResponseMessage(Response $response, string $expectedMessage): void
    {
        Assert::assertSame($expectedMessage, $response->getContent());
    }
}
