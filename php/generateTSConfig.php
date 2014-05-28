<?php
// incude the template, a (soon to be) barebones xml with some additional extraneos data and structure. 
include "template.php";
// read the xml template as an xml string into a SimpleXMLElement so that we can play around with it.
$xml = new SimpleXMLElement($xmlstr);
//$layoutType = $_POST["layoutType"];
$layoutType = "hr";
// layoutType = hr horizontal
// layoutType = vr vertical
// layoutType = gr grid

// getting json data and decode into php object
$expts_decoded = json_decode(file_get_contents("https://raw.githubusercontent.com/borevitzlab/spc-timestreamui/master/json/expts.json"));
// Maybe load this once the eperiment has been selected.
$timestreams_decoded = json_decode(file_get_contents("https://raw.githubusercontent.com/borevitzlab/spc-timestreamui/master/json/timestreams.json"));
// make a filename
$filename = "config/".$expts_decoded[0]->experiments[0]->expt_id.".xml";
// check if the filename exists

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


	// iterating through the first experiment and then the list of timestreams
	// change this to POST/GET user selection later
	for ($check=0; $check < count($expts_decoded[0]->experiments[0]->timestreams); $check++) { 

		for ($i=0; $i < count($timestreams_decoded); $i++) { 

			// check against the string names of the timestreams to make a list of the streams 
			// available for the experiment
			if ( strcmp($expts_decoded[0]->experiments[0]->timestreams[$check], $timestreams_decoded[$i]->name) ==0) {
				// add new xml child under "components".

				$tc = $xml->components->addChild('timecam');
				// anything under "components" does not show up under "view source" in chrome, 
				// but it is there. 
				
				// "exploding" the stream name for the title
				list($prefixname, $suffixname) = explode('~', $timestreams_decoded[$i]->name);
				// substr the data path, because the timestreamconfig doesnt expect the /cloud/ bit.
				$datapath = substr($timestreams_decoded[$i]->webroot, 6);

				// ALL the attribute setting!
				$tc->addAttribute('id', $timestreams_decoded[$i]->name);
				$tc->addAttribute('image_access_mode', 'TIMESTREAM');
				$tc->addAttribute('title', $prefixname);
				

				// If stream_name and stream_name_hires is important and breaks things, look HERE1 to fix
				$tc->addAttribute('url_image_list', "$datapath"."~640/full/");
				$tc->addAttribute('stream_name', $timestreams_decoded[$i]->name);

				$tc->addAttribute('url_hires', "$datapath"."full/");
				$tc->addAttribute('stream_name_hires', $timestreams_decoded->name."~hires");

				$tc->addAttribute('period', '5 minute');
				$tc->addAttribute('num_images_to_load', 50);
				$tc->addAttribute('utc', 'false');
				$tc->addAttribute('timezone', '0');

				// this here could very well be checked by this php script using getimagesize().
				$tc->addAttribute('width', '480');
				$tc->addAttribute('height', '640');
				$tc->addAttribute('width_hires', '3072');
				$tc->addAttribute('height_hires', '1728');
				$tc->addAttribute('image_type', 'JPG');
				$tc->addAttribute('play_num_images', '100');
				$tc->addAttribute('play_num_images_hires', '50');
				$tc->addAttribute('no_header', 'false');
				$tc->addAttribute('show_timestream_selector', 'false');
			}
		}	
	}
	// 
	// Apparently this must come after timecam nodes (?)
	// 
	$tbmedia = $xml->components->addChild('timebarmedia');
	$tbmedia->addAttribute('id', 'o_timebarmedia');
	$tbmedia->addAttribute('show_timeline', 'false');
	$tbmedia->addAttribute('show_date', 'true');

	// making calendar and zoom layout
	$layout = $xml->addChild('layout');
		$calendar_col1 = $layout->addChild('column');
		$calendar_col1->addAttribute('width', '150');
			$calendar_panel1 = $calendar_col1->addChild('panel');
			$calendar_panel1->addAttribute('height', '100%');
			$calendar_panel1->addAttribute('components_node_id', 'o_calendar');
			$calendar_panel2 = $calendar_col1->addChild('panel');
			$calendar_panel2->addAttribute('height', '180px');
			$calendar_panel2->addAttribute('components_node_id', 'o_zoom');


	// 
	// single config, exactly 1
	// 
	if(count($timestreams_decoded)==1){

		$cam_column = $layout->addChild('column');
		$cam_column->addAttribute('width', '100%');
			$cam_row = $cam_column->addChild('row');
			$cam_row->addAttribute('height', '100%');
				$cam_panel = $cam_row->addChild('panel');
				$cam_panel->addAttribute('height', "100%");
				$cam_panel->addAttribute('width', "100%");
				$cam_panel->addAttribute('components_node_id', $timestreams_decoded[0]->name);
	
	}

	// 
	// Horizontal strips config
	// 
	if(count($timestreams_decoded)>1&&$layoutType=="hr"){

		$cam_column = $layout->addChild('column');
		$cam_column->addAttribute('width', '100%');
			$cam_row = $cam_column->addChild('row');
			$cam_row->addAttribute('height', '100%');
			for($i = 0; $i < count($timestreams_decoded); $i++){
				$cam_panel = $cam_row->addChild('panel');
				$cam_panel->addAttribute('width', 100/count($timestreams_decoded)."%");
				$cam_panel->addAttribute('height', "100%");
				$cam_panel->addAttribute('components_node_id', $timestreams_decoded[$i]->name);
			}

	$timebar_panel = $cam_column->addChild('panel');
	$timebar_panel->addAttribute('height', '25px');
	$timebar_panel->addAttribute('components_node_id', 'o_timebarmedia');
	$timebar_panel->addAttribute('panel_padding_top', '0px');

	$timebar = $cam_column->addChild('panel');
	$timebar->addAttribute('height', '100px');
	$timebar->addAttribute('components_node_id', 'o_timebar');
	$timebar->addAttribute('panel_padding_top', '0px');
	}

	// 
	// Vertical Strips layout
	// 
	if (count($timestreams_decoded)>1&&$layoutType=="vr") {

		$cam_column = $layout->addChild('column');
		$cam_column->addAttribute('width', '100%');
		for($i = 0; $i < count($timestreams_decoded); $i++){
			$cam_row = $cam_column->addChild('row');
			$cam_row->addAttribute('height', 100/count($timestreams_decoded)."%");
				$cam_panel = $cam_row->addChild('panel');
				$cam_panel->addAttribute('width', "100%");
				$cam_panel->addAttribute('height', "100%");
				$cam_panel->addAttribute('components_node_id', $timestreams_decoded[$i]->name);

		}

	$timebar_panel = $cam_column->addChild('panel');
	$timebar_panel->addAttribute('height', '25px');
	$timebar_panel->addAttribute('components_node_id', 'o_timebarmedia');
	$timebar_panel->addAttribute('panel_padding_top', '0px');

	$timebar = $cam_column->addChild('panel');
	$timebar->addAttribute('height', '100px');
	$timebar->addAttribute('components_node_id', 'o_timebar');
	$timebar->addAttribute('panel_padding_top', '0px');
	}

	// 
	// Grid layout
	// 
	// DOESNT WORK YET AND I DONT KNOW WHY!
	// ANYTHING OVER 4 PLAYERS IS MESSED UP!
	// 
	function is_whole_number($var){
		return (is_numeric($var)&&(intval($var)==(floatval($var))));
	}
	function is_square_number($var){
		return (is_whole_number(sqrt($var)));
	}

	if (count($timestreams_decoded)>1&&$layoutType=="gr") {
		$cam_column = $layout->addChild('column');
		$cam_column->addAttribute('width', '100%');

		$number_of_streams = count($timestreams_decoded);
		$tsc = 0;
		if(is_square_number($number_of_streams)){
			for($x = 0; $x<sqrt($number_of_streams); $x++){
				$cam_row = $cam_column->addChild('row');
				$cam_row->addAttribute('height', 100/sqrt($number_of_streams)."%");
				for($y = 0; $y < sqrt($number_of_streams); $y++){
					$cam_panel = $cam_row->addChild('panel');
					$cam_panel->addAttribute('width', 100/sqrt($number_of_streams)."%");
					$cam_panel->addAttribute('height', "100%");
					$cam_panel->addAttribute('components_node_id', $timestreams_decoded[$tsc]->name);
					$tsc++;
				}
			}
		}else{
			// 
			// TODO here, some cool elegance that does layout for uneven grid.
			// needs to have desired ratio w:h, and take care of odd number of streams. 
			// 
			// EDIT: this cool elegance should make sure that the number of rows vs columns will be
			// appropriately sized. It might be good to take care of primes numbered timestreams
			// 
			$rows=1;
			$panels_per_row=1;
			while ($panels_per_row*$rows <= $number_of_streams) {
				$panels_per_row++;
				if($panels_per_row>=$rows*2){
					$rows++;
					$panels_per_row=1;
				}
			}
			for ($x=0; $x < $rows; $x++) { 
				$cam_row = $cam_column->addChild('row');
				$cam_row->addAttribute('height', 100/$rows."%");
				for ($y=0; $y < $panels_per_row; $y++) { 
					$cam_panel = $cam_row->addChild('panel');
					$cam_panel->addAttribute('width', 100/$panels_per_row."%");
					$cam_panel->addAttribute('height', "100%");
					$cam_panel->addAttribute('components_node_id', $timestreams_decoded[$x*$y]->name);
					$tsc++;
				}
			}

		}
	$timebar_panel = $cam_column->addChild('panel');
	$timebar_panel->addAttribute('height', '25px');
	$timebar_panel->addAttribute('components_node_id', 'o_timebarmedia');
	$timebar_panel->addAttribute('panel_padding_top', '0px');

	$timebar = $cam_column->addChild('panel');
	$timebar->addAttribute('height', '100px');
	$timebar->addAttribute('components_node_id', 'o_timebar');
	$timebar->addAttribute('panel_padding_top', '0px');
	}	

// 
// Layout and tools
// 
/*
    <!-- Info and tools column-->
  <!--  <column width="240px" >

		<panel height="150px" components_node_id="o_info"/>
		<panel height="100%" components_node_id="o_bookmarks"/>
		<!--<panel height="125px" components_node_id="o_toolbox"/>
    </column> 
	-->

*/

	// uncomment this next line to save a config file to server (maybe save future configs for later use?).
	// $xml->asXML($filename);
	
	// echo the data. May need to make sure that the corrcect http headers are attached,
	// but it might just need to be raw data.
	echo $xml->asXML();
?>