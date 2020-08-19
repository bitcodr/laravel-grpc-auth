FROM nginx:1.19.2-alpine

COPY ./.docker/config/nginx/default.conf /etc/nginx/conf.d/default.conf
