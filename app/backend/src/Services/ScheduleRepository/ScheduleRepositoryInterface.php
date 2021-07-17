<?php /** @noinspection PhpAttributeCanBeAddedToOverriddenMemberInspection */

namespace Rvkulikov\Yii2\Scheduler\Services\ScheduleRepository;

use JetBrains\PhpStorm\ArrayShape;
use Rvkulikov\Yii2\Scheduler\Dto\ScheduleDefinition;
use Rvkulikov\Yii2\Scheduler\Exceptions\InvalidModelException;
use Rvkulikov\Yii2\Scheduler\Models\Query\ScheduleQuery;
use Rvkulikov\Yii2\Scheduler\Models\Schedule;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;

interface ScheduleRepositoryInterface
{
    /**
     * @param array|ScheduleFilter|null $args
     *
     * @return ScheduleQuery
     * @throws InvalidModelException
     * @throws InvalidConfigException
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function query(
        #[ArrayShape(ScheduleFilter::ARRAY_SHAPE)]
        array|ScheduleFilter $args = null
    );

    /**
     * @param array|ScheduleFilter|null $args
     *
     * @return Schedule[]
     * @throws InvalidModelException
     * @throws InvalidConfigException
     */
    public function all(
        #[ArrayShape(ScheduleFilter::ARRAY_SHAPE)]
        array|ScheduleFilter $args = null
    ): array;

    /**
     * @param array|ScheduleFilter|null $args
     *
     * @return ?Schedule
     * @throws InvalidModelException
     * @throws InvalidConfigException
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function one(
        #[ArrayShape(ScheduleFilter::ARRAY_SHAPE)]
        array|ScheduleFilter $args = null
    );

    /**
     * @param array|ScheduleFilter|null $args
     *
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     * @throws InvalidModelException
     */
    public function provide(
        #[ArrayShape(ScheduleFilter::ARRAY_SHAPE)]
        array|ScheduleFilter $args = null
    ): DataProviderInterface;


    /**
     * @param ScheduleDefinition $definition
     *
     * @return Schedule
     * @throws InvalidConfigException
     * @throws InvalidModelException
     */
    public function create(ScheduleDefinition $definition): Schedule;

    /**
     * @param Schedule           $model
     * @param ScheduleDefinition $definition
     *
     * @return Schedule
     * @throws InvalidConfigException
     * @throws InvalidModelException
     */
    public function update(Schedule $model, ScheduleDefinition $definition): Schedule;
}