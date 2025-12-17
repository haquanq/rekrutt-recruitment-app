FROM nginx:alpine

WORKDIR .

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./apps/server/public /var/www/public
