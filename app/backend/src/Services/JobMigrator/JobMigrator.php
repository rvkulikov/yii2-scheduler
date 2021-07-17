<?php

namespace Rvkulikov\Yii2\Scheduler\Services\JobMigrator;

use Rvkulikov\Yii2\Scheduler\Components\ConnectionLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Dto\JobDefinition;
use Rvkulikov\Yii2\Scheduler\Exceptions\InvalidModelException;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Builders\MigrationBuilderHard;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Builders\MigrationBuilderInterface;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Builders\MigrationBuilderSoft;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto\BuildSchedulesStruct;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto\MigrateJobsStruct;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\Dto\MigrateSchedulesStruct;
use Rvkulikov\Yii2\Scheduler\Services\JobRepository\JobRepositoryInterface;
use Rvkulikov\Yii2\Scheduler\Services\ScheduleRepository\ScheduleRepositoryInterface;
use Throwable;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;

class JobMigrator implements JobMigratorInterface
{
    public function __construct(
        private ConnectionLocatorInterface $connectionLocator,
        private JobRepositoryInterface $jobRepository,
        private ScheduleRepositoryInterface $scheduleRepository
    )
    {
    }

    /**
     * @param string          $strategy
     * @param JobDefinition[] $definitions
     *
     * @throws InvalidConfigException
     * @throws InvalidModelException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function migrate(string $strategy, array $definitions)
    {
        $class = match ($strategy) {
            self::STRATEGY_HARD => MigrationBuilderHard::class,
            self::STRATEGY_SOFT => MigrationBuilderSoft::class,
            default => throw new InvalidArgumentException("Unknown strategy $strategy")
        };

        /** @var MigrationBuilderInterface $builder */
        $builder = Yii::createObject($class);

        $this->connectionLocator->getConnection()->transaction(function () use ($definitions, $builder) {
            $jobsMigrate = $builder->buildJobs($definitions);
            $jobsBuild   = $this->migrateJobs($jobsMigrate);

            foreach ($jobsBuild as $build) {
                $scheduleMigrate = $builder->buildSchedules($build->getJob(), $build->getScheduleDefinitions());
                $this->migrateSchedules($scheduleMigrate);
            }
        });
    }

    /**
     * @param MigrateJobsStruct $struct
     *
     * @return BuildSchedulesStruct[]
     * @throws InvalidModelException
     * @throws StaleObjectException
     * @throws Throwable
     */
    protected function migrateJobs(MigrateJobsStruct $struct): array
    {
        foreach ($struct->getDelete() as $job) {
            $job->delete();
        }

        foreach ($struct->getCreate() as $definition) {
            $job      = $this->jobRepository->create($definition);
            $result[] = new BuildSchedulesStruct($job, $definition->getScheduleDefinitions());
        }

        foreach ($struct->getUpdate() as $update) {
            $job      = $this->jobRepository->update($update->getModel(), $update->getDefinition());
            $result[] = new BuildSchedulesStruct($job, $update->definition->getScheduleDefinitions());
        }

        return $result ?? [];
    }

    /**
     * @param MigrateSchedulesStruct $struct
     *
     * @throws InvalidModelException
     * @throws Throwable
     * @throws InvalidConfigException
     */
    private function migrateSchedules(MigrateSchedulesStruct $struct)
    {
        foreach ($struct->getDelete() as $schedule) {
            $schedule->delete();
        }

        foreach ($struct->getCreate() as $definition) {
            $this->scheduleRepository->create($definition);
        }

        foreach ($struct->getUpdate() as $update) {
            $this->scheduleRepository->update($update->getModel(), $update->getDefinition());
        }
    }
}