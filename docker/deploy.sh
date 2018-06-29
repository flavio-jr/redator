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

echo $DOCKER_PASSWORD | docker login -u $DOCKER_USERNAME --password-stdin registry.heroku.com

docker build -t registry.heroku.com/${APP_NAME}/web $DIR/../.

docker push registry.heroku.com/${APP_NAME}/web

curl -n -X PATCH https://api.heroku.com/apps/$APP_NAME/formation \
  -d '{
  "updates": [
    {
      "type": "web",
      "docker_image": "'$(docker inspect registry.heroku.com/$APP_NAME/web --format={{.Id}})'"
    }
  ]
}' \
  -H "Content-Type: application/json" \
  -H "Accept: application/vnd.heroku+json; version=3.docker-releases" \
  -H "Authorization: Bearer $HEROKU_API_TOKEN"

echo 'The release was completed'