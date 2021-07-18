<?php

namespace Rvkulikov\Yii2\Scheduler\Components;

use Closure;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use function filter_var;
use function is_callable;
use const FILTER_VALIDATE_BOOL;

class SettingsLocator extends BaseObject implements SettingsLocatorInterface
{
    protected mixed $cronEnabled = false;

    /**
     * @throws NotInstantiableException
     * @throws InvalidConfigException
     */
    public function getCronEnabled(): bool
    {
        if (is_callable($this->cronEnabled)) {
            $closure = Closure::fromCallable($this->cronEnabled);
            $result  = Yii::$container->invoke($closure);
        }else{
            $result = $this->cronEnabled;
        }

        return filter_var($result, FILTER_VALIDATE_BOOL);
    }

    /**
     * @param mixed $cronEnabled
     */
    public function setCronEnabled(mixed $cronEnabled): void
    {
        $this->cronEnabled = $cronEnabled;
    }
}