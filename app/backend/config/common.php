<?php

use Rvkulikov\Yii2\Scheduler\Components\ConnectionLocator;
use Rvkulikov\Yii2\Scheduler\Components\ConnectionLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Components\DefinitionLocator;
use Rvkulikov\Yii2\Scheduler\Components\DefinitionLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Components\JobsLocator;
use Rvkulikov\Yii2\Scheduler\Components\JobsLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Components\SettingsLocator;
use Rvkulikov\Yii2\Scheduler\Components\SettingsLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Services\JobInvoker\JobInvoker;
use Rvkulikov\Yii2\Scheduler\Services\JobInvoker\JobInvokerInterface;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\JobMigrator;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\JobMigratorInterface;
use Rvkulikov\Yii2\Scheduler\Services\JobRepository\JobRepository;
use Rvkulikov\Yii2\Scheduler\Services\JobRepository\JobRepositoryInterface;
use Rvkulikov\Yii2\Scheduler\Services\ScheduleRepository\ScheduleRepository;
use Rvkulikov\Yii2\Scheduler\Services\ScheduleRepository\ScheduleRepositoryInterface;

return [
    'singletons' => [
        DefinitionLocatorInterface::class => DefinitionLocator::class,
        ConnectionLocatorInterface::class => ConnectionLocator::class,
        JobsLocatorInterface::class       => JobsLocator::class,
        SettingsLocatorInterface::class   => SettingsLocator::class,

        JobInvokerInterface::class         => JobInvoker::class,
        JobMigratorInterface::class        => JobMigrator::class,
        JobRepositoryInterface::class      => JobRepository::class,
        ScheduleRepositoryInterface::class => ScheduleRepository::class,
    ],
];