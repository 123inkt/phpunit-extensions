<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Symfony;

use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\Clock\Clock;
use Symfony\Component\Clock\Test\ClockSensitiveTrait;

trait ClockTestTrait
{
    use ClockSensitiveTrait;

    #[Before]
    protected function freezeTime(): void
    {
        self::mockTime((new DateTimeImmutable())->setTimestamp($this->freezeTimeAt()));
    }

    /**
     * Override this method to set your own custom time freeze.
     */
    protected function freezeTimeAt(): int
    {
        return 1634050575; // tuesday, 12-10-2021 16:56:15 +02:00
    }

    /**
     * Returns the currently mocked time as timestamp
     */
    protected static function time(): int
    {
        return Clock::get()->now()->getTimestamp();
    }

    /**
     * Returns the currently mocked time as DateTimeImmutable object
     */
    protected static function now(): DateTimeImmutable
    {
        return Clock::get()->now();
    }

    /**
     * Sleep for a given amount of seconds. Use a float to sleep fractions of a second.
     */
    protected static function sleep(int|float $seconds): void
    {
        Clock::get()->sleep($seconds);
    }

    /**
     * Asserts that the give timestamp of DateTime is equal to now
     */
    protected static function assertNow(int|DateTimeInterface $actual, string $message = ''): void
    {
        self::assertSameTime(self::now(), $actual, $message);
    }

    /**
     * Asserts that two timestamps
     *
     * @throws ExpectationFailedException
     *
     * @phpstan-template ExpectedType
     * @phpstan-param ExpectedType  $expected
     * @phpstan-assert ExpectedType $actual
     */
    protected static function assertSameTime(int|DateTimeInterface $expected, int|DateTimeInterface $actual, string $message = ''): void
    {
        if ($expected instanceof DateTimeInterface) {
            $expected = $expected->getTimestamp();
        }
        if ($actual instanceof DateTimeInterface) {
            $actual = $actual->getTimestamp();
        }

        self::assertSame($expected, $actual, $message);
    }
}
