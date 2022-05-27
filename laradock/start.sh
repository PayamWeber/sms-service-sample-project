cd "$(realpath "$0" | sed 's|\(.*\)/.*|\1|')"

export $(grep '^[^#.]*=[^".:#]*$' .env | xargs -d '\n')

docker start ${COMPOSE_PROJECT_NAME}_workspace_1 ${COMPOSE_PROJECT_NAME}_php-fpm_1 \
${COMPOSE_PROJECT_NAME}_docker-in-docker_1 ${COMPOSE_PROJECT_NAME}_mysql_1 ${COMPOSE_PROJECT_NAME}_phpmyadmin_1 \
${COMPOSE_PROJECT_NAME}_redis_1 ${COMPOSE_PROJECT_NAME}_nginx_1

