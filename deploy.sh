APP_NAME=''

case "$1" in
    production)
        APP_NAME=$APP_PROD_NAME;;
    development)
        APP_NAME=$APP_DEV_NAME;;
    *)
        APP_NAME=$APP_DEV_NAME
esac

echo $DOCKER_PASSWORD | docker login -u $DOCKER_USERNAME --password-stdin registry.heroku.com
docker build -t registry.heroku.com/${APP_NAME}/web .
docker push registry.heroku.com/${APP_NAME}/web