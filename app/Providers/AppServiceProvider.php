<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DatabaseConfigServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Surcharger la configuration PDO pour PHP 8.5
        if (version_compare(PHP_VERSION, '8.5.0', '>=')) {
            $this->app->config->set('database.connections.mysql.options', [
                \Pdo\Mysql::ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]);
            
            $this->app->config->set('database.connections.mariadb.options', [
                \Pdo\Mysql::ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]);
        }
    }

    public function boot()
    {
        //
    }
}