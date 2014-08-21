var expts;
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

	$.getJSON('../json/expts.json', function(response){
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
				    experimentIDImageTag.setAttribute("src", expts[0].experiments[i].thumbnails[0]);
				    experimentIDImageTag.setAttribute("width", "100%");
				var experimentIDLinkTag = document.createElement('a');
					experimentIDLinkTag.setAttribute("src", '#');
					experimentIDLinkTag.setAttribute("value", expts[0].experiments[i].expt_id);
					experimentIDLinkTag.setAttribute("class", "thumbnail col-lg-2 col-md-2 col-sm-3 col-xs-5 experimentselection");
					
				var experimentIDHeadingTag = document.createElement('h4');
					experimentIDHeadingTag.textContent = replaceunderscore(expts[0].experiments[i].expt_id);
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
						if(expts[0].experiments[i].timestreams[d].indexOf('~') === -1){
					    	streamSelectCheckboxLabelTag.textContent = expts[0].experiments[i].timestreams[d];
					    }else{
					    	streamSelectCheckboxLabelTag.textContent = expts[0].experiments[i].timestreams[d].split("~")[0] + "-" + expts[0].experiments[i].timestreams[d].split("~")[1].split('-')[1];
						}
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
		    	var lyt = $.parseJSON(decodeURIComponent(getCookie("layoutType")));
		    	$("#layout").val(lyt);
		    }else{
		    	var lyt = "gr";
		    	$("#layout").val(lyt);
				var layoutType= $("#layout").val();
				$.cookie.json = true;
		    	$.cookie('layoutType', layoutType, { expires: 7, path: '/' });
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