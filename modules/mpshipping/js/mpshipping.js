$(document).ready(function() {
	$("#buttonNext").on('click', function(e){
		var shipping_name_length = $('#shipping_name').val().length;
		var shipping_name_error = 'Shipping Profile name is required';

		if(shipping_name_length == 0)
		{
			e.preventDefault();
			alert(shipping_name_error);
			return false;
		}
	});
});