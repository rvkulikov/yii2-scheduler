<?php

namespace Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto;

use Rvkulikov\Yii2\Scheduler\Dto\ScheduleDefinition;
use Rvkulikov\Yii2\Scheduler\Models\Schedule;

class MigrateSchedulesStruct
{
    public function __construct(
        public array $create,
        public array $update,
        public array $delete,
    )
    {
    }

    /**
     * @return UpdateScheduleStruct[]
     */
    public function getUpdate(): array
    {
        return $this->update;
    }

    /**
     * @param UpdateScheduleStruct[] $update
     */
    public function setUpdate(array $update): void
    {
        $this->update = $update;
    }

    /**
     * @return ScheduleDefinition[]
     */
    public function getCreate(): array
    {
        return $this->create;
    }

    /**
     * @param ScheduleDefinition[] $create
     */
    public function setCreate(array $create): void
    {
        $this->create = $create;
    }

    /**
     * @return Schedule[]
     */
    public function getDelete(): array
    {
        return $this->delete;
    }

    /**
     * @param Schedule[] $delete
     */
    public function setDelete(array $delete): void
    {
        $this->delete = $delete;
    }
}