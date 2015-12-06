function saveForm(e) {
  e = e || event;
  var evt = e.type;
  if(e.keyCode == 83 && e.ctrlKey){edit_form.submit();return false;}
}
document.onkeydown = saveForm;

function display_section_menu(){
	$('#section_nav_menu').toggle();
    $( "#accordion" ).accordion({
      heightStyle: "fill",
	  active: active_section //  set in footer
    });
 
    $( "#accordion-resizer" ).resizable({
      resize: function() {
		  $( "#accordion" ).accordion( "refresh" );
	  }
    });
}

function display_menu(){
	var cancelMenu = function(event){
		if($(event.target).is("#main_nav_chosen, #main_nav *")) return;
		$(document).off('click', cancelMenu);
		$('#main_nav').css('display', '');
	}
	$(document).on('click', cancelMenu);
	$('#main_nav').toggle();
	return false;
}