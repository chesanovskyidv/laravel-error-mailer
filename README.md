<p align="right">
Описание на русском | <a href="README_EN.md">English description</a> 
</p>

# Laravel 5 Error Mailer

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)

Этот пакет помогает легко включить и настроить отправку email оповещений об ошибках в случае их возникновения.
 
### Содержание

- [Установка](#Установка)
- [Настройка в Laravel](#Настройка-в-laravel)
- [Настройка в Lumen](#Настройка-в-lumen)
- [Авторы](#Авторы)
- [Лицензия](#Лицензия)

### Установка

Установите этот пакет с помощью composer используя следующую команду:

```bash
composer require "bwt-team/laravel-error-mailer":"dev-develop"
```

### Настройка в Laravel

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

### Настройка в Lumen

Для отправки сообщений по почте вы можете воспользоваться компонентом laravel, либо создать класс для отправки сами, проинициализировав класс, реализуюзий интерфейс \Swift_Transport.
Для настройки компонента laravel:

   - Установите компонент используя следующую команду:
    ```bash
    composer require illuminate/mail
    ```
   - Скопируйте [файл конфигураций](https://github.com/laravel/laravel/blob/master/config/mail.php) в папку `config` находящуюсь в корновом каталоге (создайте папку сами, если она отсутствует).
   - В файле `bootstrap/app` подключить сервис провайдер.
    ```bash
    $app->register(\Illuminate\Mail\MailServiceProvider::class);
    ```
   - Загрузить настройки из файла настроек.
    ```
    $app->configure('mail');
    ```

Для включения отправки сообщений об ошибках в файле `bootstrap/app` необходимо создать экземпляр класса `\BwtTeam\LaravelErrorMailer\Configurators\MailConfigurator`, конструктор которого выглядит следующим образом:

```
 public function __construct($subject, $to, $from, array $processors = [], $logLevel = Logger::ERROR, \Swift_Transport $sendmailTransport = null)
```

Этот класс имеет геттер и сеттер для каждого параметра, так что вам не обязательно передавать все параметры сразу при создании экземпляра класса.
Данный класс отвечает за отправку сообщений об ошибках по почте, но его включение отключит запись логов в файл, которая по умолчанию делается в lumen.
Чтоб не отключать запись вам необходимо создать экземпляр класса `\BwtTeam\LaravelErrorMailer\Configurators\FileConfigurator`. Конструктор этого класса выглядит следующим образом:

```
 public function __construct($file = null, $logLevel = Logger::DEBUG)
```
 
После этого необходимо передать этот экземпляр в метод `with`, класса `\BwtTeam\LaravelErrorMailer\Configurators\MailConfigurator`.
После настройки нашего компонента необходимо передать его в метод `configureMonologUsing` класса Application до того как этот класс будет возвращен.<br />
Итоговая настройка будет выглядеть примерно следующим образом:

```
$configurator = new \BwtTeam\LaravelErrorMailer\Configurators\MailConfigurator('subject', 'to@example.com', 'from@example.com');
$configurator->setSendmailTransport(\Swift_MailTransport::newInstance());
$configurator->with(new \BwtTeam\LaravelErrorMailer\Configurators\FileConfigurator());
$app->configureMonologUsing($configurator);
```

### Авторы

Этот пакет создан командой [BWT](http://www.groupbwt.com/) и [Chesanovskiy Denis](mailto:chesanovskiy_dv@gmail.com) в частности.

### Лицензия

This package is licensed under the [MIT license](LICENSE.md).

[ico-version]: https://img.shields.io/badge/packagist-dev--develop-orange.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/bwt-team/laravel-error-mailer#dev-develop