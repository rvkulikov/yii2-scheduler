<?php

namespace Rvkulikov\Yii2\Scheduler\Services\JobInvoker;

use Closure;
use Rvkulikov\Yii2\Scheduler\Components\JobsLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Dto\JobDefinition;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

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
        $jobs = ArrayHelper::index($jobs, fn(JobDefinition $d) => $d->getAlias());

        /** @var JobDefinition $job */
        if (($job = $jobs[$key] ?? null) === null) {
            throw new NotFoundHttpException("Job {$key} was not found");
        }

        $callback = $job->getCallback();
        $closure  = Closure::fromCallable($callback);
        $result   = Yii::$container->invoke($closure);

        return $result;
    }
}