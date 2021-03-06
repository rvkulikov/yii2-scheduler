<?php /** @noinspection PhpUnused */

namespace Rvkulikov\Yii2\Scheduler\Components;

use Closure;
use Rvkulikov\Yii2\Scheduler\Dto\JobDefinition;
use Rvkulikov\Yii2\Scheduler\Dto\ScheduleDefinition;
use Rvkulikov\Yii2\Scheduler\Models\Job;
use Rvkulikov\Yii2\Scheduler\Models\Schedule;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use function array_map;
use function is_callable;

class JobsLocator extends BaseObject implements JobsLocatorInterface
{
    protected ?array $processed    = null;
    protected mixed  $preprocessor = null;
    protected mixed  $definitions  = [];

    public function getPreprocessor(): ?callable
    {
        return $this->preprocessor;
    }

    public function setPreprocessor(?callable $preprocessor): void
    {
        $this->preprocessor = $preprocessor;
    }

    /**
     * @throws NotInstantiableException
     * @throws InvalidConfigException
     */
    public function getDefinitions(): array
    {
        if ($this->processed === null) {

            if (is_callable($this->definitions)) {
                $callback    = $this->definitions;
                $callback    = Closure::fromCallable($callback);
                $definitions = Yii::$container->invoke($callback);
            } else {
                $definitions = $this->definitions;
            }

            if ($this->preprocessor) {
                $callback        = $this->getPreprocessor();
                $callback        = Closure::fromCallable($callback);
                $this->processed = Yii::$container->invoke($callback, [$definitions]);
            } else {
                $this->processed = $this->definitions;
            }
        }

        return $this->processed;
    }

    public function setDefinitions(mixed $definitions): static
    {
        $this->definitions = $definitions;
        $this->processed   = null;

        return $this;
    }

    public static function preprocessTuples(array $tuples): array
    {
        $definitions = array_map(fn(array $tuple) => new JobDefinition(
            alias: $tuple[0],
            callback: $tuple[1],
            stateAlias: Job::STATE_ENABLED,
            scheduleDefinitions: array_map(fn(string $expression) => new ScheduleDefinition(
                jobAlias: $tuple[0],
                expression: $expression,
                creatorAlias: Schedule::CREATOR_SYSTEM,
                stateAlias: Schedule::STATE_ENABLED,
            ), $tuple[2])
        ), $tuples);

        return $definitions;
    }

    public static function preprocessStructs(array $structs): array
    {
        $definitions = array_map(fn(array $job) => new JobDefinition(
            alias: $job['alias'],
            callback: $job['callback'],
            name: $job['name'] ?? null,
            description: $job['description'] ?? null,
            stateAlias: $job['stateAlias'] ?? Job::STATE_ENABLED,
            scheduleDefinitions: array_map(fn(string $expression) => new ScheduleDefinition(
                jobAlias: $job['alias'],
                expression: $expression,
                creatorAlias: Schedule::CREATOR_SYSTEM,
                stateAlias: Schedule::STATE_ENABLED,
            ), $job['expressions'])
        ), $structs);

        return $definitions;
    }

    public static function preprocessFull(array $structs): array
    {
        $definitions = array_map(fn(array $job) => new JobDefinition(
            alias: $job['alias'],
            callback: $job['callback'],
            name: $job['name'] ?? null,
            description: $job['description'] ?? null,
            stateAlias: $job['stateAlias'] ?? Job::STATE_ENABLED,
            scheduleDefinitions: array_map(fn(array $schedule) => new ScheduleDefinition(
                jobAlias: $job['alias'],
                expression: $schedule['expression'],
                creatorAlias: $schedule['creatorAlias'] ?? Schedule::CREATOR_SYSTEM,
                stateAlias: $schedule['stateAlias'] ?? Schedule::STATE_ENABLED,
            ), $job['schedules'])
        ), $structs);

        return $definitions;
    }
}