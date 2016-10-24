<p align="right">
English description | <a href="README_RU.md">Russian description</a> 
</p>

# Laravel 5 Error Mailer

[![Latest Stable Version][ico-stable-version]][link-stable-packagist]
[![Latest Unstable Version][ico-unstable-version]][link-unstable-packagist]
[![License][ico-license]](LICENSE.md)

This package allows to enable and set up email alerts in case errors appear. 
 
### Contents

- [Setup](#setup)
- [Setup in Laravel](#setup-in-laravel)
- [Setup in Lumen](#setup-in-lumen)
- [License](#license)

### Setup

Setup this package with composer using the following command:

```bash
composer require bwt-team/laravel-error-mailer
```

### Setup in Laravel

After composer update add service provider into `providers` in `config/app.php`. 

```php
BwtTeam\LaravelErrorMailer\Providers\ErrorMailerServiceProvider::class
```

This service provider will enable with an option to publish config file to update
package settings depending on your needs. 
After publication this service provider can be disabled, it is not needed for package work. For publication please use:

```bash
php artisan vendor:publish --provider="BwtTeam\LaravelErrorMailer\Providers\ErrorMailerServiceProvider" --tag=config
```

Also, to make package work please register the setup class in `bootstrap/app.php`. Registration should happen before Application sample is returned.

```php
$app->bind(
    \Illuminate\Foundation\Bootstrap\ConfigureLogging::class,
    \BwtTeam\LaravelErrorMailer\ConfigureLogging::class
);
```

### Setup in Lumen

To send email messages you can use laravel component or create a class for sending yourself by initializing class, which implements \Swift_Transport interface.
To set up laravel component:

   - setup component using the following command:
    ```
    composer require illuminate/mail
    ```
   - copy [config file](https://github.com/laravel/laravel/blob/master/config/mail.php) into  `config` directory, which is stored in root catalogue (or create the directory yourself). 
   - enable service provider in `bootstrap/app`.
    ```
    $app->register(\Illuminate\Mail\MailServiceProvider::class);
    ```
   - setup settings from configuration file.
    ```
    $app->configure('mail');
    ```

To enable sending email alerts you need to create a class instance `\BwtTeam\LaravelErrorMailer\Configurators\MailConfigurator` in   `bootstrap/app`, its constructor looks like following: 

```
 public function __construct($subject, $to, $from, array $processors = [], $logLevel = Logger::ERROR, \Swift_Transport $sendmailTransport = null)
```

This class is responsible for sending alert emails by mail but enabling it will disable writing logs into file, which is enabled in lumen by default. In order not to disable
writing you need to create a class instance `\BwtTeam\LaravelErrorMailer\Configurators\FileConfigurator`. Class constructor looks like the following: 

```
 public function __construct($file = null, $logLevel = Logger::DEBUG)
```

After that you need to pass this instance into `with` method in `\BwtTeam\LaravelErrorMailer\Configurators\MailConfigurator`class.
Each configuration class has options to work with monologue processors. For this you need to pass process instance to class name into constructor or add with the help of  `addProcessors` method.
In addition to standard monologue processors, the following out-of-box processors are available:
 
```php
 \BwtTeam\LaravelErrorMailer\Processors\SqlProcessor::class,
 \BwtTeam\LaravelErrorMailer\Processors\PostDataProcessor::class,
 \BwtTeam\LaravelErrorMailer\Processors\HeadersProcessor::class,
```

When component is set up you need to pass it to `configureMonologUsing` method  in  Application class before it is returned.<br />
Final setup will look like this:

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

### License

This package is using [MIT](LICENSE.md).

[ico-stable-version]: https://poser.pugx.org/bwt-team/laravel-error-mailer/v/stable?format=flat-square
[ico-unstable-version]: https://poser.pugx.org/bwt-team/laravel-error-mailer/v/unstable?format=flat-square
[ico-license]: https://poser.pugx.org/bwt-team/laravel-error-mailer/license?format=flat-square

[link-stable-packagist]: https://packagist.org/packages/bwt-team/laravel-error-mailer
[link-unstable-packagist]: https://packagist.org/packages/bwt-team/laravel-error-mailer#dev-develop