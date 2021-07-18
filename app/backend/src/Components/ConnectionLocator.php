<?php

namespace Rvkulikov\Yii2\Scheduler\Components;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\Connection;

class ConnectionLocator extends BaseObject implements ConnectionLocatorInterface
{
    protected string  $db             = 'db';
    protected ?string $schema         = '_app_schedule';
    protected string  $tableJob       = 'job';
    protected string  $tableSchedule  = 'schedule';
    protected string  $tableMigration = 'migration';

    public function getDb(): string
    {
        return $this->db;
    }

    public function setDb(string $db): void
    {
        $this->db = $db;
    }

    /**
     * @throws InvalidConfigException
     */
    public function getConnection(): Connection
    {
        $db = Yii::$app->get($this->db);

        return $db;
    }

    public function getSchema(): string
    {
        return $this->schema;
    }

    public function setSchema(string $schema): void
    {
        $this->schema = $schema;
    }

    public function getTableJob(): string
    {
        return $this->tableJob;
    }

    public function setTableJob(string $tableJob): void
    {
        $this->tableJob = $tableJob;
    }

    public function getTableSchedule(): string
    {
        return $this->tableSchedule;
    }

    public function setTableSchedule(string $tableSchedule): void
    {
        $this->tableSchedule = $tableSchedule;
    }

    public function getTableMigration(): string
    {
        return $this->tableMigration;
    }

    public function setTableMigration(string $tableMigration): void
    {
        $this->tableMigration = $tableMigration;
    }

    public function qualify(string $relation, ?string $schema = null): string
    {
        $schema ??= $this->getSchema();

        return $schema
            ? "$schema.$relation"
            : $relation;
    }
}