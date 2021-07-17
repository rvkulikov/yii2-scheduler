<?php
namespace Rvkulikov\Yii2\Scheduler\Services\JobMigrator;

interface JobMigratorInterface
{
    public const STRATEGY_SOFT = 'soft';
    public const STRATEGY_HARD = 'hard';

    public function migrate(string $strategy, array $definitions);
}