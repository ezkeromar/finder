$(document).ready(function(){
    $('section, .wanted, .news, .wanted-items, .news-items, .news-item, .news-items-holder').css('height', $(window).height());
});
$(window).resize(function(){
    $('section, .wanted, .news, .wanted-items, .news-items, .news-item, .news-items-holder').css('height', $(window).height());
});
$(document).ready(function(){
    $('.wanted-items li').css('height', $(window).height() / 2);
});
$(window).resize(function(){
    $('.wanted-items li').css('height', $(window).height() / 2);
});
$(".main-homepage .scroll-btn").click(function() {
    $('html, body').animate({
        scrollTop: $(".wanted-news").offset().top //services
    }, 1000);
}); 
$(".wanted-news .scroll-btn").click(function() {
    $('html, body').animate({
        scrollTop: $(".services").offset().top
    }, 1000);
    $('.services .col-md-6 span').each(function () {
	    $(this).delay(200).prop('Counter',0).animate({
	        Counter: $(this).text()
	    }, {
	        duration: 2000,
	        easing: 'swing',
	        step: function (now) {
	            $(this).text(Math.ceil(now));
	        }
	    });
	});
});
$('.wanted-holder').click(function(){
    $(this).parent().toggleClass('zindex-plus');
    $(this).parent().siblings().toggleClass('zindex-minus');
    $(this).parent().toggleClass('active');
})
let x = (jQuery(document).width() * 60) / 100
$('.news-items-holder').css({'width' : x})
$('.news-holder').click(function(){
	$(this).parent().toggleClass('zindex-plus');
	$(this).parent().siblings().toggleClass('zindex-minus');
    $(this).parent().toggleClass('active');
    if ($(".news-items-holder").hasClass("hide-load")) {
        $(this).removeClass('hide-load')
    }
})
$('.news-items').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    variableWidth: false,
    autoplay: true,
    dots: true,
    autoplaySpeed: 2000
});
/*
$(window).bind("load", function() {
    setTimeout(function() {
        $('body').removeClass('load');
    }, 1000);
});*/