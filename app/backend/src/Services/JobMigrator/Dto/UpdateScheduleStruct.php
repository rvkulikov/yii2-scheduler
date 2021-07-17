<?php

namespace Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto;

use Rvkulikov\Yii2\Scheduler\Dto\ScheduleDefinition;
use Rvkulikov\Yii2\Scheduler\Models\Schedule;

class UpdateScheduleStruct
{
    public function __construct(
        public Schedule $model,
        public ScheduleDefinition $definition,
    ) {
    }

    public function getModel(): Schedule
    {
        return $this->model;
    }

    public function setModel(Schedule $model): void
    {
        $this->model = $model;
    }

    public function getDefinition(): ScheduleDefinition
    {
        return $this->definition;
    }

    public function setDefinition(ScheduleDefinition $definition): void
    {
        $this->definition = $definition;
    }
}