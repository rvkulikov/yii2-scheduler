<?php

namespace Rvkulikov\Yii2\Scheduler\Helpers;

use Closure;
use Rvkulikov\Yii2\Scheduler\Exceptions\InvalidModelException;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use function array_key_exists;
use function get_class;
use function gettype;
use function is_array;
use function is_callable;
use function is_object;

class ModelHelper
{
    /**
     * @param string                    $class
     * @param Model|array|callable|null $data
     * @param string|null               $scenario
     * @param bool                      $validate
     * @param bool                      $throw
     * @param array                     $params
     *
     * @return Model
     * @throws InvalidConfigException
     * @throws InvalidModelException
     */
    public static function ensure(
        string $class,
        Model|array|callable|null $data,
        ?string $scenario = null,
        bool $validate = true,
        bool $throw = true,
        array $params = []
    ): Model
    {
        /** @var Model $model */
        if ($data === null) {
            $data = [];
        }

        if ($data instanceof $class) {
            $model = $data;
            $scenario && $model->setScenario($scenario);
        } else {
            if ($data instanceof Closure || is_callable($data)) {
                $model = Yii::$container->invoke($data, [
                    new EnsureOptions(
                        scenario: $scenario,
                        validate: $validate,
                        throw: $throw,
                        params: $params,
                    )
                ]);
                $scenario && $model->setScenario($scenario);
            } else {
                if (is_array($data)) {
                    $model = Yii::createObject($class, $params);
                    $key   = array_key_exists($model->formName(), $data)
                        ? $model->formName()
                        : '';

                    $scenario && $model->setScenario($scenario);
                    $model->load($data, $key);
                } else {
                    $given = is_object($data)
                        ? get_class($data)
                        : gettype($data);

                    throw new InvalidConfigException("\$data is not an array or $class instance. $given given.");
                }
            }
        }

        if ($validate && !$model->validate() && $throw) {
            throw new InvalidModelException($model);
        }

        return $model;
    }
}