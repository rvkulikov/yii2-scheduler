<?php /** @noinspection PhpUnused, PhpUnhandledExceptionInspection, SqlNoDataSourceInspection */

namespace Rvkulikov\Yii2\Scheduler\Migrations;

use Rvkulikov\Yii2\Scheduler\Components\ConnectionLocatorInterface;
use Yii;
use yii\db\Migration;

class M210714080311CreateSchema extends Migration
{
    public function safeUp(): bool
    {
        $locator  = Yii::createObject(ConnectionLocatorInterface::class);
        $this->db = $locator->getConnection();

        $schema = $locator->getSchema();

        $sql = /** @lang PostgreSQL */
            <<<SQL
-- noinspection SqlResolve
create schema if not exists {$schema}
SQL;

        foreach (explode("---", $sql) as $statement) {
            $this->execute($statement);
        }

        return true;
    }

    public function safeDown(): bool
    {
        $locator  = Yii::createObject(ConnectionLocatorInterface::class);
        $this->db = $locator->getConnection();

        $schema = $locator->getSchema();

        $sql = /** @lang PostgreSQL */
            <<<SQL
-- noinspection SqlResolve
drop schema if exists {$schema}
SQL;

        foreach (explode("---", $sql) as $statement) {
            $this->execute($statement);
        }

        return true;
    }
}
