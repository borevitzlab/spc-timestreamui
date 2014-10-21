<?php
//need to include xml header. Check back later.
$xmlstr = <<<XML

<datapresenter>
  <globals

    config_id="UNSET"
  background_color="0x2F4F4F"
  timespan_days_init="14"
  date_start="UNSET"
  date_end="UNSET"
  show_status="true"
  panel_padding="10"
  ></globals>


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
      
  </components>

  
</datapresenter>
XML;
?>