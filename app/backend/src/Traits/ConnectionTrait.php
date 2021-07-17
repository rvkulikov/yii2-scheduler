<?php
/** @noinspection PhpUnhandledExceptionInspection */

namespace Rvkulikov\Yii2\Scheduler\Traits;

use Rvkulikov\Yii2\Scheduler\Components\ConnectionLocatorInterface;
use Yii;
use yii\db\Connection;

trait ConnectionTrait
{
    public static function getDb(): Connection
    {
        $locator    = Yii::createObject(ConnectionLocatorInterface::class);
        $connection = $locator->getConnection();
        
        return $connection;
    }
}