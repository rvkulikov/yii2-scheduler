<?php

namespace Rvkulikov\Yii2\Scheduler\Commands;

use Rvkulikov\Yii2\Scheduler\Components\ConnectionLocatorInterface;
use yii\helpers\ArrayHelper;

class MigrateController extends \yii\console\controllers\MigrateController
{
    public function __construct($id, $module, ConnectionLocatorInterface $connectionLocator, $config = [])
    {
        parent::__construct($id, $module, ArrayHelper::merge([
            'db'                  => $connectionLocator->getDb(),
            'migrationTable'      => $connectionLocator->qualify($connectionLocator->getTableMigration()),
            'migrationPath'       => null,
            'migrationNamespaces' => ['Rvkulikov\Yii2\Scheduler\Migrations']
        ], $config));
    }
}