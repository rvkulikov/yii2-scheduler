<?php

namespace Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto;

use Rvkulikov\Yii2\Scheduler\Dto\JobDefinition;
use Rvkulikov\Yii2\Scheduler\Models\Job;

class MigrateJobsStruct
{
    public function __construct(
        public array $create,
        public array $update,
        public array $delete,
    ) {
    }

    /**
     * @return UpdateJobStruct[]
     */
    public function getUpdate(): array
    {
        return $this->update;
    }

    /**
     * @param UpdateJobStruct[] $update
     */
    public function setUpdate(array $update): void
    {
        $this->update = $update;
    }

    /**
     * @return JobDefinition[]
     */
    public function getCreate(): array
    {
        return $this->create;
    }

    /**
     * @param JobDefinition[] $create
     */
    public function setCreate(array $create): void
    {
        $this->create = $create;
    }

    /**
     * @return Job[]
     */
    public function getDelete(): array
    {
        return $this->delete;
    }

    /**
     * @param Job[] $delete
     */
    public function setDelete(array $delete): void
    {
        $this->delete = $delete;
    }
}