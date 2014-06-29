<?php
	session_start(); 
	//session variables 
	if(isset($_POST['layoutType'])&&!empty($_POST['layoutType']))
		$_SESSION['layoutType'] = $_POST['layoutType'];
	if(isset($_POST['streamselect'])&&!empty($_POST['streamselect']))
		$_SESSION['streamselect'] = $_POST['streamselect'];
	if(isset($_POST['experimentID'])&&!empty($_POST['experimentID']))
		$_SESSION['experimentID'] = $_POST['experimentID'];
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

	<!--  BEGIN Browser History required section -->
	<script src="history/history.js" language="javascript"></script>
	<script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script>
	<script src="http://cdn.jsdelivr.net/jquery.cookie/1.4.0/jquery.cookie.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<!--  END Browser History required section -->

	<style>
		.hide{ display: none; }
	</style>

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
	}

	var expts;
	 $.getJSON('../json/expts_pretty.json', function(response){
	       expts = response;
	       	for (var i = 0; i < expts[0].experiments.length; i++) { 
				var element = document.createElement("option");
	    		    element.innerHTML= expts[0].experiments[i].expt_id;
				    element.setAttribute("name", expts[0].experiments[i].expt_id);

				    element.setAttribute("class", "form-control");
				    element.setAttribute("value", expts[0].experiments[i].expt_id);
		    	var foo = document.getElementById("experimentID");
		    	foo.appendChild(element);


		    	var element2 = document.createElement("div");
				    element2.setAttribute("id", "hide-"+expts[0].experiments[i].expt_id);
				    element2.setAttribute("class", ".hide");
		    	var hs = document.getElementById("hiddenStreams");
		    	hs.appendChild(element2);

		    	for (var d = 0; d < expts[0].experiments[i].timestreams.length; d++) {
			    	 //expts[0].experiments[i].timestreams[d]
			    	var ele = document.createElement("input");
					    ele.setAttribute("name", 'streamselect[]');
					    ele.setAttribute("type", 'checkbox');
					    ele.setAttribute("id", "persistbox-"+i+"-"+d);
					    ele.setAttribute("value", expts[0].experiments[i].timestreams[d]);
					var label = document.createElement("label");
						label.setAttribute("class", "checkbox-inline")
					    label.setAttribute('for', "persistbox-"+i+"-"+d);
					    label.textContent= expts[0].experiments[i].timestreams[d].substr(0,expts[0].experiments[i].timestreams[d].indexOf('~'));
					 label.appendChild(ele);

					 var checkBoxSpan = document.createElement("span");
					 checkBoxSpan.setAttribute("class", "input-group-addon");
					 checkBoxSpan.appendChild(label);
					element2.appendChild(checkBoxSpan);
			    	element2.appendChild(document.createElement("br"));
		    	}
		    	$(element2).hide();
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

	</head>
	<body>
	<div class="container-fluid">
	<div class="col-md-4">
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
		        <div class="col-md-6">
			        <div class="form-group pull-right" id="experimentselect" >
			        	<label class="sr-only" for="experimentID">Experiment: </label>
			        	<select multiple class="form-control" name="experimentID" id="experimentID">
			        		<!--stuff goes in here!-->
			        	</select>
			        </div>
		        </div>
		    </div>
		        <div id="hiddenStreams">
		        	<!--More stuff goes in here!-->
		        </div>
		        <br />
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
	</div>
		<div class="container-fluid" id="TimeGraphDiv">
		  	<embed id="TimeGraphFlex" src="TimeGraphFlex.swf?license=def20d85a970dfad6be9f30c32280c17&config=generateTSConfig.php"
			  	width="100%" height="1000px">
			</embed>
		</div>
		<div class="container-fluid" id="TimeGraphDiv">
		  	<embed src="generateTSConfig.php" />
			</embed>
		</div>
		</div>
	</body>

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
	</html>
