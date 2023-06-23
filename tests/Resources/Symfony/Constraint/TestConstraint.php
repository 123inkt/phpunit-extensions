<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Resources\Symfony\Constraint;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class TestConstraint extends Constraint
{
    public string $message = 'TestConstraint';
}
