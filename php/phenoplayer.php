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
		.playerclass{display: none; height: 100%;}
		html,body
		{
		  height: 100%;
		}
		a.experimentselection{
			text-decoration: none;
		}
		.container-fluid{height: 100%;}
		#TimeGraphDiv{
			height: 100%; display: none;    overflow:hidden;
		}
 		 
	</style>

	<script type="text/javascript" src="pheno_getJson.js"> </script>
 	<script type="text/javascript" src="functions.js"> </script>

	<script type="text/javascript">
		$(document).ready(function(){
			var query = getQueryParams(document.location.search);
			if(typeof query.lt === 'undefined'){
				$("#layoutType").val("gr");	
			}
				$("#sub").click(function () {
					if($("#TimeGraphDiv").css('display')=='none'){
						$("#TimeGraphDiv").slideDown("slow");
					}
				});

			    $("#experimentID").delegate('.experimentselection', 'click', function(){
			    	clearCheckboxCookie();
			    	reloadEmbed();
			    	$.cookie.json = false;
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
	<div class="container-fluid" style="height:100%;">
		<div class="playerclass">
			<div class="col-md-3" style="padding:0px;">
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
				        	<! More stuff goes in here >
				        </div>
				    
				    <div class="btn-group btn-group-justified">
				    	<div class="btn-group">
				        	<input type="button" class="btn btn-primary" value="Submit" id="sub" onclick="reloadEmbed();generatePreview();" />
				        </div>
				        <div class="btn-group">
				        	<input type="button" class="btn btn-warning" value="Copy" id="save" onclick="copyAllCookies();" />
				        </div>
				        <div class="btn-group">
				        	<input type="button" class="btn btn-danger" value="Clear" id="clear" onclick="clearCheckboxCookie();closeHidden();" />
				        </div>
				    </div>
				    <div class="btn-group btn-group-justified">
				    <div class="btn-group" id="showLink">
				        	
				    </div>
				    </div>
				    <br />
				    <div class="col-md-3" id="hiddenPreview"  style="height:100%; width:100%; text-align:center;">
				    <! yarr! here be some hidden preview! >
				    </div>
				    </fieldset>
				</form>

				<br />
			</div>
		<div onmouseover="document.body.style.overflow='hidden';" onmouseout="document.body.style.overflow='auto';" id="TimeGraphDiv" class="col-md-9">
		  		<embed name="timegraph" class='TimeGraphFlex' id="TimeGraphFlex" src="TimeGraphFlex.swf?license=def20d85a970dfad6be9f30c32280c17&config=generatePhenoTSConfig.php" width="100%" height="100%">
				</embed>
		</div>
	</div>
		<!div class="col-md-12"><!input name="streamsearch" type="text" class="form-control" placeholder="Filter" onkeyup="search(this.value)" style="width:101px;"><!/div>
		<div class='row'>
			<div class="col-md-12">
				<div class="col-md-12">
			        <div id="experimentselect" >
			        	<label class="sr-only" for="experimentID">Experiment: </label>
			        	<div id="experimentID">
			        	<br>
			        		<! stuff in here >
			        	</div>
			        </div>
				</div>
			</div>
		</div>

	</div>
		<div><embed src="generatePhenoTSConfig.php"></div>
<!2498382f5249277454ec3a716f31dfea>
	</div>
	</body>
	</html>
