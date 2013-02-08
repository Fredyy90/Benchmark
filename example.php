<?php

require_once "src/Fredyy90/Benchmark.php";

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

$benchmark->time( 'str_replace split', function () {

    $replace_array = array("%time%" => 'time', "%date%" => 'date');
    $string = "am %date% um %time%";

    $string = str_replace(array_keys($replace_array), array_values($replace_array), $string);

}, true);

$benchmark->time( 'str_replace 2 arrays', function () {

    $search = array("%time%", "%date%");
    $replace = array("time", "date");
    $string = "am %date% um %time%";

    $string = str_replace($search, $replace, $string);

}, true);


echo $benchmark->get_results_table();
echo "<pre>";
var_dump($benchmark->get_results());
var_dump($benchmark->get_extended_results());
echo "</pre>";