<?php

namespace Rvkulikov\Yii2\Scheduler\Dto;

class JobDefinition
{
    public function __construct(
        public string $alias,
        public ?string $name = null,
        public ?string $description = null,
        public ?string $stateAlias = null,
        public array $scheduleDefinitions = [],
    )
    {
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getStateAlias(): ?string
    {
        return $this->stateAlias;
    }

    public function setStateAlias(?string $stateAlias): void
    {
        $this->stateAlias = $stateAlias;
    }

    public function getScheduleDefinitions(): array
    {
        return $this->scheduleDefinitions;
    }

    public function setScheduleDefinitions(array $scheduleDefinitions): void
    {
        $this->scheduleDefinitions = $scheduleDefinitions;
    }
}