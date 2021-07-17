<?php

namespace Rvkulikov\Yii2\Scheduler\Components;

use yii\base\BaseObject;

class JobsLocator extends BaseObject implements JobsLocatorInterface
{
    public function __construct(
        $config = [],
        protected array $definitions = []
    )
    {
        parent::__construct($config);
    }

    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    public function setDefinitions(array $definitions): static
    {
        $this->definitions = $definitions;
        return $this;
    }
}