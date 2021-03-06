<?php /** @noinspection PhpUnused,PhpUnhandledExceptionInspection */

namespace Rvkulikov\Yii2\Scheduler\Controllers;

use Rvkulikov\Yii2\Scheduler\Components\DefinitionLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Components\SettingsLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Data\Serializer;
use Rvkulikov\Yii2\Scheduler\Models\Job;
use Rvkulikov\Yii2\Scheduler\Models\Schedule;
use Rvkulikov\Yii2\Scheduler\Services\ScheduleRepository\ScheduleRepositoryInterface;
use Yii;
use yii\data\ArrayDataProvider;
use yii\data\DataProviderInterface;
use yii\rest\Controller;

class SchedulesController extends Controller
{
    public $serializer = Serializer::class;

    public function actionIndex(
        SettingsLocatorInterface $settingsLocator,
        DefinitionLocatorInterface $definitionLocator,
        ScheduleRepositoryInterface $scheduleRepository
    ): DataProviderInterface
    {
        if ($settingsLocator->getCronEnabled() === false) {
            return new ArrayDataProvider(['allModels' => []]);
        }

        $filter = Yii::createObject($definitionLocator->getScheduleFilterClass());
        $filter->setStateAlias(Schedule::STATE_ENABLED);
        $filter->setJobStateAlias(Job::STATE_ENABLED);

        $provider = $scheduleRepository->provide($filter);
        $provider->setPagination(false);

        return $provider;
    }
}