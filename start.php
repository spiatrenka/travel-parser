<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('Parser.php');
//require_once('Nokogiri.php');
//require_once('Encoding.php');
//use \ForceUTF8\Encoding;
$parser = new Parser();

$parser->parse('http://www.holiday.by/agencies/');
//$parser->parse('http://www.w3.org/');

//$html = file_get_contents('http://www.holiday.by/agencies/');
//$html = file_get_contents('http://www.holiday.by/agencies/');
//$html_utf8 = mb_convert_encoding($html, "utf-8", "windows-1251");
//$saw = new nokogiri($html);
//
//var_dump($saw);
//
//$result = $saw->get('a.title')->toArray();
//var_dump($result);

//foreach($result as $key => $val) {
//    var_dump(Encoding::toUTF8($val['#text']));
//}