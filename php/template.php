<?php
//need to include xml header. Check back later.
$xmlstr = <<<XML

<datapresenter>
  <globals

    config_id="UNSET"
  background_color="0x0099cc"
  timespan_days_init="7"
  date_start="UNSET"
  date_end="UNSET"
  show_status="true"
  panel_padding="10"
  />


  <components>

  
  <calendar id="o_calendar"
      image=""
      >
      <url><![CDATA[http://www.timecam.tv]]></url>
  </calendar>
      
  
    <zoom id="o_zoom"
      />
      
    <info id="o_info"
      file="config/trayscan_info.xml"
      />
    
  <bookmarks id="o_bookmarks"
      file="data/ts1/ts1_bookmarks.csv"
      show_spatial="false"
      />
      
  <toolbox id="o_toolbox"
      />

  <graphstack id="o_graphstack"
            file="config/trayscan_graph.xml" 
      graphframe_height="125"
      show_gauges="true"
      />
      
      
  <timebar id="o_timebar"
      show_actual_cam_time="false"
      show_bookmarks_in_timebar="false"
      timespan_button_triggers_play="false"
      />
  

    <timecam id="o_timecam_GC02-1_cam01"
  
      image_access_mode="TIMESTREAM"
  
      title = "GH18"
            url_image_list="data/timestreams/borevitz/NCRIS-GC02-1-BZ0012-BVZNC01~640/full"
      stream_name = "NCRIS-GC02-1-BZ0012-BVZNC01~640"
      url_hires = "data/timestreams/borevitz/NCRIS-GC02-1-BZ0012-BVZNC01/full"
      stream_name_hires = "NCRIS-GC02-1-BZ0012-BVZNC01"
            period="5 minute"
      num_images_to_load="50"
      
      utc = "false"
      timezone = "0"
      
      width="480"
      height="640"
      width_hires="3072"
      height_hires="1728"
      
      image_type="JPG"
      play_num_images="100"
      play_num_images_hires="50"
      
      no_header="false"
      
      show_timestream_selector="false"
            />
          
        
    <timecam id="o_timecam_GC04-1_cam02"
  
      image_access_mode="TIMESTREAM"
  
      title = "GH18"
            url_image_list="data/timestreams/borevitz/NCRIS-GC04-1-BZ0012-BVZNC03~640/full"
      stream_name = "NCRIS-GC04-1-BZ0012-BVZNC03~640"
      url_hires = "data/timestreams/Borevtiz/NCRIS-GC04-1-BZ0012-BVZNC03/full"
      stream_name_hires = "NCRIS-GC04-1-BZ0012-BVZNC03"
            period="5 minute"
      num_images_to_load="50"
      
      utc = "false"
      timezone = "0"
      
      width="480"
      height="640"
      width_hires="3072"
      height_hires="1728"
      
      image_type="JPG"
      play_num_images="100"
      play_num_images_hires="50"
      
      no_header="false"
      
      show_timestream_selector="false"
            />
    <timecam id="o_timecam_GC04-2_cam02"
  
      image_access_mode="TIMESTREAM"
  
      title = "GH18"
            url_image_list="data/timestreams/borevitz/NCRIS-GC04-2-BZ0012-BVZNC02~640/full"
      stream_name = "NCRIS-GC04-2-BZ0012-BVZNC02~640"
      url_hires = "data/timestreams/Borevtiz/NCRIS-GC04-2-BZ0012-BVZNC02/full"
      stream_name_hires = "NCRIS-GC04-2-BZ0012-BVZNC02"
            period="5 minute"
      num_images_to_load="50"
      
      utc = "false"
      timezone = "0"
      
      width="480"
      height="640"
      width_hires="3072"
      height_hires="1728"
      
      image_type="JPG"
      play_num_images="100"
      play_num_images_hires="50"
      
      no_header="false"
      
      show_timestream_selector="false"
            />
 <timecam id="o_timecam_GC05-1_cam01"
  
      image_access_mode="TIMESTREAM"
  
      title = "GH18"
            url_image_list="data/timestreams/borevitz/NCRIS-GC05-1-BZ0012-BVZNC04~640/full"
      stream_name = "NCRIS-GC05-1-BZ0012-BVZNC04~640"
      url_hires = "data/timestreams/Borevtiz/NCRIS-GC05-1-BZ0012-BVZNC04/full"
      stream_name_hires = "NCRIS-GC05-1-BZ0012-BVZNC04"
            period="5 minute"
      num_images_to_load="50"
      
      utc = "false"
      timezone = "0"
      
      width="480"
      height="640"
      width_hires="3072"
      height_hires="1728"
      
      image_type="JPG"
      play_num_images="100"
      play_num_images_hires="50"
      
      no_header="false"
      
      show_timestream_selector="false"
            />
      

      
  <!--Must come after timecam nodes.-->
  
  <timebarmedia id="o_timebarmedia"
      show_timeline="false"
      show_date="true"
      />
      
      
  </components>





  <layout>
  <column  width="150" >  
    <panel height="100%" components_node_id="o_calendar"/>
    <panel height="180px" components_node_id="o_zoom"/> 
  </column>
  
 
    <column  width="100%" >
    <row height="20%">
      <panel width="50%" height="100%" components_node_id="o_timecam_GC02-1_cam01"/>
      <panel width="50%" height="100%" components_node_id="o_timecam_GC05-1_cam01"/>
    </row>

    <row height="20%">
      <panel width="50%" height="100%" components_node_id="o_timecam_GC04-1_cam02"/>
      <panel width="50%" height="100%" components_node_id="o_timecam_GC04-2_cam02"/>
    </row>

    <panel height="25px" components_node_id="o_timebarmedia" panel_padding_top="0px"   />
    <panel height="100px" components_node_id="o_timebar"  panel_padding_top="0px"  />
    </column>
  

  </layout>
  
</datapresenter>
XML;
?>