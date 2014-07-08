var expts;
 $.getJSON('../json/expts_pretty.json', function(response){
	       expts = response;
	       	for (var i = 0; i < expts[0].experiments.length; i++) { 
	       		 
				var experimentIDImageTag = document.createElement("img");
	    		    //experimentIDImageTag.innerHTML= expts[0].experiments[i].expt_id;
				    experimentIDImageTag.setAttribute("name", expts[0].experiments[i].expt_id);
				    experimentIDImageTag.setAttribute("value", expts[0].experiments[i].expt_id);
				    experimentIDImageTag.setAttribute("src", "img.svg");
				    // experimentIDImageTag.setAttribute("class", "experimentselection");
				var experimentIDLinkTag = document.createElement('a')
					experimentIDLinkTag.setAttribute("class", "thumbnail col-md-3 col-lg-2 col-sm-4 experimentselection");
					experimentIDLinkTag.setAttribute("value", expts[0].experiments[i].expt_id);
					experimentIDLinkTag.setAttribute("src", '#')
					experimentIDLinkTag.appendChild(experimentIDImageTag);
		    	var experimentIDDivTag = document.getElementById("experimentID");
		    	experimentIDDivTag.appendChild(experimentIDLinkTag);


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