<?php

namespace Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto;

use Rvkulikov\Yii2\Scheduler\Dto\JobDefinition;
use Rvkulikov\Yii2\Scheduler\Models\Job;

/**
 * @property UpdateJobStruct[] $update
 * @property JobDefinition[]   $create
 * @property Job[]             $delete
 */
class MigrateJobsStruct
{
    public function __construct(
        public array $create,
        public array $update,
        public array $delete,
    ) {
    }
}