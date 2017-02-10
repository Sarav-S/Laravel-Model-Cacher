# Laravel Model Cacher

### A simple laravel package to cache database values and manage them efficiently through model events

- **Laravel**: 5.4.*
- **Author**: Sarav
- **Author Homepage**: http://sarav.co

##Step 1 : Require Composer package##

Open your terminal and navigate to your laravel folder. Now run the following command

	composer require sarav/model-cacher

or 

    "require": {
        "sarav/model-cacher": "^0.1"
    }

##Step 2 : Add the Service Provider to app.php##

Once you have installed the package, add the service provider to app.php file

    Sarav\Providers\ModelCacherServiceProvider::Class

##Step 3 : Publish the config file ##

After adding the service provider, publish the configuration file.

    php artisan vendor:publish --tag=public --force

> If nothing gets published, try clearing the cache by running *php artisan config:cache*

##Step 4 : Configuring the cacheable.php ##

When you publish, you will see cacheable.php under *config* folder. Open up the file and you see
array containing the models and timing for cache to remember. Add the list of models with namespace
for which you wanted to add caching.

##Step 5 : Configuring the model ##

Add the CacheHandler trait for the model you wanted to manage the caching

    <?php

	namespace App;

	use Sarav\Traits\CacheHandler;
	use Illuminate\Notifications\Notifiable;
	use Illuminate\Foundation\Auth\User as Authenticatable;

	class User extends Authenticatable
	{
	    use Notifiable, CacheHandler;

	    protected $individualCache = true;

	    protected $cacheName = "users";
	}

Here **$individualCache** means you are trying to cache each and every row separately. If you want to cache it on the whole, you could remove that and add 

    protected $cacheAll = true;

**$cacheName** represents the name of the caching. For individual caching it will be like users.{id} ie, users.1. For whole caching it will be simply users.
