// noinspection HttpUrlsUsage

import TaskManager from "./src/TaskManager";

const express = require("express");
const logger  = require("morgan");
const axios   = require("axios");

const app = express();

app.use(logger("dev"));
app.use(express.json());
app.use(express.urlencoded({extended: false}));

const config = {
    refreshInterval: parseInt(process.env.REFRESH_INTERVAL || '5', 10) * 1000, // 5 seconds default
    baseUrl: process.env.BASE_URL,
    scheduleIndexPath: process.env.SCHEDULE_INDEX_PATH || "schedule/schedules/index",
    jobInvokePath: process.env.JOB_INVOKE_PATH || "schedule/jobs/invoke"
};

console.log('TaskManager config: ', config);

const taskManager = new TaskManager(axios, config)
taskManager.startInterval();

module.exports = app;