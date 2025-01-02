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
     * Override this method to set your own custom fix time freeze.
     */
    protected function freezeTimeAt(): int
    {
        return 1634050575; // tuesday, 12-10-2021 16:56:15 +02:00
    }

    protected static function time(): int
    {
        return Clock::get()->now()->getTimestamp();
    }

    protected static function now(): DateTimeImmutable
    {
        return Clock::get()->now();
    }

    protected static function sleep(int|float $seconds): void
    {
        Clock::get()->sleep($seconds);
    }
}
