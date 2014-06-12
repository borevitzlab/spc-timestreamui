<?php
session_start();
// incude the template, a (soon to be) barebones xml with some additional extraneos data and structure. 
include "template.php";
include "globals.php";

// read the xml template as an xml string into a SimpleXMLElement so that we can play around with it.

$expire=time()+60*60*24*30;

$layoutType = $_SESSION["layoutType"];
	setcookie("layoutType", $layoutType, $expire);

$experiment_ID = $_SESSION['experimentID'];
	setcookie("experimentID", $_SESSION['experimentID'], $expire);
	
$streams = $_SESSION['streamselect'];

// getting json data and decode into php object
$expts_decoded = json_decode(file_get_contents("../json/expts_pretty.json"));
$timestreams_decoded = json_decode(file_get_contents("../json/timestreams_pretty.json"));
$number_of_streams = count($streams);
//echo $streams[1];
// useful functions
	// checks to see whether a number is whole
	function is_whole_number($var){
		return (is_numeric($var)&&(intval($var)==(floatval($var))));
	}
	// checks to see if a number is a square number
	function is_square_number($var){
		return (is_whole_number(sqrt($var)));
	}
	// checks to see whether a number is a prime
	function is_prime($num) {
	    if($num == 1)
	        return false;
	    if($num == 2)
	        return false;
	    if($num % 2 == 0) {
	        return false;
	    }
	    for($i = 3; $i <= ceil(sqrt($num)); $i = $i + 2) {
	        if($num % $i == 0)
	            return false;
	    }
	    return true;
	}
	// returns an array of th factors of a number
	function get_factors($n){
		$factors = array(1, $n);
	  	for($i = 2; $i * $i <= $n; $i++){
	    	if($n % $i == 0){
	        	$factors[] = $i;
	        	if($i * $i != $n){
	            	$factors[] = $n/$i;
	            }
	      }
	   }
	   sort($factors);
	   return $factors;
	}
	// returns an array of the two median factors
	function find_median_factors($array) {
		    $result = array(0,0);
	        $result[0] = $array[-1+count($array)/2];
	        $result[1] = $array[count($array)/2];
	    return $result;
	}

// all the timecam node stuff and xml setup
		$xml = new SimpleXMLElement($xmlstr);
		// setting the config name to the expt id (assuming all the config files)
		$xml->globals['config_id'] = $expts_decoded[0]->experiments[$experimentID]->expt_id;

		//should functionalise this date string screwery.
		$full_backwards_start_date = $expts_decoded[0]->experiments[$experimentID]->start_date;
		$start_day = substr($full_backwards_start_date, 8, 2);
		$start_month = substr($full_backwards_start_date, 5, 2);
		$start_year = substr($full_backwards_start_date, 0 , 4);

		$start_time = $expts_decoded[0]->experiments[$experimentID]->start_time;

		$full_backwards_end_date = $expts_decoded[0]->experiments[$experimentID]->end_date;
		$end_day = substr($full_backwards_end_date, 8, 2);
		$end_month = substr($full_backwards_end_date, 5, 2);
		$end_year = substr($full_backwards_end_date, 0 , 4);
		$end_time = $expts_decoded[0]->experiments[$experimentID]->end_time;

		// more date string concat screwery setting up the dates for the globals
		$xml->globals['date_start'] = "$start_day"."/"."$start_month"."/"."$start_year"." "."$start_time"." PM";
		$xml->globals['date_end'] = "$end_day"."/"."$end_month"."/"."$end_year"." "."$end_time"." PM";

		// iterating through the first experiment and then the list of timestreams
		// change this to POST/GET user selection later
		for ($check=0; $check < count($streams); $check++) { 
			for ($i=0; $i < count($timestreams_decoded); $i++) { 
				// check against the string names of the timestreams to make a list of the streams 
				// available for the experiment
				if ( strcmp($streams[$check], $timestreams_decoded[$i]->name) ==0) {
					// add new xml child under "components".

					$tc = $xml->components->addChild('timecam');
					// anything under "components" does not show up under "view source" in chrome, 
					// but it is there. 
					
					// "exploding" the stream name for the title
					list($prefixname, $suffixname) = explode('~', $timestreams_decoded[$i]->name);
					// substr the data path, because the timestreamconfig doesnt expect the /cloud/ bit.
					$datapath =$timestreams_decoded[$i]->webroot;

					// ALL the attribute setting!
					$tc->addAttribute('id', $timestreams_decoded[$i]->name."-".$check); //this lets the script allow doubles.
					$tc->addAttribute('image_access_mode', 'TIMESTREAM');
					$tc->addAttribute('title', $prefixname);
					

					// If stream_name and stream_name_hires is important and breaks things, look HERE1 to fix
					$tc->addAttribute('url_image_list', "$datapath"."~640/full/");
					$tc->addAttribute('stream_name', $timestreams_decoded[$i]->name);

					$tc->addAttribute('url_hires', "$datapath"."full/");
					$tc->addAttribute('stream_name_hires', $timestreams_decoded->name."~hires");

					// 
					// TODO: push these changes to the json schema/get from json
					// 
					$tc->addAttribute('period', $timestreams_decoded[$i]->period_in_minutes." minute");
					$tc->addAttribute('utc', $timestreams_decoded[$i]->utc);
					$tc->addAttribute('timezone', $timestreams_decoded[$i]->timezone);
					$tc->addAttribute('width', $timestreams_decoded[$i]->width);
					$tc->addAttribute('height', $timestreams_decoded[$i]->height);
					$tc->addAttribute('width_hires', $timestreams_decoded[$i]->width_hires);
					$tc->addAttribute('height_hires', $timestreams_decoded[$i]->height_hires);
					$tc->addAttribute('image_type', $timestreams_decoded[$i]->image_type);

					// 
					// TODO: server globals, push to serverglobals.php
					// 
					$tc->addAttribute('num_images_to_load', $num_images_to_load);
					$tc->addAttribute('play_num_images', $play_num_images);
					$tc->addAttribute('play_num_images_hires', $play_num_images_hires);


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

// layouts
	// 
	// single
	// 
	if(count($streams)==1){

		$cam_column = $layout->addChild('column');
		$cam_column->addAttribute('width', '100%');
			$cam_row = $cam_column->addChild('row');
			$cam_row->addAttribute('height', '100%');
				$cam_panel = $cam_row->addChild('panel');
				$cam_panel->addAttribute('height', "100%");
				$cam_panel->addAttribute('width', "100%");
				$cam_panel->addAttribute('components_node_id', $streams[0]."-0");
	
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
	// Vertical 
	// 
	if(count($streams)>1&&$layoutType=="vr"){

		$cam_column = $layout->addChild('column');
		$cam_column->addAttribute('width', '100%');
			$cam_row = $cam_column->addChild('row');
			$cam_row->addAttribute('height', '100%');
			for($i = 0; $i < count($streams); $i++){
				$cam_panel = $cam_row->addChild('panel');
				$cam_panel->addAttribute('width', 100/count($streams)."%");
				$cam_panel->addAttribute('height', "100%");
				$cam_panel->addAttribute('components_node_id', $streams[$i]."-".$i);
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
	// horizontal
	// 
	if (count($streams)>1&&$layoutType=="hr") {

		$cam_column = $layout->addChild('column');
		$cam_column->addAttribute('width', '100%');
		for($i = 0; $i < count($streams); $i++){
			$cam_row = $cam_column->addChild('row');
			$cam_row->addAttribute('height', 100/count($streams)."%");
				$cam_panel = $cam_row->addChild('panel');
				$cam_panel->addAttribute('width', "100%");
				$cam_panel->addAttribute('height', "100%");
				$cam_panel->addAttribute('components_node_id', $streams[$i]."-".$i);

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
	// Grid
	// 
	if (count($streams)>1&&$layoutType=="gr") {
		$cam_column = $layout->addChild('column');
		$cam_column->addAttribute('width', '100%');


		$tsc = 0;

		if(is_square_number($number_of_streams)){
			for($x = 0; $x<sqrt($number_of_streams); $x++){
				$cam_row = $cam_column->addChild('row');
				$cam_row->addAttribute('height', 100/sqrt($number_of_streams)."%");
				for($y = 0; $y < sqrt($number_of_streams); $y++){
					$cam_panel = $cam_row->addChild('panel');
					$cam_panel->addAttribute('width', 100/sqrt($number_of_streams)."%");
					$cam_panel->addAttribute('height', "100%");
					$cam_panel->addAttribute('components_node_id', $streams[$tsc]."-".$tsc);
					$tsc++;
				}
			}
		}else{
			if(is_prime($number_of_streams)){
				$closest_factors = find_median_factors(get_factors($number_of_streams+1));
				$number_of_columns = $closest_factors[0];
				$number_of_rows = $closest_factors[1];
					for($x = 0; $x<$number_of_rows; $x++){
							$cam_row = $cam_column->addChild('row');
							if($x==0){
								$n_missing = $number_of_columns*$number_of_rows - $number_of_streams;
								$num_col_sub = $number_of_columns-$n_missing;
								$cam_row->addAttribute('height', 100/$number_of_rows.'%');
								for($y = 0; $y < $num_col_sub; $y++){
									$cam_panel = $cam_row->addChild('panel');
									$cam_panel->addAttribute('width', 100/$num_col_sub."%");
									$cam_panel->addAttribute('height', "100%");
									$cam_panel->addAttribute('components_node_id', $streams[$tsc]."-".$tsc);
									$tsc++;
								}
							}else{
								$cam_row->addAttribute('height', 100/$number_of_columns.'%');
								for($y = 0; $y < $number_of_columns; $y++){
									$cam_panel = $cam_row->addChild('panel');
									$cam_panel->addAttribute('width', 100/$number_of_rows."%");
									$cam_panel->addAttribute('height', "100%");
									$cam_panel->addAttribute('components_node_id', $streams[$tsc]."-".$tsc);
									$tsc++;
								}
							}
						}
			}else{
				$closest_factors = find_median_factors(get_factors($number_of_streams));
				$number_of_columns = $closest_factors[0];
				$number_of_rows = $closest_factors[1];
				if($number_of_rows*$number_of_columns == $number_of_streams){
					for($x = 0; $x<$number_of_rows; $x++){
						$cam_row = $cam_column->addChild('row');
						$cam_row->addAttribute('height', 100/$number_of_columns.'%');
						$cam_row->addAttribute('rows&cols', $number_of_rows."&".$number_of_columns);
						for($y = 0; $y < $number_of_columns; $y++){
							$cam_panel = $cam_row->addChild('panel');
							$cam_panel->addAttribute('width', 100/$number_of_rows."%");
							$cam_panel->addAttribute('height', "100%");
							$cam_panel->addAttribute('components_node_id', $streams[$tsc]."-".$tsc);
							$tsc++;
						}
					}
				}else{
					for($x = 0; $x<$number_of_rows; $x++){
							$cam_row = $cam_column->addChild('row');
							if($x==0){
								$n_missing = $number_of_columns*$number_of_rows - $number_of_streams;
								$num_col_sub = $number_of_columns-$n_missing;
								$cam_row->addAttribute('height', 100/$number_of_rows.'%');
								for($y = 0; $y < $num_col_sub; $y++){
									$cam_panel = $cam_row->addChild('panel');
									$cam_panel->addAttribute('width', 100/$num_col_sub."%");
									$cam_panel->addAttribute('height', "100%");
									$cam_panel->addAttribute('components_node_id', $streams[$tsc]."-".$tsc);
									$tsc++;
								}
							}else{
								$cam_row->addAttribute('height', 100/$number_of_columns.'%');
								for($y = 0; $y < $number_of_columns; $y++){
									$cam_panel = $cam_row->addChild('panel');
									$cam_panel->addAttribute('width', 100/$number_of_rows."%");
									$cam_panel->addAttribute('height', "100%");
									$cam_panel->addAttribute('components_node_id', $streams[$tsc]."-".$tsc);
									$tsc++;
								}
							}
						}
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
	
	// echo the data. this script needs to be passed directly into the url. it doesnt work otherwise. 
	// I dont know why.
	echo $xml->asXML();
?>