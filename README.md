# Installation

1. Run composer

```
composer require sergeich5/laravel-ip-middleware
```

2. Publish `config` file

```bash
php artisan vendor:publish --tag=ip-middleware
```

3. Use `\Sergeich5\LaravelIpMiddleware\Http\Middleware\IpFilterMiddleware` wherever you want
