<?php

namespace Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto;

use Rvkulikov\Yii2\Scheduler\Dto\JobDefinition;
use Rvkulikov\Yii2\Scheduler\Models\Job;

class UpdateJobStruct
{
    public function __construct(
        public Job $model,
        public JobDefinition $definition,
    ) {
    }

    public function getModel(): Job
    {
        return $this->model;
    }

    public function setModel(Job $model): void
    {
        $this->model = $model;
    }

    public function getDefinition(): JobDefinition
    {
        return $this->definition;
    }

    public function setDefinition(JobDefinition $definition): void
    {
        $this->definition = $definition;
    }
}