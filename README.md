# Redator API
### Web content management made easy

## Description
Redator is an open source project that contains an API to manage web content. It's super easy to install, thanks to **Docker**. With it you can create publication for your websites with your team, or even by yourself.

## Installation
First of all:
```bash
# Clone the repo
$ git clone https://github.com/flavio-jr/redator-api

# Set up the .env file
$ cp .env.example .env
```

If you are going to use Docker(which I recommend), you will be ready just running the following commands (remember to set the DB_HOST env variable to pgsql):
```bash
# Will run the api on http://localhost
$ docker-compose up -d

# Install the dependencies
$ docker exec redator composer install
```
And that's all, folks!

To run the API on your machine there are some requirements:

* PHP >= 7
* Composer
* One of the following relational databases -> [ Postgresql, Mysql, Sqlite ]
  
You can run the api with the following commands:
```bash
# Install the dependencies
$ composer install

# Get the api running on http://localhost:8000
$ php -S localhost:8000 -t public
```

## <a name="docs"></a>Documentation
The api documentation can be found at https://app.swaggerhub.com/apis/flavio-jr/Redator/1.0.0

## How it works
There are 3 actors in the API: users, applications and publications. To create publications you must understand the following about them:

#### Publications
The publication is the web content, it stores the HTML markup. It can have a category, to help filtering them.

#### Applications
An application is the representation of the website that going to consume the API. It's where you store the publications. The application has an owner, which can create new publications to it. Beyond that, it can have a **Team**, a group of users that can write publications to that application.

#### Users
There are 3 types of users: Master, Partner and Writter. Here is a brief explanation about each:
* Master: Can do anything, register new users, exclude others and can create publications in any registered applications. It can enable/disable any user and remove they from application **teams**.
* Partner: Can create new applications, register new users and enable/disable they. This user can add new users to they application(s) team(s).
* Writter: This type of user can only create publications in applications which it are part of.

> **Note**: The master user can only be created via [cli](#create-master)

To get further explanation, read the [docs](#docs).

## CLI
The project has a small cli application to help manage the Master user data(username and password).

* <a name="create-master"></a>Command to create the **Master User** with the password defined in the $USER_DEFAULT_PASSWORD env variable:
```bash
# Creates a master user with the master username
$ php bin/console user:create-master
```
* Command to update the **Master User** name and username:
```bash
# Will prompt for the username and name fields
$ php bin/console user:update-master
```