<?php /** @noinspection PhpUnused, PhpUnhandledExceptionInspection, SqlNoDataSourceInspection */

namespace Rvkulikov\Yii2\Scheduler\Migrations;

use Rvkulikov\Yii2\Scheduler\Components\ConnectionLocatorInterface;
use Yii;
use yii\db\Migration;

class M210714080314CreateTableJob extends Migration
{
    public function safeUp(): bool
    {
        $locator  = Yii::createObject(ConnectionLocatorInterface::class);
        $this->db = $locator->getConnection();

        $tableJob = $locator->getTableJob();
        $fullJob  = $locator->qualify($tableJob);

        $sql = /** @lang PostgreSQL */
            <<<SQL
-- noinspection SqlResolve
create table $fullJob
(
  job_id          uuid not null generated always as ( public.uuid_generate_v4() ) stored,
  job_alias       text not null,
  job_name        text not null default '',
  job_description text not null default '',
  job_state_alias text not null default 'enabled',
  constraint {$tableJob}_job_id_pk
    primary key (job_id),
  constraint {$tableJob}_job_alias_uindex
    unique (job_alias),
  constraint {$tableJob}_job_state_alias_check
    check ( job_state_alias in ('enabled', 'disabled') )
);
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

        $table = $locator->qualify($locator->getTableJob());

        $this->dropTable($table);

        return true;
    }
}
