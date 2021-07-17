<?php /** @noinspection PhpDocSignatureInspection */

namespace Rvkulikov\Yii2\Scheduler\Components;

use Rvkulikov\Yii2\Scheduler\Models\Job;
use Rvkulikov\Yii2\Scheduler\Models\Schedule;
use Rvkulikov\Yii2\Scheduler\Services\JobRepository\JobFilter;
use Rvkulikov\Yii2\Scheduler\Services\ScheduleRepository\ScheduleFilter;
use yii\base\BaseObject;

class DefinitionLocator extends BaseObject implements DefinitionLocatorInterface
{
    public function __construct(
        $config = [],
        public string $jobClass = Job::class,
        public string $scheduleClass = Schedule::class,
        public string $jobFilterClass = JobFilter::class,
        public string $scheduleFilterClass = ScheduleFilter::class,
    )
    {
        parent::__construct($config);
    }


    public function setJobClass(string $jobClass): static
    {
        $this->jobClass = $jobClass;
        return $this;
    }

    public function setJobFilterClass(string $jobFilterClass): static
    {
        $this->jobFilterClass = $jobFilterClass;
        return $this;
    }

    public function setScheduleClass(string $scheduleClass): static
    {
        $this->scheduleClass = $scheduleClass;
        return $this;
    }

    public function setScheduleFilterClass(string $scheduleFilterClass): static
    {
        $this->scheduleFilterClass = $scheduleFilterClass;
        return $this;
    }

    /**
     * @return string|Job
     */
    public function getJobClass(): string
    {
        return $this->jobClass;
    }

    /**
     * @return string|JobFilter
     */
    public function getJobFilterClass(): string
    {
        return $this->jobFilterClass;
    }

    /**
     * @return string|Schedule
     */
    public function getScheduleClass(): string
    {
        return $this->scheduleClass;
    }

    /**
     * @return string|ScheduleFilter
     */
    public function getScheduleFilterClass(): string
    {
        return $this->scheduleFilterClass;
    }
}