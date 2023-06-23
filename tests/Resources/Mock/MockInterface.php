<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Resources\Mock;

interface MockInterface
{
    public function myMethodA(int $first): void;

    public function myMethodB(int $first, string $second): void;
}
