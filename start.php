<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
set_time_limit(0);
die('remove this');

require_once('Parser.php');
$parser = new Parser();
$agencies = array();

$agencies = $parser->parse('http://www.holiday.by/agencies/');
var_dump(count($agencies));

$fp = fopen('/tmp/parsing.csv', 'w');
foreach($agencies as $val){
    fputcsv($fp, (array)$val);
}
