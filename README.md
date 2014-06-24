## Fast for Laravel 4

Fast is a robot making cache instead of your client.

### Installation

- [Fast on Packagist](https://packagist.org/packages/teepluss/fast)
- [Fast on GitHub](https://github.com/teepluss/laravel4-fast)

To get the latest version of Theme simply require it in your `composer.json` file.

~~~
"teepluss/fast": "dev-master"
~~~

You'll then need to run `composer install` to download it and have the autoloader updated.

Once Theme is installed you need to register the service provider with the application. Open up `app/config/app.php` and find the `providers` key.

~~~
'providers' => array(

    'Teepluss\Fast\FastServiceProvider',

)
~~~

Fast also ships with a facade which provides the static syntax for creating collections. You can register the facade in the `aliases` key of your `app/config/app.php` file.

~~~
'aliases' => array(

    'Fast' => 'Teepluss\Fast\Facades\Fast',

)
~~~

Publish config using artisan CLI.

~~~
php artisan config:publish teepluss/fast
~~~

### Basic usage

Remember your content in (x) seconds.

~~~php
$content = Fast::expireInSecond(10)->remember('key-of-page', function()
{
    $html = 'Your HTML Goes Here.' . rand(1, 1000);

    return $html;
});

return $content;
~~~

Find out the content problem.

~~~php
$content = Fast::debug(true)->expireInSecond(10)->remember('key-of-page', function()
{
    $html = 'Your HTML Goes Here.' . rand(1, 1000);

    return $html;
});

return $content;
~~~

Forget your page cache.

~~~php
Fast::forget('key-of-page');
~~~

Flush all cache.

~~~php
Fast::flush()
~~~

### Working with artisan.

Forget cache.

~~~
php artisan fast:forget key-cache
~~~

Flush all cache.

~~~
php artisan fast:flush
~~~

## Support or Contact

If you have any problems, Contact teepluss@gmail.com


[![Support via PayPal](https://rawgithub.com/chris---/Donation-Badges/master/paypal.jpeg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9GEC8J7FAG6JA)
