<?php /** @noinspection PhpUnused */

namespace Rvkulikov\Yii2\Scheduler\Services\ScheduleRepository;

use JetBrains\PhpStorm\ExpectedValues;
use Rvkulikov\Yii2\Scheduler\Models\Job;
use Rvkulikov\Yii2\Scheduler\Models\Query\ScheduleQuery;
use Rvkulikov\Yii2\Scheduler\Models\Schedule;
use yii\base\Model;

class ScheduleFilter extends Model
{
    public const ARRAY_SHAPE = [
        'jobAlias'      => 'mixed',
        'stateAlias'    => 'mixed',
        'creatorAlias'  => 'mixed',
        'jobStateAlias' => 'mixed',
    ];

    public function __construct(
        $config = [],
        public mixed $jobAlias = null,
        public mixed $stateAlias = null,
        public mixed $creatorAlias = null,
        public mixed $jobStateAlias = null,
    )
    {
        parent::__construct($config);
    }

    /**
     * @param ScheduleQuery $query
     *
     * @return ScheduleQuery
     * @noinspection PhpMissingReturnTypeInspection
     * @noinspection PhpMissingParamTypeInspection
     */
    public function apply($query)
    {
        $query->andFilterWhere(['schedule_job_alias' => $this->getJobAlias()]);
        $query->andFilterWhere(['schedule_state_alias' => $this->getStateAlias()]);
        $query->andFilterWhere(['schedule_creator_alias' => $this->getCreatorAlias()]);

        if ($this->getJobStateAlias()) {
            $query->joinWith(['job job'], false);
            $query->andFilterWhere(['job.job_state_alias' => $this->getJobStateAlias()]);
        }

        return $query;
    }


    public function rules(): array
    {
        return [
            [$this->attributes(), 'safe']
        ];
    }

    public function getJobAlias(): mixed
    {
        return $this->jobAlias;
    }

    public function setJobAlias(mixed $jobAlias): static
    {
        $this->jobAlias = $jobAlias;
        return $this;
    }

    public function getStateAlias(): mixed
    {
        return $this->stateAlias;
    }

    public function setStateAlias(
        #[ExpectedValues(values: [Schedule::STATE_ENABLED, Schedule::STATE_DISABLED])]
        mixed $stateAlias
    ): static
    {
        $this->stateAlias = $stateAlias;
        return $this;
    }

    public function getCreatorAlias(): mixed
    {
        return $this->creatorAlias;
    }

    public function setCreatorAlias(
        #[ExpectedValues(values: [Schedule::CREATOR_SYSTEM, Schedule::CREATOR_USER])]
        mixed $creatorAlias
    ): static
    {
        $this->creatorAlias = $creatorAlias;
        return $this;
    }

    public function getJobStateAlias(): mixed
    {
        return $this->jobStateAlias;
    }

    public function setJobStateAlias(
        #[ExpectedValues(values: [Job::STATE_ENABLED, Job::STATE_DISABLED])]
        mixed $jobStateAlias
    ): void
    {
        $this->jobStateAlias = $jobStateAlias;
    }
}