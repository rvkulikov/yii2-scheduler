<?php /** @noinspection PhpUnhandledExceptionInspection */
namespace Rvkulikov\Yii2\Scheduler\Traits;

use Rvkulikov\Yii2\Scheduler\Exceptions\InvalidModelException;
use yii\db\ActiveRecord;

/**
 * @mixin ActiveRecord
 */
trait ActiveRecordTrait
{
    /**
     * {@inheritDoc}
     */
    public function save($runValidation = true, $attributeNames = null, bool $safe = true): bool
    {
        if ($this->getIsNewRecord()) {
            $saved = $this->insert($runValidation, $attributeNames);
        } else {
            $saved = $this->update($runValidation, $attributeNames) !== false;
        }

        if ($safe && !$saved) {
            /** @noinspection PhpParamsInspection */
            throw new InvalidModelException($this);
        }

        return $saved;
    }
}