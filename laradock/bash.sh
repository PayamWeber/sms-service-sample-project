cd "$(realpath "$0" | sed 's|\(.*\)/.*|\1|')"

export $(grep '^[^#.]*=[^".:#]*$' .env | xargs -d '\n')

docker exec -it -u laradock ${COMPOSE_PROJECT_NAME}_workspace_1 bash
