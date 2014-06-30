<?php
	$expire=time()+60*60*24*30;
	if(isset($_POST['layoutType'])&&!empty($_POST['layoutType']))
		setcookie('layoutType',$_POST['layoutType'],$expire, '/');
		//$_SESSION['layoutType'] = $_POST['layoutType'];
	if(isset($_POST['streamselect'])&&!empty($_POST['streamselect']))
		setcookie('streamselect',json_encode($_POST['streamselect']),$expire, '/');
		//$_SESSION['streamselect'] = $_POST['streamselect'];
	if(isset($_POST['experimentID'])&&!empty($_POST['experimentID']))
		setcookie('experimentID',$_POST['experimentID'],$expire,'/');
		//$_SESSION['experimentID'] = $_POST['experimentID'];
?>
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
		html,body
		{
		  height: 100%;
		}
		.container-fluid{height: 100%;}
		#TimeGraphDiv{height: 100%;}
	</style>

	<script type="text/javascript">

	var expts;
	 $.getJSON('../json/expts_pretty.json', function(response){
	       expts = response;
	       	for (var i = 0; i < expts[0].experiments.length; i++) { 
				var experimentIDOptionTag = document.createElement("option");
	    		    experimentIDOptionTag.innerHTML= expts[0].experiments[i].expt_id;
				    experimentIDOptionTag.setAttribute("name", expts[0].experiments[i].expt_id);
				    experimentIDOptionTag.setAttribute("id", expts[0].experiments[i].expt_id)
				    experimentIDOptionTag.setAttribute("class", "form-control");
				    experimentIDOptionTag.setAttribute("value", expts[0].experiments[i].expt_id);
		    	var experimentIDDivTag = document.getElementById("experimentID");
		    	experimentIDDivTag.appendChild(experimentIDOptionTag);


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
			if(getCookie("experimentID")!=""){
		    	$("#experimentID").val(getCookie("experimentID"));
		    }
		    if(getCookie("layoutType")!=""){
		    	$("#layoutType").val(getCookie("layoutType"));
		    }
		    $(":checkbox").on("change", function(){
		        var checkboxValues = {};
		        $(":checkbox").each(function(){
		          checkboxValues[this.id] = this.checked;
		        });
		        $.cookie('checkboxValues', checkboxValues, { expires: 7, path: '/' })
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
			    $("#experimentID").click(function(){
			    	for(var i = 0; i < expts[0].experiments.length; i++){
			    		if(expts[0].experiments[i].expt_id == $("#experimentID").val()){
			    			$("#hide-"+expts[0].experiments[i].expt_id).slideDown("slow");
			    		}else{
			    			$("#hide-"+expts[0].experiments[i].expt_id).slideUp("fast");
			    		}
			    	}
			    });

			});
 	</script>

	</head>
	<body>
	<div class="container-fluid">
		<div class="col-md-3">
		<br>
			<form id="form" method="POST" action="?" role="form" class="form-inline">
			    <fieldset>
			    <div class="row">
			   		<div class="col-md-6">
				        <div class="form-group pull-left">
				            <label class="sr-only" for="layoutType">Layout: </label>
				            <select multiple class="form-control" name="layoutType" id="layoutType">
				                <option class="form-control" value="vr">Vertical</option>
				                <option class="form-control" value="hr">Horizontal</option>
				                <option class="form-control" value="gr">Grid</option>
				            </select>
				        </div>
			        </div>
			        <div class="col-md-6"><input name="streamsearch" type="text" class="form-control pull-right" placeholder="Filter" onkeyup="search(this.value)" style="width:101px;">
				        <div class="form-group pull-right" id="experimentselect" >
			        		
				        	<label class="sr-only" for="experimentID">Experiment: </label>
				        	<select multiple class="form-control" name="experimentID" id="experimentID">
				        		<!--stuff goes in here!-->
				        	</select>
				        </div>
				        	
			        </div>
			    </div>
			    <br />
			        <div id="hiddenStreams">
			        	<!--More stuff goes in here!-->
			        </div>
			    <div class="btn-group btn-group-justified">
			    	<div class="btn-group">
			        	<input type="submit" class="btn btn-primary" value="Submit" />
			        </div>
			        <div class="btn-group">
			        	<input type="button" class="btn btn-warning" value="Clear" id="clear" onclick="clearCheckboxCookie();" />
			        </div>
			    </div>
			    </fieldset>
			</form>
			<br />
		</div>

		<div id="TimeGraphDiv" class="col-md-9">
		  	<embed id="TimeGraphFlex" src="TimeGraphFlex.swf?license=def20d85a970dfad6be9f30c32280c17&config=generateTSConfig.php" width="100%" height="100%">
			</embed>
		</div>

	</div>
	</body>
	</html>
