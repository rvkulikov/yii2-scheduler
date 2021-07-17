<?php

namespace Rvkulikov\Yii2\Scheduler;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Application;

class Module extends \yii\base\Module
{
    public function __construct($id, $parent = null, $config = [])
    {
        parent::__construct($id, $parent, ArrayHelper::merge([
            'controllerNamespace' => Yii::$app instanceof Application
                ? __NAMESPACE__ . '\Controllers'
                : __NAMESPACE__ . '\Commands',
        ], $config));
    }
}