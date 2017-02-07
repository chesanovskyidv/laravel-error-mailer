<p align="right">
<a href="README.md">Описание на английском</a> | Описание на русском 
</p>

# Laravel 5 Error Mailer

[![Latest Stable Version][ico-stable-version]][link-stable-packagist]
[![Latest Unstable Version][ico-unstable-version]][link-unstable-packagist]
[![License][ico-license]](LICENSE.md)

__Для Laravel ниже версии 5.4 смотрите [версию 1.0.0](https://github.com/bwtgroup/laravel-error-mailer/tree/v1.0.0)__

Этот пакет помогает легко включить и настроить отправку email оповещений об ошибках в случае их возникновения.
 
### Содержание

- [Установка](#Установка)
- [Настройка в Laravel](#Настройка-в-laravel)
- [Лицензия](#Лицензия)

### Установка

Установите этот пакет с помощью composer используя следующую команду:

```bash
composer require bwt-team/laravel-error-mailer
```

### Настройка в Laravel

После обновления composer добавьте service provider в начало массива `providers` в `config/app.php`. 

```php
BwtTeam\LaravelErrorMailer\Providers\ErrorMailerServiceProvider::class
```

Для публикации файла с настройками используйте команду:

```bash
php artisan vendor:publish --provider="BwtTeam\LaravelErrorMailer\Providers\ErrorMailerServiceProvider" --tag=config
```

### Лицензия

Этот пакет использует лицензию [MIT](LICENSE.md).

[ico-stable-version]: https://poser.pugx.org/bwt-team/laravel-error-mailer/v/stable?format=flat-square
[ico-unstable-version]: https://poser.pugx.org/bwt-team/laravel-error-mailer/v/unstable?format=flat-square
[ico-license]: https://poser.pugx.org/bwt-team/laravel-error-mailer/license?format=flat-square

[link-stable-packagist]: https://packagist.org/packages/bwt-team/laravel-error-mailer
[link-unstable-packagist]: https://packagist.org/packages/bwt-team/laravel-error-mailer#dev-develop