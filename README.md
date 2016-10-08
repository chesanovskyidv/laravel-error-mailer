## Laravel 5 Error Mailer

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)

Этот пакет помогает легко включить и настроить отправку email оповещений об ошибках в случае их возникновения.
 
### Install

Установите этот пакет с помощью composer используя следующую команду:

```bash
composer require "bwt-team/laravel-error-mailer":"dev-develop"
```

После обновления composer добавьте service provider в массив `providers` в `config/app.php`. 

```php
BwtTeam\LaravelErrorMailer\Providers\ErrorMailerServiceProvider::class
```

Этот service provider предоставит возможность опубликовать конфигурационный файл, чтоб изменить настройки пакета исходя из ваших потребностей.
После публикации настроек этот service provider можно отключить, для работы пакета он не нужен. Для публикации используйте команду:

```bash
php artisan vendor:publish --provider="BwtTeam\LaravelErrorMailer\Providers\ErrorMailerServiceProvider" --tag=config
```

Также, чтоб данный пакет заработал, необходимо в `bootstrap/app.php` зарегистрировать класс - настройщик. Регистрация должна быть до того как возвращается экземляр Application.

```php
$app->bind(
    \Illuminate\Foundation\Bootstrap\ConfigureLogging::class,
    \BwtTeam\LaravelErrorMailer\ConfigureLogging::class
);
```

[ico-version]: https://img.shields.io/badge/packagist-dev--develop-orange.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/bwt-team/laravel-error-mailer#dev-develop