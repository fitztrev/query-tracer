## Laravel Query Tracer

[![Build Status](https://travis-ci.org/fitztrev/query-tracer.svg?branch=master)](https://travis-ci.org/fitztrev/query-tracer) [![Latest Stable Version](https://poser.pugx.org/fitztrev/query-tracer/v/stable)](https://packagist.org/packages/fitztrev/query-tracer)

Find exactly *where* a specific database query is being called in your Laravel application.

Want to optimize or debug your database queries but not sure where they're being called? See below.

Works with Clockwork:

![](http://i.imgur.com/0cRs7TU.png)

And works with Debugbar:

![](http://i.imgur.com/dKm82S2.png)

### Requirements

[Clockwork](https://github.com/itsgoingd/clockwork) or [Debugbar](https://github.com/barryvdh/laravel-debugbar) or your own custom query listener

### Installation

1. Install via composer:

    ```bash
    composer require fitztrev/query-tracer
    ```

2. Add the service provider to your `config/app.php`:

    ```php
    'providers' => [
        // ...
        Fitztrev\QueryTracer\Providers\QueryTracerServiceProvider::class,
    ],
    ```

### How does it do it?

It makes use of Laravel's global query scopes to do a backtrace and find where a query originated. Then it puts that info in extraneous but helpful `WHERE` clauses.

By default, it's only enabled when `debug` is on. You can change this behavior for specific models by adding an `enableQueryTracer()` method to your model(s). For example:

```php
public function enableQueryTracer()
{
    return config('app.env') == 'local';
}
```
