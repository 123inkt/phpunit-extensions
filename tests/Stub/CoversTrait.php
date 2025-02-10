<?php

declare(strict_types=1);

namespace PHPUnit\Framework\Attributes;

use Attribute;

if (class_exists(CoversTrait::class) === false) {
    #[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
    class CoversTrait
    {
    }
}
