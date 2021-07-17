<?php
namespace Rvkulikov\Yii2\Scheduler\Components;

/**
 * @mixin SettingsLocator
 */
interface SettingsLocatorInterface
{
    public function getCronEnabled(): bool;
}