<?php
// incude the template, a (soon to be) barebones xml with some additional extraneos data and structure. 
include "template.php";
// read the xml template as an xml string into a SimpleXMLElement so that we can play around with it.
$xml = new SimpleXMLElement($xmlstr);
// getting json data and decode into php object
$expts_decoded = json_decode(file_get_contents("json/expts_pretty.json"));
// make a filename
$filename = "config/".$expts_decoded[0]->experiments[0]->expt_id.".xml";
// check if the filename exists
if (!file_exists($filename)){
	// setting the config name to the expt id (assuming all the config files)
	$xml->globals['config_id'] = $expts_decoded[0]->experiments[0]->expt_id;
	
	//should functionalise this date string screwery.
	$full_backwards_start_date = $expts_decoded[0]->experiments[0]->start_date;
	$start_day = substr($full_backwards_start_date, 8, 2);
	$start_month = substr($full_backwards_start_date, 5, 2);
	$start_year = substr($full_backwards_start_date, 0 , 4);
	$start_time = "00:00";

	$full_backwards_end_date = $expts_decoded[0]->experiments[0]->end_date;
	$end_day = substr($full_backwards_end_date, 8, 2);
	$end_month = substr($full_backwards_end_date, 5, 2);
	$end_year = substr($full_backwards_end_date, 0 , 4);
	$end_time = "00:00";

	// more date string concat screwery setting up the dates for the globals
	$xml->globals['date_start'] = "$start_day"."/"."$start_month"."/"."$start_year"." "."$start_time"." PM";
	$xml->globals['date_end'] = "$end_day"."/"."$end_month"."/"."$end_year"." "."$end_time"." PM";

	// save the output.
	$xml->asXML($filename);
}
?>