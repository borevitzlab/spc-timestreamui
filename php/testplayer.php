<?php
session_start(); 
if(isset($_POST['layoutType'])&&!empty($_POST['layoutType'])){
	$_SESSION['layoutType'] = $_POST['layoutType'];
}
if(isset($_POST['streamselect'])&&!empty($_POST['streamselect'])){
	$_SESSION['streamselect'] = $_POST['streamselect'];
}
if(isset($_POST['experimentID'])&&!empty($_POST['experimentID'])){
	$_SESSION['experimentID'] = $_POST['experimentID'];
}
?>
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
	<!--  END Browser History required section -->

	<style>
		body { margin: 0px; overflow:hidden }
		.hide{ display: none; }
		input[type="submit"], button {
		  background: #383838;
		  border:none;
		  border-radius: 4px;
		  color:white;
		  border: 1px solid rgba(0,0,0,.1);
		  box-shadow: inset 0 0 1em rgba(20,20,20,.07);
		  padding: 1em;
		  width: 7em;
		}

		input[type="submit"]:hover, button:hover {
		  box-shadow: inset 0 0 2em rgba(0,0,255,.4);
		}

		input[type="submit"]:active, button:active {
		  box-shadow: inset 0 0 .5em rgba(128,128,255,.8);
		}

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
	
	var expts;
	 $.getJSON('../json/expts_pretty.json', function(response){
	       expts = response;
	       	for (var i = 0; i < expts[0].experiments.length; i++) { 
				var element = document.createElement("option");
	    		    element.innerHTML= expts[0].experiments[i].expt_id;
				    element.setAttribute("name", expts[0].experiments[i].expt_id);
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
					    label.setAttribute('for', "persistbox-"+i+"-"+d);
					    label.textContent= expts[0].experiments[i].timestreams[d];

					element2.appendChild(label);
			    	element2.appendChild(ele);
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


 	</script></script>

	</head>
	<body>
<div>
      <label for="option1">Option 1</label>
      <input type="checkbox" id="option1">
    </div>
		<form id="form" method="POST" action="?">
		    <fieldset>
		        <legend>Setup</legend>
		        <div class="input select" id=>
		            <label for="layoutType">Layout: </label>
		            <select name="layoutType" id="layoutType">
		                <option value="vr">Vertical</option>
		                <option value="hr">Horizontal</option>
		                <option value="gr">Grid</option>
		            </select>
		        </div>
		        <div class="input select" id="experimentselect">
		        	<select name="experimentID" id="experimentID">
		        		<!--stuff goes in here!-->
		        	</select>
		        </div>
		        <div id="hiddenStreams">
		        	<!--More stuff goes in here!-->
		        </div>
		    <div class="submit">
		        <input type="submit" value="Submit" />
		    </div>
		    </fieldset>

		</form>

		<div id="TimeGraphDiv">
		  	<embed id="TimeGraphFlex" src="TimeGraphFlex.swf?license=2498382f5249277454ec3a716f31dfea&config=generateTSConfig.php" 
			  	width="100%" height="100%">
				</embed>
			</div>

		<div>
			<embed src="generateTSConfig.php"></embed> 
		</div>
		</body>

	<script type="text/javascript">
	$(document).ready(function(){
		    $("#experimentID").change(function(){
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