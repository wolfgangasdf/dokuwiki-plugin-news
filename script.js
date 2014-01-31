
  function newshandler() {
 
      var prev_del = document.getElementById("prev_del");
      var inputs = document.getElementsByTagName("input");
	  var prev = "";
	 	  
	  for(var i=0; i<inputs.length;i++) {
	   if(inputs[i].type == 'checkbox') {	 
              if(inputs[i].checked  && inputs[i].name.match(/delete/))  {	
			      prev += inputs[i].value + ",";
				 }
		 }		
	  }	  
	 
	  if(prev_del.value && prev) {
	     prev_del.value += "," + prev;
	  }
	  else if (prev) prev_del.value = prev;
	  prev_del.value = prev_del.value.replace(/\s*,$/,"");
	  document.news_data.subfeed_dir.value=document.news_data.subfeeds.value;	
  }
  
  function confirm_del() {       
      if(window.confirm("The deleted feeds will be removed from the current news feed.")) return true;
	  return false;
  }
  
  function subfeedshandler() {
     var selected = document.news_data.subfeeds;
     var index = selected.selectedIndex;     
     document.news_data.subfeed_inx.value = index;
  }
  
  jQuery(document).ready(function(){
  jQuery("#news_infobtn").click(function(){
    jQuery(".news_info").toggle();
  });
});