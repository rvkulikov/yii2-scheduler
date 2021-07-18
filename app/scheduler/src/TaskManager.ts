import {schedule as scheduleJob, ScheduledTask} from "node-cron";
import {app} from "../@types";
import {AxiosInstance} from "axios";
import Dict = NodeJS.Dict;
import CronSchedule = app.Schedule;
import CreateTuple = app.CreateTuple;
import RemoveTuple = app.RemoveTuple;
import CronSchedulesEnvelope = app.CronSchedulesEnvelope;
import TaskManagerConfig = app.TaskManagerConfig;
import Timeout = NodeJS.Timeout;
import Schedule = app.Schedule;

// noinspection HttpUrlsUsage
export default class TaskManager {
    private tasks: Dict<ScheduledTask> = {};
    private intervalId: Timeout;

    constructor(
        private readonly axios: AxiosInstance,
        private readonly config: TaskManagerConfig,
    ) {
    }

    public startInterval() {
        this.intervalId = setInterval(() => this.syncJobs(), this.config.refreshInterval);
        console.log('Interval started', this.intervalId);
    }

    // noinspection JSUnusedGlobalSymbols
    public clearInterval() {
        clearInterval(this.intervalId);
        console.log('Interval cleared', this.intervalId);
        this.intervalId = undefined;
    }

    public setTasks(schedules: CronSchedule[]) {
        const tasks = this.tasks;
        const dict  = schedules.reduce((accum, item) => ({...accum, [this.getTaskKey(item)]: item}), {})

        let create: CreateTuple[] = [];
        let remove: RemoveTuple[] = [];

        for (const key in tasks) {
            if (tasks.hasOwnProperty(key) && !dict.hasOwnProperty(key)) {
                remove.push([key, tasks[key]]);
            }
        }

        for (const key in dict) {
            if (dict.hasOwnProperty(key) && !tasks.hasOwnProperty(key)) {
                create.push([key, dict[key]]);
            }
        }

        create.forEach((tuple) => this.createTask(tuple));
        remove.forEach((tuple) => this.removeTask(tuple));
    }

    public createTask(tuple: CreateTuple) {
        const [key, schedule] = tuple;

        const task = scheduleJob(schedule.schedule_expression, async () => {
            await this.invokeJob(schedule)
        })

        console.log('Task scheduled: ', key, schedule, task);
        this.tasks[key] = task;
    }

    public removeTask(tuple: RemoveTuple) {
        let [key, task] = tuple;
        delete this.tasks[key];
        task.destroy();
        console.log('Task removed: ', key, task);
    }

    public flushTasks() {
        for (let key in this.tasks) {
            if (this.tasks.hasOwnProperty(key)) {
                let task = this.tasks[key];
                this.removeTask([key, task]);
            }
        }
    }

    public getTaskKey(schedule: CronSchedule): string {
        return `${schedule.schedule_job_alias}|${schedule.schedule_expression}`;
    }

    public async syncJobs() {
        const url = `http://${this.config.baseUrl}/${this.config.scheduleIndexPath}`;

        try {
            const {data} = await this.axios.get<CronSchedulesEnvelope>(url);
            data._runtime.cronEnabled
                ? this.setTasks(data.items)
                : this.flushTasks();

            console.debug(
                `[${new Date().toISOString()}]`,
                `[sync_jobs]`,
                data
            )
        } catch (e) {
            console.error(
                `[${new Date().toISOString()}]`,
                `[sync_jobs]`,
                e.message
            );
        }
    }

    public async invokeJob(schedule: Schedule) {
        const url    = `http://${this.config.baseUrl}/${this.config.jobInvokePath}`;

        try {
            const {data} = await this.axios.get(url, {params: {key: schedule.schedule_job_alias}});
            console.debug(
                `[${new Date().toISOString()}]`,
                `[${schedule.schedule_job_alias}]`,
                `[${schedule.schedule_expression}]`,
                data
            );

            return data;
        } catch (e) {
            console.error(
                `[${new Date().toISOString()}]`,
                `[${schedule.schedule_job_alias}]`,
                `[${schedule.schedule_expression}]`,
                e.message
            );
        }

        return undefined;
    }
}
