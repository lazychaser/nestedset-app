This is an example app for [Laravel 4 Nested Set][1] package.

It uses almost all features of the package. The layout is based on 
[Twitter Bootstrap][2] markup.

It is not intended to be used in production due to incomplete browsers support
and other issues.

[1]: http://github.com/lazychaser/laravel4-nestedset
[2]: http://getbootstrap.com

## Installation

To install this application you need to clone this repository and run 
`composer install`. After this you need to configure a database, and run following 
command to set it up:

```
php artisan migrate --seed
```

Note that this application uses latest _dev_ versions of Laravel 4 and NestedSet 
packages.
