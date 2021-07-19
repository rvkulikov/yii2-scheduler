### Подключение к приложению через docker-compose
```yaml
  myapp.scheduler:
    restart: always
    image: ghcr.io/rvkulikov/yii2-scheduler/scheduler:latest
    environment:
      - PORT=3000
      - REFRESH_INTERVAL=60
      - BASE_URL=myapp.nginx-internal
    links:
      - myapp.nginx-internal
```

### Миграции для схемы бд
```postgresql
-- noinspection SqlNoDataSourceInspectionForFile
set role "postgres";

do $$ begin
  create user "_app_admin" with createrole password 'password';
  exception
    when duplicate_object
      then raise notice 'not creating user "_app_admin"';
end $$;

do $$ begin
  create role "_app_reader" with noinherit;
  exception
    when duplicate_object
      then raise notice 'not creating role "_app_reader"';
end $$;

create schema if not exists "_app_schedule";
alter schema "_app_schedule" owner to "_app_admin";

grant all on schema "_app_schedule" to "_app_admin";

grant all on all tables in schema "_app_schedule" to "_app_admin";
grant all on all sequences in schema "_app_schedule" to "_app_admin";
grant all on all functions in schema "_app_schedule" to "_app_admin";
grant all on all routines in schema "_app_schedule" to "_app_admin";

alter default privileges in schema "_app_schedule" grant all on tables to "_app_admin";
alter default privileges in schema "_app_schedule" grant all on sequences to "_app_admin";
alter default privileges in schema "_app_schedule" grant all on functions to "_app_admin";
alter default privileges in schema "_app_schedule" grant all on routines to "_app_admin";

-- grant reader
grant usage on schema "_app_schedule" to "_app_reader";

grant select on all tables in schema "_app_schedule" to "_app_reader";
grant select on all sequences in schema "_app_schedule" to "_app_reader";

alter default privileges in schema "_app_schedule" grant select on tables to "_app_reader";
alter default privileges in schema "_app_schedule" grant select on sequences to "_app_reader";

set role "_app_admin";
  alter default privileges in schema "_app_schedule" grant select on tables to "_app_reader";
  alter default privileges in schema "_app_schedule" grant select on sequences to "_app_reader";
set role postgres;
```

### Настройки в конфиге приложения
```php
<?php
use Rvkulikov\Yii2\Scheduler\Components\ConnectionLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Components\JobsLocator;
use Rvkulikov\Yii2\Scheduler\Components\JobsLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Components\SettingsLocatorInterface;
use Rvkulikov\Yii2\Scheduler\Module as ScheduleModule;

return [
    'modules'             => [
        /// регистрировать можно только в консольном приложении
        /// или в приложении, которое не смотрит наружу, так как
        /// в контроллерах нет авторизации
        'schedule' => ['class' => ScheduleModule::class],
    ],
    'container' => [
        'singletons' => [    
            ConnectionLocatorInterface::class => [
                'db'     => 'dbApp',
                'schema' => $params['db_app.schema_app_schedule']
            ],
    
            JobsLocatorInterface::class => [
                'preprocessor' => [JobsLocator::class, 'preprocessTuples'],
                'definitions'  => fn() => require __DIR__ . '/jobs.php'
            ],
            SettingsLocatorInterface::class => [
                'cronEnabled'  => fn() => \Yii::$app->params['app.schedule.cronEnabled']
            ],        
        ]       
    ],
];
```

### Сборка записей для планировщика
```php
<?php

return array_merge(...[
    AppJobInvoker::buildJobDefinitionTuples(),
    []
]);
```

### Пример класса для регистрации записей планировщика
```php
<?php
class AppJobInvoker
{
    public const JOB_1 = 'app/job1';
    public const JOB_2 = 'app/job2';
    public const JOB_3 = 'app/job3';
    public const JOB_4 = 'app/job4';
    public const JOB_5 = 'app/job5';

    public static function buildJobDefinitionTuples(): array
    {
        return [
            //@formatter:off
            [self::JOB_1, [self::class, 'job1'], ["*/3  * * * * *"]],
            [self::JOB_2, [self::class, 'job2'], ["*/5  * * * * *"]],
            [self::JOB_3, [self::class, 'job3'], ["*/7  * * * * *"]],
            [self::JOB_4, [self::class, 'job4'], ["*/11 * * * * *"]],
            [self::JOB_5, [self::class, 'job5'], ["*/13 * * * * *"]],
            //@formatter:on
        ];
    }

    public static function job1(): array
    {
        return ['job_id' => 1];
    }

    public static function job2(): array
    {
        return ['job_id' => 2];
    }

    public static function job3(): array
    {
        return ['job_id' => 3];
    }

    public static function job4(): array
    {
        return ['job_id' => 4];
    }

    public static function job5(): array
    {
        return ['job_id' => 5];
    }
}
```

### Обновление записей в бд (после добавления новой задачи, например) 
```bash
php yii schedule/migrate/up
php yii schedule/migrate-jobs/run --strategy soft
```