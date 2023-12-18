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
}
