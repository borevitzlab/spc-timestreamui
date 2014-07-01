<?php
date_default_timezone_set('UTC');
$timestamp = strtotime("2013_03_04_17_40_00");
$time = explode('_', "2013_03_04_17_40_00");
$test = new DateTime('2013-02-03'."14:00:00");
echo date_format($test, 'm/d/Y h:i:s A')
?>
