<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Mock;

use InvalidArgumentException;
use PHPUnit\Framework\Constraint\Callback;

/**
 * @param array<mixed> $firstInvocationArguments A list of arguments for each invocation that will be asserted against.
 *                                               Will fail on: too many invocations or if the argument doesn't match for each invocation.
 * @param array<mixed> $secondInvocationArguments
 * @param array<mixed> ...$expectedArgumentList
 *
 * @note    Full qualified name to appease to phpstorm inspection gods
 *  phpcs:ignore
 * @return \PHPUnit\Framework\Constraint\Callback<mixed>[]
 * @example <code>->with(...consecutive([5, 'foo'], [6, 'bar']))</code>
 */
function consecutive(array $firstInvocationArguments, array $secondInvocationArguments, array ...$expectedArgumentList): array
{
    array_unshift($expectedArgumentList, $secondInvocationArguments);
    array_unshift($expectedArgumentList, $firstInvocationArguments);

    // reorganize arguments per argument index
    $argumentsByIndex = [];
    foreach ($expectedArgumentList as $invocation => $expectedArguments) {
        if (count($expectedArguments) === 0) {
            throw new InvalidArgumentException('consecutive() is expecting at least 1 or more arguments for invocation #' . ((int)$invocation + 1));
        }

        foreach ($expectedArguments as $index => $argument) {
            $argumentsByIndex[$index][] = $argument;
        }
    }

    $callbacks = [];
    foreach ($argumentsByIndex as $arguments) {
        $constraint  = new ConsecutiveParameters($arguments);
        $callbacks[] = new Callback(static fn($actualArgument): bool => $constraint->evaluate($actualArgument));
    }

    return $callbacks;
}
