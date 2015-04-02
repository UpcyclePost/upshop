$(function(){
	$('.open-review-form').fancybox({
		width: 600,
	    height: 310,
	    autoSize : false,
	    maxWidth : '100%',
		'hideOnContentClick': false
	});

	$(document).on('click', '#review_submit .closefb', function(e){
		e.preventDefault();
		$.fancybox.close();
	});
});