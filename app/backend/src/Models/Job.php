<?php /** @noinspection PhpUnhandledExceptionInspection, PhpReturnDocTypeMismatchInspection */

namespace Rvkulikov\Yii2\Scheduler\Models;

use Rvkulikov\Yii2\Scheduler\Components\ConnectionLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Models\Query\JobQuery;
use Rvkulikov\Yii2\Scheduler\Models\Query\ScheduleQuery;
use Rvkulikov\Yii2\Scheduler\Traits\ActiveRecordTrait;
use Rvkulikov\Yii2\Scheduler\Traits\ConnectionTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property string $job_id
 * @property string $job_alias
 * @property string $job_name
 * @property string $job_description
 * @property string $job_state_alias
 *
 * @property-read Schedule[] $schedules
 */
class Job extends ActiveRecord
{
    public const STATE_ENABLED  = 'enabled';
    public const STATE_DISABLED = 'disabled';

    use ActiveRecordTrait, ConnectionTrait;

    public static function tableName(): string
    {
        $locator = Yii::createObject(ConnectionLocatorInterface::class);
        $table   = $locator->qualify($locator->getSchema(), $locator->getTableJob());

        return $table;
    }

    public function rules(): array
    {
        return [
            ['job_id', 'string'],
            ['job_alias', 'string'],
            ['job_title', 'integer'],
            ['job_description', 'string'],
            ['job_state_alias', 'in', 'range' => [static::STATE_ENABLED, static::STATE_DISABLED]],
        ];
    }

    public static function find(): JobQuery
    {
        return new JobQuery(static::class);
    }

    /**
     * @return ScheduleQuery
     * @noinspection PhpMissingReturnTypeInspection
     * @noinspection PhpIncompatibleReturnTypeInspection
     */
    public function getSchedules()
    {
        return $this->hasMany(Schedule::class, ['job_alias' => 'schedule_job_alias']);
    }

    public function getId(): string
    {
        return $this->job_id;
    }

    public function getAlias(): string
    {
        return $this->job_alias;
    }

    public function getName(): string
    {
        return $this->job_name;
    }

    public function getDescription(): string
    {
        return $this->job_description;
    }

    public function getStateAlias(): string
    {
        return $this->job_state_alias;
    }

    public function setAlias(string $job_alias): static
    {
        $this->job_alias = $job_alias;
        return $this;
    }

    public function setName(string $job_title): static
    {
        $this->job_name = $job_title;
        return $this;
    }

    public function setDescription(string $job_description): static
    {
        $this->job_description = $job_description;
        return $this;
    }

    public function setStateAlias(string $job_state_alias): static
    {
        $this->job_state_alias = $job_state_alias;
        return $this;
    }
}