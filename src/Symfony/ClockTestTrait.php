<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Symfony;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Before;
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
}
