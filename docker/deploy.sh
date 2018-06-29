APP_NAME=''
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

case "$1" in
    production)
        APP_NAME=$APP_PROD_NAME;;
    development)
        APP_NAME=$APP_DEV_NAME;;
    *)
        APP_NAME=$APP_DEV_NAME
esac

cp .netrc $HOME/.

echo $DOCKER_PASSWORD | docker login -u $DOCKER_USERNAME --password-stdin registry.heroku.com
docker build -t registry.heroku.com/${APP_NAME}/web $DIR/../.
docker push registry.heroku.com/${APP_NAME}/web
heroku container:release web -a $APP_NAME