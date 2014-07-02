<!DOCTYPE html>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<html lang="en">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<!--  BEGIN Browser History required section -->
	<link rel="stylesheet" type="text/css" href="history/history.css" />
	<!--  END Browser History required section -->

	<title></title>
	<script src="AC_OETags.js" language="javascript"></script>

	<!--  BEGIN Browser required section -->
	<script src="history/history.js" language="javascript"></script>
	<script src="http://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
	<script src="http://cdn.jsdelivr.net/jquery.cookie/1.4.0/jquery.cookie.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<!--  END Browser required section -->

	<style>
		.hide{ display: none; }
		.playerclass{display: none; }
		html,body
		{
		  height: 100%;
		}
		a.experimentselection{
			text-decoration: none;
		}
		.container-fluid{height: 100%;}
		#TimeGraphDiv{height: 100%; display: none;    overflow:hidden;}
	</style>

	<script type="text/javascript">

	var expts;
	 $.getJSON('../json/expts_pretty.json', function(response){
	       expts = response;
	       function parseDateTime(input1, input2) {
			  var parts1 = input1.split('-');
			  var parts2 = input2.split(':');
			  // new Date(year, month [, day [, hours[, minutes[, seconds[, ms]]]]])
			  return new Date(parts1[0], parts1[1]-1, parts1[2], parts2[0],parts2[1]); // Note: months are 0-based
			}
	       	for (var i = 0; i < expts[0].experiments.length; i++) { 
	       		 
				var experimentIDImageTag = document.createElement("img");
				    experimentIDImageTag.setAttribute("name", expts[0].experiments[i].expt_id);
				    experimentIDImageTag.setAttribute("value", expts[0].experiments[i].expt_id);
				    experimentIDImageTag.setAttribute("src", "img.svg");
				var experimentIDLinkTag = document.createElement('a');
					experimentIDLinkTag.setAttribute("src", '#')
					experimentIDLinkTag.setAttribute("value", expts[0].experiments[i].expt_id);
					experimentIDLinkTag.setAttribute("class", "thumbnail col-lg-2 col-md-2 col-sm-3 col-xs-5 experimentselection");
					
				var experimentIDHeadingTag = document.createElement('h4');
					experimentIDHeadingTag.textContent = expts[0].experiments[i].expt_id;
					experimentIDHeadingTag.setAttribute('style', 'text-align:center;')
				var experimentIDList = document.createElement("ul");
				var	experimentIDUserName = document.createElement("li");
					experimentIDUserName.textContent= expts[0].experiments[i].user;
					experimentIDList.appendChild(experimentIDUserName);
				var experimentIDSPP = document.createElement('li');
					experimentIDSPP.textContent = expts[0].experiments[i].spp;
					experimentIDList.appendChild(experimentIDSPP);
				var experimentIDLocation = document.createElement('li');
					experimentIDLocation.textContent = expts[0].experiments[i].location;
					experimentIDList.appendChild(experimentIDLocation);
					// use this to parse the date/time with a datetime object.
				/*var startTime = parseDateTime(expts[0].experiments[i].start_date, expts[0].experiments[i].start_time);
				var endTime = parseDateTime(expts[0].experiments[i].end_date, expts[0].experiments[i].end_time);
				var experimentIDTimeLi = document.createElement("li");
					experimentIDTimeLi.textContent = startTime.toDateString()+" "+startTime.toTimeString()+"-"+endTime.toDateString()+" "+endTime.toTimeString();
					*/
				var experimentIDTimeLiStart = document.createElement("li");
					experimentIDTimeLiStart.textContent =  "start: " + expts[0].experiments[i].start_date; 
				var experimentIDTimeLiEnd = document.createElement("li");
					experimentIDTimeLiEnd.textContent="end: " +expts[0].experiments[i].end_date;
					experimentIDList.appendChild(experimentIDTimeLiStart);
					experimentIDList.appendChild(experimentIDTimeLiEnd);


					experimentIDLinkTag.appendChild(experimentIDImageTag);
					experimentIDLinkTag.appendChild(experimentIDHeadingTag);
					experimentIDLinkTag.appendChild(experimentIDList);
		    	var experimentIDParentDivTag = document.getElementById("experimentID");
		    	experimentIDParentDivTag.appendChild(experimentIDLinkTag);


		    	var hiddenStreamsDivTag = document.createElement("div");
				    hiddenStreamsDivTag.setAttribute("id", "hide-"+expts[0].experiments[i].expt_id);
				    hiddenStreamsDivTag.setAttribute("class", ".hide");
		    	var hs = document.getElementById("hiddenStreams");
		    	hs.appendChild(hiddenStreamsDivTag);

		    	for (var d = 0; d < expts[0].experiments[i].timestreams.length; d++) {
			    	 //expts[0].experiments[i].timestreams[d]
			    	var streamSelectCheckboxTag = document.createElement("input");
					    streamSelectCheckboxTag.setAttribute("name", 'streamselect[]');
					    streamSelectCheckboxTag.setAttribute("type", 'checkbox');
					    streamSelectCheckboxTag.setAttribute("id", "persistbox-"+i+"-"+d);
					    streamSelectCheckboxTag.setAttribute("value", expts[0].experiments[i].timestreams[d]);
					var streamSelectCheckboxLabelTag = document.createElement("label");
						streamSelectCheckboxLabelTag.setAttribute("class", "checkbox-inline")
					    streamSelectCheckboxLabelTag.setAttribute('for', "persistbox-"+i+"-"+d);
					    streamSelectCheckboxLabelTag.textContent= expts[0].experiments[i].timestreams[d].substr(0,expts[0].experiments[i].timestreams[d].indexOf('~'));
					var checkBoxSpan = document.createElement("span");
						checkBoxSpan.setAttribute("class", "input-group-addon");

					streamSelectCheckboxLabelTag.appendChild(streamSelectCheckboxTag);
					checkBoxSpan.appendChild(streamSelectCheckboxLabelTag);
					hiddenStreamsDivTag.appendChild(checkBoxSpan);

			    	hiddenStreamsDivTag.appendChild(document.createElement("br"));

		    	}
		    	
		    	$(hiddenStreamsDivTag).hide();
			}
		    if(getCookie("layoutType")!=""){
		    	$("#layout").val(getCookie("layoutType"));
		    }
		    $(":checkbox").on("change", function(){
		        var checkboxValues = {};
		        var streamselect = {};
		        $(":checkbox").each(function(){
		          checkboxValues[this.id] = this.checked;
		          if(this.checked==true){
		          	streamselect[this.id] = this.value;
		          }
		        });
		        $.cookie('checkboxValues', checkboxValues, { expires: 7, path: '/' })
		        $.cookie('streamselect', streamselect, { expires: 7, path: '/' })
		      });
		    $("#layout").on("change", function(){
		    	var layoutType= $("#layout").val();
		    	$.cookie('layoutType', layoutType, { expires: 7, path: '/' })
		    });
		    function repopulateCheckboxes(){
		        var checkboxValues = $.cookie('checkboxValues');
		        if(checkboxValues){
		          Object.keys(checkboxValues).forEach(function(element) {
		            var checked = checkboxValues[element];
		            $("#" + element).prop('checked', checked);
		          });
		        }
		      }
		      $.cookie.json = true;
		      repopulateCheckboxes();

	 });
 	</script>
 	<script type="text/javascript">
 		
	 	function getCookie(cname) {
		    var name = cname + "=";
		    var ca = document.cookie.split(';');
		    for(var i=0; i<ca.length; i++) {
		        var c = ca[i].trim();
		        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
		    }
		    return "";
		}
		function clearCheckboxCookie(){
			$(":checkbox").prop("checked", false);
			$.cookie('checkboxValues', null, { expires: 7, path: '/' });
			$.cookie('streamselect', null, { expires: 7, path: '/' });
			$.cookie('experimentID',null, {expires: 7, path: '/'});
			}
		function reloadEmbed(){
			    var doc = $('<embed name="TimeGraph" id="TimeGraphFlex" src="TimeGraphFlex.swf?license=def20d85a970dfad6be9f30c32280c17&config=generateTSConfig.php" width="100%" height="100%">');
			    $("#TimeGraphDiv").slideUp("fast");
			    $('#TimeGraphDiv').empty().append(doc);
			    if(getCookie("streamselect")!="null")
			    $("#TimeGraphDiv").slideDown("fast");
		}

		function closeHidden(){
			    	$("#TimeGraphDiv").slideUp("slow");
			    	$(".playerclass").slideUp("slow");
			for(var i = 0; i < expts[0].experiments.length; i++){
			    			$("#hide-"+expts[0].experiments[i].expt_id).slideUp("slow");
			    	}
			    	
		}
		
		function search(val) {
		    for ( var i in expts[0].experiments) {
		        if (expts[0].experiments[i].expt_id.toLowerCase().search(val.toLowerCase()) == -1) {
		            $("#"+expts[0].experiments[i].expt_id).hideOptionGroup();
		        }else{
		        	$("#"+expts[0].experiments[i].expt_id).showOptionGroup();
		        }
		        if(String(expts[0].experiments[i].expt_id).toLowerCase()==String(val).toLowerCase()){
		        	$("#experimentID").val(expts[0].experiments[i].expt_id);
		        	$("#experimentID").trigger( "click" );
		        }
		    }
		}
		$.fn.hideOptionGroup = function() {
		 $(this).hide();
		 $(this).children().each(function(){
		 $(this).attr("disabled", "disabled").removeAttr("selected");
		 });
		 $(this).appendTo($(this).parent());

		}

		$.fn.showOptionGroup = function() {
		 $(this).show();    
		 $(this).children().each(function(){
		 $(this).removeAttr("disabled" );
		 });
		 $(this).prependTo($(this).parent());
		 $(this).parent().animate({scrollTop:0},0);
		}

	</script>

	<script type="text/javascript">
		$(document).ready(function(){
			$("#layoutType").val("gr");

				$("#sub").click(function () {
					if($("#TimeGraphDiv").css('display')=='none'){
						$("#TimeGraphDiv").slideDown("slow");
					}
				});

			    // $("#experimentID").click(function(){
			    	 $("#experimentID").delegate('.experimentselection', 'click', function(){
			    	clearCheckboxCookie();
			    	reloadEmbed();
			    	var eid = $(this).attr("value");
			    	$.cookie('experimentID', eid, { expires: 7, path: '/' });
			    	if($(".playerclass").css('display') == 'none' ){
			    		for(var i = 0; i < expts[0].experiments.length; i++){
			    			if(expts[0].experiments[i].expt_id == $(this).attr("value")) {
			    				$("#hide-"+expts[0].experiments[i].expt_id).show();
			    			}
			    		}
			    		$(".playerclass").slideDown("slow");
			    	}else{
			    		$(".playerclass").slideUp("fast");
			    		for(var i = 0; i < expts[0].experiments.length; i++){
			    			$("#hide-"+expts[0].experiments[i].expt_id).hide();
				    		if(expts[0].experiments[i].expt_id == $(this).attr("value")){
				    			$("#hide-"+expts[0].experiments[i].expt_id).hide();
				    		}
			    		}
			    		
			    		$(".playerclass").slideDown("slow");
			    		for(var i = 0; i < expts[0].experiments.length; i++){
			    			if(expts[0].experiments[i].expt_id == $(this).attr("value")){
			    				$("#hide-"+expts[0].experiments[i].expt_id).show();

			    			}
			    		}
			    		
			    	}

			    });

			});
 	</script>

	</head>
	<?php include "globals.php"; echo "<body style='background-color:#".$bg_color."'>" ?>
	<div class="container-fluid">
		<div class="col-md-12">
		<div class="playerclass">
			<div class="col-md-3">
				<br>
				<form id="form" onsubmit="reloadEmbed();showTimegraph();" role="form" class="form-inline">
				    <fieldset>
				    <div class="row">
				   		<div class="col-md-3">
						        <div class="form-group" style="width:300px;">
						            <label class="sr-only" for="layoutType">Layout: </label>
						            <select multiple class="form-control" name="layoutType" id="layout" style="width:100%;">
						                <option class="form-control" value="vr">Vertical</option>
						                <option class="form-control" value="hr">Horizontal</option>
						                <option class="form-control" value="gr">Grid</option>
						            </select>
						        </div>
						    </div>
						</div>
						<br>
				        <div id="hiddenStreams">
				        	<!--More stuff goes in here!-->
				        </div>
				    
				    <div class="btn-group btn-group-justified">
				    	<div class="btn-group">
				        	<input type="button" class="btn btn-primary" value="Submit" id="sub" onclick="reloadEmbed();" />
				        </div>
				        <div class="btn-group">
				        	<input type="button" class="btn btn-danger" value="Clear" id="clear" onclick="clearCheckboxCookie();closeHidden();hideTimeGraph();" />
				        </div>
				    </div>
				    </fieldset>
				</form>

				<br />
			</div>
		</div>
		<div onmouseover="document.body.style.overflow='hidden';" onmouseout="document.body.style.overflow='auto';" id="TimeGraphDiv" class="col-md-9">
		  		<embed name="timegraph" class='TimeGraphFlex' id="TimeGraphFlex" src="TimeGraphFlex.swf?license=def20d85a970dfad6be9f30c32280c17&config=generateTSConfig.php" width="100%" height="100%">
				</embed>
		</div>
	</div>
		<!div class="col-md-12"><!input name="streamsearch" type="text" class="form-control" placeholder="Filter" onkeyup="search(this.value)" style="width:101px;"><!/div>
		<div class='row'>
	        <div id="experimentselect" >
        		
	        	<label class="sr-only" for="experimentID">Experiment: </label>
	        	<div id="experimentID">
	        		<! stuff in here >
	        	</div>
	        </div>
			        	
		</div>

	</div>
		<div><embed src="generateTSConfig.php"></div>
<!2498382f5249277454ec3a716f31dfea>
	</div>
	</body>
	</html>
