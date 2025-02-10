<?php

declare(strict_types=1);

namespace PHPUnit\Framework\Attributes;

use Attribute;

if (class_exists(CoversTrait::class) === false) {
    #[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
    class CoversTrait
    {
        /**
         * @var trait-string
         */
        private string $traitName;

        /**
         * @param trait-string $traitName
         */
        public function __construct(string $traitName)
        {
            $this->traitName = $traitName;
        }

        /**
         * @return trait-string
         */
        public function traitName(): string
        {
            return $this->traitName;
        }
    }
}
