<?php

namespace Rvkulikov\Yii2\Scheduler\Helpers;

class EnsureOptions
{
    public function __construct(
        public ?string $scenario = null,
        public bool $validate = true,
        public bool $throw = true,
        public array $params = [],
    )
    {
    }
}