<?php

namespace Rvkulikov\Yii2\Scheduler\Services\JobInvoker;

use Closure;
use Rvkulikov\Yii2\Scheduler\Components\JobsLocatorInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use function array_key_exists;

class JobInvoker implements JobInvokerInterface
{
    public function __construct(
        private JobsLocatorInterface $locator
    )
    {
    }

    /**
     * @param string $key
     *
     * @return mixed
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws NotInstantiableException
     */
    public function invoke(string $key): mixed
    {
        $jobs = $this->locator->getDefinitions();
        $jobs = ArrayHelper::index($jobs, 'key');

        if (!array_key_exists($key, $jobs)) {
            throw new NotFoundHttpException("Job {$key} was not found");
        }

        $callback = $jobs[$key]['callback'];
        $closure  = Closure::fromCallable($callback);
        $result   = Yii::$container->invoke($closure);

        return $result;
    }
}