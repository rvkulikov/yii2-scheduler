<?php /** @noinspection PhpAttributeCanBeAddedToOverriddenMemberInspection */

namespace Rvkulikov\Yii2\Scheduler\Services\JobRepository;

use JetBrains\PhpStorm\ArrayShape;
use Rvkulikov\Yii2\Scheduler\Dto\JobDefinition;
use Rvkulikov\Yii2\Scheduler\Exceptions\InvalidModelException;
use Rvkulikov\Yii2\Scheduler\Models\Job;
use Rvkulikov\Yii2\Scheduler\Models\Query\JobQuery;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;

interface JobRepositoryInterface
{
    /**
     * @param array|JobFilter|null $args
     *
     * @return JobQuery
     * @throws InvalidModelException
     * @throws InvalidConfigException
     */
    public function query(
        #[ArrayShape(JobFilter::ARRAY_SHAPE)]
        array|JobFilter $args = null
    ): JobQuery;

    /**
     * @return Job[]
     * @throws InvalidModelException
     * @throws InvalidConfigException
     */
    public function all(
        #[ArrayShape(JobFilter::ARRAY_SHAPE)]
        array|JobFilter $args = null
    ): array;

    /**
     * @param array|JobFilter|null $args
     *
     * @return ?Job
     * @throws InvalidModelException
     * @throws InvalidConfigException
     */
    public function one(
        #[ArrayShape(JobFilter::ARRAY_SHAPE)]
        array|JobFilter $args = null
    ): ?Job;

    /**
     * @param array|JobFilter|null $args
     *
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     * @throws InvalidModelException
     */
    public function provide(
        #[ArrayShape(JobFilter::ARRAY_SHAPE)]
        array|JobFilter $args = null
    ): DataProviderInterface;

    /**
     * @param JobDefinition $definition
     *
     * @return Job
     * @throws InvalidConfigException
     * @throws InvalidModelException
     */
    public function create(JobDefinition $definition): Job;

    /**
     * @param Job           $model
     * @param JobDefinition $definition
     *
     * @return Job
     * @throws InvalidConfigException
     * @throws InvalidModelException
     */
    public function update(Job $model, JobDefinition $definition): Job;
}