<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Resources\Mock;

class ConsecutiveMock
{
    public function __construct(private readonly MockInterface $myMock)
    {
    }

    public function myMethodA(int $first): void
    {
        $this->myMock->myMethodA($first);
    }

    public function myMethodB(int $first, string $second): void
    {
        $this->myMock->myMethodB($first, $second);
    }
}
