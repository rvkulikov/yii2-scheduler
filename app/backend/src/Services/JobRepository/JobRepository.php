<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace Rvkulikov\Yii2\Scheduler\Services\JobRepository;

use Rvkulikov\Yii2\Scheduler\Components\DefinitionLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Dto\JobDefinition;
use Rvkulikov\Yii2\Scheduler\Exceptions\InvalidModelException;
use Rvkulikov\Yii2\Scheduler\Helpers\ModelHelper;
use Rvkulikov\Yii2\Scheduler\Models\Job;
use Rvkulikov\Yii2\Scheduler\Models\Query\JobQuery;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;

class JobRepository implements JobRepositoryInterface
{
    public function __construct(
        private DefinitionLocatorInterface $definitionLocator
    )
    {
    }

    public function query(array|JobFilter $args = null): JobQuery
    {
        $filter = ModelHelper::ensure(JobFilter::class, $args);

        $class = $this->definitionLocator->getJobClass();
        $query = $class::find();

        $filter->apply($query);

        return $query;
    }

    public function provide(array|JobFilter $args = null): DataProviderInterface
    {
        $query    = $this->query($args);
        $provider = new ActiveDataProvider([
            'query' => $query
        ]);

        return $provider;
    }

    public function all(array|JobFilter $args = null): array
    {
        $query  = $this->query($args);
        $models = $query->all();

        return $models;
    }

    public function one(array|JobFilter $args = null): Job
    {
        $query = $this->query($args);
        $model = $query->one();

        return $model;
    }

    public function create(JobDefinition $definition): Job
    {
        $job = Yii::createObject($this->definitionLocator->getJobClass());
        $this->update($job, $definition);

        return $job;
    }

    public function update(Job $model, JobDefinition $definition): Job
    {
        $model->setAlias($definition->getAlias());
        $model->setName($definition->getName());
        $model->setDescription($definition->getDescription());
        $model->setStateAlias($definition->getStateAlias());

        $model->save() || throw new InvalidModelException($model);
        $model->refresh();

        return $model;
    }
}