# SMS Gateway

Here's an app for sending sms to users. This app needs RabbitMQ, Mysql, php8.1, Nginx.

## Installation

First you need to install mysql, php8.1, composer and rabbitmq by yourself or by using docker in "laradock" folder. More info on how to run this laradock see details down bellow.

Then modify make .env file based on .env.example and modify your configuration. Here's an example:

```dotenv
DEBUGGING=false

RABBITMQ_HOST=127.0.0.1
RABBITMQ_PORT=5672
RABBITMQ_USERNAME=guest
RABBITMQ_PASSWORD=guest
RABBITMQ_QUEUE_NAME=default

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=default
DB_USERNAME=root
DB_PASSWORD=root

NOTIFICATION_DRIVER=kavenegar
```

Then run these commands:
```bash
composer install
php migrations.php
php seeders.php
```

## Usage
* First of all you need to import the postman exported file to your Postman App.
* Then use "sms send" route to send a sms.
* Then run command ``php consume.php``. This command will send sms which was stored in rabbitmq.

You can put command ``php consume.php`` in supervisor

## Notifications Log In Database
Everytime a notification send, we store a record in mysql for that in notification_logs table.

## Notifications Driver
To add a new driver for sms sending, you need to do a few steps
* First go to `app/NotificationSenders` folder and make a new sender class
```php
class SampleNotification extends BaseNotification
{
    public function send(): bool
    {
        // send sms here

        return true;
    }
}
```
* Then go to `app/MessageConsumer.php` file and add the class into the list of drivers
```php
$notificationSender = match ($_ENV['NOTIFICATION_DRIVER'] ?? 'kavenegar') {
    'kavenegar' => new KavenegarNotification(),
    'qasedak' => new QasedakNotification(),
    // your new driver comes here
};
```
* Then change the current active driver in .env file

## Tests
For testing, you need to do some steps
* Copy your `.env` file and rename it to `.env.testing`
* Go to phpmyadmin and make a new database
* In that file you need to change the name of the database to the new database name
* Then run command bellow to run tests. 
```bash
./test
```

## Docker
* First you need to go "laradock" folder.
* Then copy `.env.example` file to `.env` file in that folder and change it for your needs. These are the most important things you want to change:
* Change these ports to not get conflicted with your existing apps
```dotenv
WORKSPACE_SSH_PORT=9762
WORKSPACE_BROWSERSYNC_HOST_PORT=9760
WORKSPACE_BROWSERSYNC_UI_HOST_PORT=9761
WORKSPACE_VUE_CLI_SERVE_HOST_PORT=9756
WORKSPACE_VUE_CLI_UI_HOST_PORT=9758
WORKSPACE_ANGULAR_CLI_SERVE_HOST_PORT=9759
PHP_FPM_XDEBUG_PORT=9763
NGINX_HOST_HTTP_PORT=9751
NGINX_HOST_HTTPS_PORT=9752
MYSQL_PORT=9753
RABBITMQ_NODE_HOST_PORT=5672
RABBITMQ_MANAGEMENT_HTTP_HOST_PORT=15672
RABBITMQ_MANAGEMENT_HTTPS_HOST_PORT=15671
RABBITMQ_WEB_STOMP_HOST_PORT=15674
PMA_PORT=9754
```
* Then run ``docker-compose up -d rabbitmq mysql phpmyadmin workspace nginx php-fpm`` command

For going into workspace container and running "php" and "composer" commands you can execute ``./bash.sh`` script inside laradock folder.