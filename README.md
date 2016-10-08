## Laravel 5 Error Mailer

Этот пакет помогает легко включить и настроить отправку email оповещений об ошибках, в случае их возникновения.
 
### Install

Установите этот пакет с помощью composer используя следующую команду:

```bash
composer require "bwt-team/laravel-error-mailer":"dev-develop"
```

После обновления composer, добавьте service provider в массив `providers` в `config/app.php`. 

```bash
BwtTeam\LaravelErrorMailer\Providers\ErrorMailerServiceProvider::class
```

Этот service provider предоставит возможность опубликовать конфигурационный файл, чтоб изменить настройки пакета исходя из ваших потребностей.
После публикации настроек этот сервис провайдер можно отключить, для работы пакета он не нужен. Для публикации используйте команду:

```bash
php artisan vendor:publish --provider="BwtTeam\LaravelErrorMailer\Providers\ErrorMailerServiceProvider" --tag=config
```

Также чтоб данный пакет заработа необходимо в `bootstrap/app.php` зарегестрировать класс - настройщик. Регистрация должна быть до того как возвращается экземляр Application.

```bash
$app->bind(
    \Illuminate\Foundation\Bootstrap\ConfigureLogging::class,
    \BwtTeam\LaravelErrorMailer\ConfigureLogging::class
);
```