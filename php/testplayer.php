<?php 
session_start(); 
if(isset($_SESSION['layoutType']))
	$_SESSION['layoutType'] = $_SESSION['layoutType'];
else
	$_SESSION['layoutType']='hr';
if (isset($_POST['layoutType'])) {
	$_SESSION['layoutType'] = $_POST['layoutType'];
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
	<!--  END Browser History required section -->

	<style>
		body { margin: 0px; overflow:hidden }
	.hide{
		display: none;
	}


	</style>
	</head>
	<body>

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
		        <div class="input select" id="exprimentselect">
		        	<select name="experimentID" id="experimentID">
		        		<!--stuff goes in here!-->
		        	</select>
		        </div>
		        <div id="hiddenStreams">
		        	<!--More stuff goes in here!-->
		        </div>
		    </fieldset>
		    <div class="submit">
		        <input type="submit" value="Submit" />
		    </div>
		</form>

		<div id="TimeGraphDiv">
		  	<embed id="TimeGraphFlex" src="TimeGraphFlex.swf?license=2498382f5249277454ec3a716f31dfea&config=generateTSConfig.php" 
			  	width="100%" height="100%">
				</embed>
			</div>
		</body>

	<script type="text/javascript">
		//$(document).ready(function(){
		    $("#experimentID").change(function(){
		    	$("#hiddenStreams").slideUp("fast");
		    	$("div").remove(".removeme");
		    	$("#hiddenStreams").append("<div class='removeme'></div>");
		    	for(var i = 0; i < expts[0].experiments.length; i++){
		    		if(expts[0].experiments[i].expt_id == $("#experimentID").val()){
		    			for (var d = 0; d < expts[0].experiments[i].timestreams.length; d++) {
		    				var str = expts[0].experiments[i].timestreams[d];
		    				$(".removeme").append("<input type='checkbox' name='streamselect' value='"+str+ "'>"+str+"</input><br />");
		    			}
		    		}
		    	}
		     	$("#hiddenStreams").slideDown("slow"); //Slide Down Effect
		 
		    });
		//});


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
			}
	 });


 	</script>
 	<script type="text/javascript">

 	</script>

	</html>