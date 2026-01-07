<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Mock;

use DR\PHPUnitExtensions\Mock\ConsecutiveParameters;
use DR\PHPUnitExtensions\Tests\Resources\Mock\ConsecutiveMock;
use DR\PHPUnitExtensions\Tests\Resources\Mock\MockInterface;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function DR\PHPUnitExtensions\Mock\consecutive;

/**
 * @covers \DR\PHPUnitExtensions\Mock\ConsecutiveParameters
 * @covers \DR\PHPUnitExtensions\Mock\consecutive
 */
#[CoversClass(ConsecutiveParameters::class)]
#[CoversFunction('DR\PHPUnitExtensions\Mock\consecutive')]
class ConsecutiveTest extends TestCase
{
    public function testConsecutiveSingle(): void
    {
        $mock = $this->createMock(MockInterface::class);
        $mock->expects(self::exactly(2))
            ->method('myMethodA')
            ->with(...consecutive([123], [456]));

        $consecutiveMock = new ConsecutiveMock($mock);
        $consecutiveMock->myMethodA(123);
        $consecutiveMock->myMethodA(456);
    }

    public function testConsecutiveDoubleArguments(): void
    {
        $mock = $this->createMock(MockInterface::class);
        $mock->expects(self::exactly(2))
            ->method('myMethodB')
            ->with(...consecutive([123, 'Sherlock'], [456, 'Watson']));

        $consecutiveMock = new ConsecutiveMock($mock);
        $consecutiveMock->myMethodB(123, 'Sherlock');
        $consecutiveMock->myMethodB(456, 'Watson');
    }

    public function testConsecutiveUnevenArguments(): void
    {
        $mock = $this->createMock(MockInterface::class);
        $mock->expects(self::exactly(4))
            ->method('myMethodB')
            ->with(...consecutive([123], [456, 'Watson'], [111], [222]));

        $consecutiveMock = new ConsecutiveMock($mock);
        $consecutiveMock->myMethodB(123, 'Sherlock');
        $consecutiveMock->myMethodB(456, 'Watson');
        $consecutiveMock->myMethodB(111, 'Mycroft');
        $consecutiveMock->myMethodB(222, 'Moriarty');
    }

    public function testConsecutiveMinimumOfOneArguments(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('consecutive() is expecting at least 1 or more arguments for invocation');
        consecutive([], []);
    }

    public function testConsecutiveInvokeMoreThanExpected(): void
    {
        $callbacks = consecutive([123], [456]);
        $callback  = $callbacks[0];
        $callback->evaluate(123);
        $callback->evaluate(456);

        try {
            $callback->evaluate(789);
            $assertionFailed = false;
        } catch (ExpectationFailedException) {
            $assertionFailed = true;
        }
        static::assertTrue($assertionFailed);
    }
}
