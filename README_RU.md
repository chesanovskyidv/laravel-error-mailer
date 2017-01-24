<p align="right">
<a href="README.md">Описание на английском</a> | Описание на русском 
</p>

# Laravel 5 Error Mailer

[![Latest Stable Version][ico-stable-version]][link-stable-packagist]
[![Latest Unstable Version][ico-unstable-version]][link-unstable-packagist]
[![License][ico-license]](LICENSE.md)

Этот пакет помогает легко включить и настроить отправку email оповещений об ошибках в случае их возникновения.
 
### Содержание

- [Установка](#Установка)
- [Настройка в Laravel](#Настройка-в-laravel)
- [Настройка в Lumen](#Настройка-в-lumen)
- [Лицензия](#Лицензия)

### Установка

Установите этот пакет с помощью composer используя следующую команду:

```bash
composer require bwt-team/laravel-error-mailer
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
    ```
    composer require illuminate/mail
    ```
   - Скопируйте [файл конфигураций](https://github.com/laravel/laravel/blob/master/config/mail.php) в папку `config` находящуюсь в корневом каталоге (создайте папку сами, если она отсутствует).
   - Скопируйте файл конфигураций `vendor/bwt-team/laravel-error-mailer/config/error-mailer.php` в папку `config` находящуюсь в корневом каталоге (создайте папку сами, если она отсутствует) и настройте его в соответствии с вашими нуждами.
   - В файле `bootstrap/app` подключите сервис провайдер.
    ```
    $app->register(\Illuminate\Mail\MailServiceProvider::class);
    ```
   - Загрузите настройки из файла настроек.
    ```
    $app->configure('mail');
    $app->configure('error-mailer');
    ```

Для включения отправки сообщений об ошибках в файле `bootstrap/app` необходимо создать экземпляр класса `\BwtTeam\LaravelErrorMailer\Configurators\MailConfigurator`, конструктор которого выглядит следующим образом:

```
 public function __construct($subject, $to, $from, array $processors = [], $logLevel = Logger::ERROR, \Swift_Transport $sendmailTransport = null)
```

Данный класс отвечает за отправку сообщений об ошибках по почте, но его включение отключит запись логов в файл, которая по умолчанию делается в lumen.
Чтоб не отключать запись вам необходимо создать экземпляр класса `\BwtTeam\LaravelErrorMailer\Configurators\FileConfigurator`. Конструктор этого класса выглядит следующим образом:

```
 public function __construct($file = null, $logLevel = Logger::DEBUG)
```

После этого необходимо передать этот экземпляр в метод `with` класса `\BwtTeam\LaravelErrorMailer\Configurators\MailConfigurator`.
Каждый класс для конфигурации имеет возможность работы с монологовскими процессорами. Для этого необходимо передать экземпляр процессора или имя класса в конструктор или добавить, используя метод `addProcessors`.
Помимо стандартных процессоров монолога, из коробки доступны следующие процессоры:
    
```php
 \BwtTeam\LaravelErrorMailer\Processors\SqlProcessor::class,
 \BwtTeam\LaravelErrorMailer\Processors\PostDataProcessor::class,
 \BwtTeam\LaravelErrorMailer\Processors\HeadersProcessor::class,
```

После настройки компонента необходимо передать его в метод `configureMonologUsing` класса Application, до того как этот класс будет возвращен.<br />
Итоговая настройка будет выглядеть примерно следующим образом:

```
$configurator = new \BwtTeam\LaravelErrorMailer\Configurators\MailConfigurator('subject', 'to@example.com', 'from@example.com');
$configurator->setSendmailTransport(\Swift_MailTransport::newInstance());
$configurator->addProcessors([
     \BwtTeam\LaravelErrorMailer\Processors\SqlProcessor::class,
     \BwtTeam\LaravelErrorMailer\Processors\PostDataProcessor::class,
     \BwtTeam\LaravelErrorMailer\Processors\HeadersProcessor::class,
     \Monolog\Processor\WebProcessor::class
]);
$configurator->with(new \BwtTeam\LaravelErrorMailer\Configurators\FileConfigurator());
$app->configureMonologUsing($configurator);
```

### Лицензия

Этот пакет использует лицензию [MIT](LICENSE.md).

[ico-stable-version]: https://poser.pugx.org/bwt-team/laravel-error-mailer/v/stable?format=flat-square
[ico-unstable-version]: https://poser.pugx.org/bwt-team/laravel-error-mailer/v/unstable?format=flat-square
[ico-license]: https://poser.pugx.org/bwt-team/laravel-error-mailer/license?format=flat-square

[link-stable-packagist]: https://packagist.org/packages/bwt-team/laravel-error-mailer
[link-unstable-packagist]: https://packagist.org/packages/bwt-team/laravel-error-mailer#dev-develop