<?php

namespace Rvkulikov\Yii2\Scheduler\Commands;

use Rvkulikov\Yii2\Scheduler\Components\ConnectionLocatorInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

class MigrateController extends \yii\console\controllers\MigrateController
{
    public function __construct($id, $module, private ConnectionLocatorInterface $connectionLocator, $config = [])
    {
        parent::__construct($id, $module, ArrayHelper::merge([
            'db'                  => $connectionLocator->getDb(),
            'migrationTable'      => $connectionLocator->qualify($connectionLocator->getTableMigration()),
            'migrationPath'       => null,
            'migrationNamespaces' => ['Rvkulikov\Yii2\Scheduler\Migrations']
        ], $config));
    }

    protected function createMigrationHistoryTable()
    {
        $names  = $this->db->schema->schemaNames;
        $schema = $this->connectionLocator->getSchema();

        if (empty($names[$schema])) {
            $this->stdout("Creating schema \"$schema\"...", Console::FG_YELLOW);
            $this->db->createCommand("create schema if not exists {$schema}")->execute();
            $this->stdout("Done.\n", Console::FG_GREEN);
        }

        parent::createMigrationHistoryTable();
    }
}