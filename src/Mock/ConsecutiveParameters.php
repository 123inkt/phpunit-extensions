<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Mock;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @internal
 */
class ConsecutiveParameters
{
    private int $callIndex = 0;

    /**
     * @param array<int, mixed|Constraint> $expectedArguments
     */
    public function __construct(private readonly array $expectedArguments)
    {
    }

    /**
     * @throws ExpectationFailedException
     */
    public function evaluate(mixed $actualArgument): bool
    {
        if (array_key_exists($this->callIndex, $this->expectedArguments) === false) {
            throw new ExpectationFailedException(
                sprintf('consecutive was called %d times, but only received arguments for %d', $this->callIndex + 1, count($this->expectedArguments))
            );
        }

        // take the argument for the correct call index
        $expectedArgument = $this->expectedArguments[$this->callIndex];

        // evaluate the argument against the expected argument
        $constraint = $expectedArgument instanceof Constraint ? $expectedArgument : new IsEqual($expectedArgument);
        $constraint->evaluate($actualArgument);

        ++$this->callIndex;

        return true;
    }
}
