<?php /** @noinspection PhpUnused,PhpUnhandledExceptionInspection */

namespace Rvkulikov\Yii2\Scheduler\Controllers;

use Rvkulikov\Yii2\Scheduler\Components\DefinitionLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Data\Serializer;
use Rvkulikov\Yii2\Scheduler\Models\Job;
use Rvkulikov\Yii2\Scheduler\Services\JobInvoker\JobInvokerInterface;
use Rvkulikov\Yii2\Scheduler\Services\JobRepository\JobRepositoryInterface;
use Yii;
use yii\data\DataProviderInterface;
use yii\rest\Controller;

class JobsController extends Controller
{
    public $serializer = Serializer::class;

    public function actionIndex(
        DefinitionLocatorInterface $definitionLocator,
        JobRepositoryInterface $jobRepository
    ): DataProviderInterface
    {
        $filter = Yii::createObject($definitionLocator->getJobFilterClass());
        $filter->setStateAlias(Job::STATE_ENABLED);

        $provider = $jobRepository->provide($filter);
        $provider->setPagination(false);

        return $provider;
    }

    public function actionInvoke(JobInvokerInterface $jobInvoker, string $key)
    {
        return $jobInvoker->invoke($key);
    }
}