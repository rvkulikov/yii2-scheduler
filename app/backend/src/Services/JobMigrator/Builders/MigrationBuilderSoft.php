<?php

namespace Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Builders;

use Rvkulikov\Yii2\Scheduler\Components\DefinitionLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Dto\JobDefinition;
use Rvkulikov\Yii2\Scheduler\Dto\ScheduleDefinition as Definition;
use Rvkulikov\Yii2\Scheduler\Models\Job;
use Rvkulikov\Yii2\Scheduler\Models\Schedule;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto\MigrateJobsStruct;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto\MigrateSchedulesStruct;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto\UpdateJobStruct;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto\UpdateScheduleStruct;
use Rvkulikov\Yii2\Scheduler\Services\JobRepository\JobRepositoryInterface;
use Rvkulikov\Yii2\Scheduler\Services\ScheduleRepository\ScheduleRepositoryInterface;
use Yii;
use yii\helpers\ArrayHelper;
use function array_key_exists;
use function array_keys;

class MigrationBuilderSoft implements MigrationBuilderInterface
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
        $definitions = ArrayHelper::index($definitions, fn(JobDefinition $d) => $d->getAlias());

        $filter = Yii::createObject($this->definitionLocator->getJobFilterClass());
        $filter->setAlias(array_keys($definitions));

        $models = $this->jobRepository->all($filter);
        $models = ArrayHelper::index($models, fn(Job $j) => $j->getAlias());

        foreach ($models as $model) {
            if (array_key_exists($model->getAlias(), $definitions)) {
                $update[] = new UpdateJobStruct($model, $definitions[$model->getAlias()]);
            } else {
                $delete[] = $model;
            }
        }

        foreach ($definitions as $definition) {
            if (!array_key_exists($definition->getAlias(), $models)) {
                $create[] = $definition;
            }
        }

        return new MigrateJobsStruct(
            create: $create ?? [],
            update: $update ?? [],
            delete: $delete ?? [],
        );
    }

    public function buildSchedules(Job $job, array $definitions): MigrateSchedulesStruct
    {
        $class  = $this->definitionLocator->getScheduleClass();
        $filter = Yii::createObject($this->definitionLocator->getScheduleFilterClass());
        $filter->setJobAlias($job->getAlias());

        $user   = $this->scheduleRepository->all($filter->setCreatorAlias($class::CREATOR_USER));
        $system = $this->scheduleRepository->all($filter->setCreatorAlias($class::CREATOR_SYSTEM));

        $user        = ArrayHelper::index($user, fn(Schedule $d) => $d->getExpression());
        $system      = ArrayHelper::index($system, fn(Schedule $d) => $d->getExpression());
        $definitions = ArrayHelper::index($definitions, fn(Definition $d) => $d->getExpression());

        /** @var Schedule $schedule */
        foreach ($system as $schedule) {
            if (array_key_exists($schedule->getExpression(), $definitions)) {
                $update[] = new UpdateScheduleStruct(
                    model: $schedule,
                    definition: $definitions[$schedule->getExpression()]
                );
            } else {
                $delete[] = $schedule;
            }
        }

        /** @var Definition $definition */
        foreach ($definitions as $definition) {
            if (array_key_exists($definition->getExpression(), $user)) {
                // remove the user's in favor of the system's
                $delete[] = $user[$definition->getExpression()];
            }
            if (!array_key_exists($definition->getExpression(), $system)) {
                $create[] = $definition;
            }
        }

        return new MigrateSchedulesStruct(
            create: $create ?? [],
            update: $update ?? [],
            delete: $delete ?? [],
        );
    }
}