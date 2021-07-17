<?php /** @noinspection PhpUnused */

namespace Rvkulikov\Yii2\Scheduler\Services\JobRepository;

use JetBrains\PhpStorm\ExpectedValues;
use Rvkulikov\Yii2\Scheduler\Models\Job;
use Rvkulikov\Yii2\Scheduler\Models\Query\JobQuery;
use yii\base\Model;

class JobFilter extends Model
{
    public const ARRAY_SHAPE = [
        'alias'      => 'mixed',
        'stateAlias' => 'mixed',
    ];

    public function __construct(
        $config = [],
        public mixed $alias = null,
        public mixed $stateAlias = null,
    )
    {
        parent::__construct($config);
    }

    /**
     * @param JobQuery $query
     *
     * @return JobQuery
     * @noinspection PhpMissingReturnTypeInspection
     * @noinspection PhpMissingParamTypeInspection
     */
    public function apply($query)
    {
        $query->andFilterWhere(['job_alias' => $this->getAlias()]);
        $query->andFilterWhere(['job_state_alias' => $this->getStateAlias()]);

        return $query;
    }

    public function rules(): array
    {
        return [
            [$this->attributes(), 'safe']
        ];
    }

    public function getAlias(): mixed
    {
        return $this->alias;
    }

    public function setAlias(mixed $alias): static
    {
        $this->alias = $alias;
        return $this;
    }

    public function getStateAlias(): mixed
    {
        return $this->stateAlias;
    }

    public function setStateAlias(
        #[ExpectedValues(values: [Job::STATE_ENABLED, Job::STATE_DISABLED])]
        mixed $stateAlias
    ): static
    {
        $this->stateAlias = $stateAlias;
        return $this;
    }
}