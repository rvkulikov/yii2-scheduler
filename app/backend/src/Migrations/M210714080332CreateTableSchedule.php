<?php /** @noinspection PhpUnused, PhpUnhandledExceptionInspection, SqlNoDataSourceInspection */

namespace Rvkulikov\Yii2\Scheduler\Migrations;

use Rvkulikov\Yii2\Scheduler\Components\ConnectionLocatorInterface;
use Yii;
use yii\db\Migration;

class M210714080332CreateTableSchedule extends Migration
{
    public function safeUp(): bool
    {
        $locator  = Yii::createObject(ConnectionLocatorInterface::class);
        $this->db = $locator->getConnection();

        $tableJob = $locator->getTableJob();
        $fullJob  = $locator->qualify($tableJob);

        $tableSchedule = $locator->getTableSchedule();
        $fullSchedule  = $locator->qualify($tableSchedule);

        $sql = /** @lang PostgreSQL */
            <<<SQL
-- noinspection SqlResolve
create table $fullSchedule
(
  schedule_job_alias     text not null,
  schedule_expression    text not null,
  schedule_state_alias   text not null default 'enabled',
  schedule_creator_alias text not null default 'user',

  constraint {$tableSchedule}_schedule_job_alias_fk
    foreign key (schedule_job_alias)
      references $fullJob (job_alias)
      on delete cascade 
      on update cascade,

  -- https://regexr.com/5bdes
  -- https://stackoverflow.com/a/63729682
  constraint {$tableSchedule}_schedule_expression_check
    check ( schedule_expression ~ '/^((((\d+,)+\d+|(\d+(\/|-|#)\d+)|\d+L?|\*(\/\d+)?|L(-\d+)?|\?|[A-Z]{3}(-[A-Z]{3})?)(\s*)?){6,6})$/gm' ),

  constraint {$tableSchedule}_schedule_state_alias_check
    check ( schedule_state_alias in ('enabled', 'disabled') ),

  constraint {$tableSchedule}_schedule_creator_alias_check
    check ( schedule_creator_alias in ('system', 'user') ),

  constraint {$tableSchedule}_uindex
    unique (schedule_job_alias, schedule_expression)
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

        $table = $locator->qualify($locator->getTableSchedule());

        $this->dropTable($table);

        return true;
    }
}
