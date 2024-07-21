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

    protected static function assertResponse(Response $response, int $expectedStatusCode, ?string $expectedContent = null): void
    {
        self::assertStatusCode($response, $expectedStatusCode);

        if ($expectedContent !== null) {
            self::assertResponseContent($response, $expectedContent);
        }
    }

    protected static function assertResponseIsSuccessful(Response $response, ?string $expectedContent = null): void
    {
        self::assertTrue($response->isSuccessful(), 'Response is not successful.');

        if ($expectedContent !== null) {
            self::assertResponseContent($response, $expectedContent);
        }
    }

    protected static function assertResponseIsRedirection(Response $response, ?string $expectedRedirectionUrl = null): void
    {
        self::assertTrue($response->isRedirection(), 'Response is not a redirection.');

        if ($expectedRedirectionUrl !== null) {
            self::assertSame($response->headers->get('Location'), $expectedRedirectionUrl);
        }
    }

    protected static function assertResponseIsClientError(Response $response, ?string $expectedContent = null): void
    {
        self::assertTrue($response->isClientError(), 'Response is not a client error.');

        if ($expectedContent !== null) {
            self::assertResponseContent($response, $expectedContent);
        }
    }

    protected static function assertResponseIsServerError(Response $response, ?string $expectedContent = null): void
    {
        self::assertTrue($response->isServerError(), 'Response is not a server error.');

        if ($expectedContent !== null) {
            self::assertResponseContent($response, $expectedContent);
        }
    }

    private static function assertStatusCode(Response $response, int $expectedStatusCode): void
    {
        Assert::assertSame(
            $expectedStatusCode,
            $response->getStatusCode(),
            sprintf('Expected status code %d but got %d.', $expectedStatusCode, $response->getStatusCode())
        );
    }

    private static function assertResponseContent(Response $response, string $expectedContent): void
    {
        Assert::assertSame($expectedContent, $response->getContent());
    }
}
