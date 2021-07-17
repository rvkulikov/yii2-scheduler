<?php

namespace Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Builders;

use Rvkulikov\Yii2\Scheduler\Dto\JobDefinition;
use Rvkulikov\Yii2\Scheduler\Dto\ScheduleDefinition;
use Rvkulikov\Yii2\Scheduler\Exceptions\InvalidModelException;
use Rvkulikov\Yii2\Scheduler\Models\Job;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto\MigrateJobsStruct;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto\MigrateSchedulesStruct;
use yii\base\InvalidConfigException;

interface MigrationBuilderInterface
{
    /**
     * @param JobDefinition[] $definitions
     *
     * @return MigrateJobsStruct
     * @throws InvalidModelException
     * @throws InvalidConfigException
     */
    public function buildJobs(array $definitions): MigrateJobsStruct;

    /**
     * @param Job                  $job
     * @param ScheduleDefinition[] $definitions
     *
     * @return MigrateSchedulesStruct
     * @throws InvalidModelException
     * @throws InvalidConfigException
     */
    public function buildSchedules(Job $job, array $definitions): MigrateSchedulesStruct;
}