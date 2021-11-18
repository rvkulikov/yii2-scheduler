<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace Rvkulikov\Yii2\Scheduler\Commands;

use Rvkulikov\Yii2\Scheduler\Components\JobsLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Dto\JobDefinition;
use Rvkulikov\Yii2\Scheduler\Services\JobInvoker\JobInvokerInterface;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\Json;

class JobsController extends Controller
{
    public function actionInvoke(?string $key = null)
    {
        $jobLocator = Yii::createObject(JobsLocatorInterface::class);
        $jobInvoker = Yii::createObject(JobInvokerInterface::class);

        if (empty($key)) {
            $jobs    = $jobLocator->getDefinitions();
            $aliases = ArrayHelper::getColumn($jobs, fn(JobDefinition $jd) => $jd->getAlias());
            $index   = Console::select('Pick job to run: ', $aliases);
            $key     = $aliases[$index];
        }

        $result = $jobInvoker->invoke($key);
        $result = Json::encode($result);

        Console::output($result);
    }
}