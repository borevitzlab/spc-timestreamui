<?php

include "template.php";
$xml = new SimpleXMLElement($xmlstr);
$config_id = "ts8";
$start_day = 5;
$start_month = 05;
$start_year = 2555;
$start_time = "00:00";
$end_day = 7;
$end_month = 10;
$end_year = 2745;
$end_time = "00:00";

//$config = new SimpleXMLElement($xmlstr);
$xml->globals['config_id'] = $config_id;
$xml->globals['date_start'] = "$start_day"."/"."$start_month"."/"."$start_year"." "."$start_time"." PM";
$xml->globals['date_end'] = "$end_day"."/"."$end_month"."/"."$end_year"." "."$end_time"." PM";

echo $xml->globals['config_id'];
//echo $config_id;

$xml->asXML("config/$config_id.xml");
//echo$xml) ;

?>