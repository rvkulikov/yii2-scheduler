<?php

use Rvkulikov\Yii2\Scheduler\Components\ConnectionLocator;
use Rvkulikov\Yii2\Scheduler\Components\ConnectionLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Components\DefinitionLocator;
use Rvkulikov\Yii2\Scheduler\Components\DefinitionLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Components\JobsLocator;
use Rvkulikov\Yii2\Scheduler\Components\JobsLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Components\SettingsLocator;
use Rvkulikov\Yii2\Scheduler\Components\SettingsLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Models\Job;
use Rvkulikov\Yii2\Scheduler\Models\Schedule;
use Rvkulikov\Yii2\Scheduler\Services\JobInvoker\JobInvoker;
use Rvkulikov\Yii2\Scheduler\Services\JobInvoker\JobInvokerInterface;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\JobMigrator;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\JobMigratorInterface;
use Rvkulikov\Yii2\Scheduler\Services\JobRepository\JobFilter;
use Rvkulikov\Yii2\Scheduler\Services\JobRepository\JobRepository;
use Rvkulikov\Yii2\Scheduler\Services\JobRepository\JobRepositoryInterface;
use Rvkulikov\Yii2\Scheduler\Services\ScheduleRepository\ScheduleFilter;
use Rvkulikov\Yii2\Scheduler\Services\ScheduleRepository\ScheduleRepository;
use Rvkulikov\Yii2\Scheduler\Services\ScheduleRepository\ScheduleRepositoryInterface;

return [
    'aliases'   => [
        '@Rvkulikov/Yii2/Scheduler' => dirname(__DIR__) . '/src'
    ],
    'container' => [
        'singletons' => [
            DefinitionLocatorInterface::class => [
                'class'               => DefinitionLocator::class,
                'jobClass'            => Job::class,
                'scheduleClass'       => Schedule::class,
                'jobFilterClass'      => JobFilter::class,
                'scheduleFilterClass' => ScheduleFilter::class,
            ],
            ConnectionLocatorInterface::class => [
                'class'          => ConnectionLocator::class,
                'db'             => 'db',
                'schema'         => '_app_schedule',
                'tableJob'       => 'job',
                'tableSchedule'  => 'schedule',
                'tableMigration' => 'migration',
            ],
            JobsLocatorInterface::class       => [
                'class'        => JobsLocator::class,
                'preprocessor' => null,
                'definitions'  => [],
            ],
            SettingsLocatorInterface::class   => [
                'class'       => SettingsLocator::class,
                'cronEnabled' => fn() => false,
            ],

            JobInvokerInterface::class         => JobInvoker::class,
            JobMigratorInterface::class        => JobMigrator::class,
            JobRepositoryInterface::class      => JobRepository::class,
            ScheduleRepositoryInterface::class => ScheduleRepository::class,
        ],
    ],
];