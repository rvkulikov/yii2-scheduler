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
        $table   = $locator->qualify($locator->getTableJob());

        return $table;
    }

    public function rules(): array
    {
        return [
            ['job_id', 'string'],
            ['job_alias', 'string'],
            ['job_name', 'integer'],
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

    public function setAlias(string $alias): static
    {
        $this->job_alias = $alias;
        return $this;
    }

    public function setName(?string $title): static
    {
        $this->job_name = $title;
        return $this;
    }

    public function setDescription(?string $description): static
    {
        $this->job_description = $description;
        return $this;
    }

    public function setStateAlias(?string $stateAlias): static
    {
        $this->job_state_alias = $stateAlias;
        return $this;
    }
}