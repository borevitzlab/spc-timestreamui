
	function is_whole_number(in_var){
	return (int(in_var)==(float(in_var)));
}
// checks to see if a number is a square number
function is_square_number(in_var){
	return (is_whole_number(sqrt(in_var)));
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
    for(var i = 3; i <= ceil(sqrt(num)); i = i + 2) {
        if(num % i == 0)
            return false;
    }
    return true;
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

// returns an array of the two median factors
function find_median_factors(array) {
	    var result = [0,0];
        result[0] = array[-1+array.length/2];
        result[1] = array[array.length/2];
    return result;
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