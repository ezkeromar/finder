$(document).ready(function(){

	//alert('app js loaded');

	$(".input-file").change(function () {
	    $(".input-file").each(function() {
		    var fileName = $(this).val().split('/').pop().split('\\').pop();
	 	    $(".input-file-holder span").empty();
			$( ".input-file-holder span" ).append(fileName);
		});
	});
	$('.satisfaction-level a').click(function(){
		$(this).toggleClass('active');
		$(this).siblings().removeClass('active');
	})
	$('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');
		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	})
	$( ".alert-icon" ).click(function() {
		$('.alert-menu').toggleClass('active');
	});
	$( ".forgotpassword-link" ).click(function() {
	  $( ".seconnecter" ).slideToggle( "slow" );
	  $( ".forgotpassword-holder" ).delay(500).slideToggle( "slow" );
	});
	$( ".revenir" ).click(function() {
	  $( ".forgotpassword-holder" ).slideToggle( "slow" );  
	  $( ".seconnecter" ).delay(500).slideToggle( "slow" );
	});
	$('.search-icon').click(function(){
		$(this).parent().toggleClass('active'); 
	});
	$('.filter-btn').click(function(){
		$(this).parent().toggleClass('active');
	})
	$('.popup-close, .popup-click').click(function(){
		$('.popup').removeClass('active');
		$('body').removeClass('no-scroll');
		$('.popup-container.popup-consultant').addClass('active');
		$('.popup-container.popup-consultant').siblings().removeClass('active');
	})
	$('.list-profile').click(function(){
		$('.popup').addClass('active');
		$('body').addClass('no-scroll');
	})
	$('.add-exp').click(function(){
		$('.popup, .popup-addCV').addClass('active'); 
		$('body').addClass('no-scroll');
	})
	$('.consultant-cv').click(function(){
		$('.popup-container.popup-envoye-demande').addClass('active');
		$('.popup-container.popup-envoye-demande').siblings().removeClass('active');
	})
    $('<div class="number-button number-up">+</div><div class="number-button number-down">-</div>').insertAfter('.number input');
    $('.number').each(function() {
      var spinner = $(this),
        input = spinner.find('input[type="number"]'),
        btnUp = spinner.find('.number-up'),
        btnDown = spinner.find('.number-down'),
        min = input.attr('min'),
        max = input.attr('max');

      btnUp.click(function() {
        var oldValue = parseFloat(input.val());
        if (oldValue >= max) {
          var newVal = oldValue;
        } else {
          var newVal = oldValue + 1;
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });

      btnDown.click(function() {
        var oldValue = parseFloat(input.val());
        if (oldValue <= min) {
          var newVal = oldValue;
        } else {
          var newVal = oldValue - 1;
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });

    }); 
})