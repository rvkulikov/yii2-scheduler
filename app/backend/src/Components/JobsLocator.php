<?php /** @noinspection PhpUnused */

namespace Rvkulikov\Yii2\Scheduler\Components;

use Closure;
use Rvkulikov\Yii2\Scheduler\Dto\JobDefinition;
use Rvkulikov\Yii2\Scheduler\Dto\ScheduleDefinition;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use function array_map;

class JobsLocator extends BaseObject implements JobsLocatorInterface
{
    protected ?array $processed = null;

    public function __construct(
        $config = [],
        protected mixed $preprocessor = null,
        protected array $definitions = [],
    )
    {
        parent::__construct($config);
    }

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
            if ($this->preprocessor) {
                $callback        = $this->getPreprocessor();
                $callback        = Closure::fromCallable($callback);
                $this->processed = Yii::$container->invoke($callback, [$this->definitions]);
            } else {
                $this->processed = $this->definitions;
            }
        }

        return $this->definitions;
    }

    public function setDefinitions(array $definitions): static
    {
        $this->definitions = $definitions;
        return $this;
    }

    public static function preprocessTuples(array $tuples): array
    {
        $definitions = array_map(fn(array $tuple) => new JobDefinition(
            alias: $tuple[0],
            callback: $tuple[1],
            scheduleDefinitions: array_map(fn(string $expression) => new ScheduleDefinition(
                jobAlias: $tuple[0],
                expression: $expression,
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
            stateAlias: $job['stateAlias'] ?? null,
            scheduleDefinitions: array_map(fn(string $expression) => new ScheduleDefinition(
                jobAlias: $job['alias'],
                expression: $expression,
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
            stateAlias: $job['stateAlias'] ?? null,
            scheduleDefinitions: array_map(fn(array $schedule) => new ScheduleDefinition(
                jobAlias: $job['alias'],
                expression: $schedule['expression'],
                creatorAlias: $schedule['creatorAlias'] ?? null,
                stateAlias: $schedule['stateAlias'] ?? null,
            ), $job['schedules'])
        ), $structs);

        return $definitions;
    }
}