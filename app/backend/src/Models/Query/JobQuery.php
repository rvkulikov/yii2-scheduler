<?php /** @noinspection PhpReturnDocTypeMismatchInspection,PhpMissingReturnTypeInspection */

namespace Rvkulikov\Yii2\Scheduler\Models\Query;

use Rvkulikov\Yii2\Scheduler\Models\Job;
use yii\db\ActiveQuery;

class JobQuery extends ActiveQuery
{
    /**
     * {@inheritDoc}
     * @return Job[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritDoc}
     * @return Job|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}