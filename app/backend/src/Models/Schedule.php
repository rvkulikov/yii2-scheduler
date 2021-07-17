<?php /** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpReturnDocTypeMismatchInspection */

namespace Rvkulikov\Yii2\Scheduler\Models;

use Rvkulikov\Yii2\Scheduler\Components\ConnectionLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Models\Query\JobQuery;
use Rvkulikov\Yii2\Scheduler\Models\Query\ScheduleQuery;
use Rvkulikov\Yii2\Scheduler\Traits\ActiveRecordTrait;
use Rvkulikov\Yii2\Scheduler\Traits\ConnectionTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property string   $schedule_job_alias
 * @property string   $schedule_expression
 * @property string   $schedule_creator_alias
 * @property string   $schedule_state_alias
 *
 * @property-read Job $job
 */
class Schedule extends ActiveRecord
{
    public const STATE_ENABLED  = 'enabled';
    public const STATE_DISABLED = 'disabled';

    public const CREATOR_SYSTEM = 'system';
    public const CREATOR_USER   = 'user';

    use ActiveRecordTrait, ConnectionTrait;

    public static function tableName(): string
    {
        $locator = Yii::createObject(ConnectionLocatorInterface::class);
        $table   = $locator->qualify($locator->getSchema(), $locator->getTableSchedule());

        return $table;
    }

    public function rules(): array
    {
        return [
            ['schedule_job_alias', 'string'],
            ['schedule_expression', 'string'],
            ['schedule_creator_alias', 'in', 'range', [static::CREATOR_SYSTEM, static::CREATOR_USER]],
            ['schedule_state_alias', 'in', 'range', [static::STATE_ENABLED, static::STATE_DISABLED]],
        ];
    }

    public static function find(): ScheduleQuery
    {
        return new ScheduleQuery(static::class);
    }

    /**
     * @return JobQuery
     * @noinspection PhpMissingReturnTypeInspection
     * @noinspection PhpIncompatibleReturnTypeInspection
     */
    public function getJob()
    {
        return $this->hasOne(Job::class, ['job_alias' => 'job_alias']);
    }

    public function getJobAlias(): string
    {
        return $this->schedule_job_alias;
    }

    public function setJobAlias(string $jobAlias): static
    {
        $this->schedule_job_alias = $jobAlias;
        return $this;
    }

    public function getExpression(): string
    {
        return $this->schedule_expression;
    }

    public function setExpression(string $expression): static
    {
        $this->schedule_expression = $expression;
        return $this;
    }

    public function getCreatorAlias(): string
    {
        return $this->schedule_creator_alias;
    }

    public function setCreatorAlias(string $creatorAlias): static
    {
        $this->schedule_creator_alias = $creatorAlias;
        return $this;
    }

    public function getStateAlias(): string
    {
        return $this->schedule_state_alias;
    }

    public function setStateAlias(string $stateAlias): static
    {
        $this->schedule_state_alias = $stateAlias;
        return $this;
    }
}