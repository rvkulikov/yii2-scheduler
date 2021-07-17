<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace Rvkulikov\Yii2\Scheduler\Services\ScheduleRepository;

use Rvkulikov\Yii2\Scheduler\Components\DefinitionLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Dto\ScheduleDefinition;
use Rvkulikov\Yii2\Scheduler\Exceptions\InvalidModelException;
use Rvkulikov\Yii2\Scheduler\Helpers\ModelHelper;
use Rvkulikov\Yii2\Scheduler\Models\Schedule;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function __construct(
        private DefinitionLocatorInterface $definitionLocator
    )
    {
    }

    public function query(array|ScheduleFilter $args = null)
    {
        $filter = ModelHelper::ensure(ScheduleFilter::class, $args);

        $class = $this->definitionLocator->getScheduleClass();
        $query = $class::find();

        $filter->apply($query);

        return $query;
    }

    public function provide(array|ScheduleFilter $args = null): DataProviderInterface
    {
        $query    = $this->query($args);
        $provider = new ActiveDataProvider(['query' => $query]);

        return $provider;
    }

    public function all(array|ScheduleFilter $args = null): array
    {
        $query  = $this->query($args);
        $models = $query->all();

        return $models;
    }

    public function one(array|ScheduleFilter $args = null)
    {
        $query = $this->query($args);
        $model = $query->one();

        return $model;
    }

    public function create(ScheduleDefinition $definition): Schedule
    {
        $job = Yii::createObject($this->definitionLocator->getScheduleClass());
        $this->update($job, $definition);

        return $job;
    }

    public function update(Schedule $model, ScheduleDefinition $definition): Schedule
    {
        $model->setJobAlias($definition->getJobAlias());
        $model->setExpression($definition->getExpression());
        $model->setExpression($definition->getExpression());
        $model->setStateAlias($definition->getStateAlias());
        $model->setCreatorAlias($definition->getCreatorAlias());

        $model->save() || throw new InvalidModelException($model);
        $model->refresh();

        return $model;
    }
}