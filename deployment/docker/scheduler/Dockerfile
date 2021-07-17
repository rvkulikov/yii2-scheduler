# syntax = docker/dockerfile:1.0-experimental
FROM node:16-alpine3.12 as compile

WORKDIR /app
COPY ./app/scheduler /app

RUN yarn global add typescript
RUN yarn install
RUN yarn build

FROM node:16-alpine3.12 as install

WORKDIR /app
COPY ./app/scheduler /app

RUN yarn install --prod

FROM node:16-alpine3.12

ENV PORT = 3000;
ENV REFRESH_INTERVAL = 5;
ENV BASE_URL = 'yii2-scheduler.scheduler';
ENV SCHEDULE_INDEX_PATH = 'schedule/schedules/index';
ENV JOB_INVOKE_PATH = 'schedule/jobs/invoke';

WORKDIR /app
COPY ./app/scheduler /app

COPY --from=compile /app/dist /app/dist
COPY --from=install /app/node_modules /app/node_modules

CMD ["yarn", "start"]