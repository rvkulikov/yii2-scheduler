<?php

namespace Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Builders;

use Rvkulikov\Yii2\Scheduler\Components\DefinitionLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Models\Job;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto\MigrateJobsStruct;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto\MigrateSchedulesStruct;
use Rvkulikov\Yii2\Scheduler\Services\JobRepository\JobRepositoryInterface;
use Rvkulikov\Yii2\Scheduler\Services\ScheduleRepository\ScheduleRepositoryInterface;
use Yii;

class MigrationBuilderHard implements MigrationBuilderInterface
{
    public function __construct(
        private DefinitionLocatorInterface $definitionLocator,
        private JobRepositoryInterface $jobRepository,
        private ScheduleRepositoryInterface $scheduleRepository,
    )
    {
    }

    public function buildJobs(array $definitions): MigrateJobsStruct
    {
        $models = $this->jobRepository->all();

        return new MigrateJobsStruct(
            create: $definitions ?? [],
            update: [],
            delete: $models ?? [],
        );
    }

    public function buildSchedules(Job $job, array $definitions): MigrateSchedulesStruct
    {
        $filter = Yii::createObject($this->definitionLocator->getScheduleFilterClass());
        $filter->setJobAlias($job->getAlias());
        $models = $this->scheduleRepository->all($filter);

        return new MigrateSchedulesStruct(
            create: $definitions ?? [],
            update: [],
            delete: $models ?? [],
        );
    }
}