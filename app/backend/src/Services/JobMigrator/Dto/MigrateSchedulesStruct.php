<?php

namespace Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto;

use Rvkulikov\Yii2\Scheduler\Dto\ScheduleDefinition;
use Rvkulikov\Yii2\Scheduler\Models\Schedule;

/**
 * @property UpdateScheduleStruct[] $update
 * @property ScheduleDefinition[]   $create
 * @property Schedule[]             $delete
 */
class MigrateSchedulesStruct
{
    public function __construct(
        public array $create,
        public array $update,
        public array $delete,
    )
    {
    }
}