<?php /** @noinspection PhpReturnDocTypeMismatchInspection,PhpMissingReturnTypeInspection */

namespace Rvkulikov\Yii2\Scheduler\Models\Query;

use Rvkulikov\Yii2\Scheduler\Models\Schedule;
use yii\db\ActiveQuery;

class ScheduleQuery extends ActiveQuery
{
    /**
     * {@inheritDoc}
     * @return Schedule[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritDoc}
     * @return Schedule|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}