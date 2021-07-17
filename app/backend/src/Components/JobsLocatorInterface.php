<?php

namespace Rvkulikov\Yii2\Scheduler\Components;

use Rvkulikov\Yii2\Scheduler\Dto\JobDefinition;

interface JobsLocatorInterface
{
    public function getPreprocessor(): ?callable;

    public function setPreprocessor(?callable $preprocessor): void;

    /**
     * @return JobDefinition[]
     */
    public function getDefinitions(): array;

    /**
     * @param array[]|JobDefinition[] $definitions
     *
     * @return $this
     */
    public function setDefinitions(array $definitions): static;
}