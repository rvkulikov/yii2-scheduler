# syntax = docker/dockerfile:1.0-experimental
FROM node as compile

WORKDIR /app
COPY ./app/scheduler /app

RUN yarn install
RUN yarn clean

FROM node as install

WORKDIR /app
COPY ./app/scheduler /app

RUN yarn install --prod

FROM node

ENV PORT = 3000;
ENV REFRESH_INTERVAL = 5;
ENV BASE_URL = 'yii2-scheduler.scheduler';
ENV SCHEDULE_INDEX_PATH = 'schedule/schedules/index';
ENV JOB_INVOKE_PATH = 'schedule/jobs/invoke';

WORKDIR /app
COPY ./app/scheduler /app

COPY --from=compile /app/dist /app/dist
COPY --from=install /app/node_modules /app/node_modules

ENTRYPOINT ["node", "/app/bin/www"]