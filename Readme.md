[![Total Downloads](https://img.shields.io/packagist/dt/josh/restexception.svg?style=flat-square)](https://packagist.org/packages/josh/restexception)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/iamalirezaj/restexception.svg?style=flat-square)](https://scrutinizer-ci.com/g/iamalirezaj/restexception/?branch=develop)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://packagist.org/packages/josh/restexception)

# Josh RestException
Handle Exceptions with json response in laravel

## Requirement
* php 5.5 >=

## Install with composer
You can install this package throw the [Composer](http://getcomposer.org) by running:

```
composer require josh/restexcpetion
```

## Use RestException in Exception Handler
* you should use the RestException in Handler object
* The Exception Handler is here ``` app\Exceptions\Hanldler.php ```

```php
class Handler extends ExceptionHandler
{
    /**
     * Use rest exception in class
     */
    use \Josh\Exception\RestException;
```

* then call the renderRestException method in render method

```php
return $this->renderRestException($request , $exception);
```

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
