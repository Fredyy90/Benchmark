Benchmark
====

Simple Class to Benchmark and compare PHP-Code


# Installation

To install LTSV-Serializer with Composer just add the following to your composer.json file:

```js
// composer.json
{
    // ...
    require: {
        // ...
        "fredyy90/Benchmark": "dev-master"
    }
}
```

Then, you can install the new dependencies by running Composer’s update command from the directory where your composer.json file is located:

```sh
# install
$ php composer.phar install
# update
$ php composer.phar update fredyy90/Benchmark

# or you can simply execute composer command if you set composer command to
# your PATH environment variable
$ composer install
$ composer update fredyy90/Benchmark
```

Packagist page for this library is [https://packagist.org/packages/fredyy90/Benchmark](https://packagist.org/packages/fredyy90/Benchmark)

Or you can use git clone

```sh
# HTTP
$ git clone https://github.com/fredyy90/Benchmark.git
# SSH
$ git clone git@github.com:fredyy90/Benchmark.git
```

# Usage


```php
<?php

use \Fredyy90\Benchmark as Benchmark;

$benchmark = new Benchmark();

$benchmark->time( 'strtr', function () {

    $replace_array = array("%time%" => 'time', "%date%" => 'date');
    $string = "am %date% um %time%";

    $string = strtr($string, $replace_array);

}, true);

$benchmark->time( 'str_replace foreach', function () {

    $replace_array = array("%time%" => 'time', "%date%" => 'date');
    $string = "am %date% um %time%";

    foreach($replace_array as $key=>$value) $string = str_replace($key,$value,$string);

}, true);


echo $benchmark->get_results_table();
echo "<pre>";
var_dump($benchmark->get_results());
var_dump($benchmark->get_extended_results());
echo "</pre>";
```
