<?php /** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpMissingFieldTypeInspection */

namespace Rvkulikov\Yii2\Scheduler\Data;

use Rvkulikov\Yii2\Scheduler\Components\SettingsLocatorInterface;
use Yii;
use yii\helpers\ArrayHelper;

class Serializer extends \yii\rest\Serializer
{
    public $collectionEnvelope = 'items';
    public $runtimeEnvelope    = '_runtime';
    public $linksEnvelope      = '_links';
    public $metaEnvelope       = '_pagination';

    protected function serializeDataProvider($dataProvider): array
    {
        $locator = Yii::createObject(SettingsLocatorInterface::class);
        $result  = ArrayHelper::merge(parent::serializeDataProvider($dataProvider), [
            $this->runtimeEnvelope => [
                'cronEnabled' => $locator->getCronEnabled(),
            ]
        ]);

        return $result;
    }
}