<?php /** @noinspection PhpUnused,PhpUnhandledExceptionInspection */

namespace Rvkulikov\Yii2\Scheduler\Controllers;

use Rvkulikov\Yii2\Scheduler\Components\DefinitionLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Data\Serializer;
use Rvkulikov\Yii2\Scheduler\Models\Job;
use Rvkulikov\Yii2\Scheduler\Models\Schedule;
use Rvkulikov\Yii2\Scheduler\Services\ScheduleRepository\ScheduleRepositoryInterface;
use Yii;
use yii\data\DataProviderInterface;
use yii\rest\ActiveController;

class SchedulesController extends ActiveController
{
    public $serializer = Serializer::class;

    public function actionIndex(
        DefinitionLocatorInterface $definitionLocator,
        ScheduleRepositoryInterface $scheduleRepository
    ): DataProviderInterface
    {
        $filter = Yii::createObject($definitionLocator->getScheduleFilterClass());
        $filter->setStateAlias(Schedule::STATE_ENABLED);
        $filter->setJobStateAlias(Job::STATE_ENABLED);

        $provider = $scheduleRepository->provide($filter);

        return $provider;
    }
}