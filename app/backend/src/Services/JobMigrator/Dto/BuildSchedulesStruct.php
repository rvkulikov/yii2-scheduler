<?php
namespace Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto;

use Rvkulikov\Yii2\Scheduler\Dto\ScheduleDefinition;
use Rvkulikov\Yii2\Scheduler\Models\Job;

class BuildSchedulesStruct
{
    public function __construct(
        public Job $job,
        public array $scheduleDefinitions
    )
    {
    }

    /**
     * @return ScheduleDefinition[]
     */
    public function getScheduleDefinitions(): array
    {
        return $this->scheduleDefinitions;
    }

    public function setScheduleDefinitions(array $scheduleDefinitions): void
    {
        $this->scheduleDefinitions = $scheduleDefinitions;
    }

    public function getJob(): Job
    {
        return $this->job;
    }

    public function setJob(Job $job): void
    {
        $this->job = $job;
    }
}