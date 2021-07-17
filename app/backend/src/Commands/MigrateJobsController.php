<?php /** @noinspection PhpUnused,PhpUnhandledExceptionInspection */

namespace Rvkulikov\Yii2\Scheduler\Commands;

use Rvkulikov\Yii2\Scheduler\Components\JobsLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Services\JobMigrator\JobMigratorInterface;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

class MigrateJobsController extends Controller
{
    public $defaultAction = 'run';

    public string $strategy = JobMigratorInterface::STRATEGY_SOFT;

    public function options($actionID): array
    {
        return ArrayHelper::merge(parent::options($actionID), [
            'strategy'
        ]);
    }

    public function optionAliases(): array
    {
        return ArrayHelper::merge(parent::optionAliases(), [
            's' => 'strategy',
        ]);
    }

    public function actionRun(
        ?string $strategy = null
    )
    {
        $migrator = Yii::createObject(JobMigratorInterface::class);
        $locator  = Yii::createObject(JobsLocatorInterface::class);

        $select   = [JobMigratorInterface::STRATEGY_SOFT => 'Soft (Merge)', JobMigratorInterface::STRATEGY_HARD => 'Hard (Full reset)'];
        $strategy ??= $this->strategy ?? Console::select('Pick strategy: ', $select);

        $migrator->migrate($strategy, $locator->getDefinitions());
    }
}