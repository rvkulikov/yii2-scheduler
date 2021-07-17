<?php

namespace Rvkulikov\Yii2\Scheduler\Components;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\Connection;

class ConnectionLocator extends BaseObject implements ConnectionLocatorInterface
{
    public function __construct(
        $config = [],
        protected string $connection = 'db',
        protected ?string $schema = '_app_schedule',
        protected string $tableJob = 'job',
        protected string $tableSchedule = 'schedule',
    )
    {
        parent::__construct($config);
    }

    /**
     * @throws InvalidConfigException
     */
    public function getConnection(): Connection
    {
        $db = Yii::$app->get($this->connection);

        return $db;
    }

    public function setConnection(mixed $connection): void
    {
        $this->connection = $connection;
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

    public function qualify(?string $schema, string $relation): string
    {
        return $schema
            ? "{$schema}.{$relation}"
            : $relation;
    }
}