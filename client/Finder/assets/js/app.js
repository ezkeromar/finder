$(document).ready(function(){
	
	$('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	})
	$( ".forgotpassword-link" ).click(function() {
	  $( ".seconnecter" ).slideToggle( "slow" );
	  $( ".forgotpassword-holder" ).delay(500).slideToggle( "slow" );
	});
	$( ".revenir" ).click(function() {
	  $( ".forgotpassword-holder" ).slideToggle( "slow" );  
	  $( ".seconnecter" ).delay(500).slideToggle( "slow" );
	});
})