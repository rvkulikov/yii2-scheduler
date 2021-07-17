<?php

namespace Rvkulikov\Yii2\Scheduler\Dto;

class ScheduleDefinition
{
    public function __construct(
        public string $jobAlias,
        public string $expression,
        public ?string $creatorAlias = null,
        public ?string $stateAlias = null,
    )
    {
    }

    public function getJobAlias(): string
    {
        return $this->jobAlias;
    }

    public function setJobAlias(string $jobAlias): void
    {
        $this->jobAlias = $jobAlias;
    }

    public function getExpression(): string
    {
        return $this->expression;
    }

    public function setExpression(string $expression): void
    {
        $this->expression = $expression;
    }

    public function getCreatorAlias(): ?string
    {
        return $this->creatorAlias;
    }

    public function setCreatorAlias(?string $creatorAlias): void
    {
        $this->creatorAlias = $creatorAlias;
    }

    public function getStateAlias(): ?string
    {
        return $this->stateAlias;
    }

    public function setStateAlias(?string $stateAlias): void
    {
        $this->stateAlias = $stateAlias;
    }
}