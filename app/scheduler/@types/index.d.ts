import {ScheduledTask} from "node-cron";

declare namespace app {
    type CreateTuple = [string, Schedule];
    type RemoveTuple = [string, ScheduledTask];
    type CronSchedulesEnvelope = ResponseEnvelope<Schedule>;

    type ResponseEnvelope<T> = {
        items: T[];
        _links?: {
            self: string;
            next?: string;
            prev?: string;
            first?: string;
            last?: string;
        };
        _pagination?: {
            totalCount: string;
            pageCount: string;
            currentPage: string;
            perPage: string;
        };
        _runtime: {
            cronEnabled: boolean;
        }
    }

    type Job = {
        job_id: string;
        job_alias: string;
        job_title: string;
        job_description: string;
        job_state_alias: string;
    }

    type Schedule = {
        schedule_job_alias: string;
        schedule_expression: string;
        schedule_state_alias: string;
        schedule_creator_alias: string;
    }

    type TaskManagerConfig = {
        refreshInterval: number,
        baseUrl: string,
        scheduleIndexPath: string,
        jobInvokePath: string,
    }
}
