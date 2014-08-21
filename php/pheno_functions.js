function is_whole_number(value){
	return !isNaN(value) && parseInt(Number(value)) == value;
}
// checks to see if a number is a square number
function is_square_number(in_var){
	return (is_whole_number(Math.sqrt(in_var)));
}
// checks to see whether a number is a prime
function is_prime(num) {
    if(num == 1)
        return false;
    if(num == 2)
        return false;
    if(num % 2 == 0) {
        return false;
    }
    for(var i = 3; i <= Math.ceil(Math.sqrt(num)); i = i + 2) {
        if(num % i == 0)
            return false;
    }
    return true;
}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}
// returns an array of the factors of a number
function get_factors(num) {
	 var n_factors = [], i;
	 for (i = 1; i <= Math.floor(Math.sqrt(num)); i += 1)
	  if (num % i === 0)
	  {
	   n_factors.push(i);
	   if (num / i !== i)
	    n_factors.push(num / i);
	  }
	 n_factors.sort(function(a, b){return a - b;});  // numeric sort
	 return n_factors;
}

function replaceunderscore(inputstring){
    return inputstring.split('_').join(' ');
}

// returns an array of the two median factors
function find_median_factors(array) {
	    var result = [0,0];
        result[0] = array[-1+array.length/2];
        result[1] = array[array.length/2];
    return result;
}

function clearCheckboxCookie(){
	$(":checkbox").prop("checked", false);
	$.cookie('checkboxValues', null, { expires: 7, path: '/' });
	$.cookie('streamselect', null, { expires: 7, path: '/' });
	$.cookie('experimentID',null, {expires: 7, path: '/'});
}

function reloadEmbed(){
	    var doc = $('<embed name="TimeGraph" id="TimeGraphFlex" src="TimeGraphFlex.swf?license=def20d85a970dfad6be9f30c32280c17&config=generatePhenoTSConfig.php" width="100%" height="100%">');
    	    $("#TimeGraphDiv").slideUp("fast");
            $("#hiddenPreview").slideUp("fast");
    	    $('#TimeGraphDiv').empty().append(doc);
    	    if(getCookie("streamselect")!="null"){
    	        $("#TimeGraphDiv").slideDown("fast");
                $("#hiddenPreview").slideDown("fast");
            }
}

function closeHidden(){
	    	$("#TimeGraphDiv").slideUp("slow");
	    	$(".playerclass").slideUp("slow");
	       for(var i = 0; i < expts[0].experiments.length; i++){
	    			$("#hide-"+expts[0].experiments[i].expt_id).slideUp("slow");
	    	}
	    	$('#hiddenPreview').slideUp("slow").empty();
}

//search stuff, broken at the moment.
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




function generatePreview(){
   //if(getCookie("streamselect")!="null"){
    var previewarray = $.parseJSON(decodeURIComponent(getCookie("streamselect")));
    var layoutType = $.parseJSON(decodeURIComponent(getCookie("layoutType")));
    var prevar = $.map(previewarray,function(value,index){ return [value];});
    var hiddenPreview = document.getElementById("hiddenPreview");
        hiddenPreview.innerHTML = '';
    
        if(prevar.length==1){
            var thispreviewdiv = document.createElement('div');
                var thispreviewhref = document.createElement('a');
                    thispreviewhref.setAttribute("class","thumbnail");
                    thispreviewhref.setAttribute("href", '#');
                    thispreviewdiv.setAttribute("style", "width:100%; float:left; height: " + (100/prevar.length) + "%");
                    thispreviewhref.textContent = prevar[0].split("~")[0] + "-" + prevar[0].split("~")[1].split('-')[1];
                    thispreviewdiv.appendChild(thispreviewhref);
                    hiddenPreview.appendChild(thispreviewdiv);
        }

        if (prevar.length>1&&layoutType=="hr") {
            for (var i = 0; i < prevar.length; i++) {
                var thispreviewdiv = document.createElement('div');
                var thispreviewhref = document.createElement('a');
                    thispreviewhref.setAttribute("class","thumbnail");
                    thispreviewhref.setAttribute("href", '#');
                    thispreviewdiv.setAttribute("style", "width:" +(100/prevar.length)+"%; height:100%; float:left;");
                    thispreviewhref.textContent = prevar[i].split("~")[0] + "-" + prevar[delta].split("~")[1].split('-')[1];
                    thispreviewdiv.appendChild(thispreviewhref);
                    hiddenPreview.appendChild(thispreviewdiv);
            }
        }

        if (prevar.length>1&&layoutType=="vr") {
            for (var i = 0; i < prevar.length; i++) {
                var thispreviewdiv = document.createElement('div');
                var thispreviewhref = document.createElement('a');
                    thispreviewhref.setAttribute("class","thumbnail");
                    thispreviewhref.setAttribute("href", '#');
                    thispreviewdiv.setAttribute("style", "width:100%; float:left; height: " + (100/prevar.length) + "%");
                    thispreviewhref.textContent = prevar[i].split("~")[0] + "-" + prevar[delta].split("~")[1].split('-')[1];
                    thispreviewdiv.appendChild(thispreviewhref);
                    hiddenPreview.appendChild(thispreviewdiv);
            }
        }

        if (prevar.length>1&&layoutType=="gr") {
            if(is_square_number(prevar.length)){
                var delta = 0;
                for(var x = 0; x<Math.sqrt(prevar.length); x++){
                    for(var y = 0; y < Math.sqrt(prevar.length); y++){
                        var thispreviewdiv = document.createElement('div');
                        var thispreviewhref = document.createElement('a');
                            thispreviewhref.setAttribute("class","thumbnail");
                            thispreviewhref.setAttribute("href", '#');
                            thispreviewdiv.setAttribute("style", "width:" +(100/Math.sqrt(prevar.length))+"%; float:left; height: " + (100/Math.sqrt(prevar.length)) + "%");
                            thispreviewhref.textContent = prevar[delta].split("~")[0] + "-" + prevar[delta].split("~")[1].split('-')[1];;
                            thispreviewdiv.appendChild(thispreviewhref);
                            hiddenPreview.appendChild(thispreviewdiv);
                            delta++;
                    }
                }               
            }else{
                if (is_prime(prevar.length)) {
                    var delta = 0;
                    var closest_factors = find_median_factors(get_factors(prevar.length+1));
                    var number_of_columns = closest_factors[0];
                    var number_of_rows = closest_factors[1];
                    for(var x = 0; x<number_of_rows; x++){
                        if(x==0){
                            var n_missing = number_of_columns*number_of_rows - prevar.length;
                            var num_col_sub = number_of_columns-n_missing;
                            for(var y = 0; y < num_col_sub; y++){
                                var thispreviewdiv = document.createElement('div');
                                var thispreviewhref = document.createElement('a');
                                    thispreviewhref.setAttribute("class","thumbnail");
                                    thispreviewhref.setAttribute("href", '#');
                                    thispreviewdiv.setAttribute("style", "width:" +(100/num_col_sub)+"%; float:left; height: " + (100/number_of_rows) + "%");
                                    thispreviewhref.textContent = prevar[delta].split("~")[0] + "-" + prevar[delta].split("~")[1].split('-')[1];
                                    thispreviewdiv.appendChild(thispreviewhref);
                                    hiddenPreview.appendChild(thispreviewdiv);
                                    delta++;
                            }
                        }else{
                            for(y = 0; y < number_of_columns; y++){
                                var thispreviewdiv = document.createElement('div');
                                var thispreviewhref = document.createElement('a');
                                    thispreviewhref.setAttribute("class","thumbnail");
                                    thispreviewhref.setAttribute("href", '#');
                                    thispreviewdiv.setAttribute("style", "width:" +(100/number_of_columns)+"%; float:left; height: " + (100/number_of_rows) + "%");
                                    thispreviewhref.textContent = prevar[delta].split("~")[0] + "-" + prevar[delta].split("~")[1].split('-')[1];;
                                    thispreviewdiv.appendChild(thispreviewhref);
                                    hiddenPreview.appendChild(thispreviewdiv);
                                delta++;
                            }
                        }
                    }
                }else{
                    var closest_factors = find_median_factors(get_factors(prevar.length));
                    var number_of_columns = closest_factors[0];
                    var number_of_rows = closest_factors[1];
                    var delta = 0;
                    for(var x = 0; x<number_of_rows; x++){
                        for(y = 0; y < number_of_columns; y++){
                            var thispreviewdiv = document.createElement('div');
                            var thispreviewhref = document.createElement('a');
                                thispreviewhref.setAttribute("class","thumbnail");
                                thispreviewhref.setAttribute("href", '#');
                                thispreviewdiv.setAttribute("style", "width:" +(100/number_of_columns)+"%; float:left; height: " + (100/number_of_rows) + "%");
                                thispreviewhref.textContent = prevar[delta].split("~")[0] + "-" + prevar[delta].split("~")[1].split('-')[1];
                                thispreviewdiv.appendChild(thispreviewhref);
                                hiddenPreview.appendChild(thispreviewdiv);
                            delta++;
                        }
                    }  
                }
            }
        }
}