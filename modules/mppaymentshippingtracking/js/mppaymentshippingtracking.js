$(document).ready(function(){

	var selected_option_value = $("#id_order_state option:selected").val();
	if(selected_option_value == 4)
	{
		$('#update_order_status_shipping').show();
		$('#update_order_status_delivary').hide();
		$('#update_order_status').hide();
	}
	else if(selected_option_value == 5)
	{
		$('#update_order_status_shipping').hide();
		$('#update_order_status_delivary').show();
		$('#update_order_status').hide();
	}

	$('#update_order_status_shipping').fancybox({
		width: 600,
	    height: 450,
	    autoSize : false,
	    maxWidth : '100%',
		'hideOnContentClick': false
	});
	$('#update_order_status_delivary').fancybox({
		width: 600,
	    height: 450,
	    autoSize : false,
	    maxWidth : '100%',
		'hideOnContentClick': false
	});

	
	$(function(){
		//$( ".datetime" ).datetimepicker({ dateFormat: "yy-mm-dd" });
	});

	$(document).on('change', '#id_order_state', function(){
		selected_state_id = $('#id_order_state').val();
		$('.id_order_state_checked').val(selected_state_id);
		if(selected_state_id == 4)
		{
			$('#update_order_status_shipping').show();
			$('#update_order_status_delivary').hide();
			$('#update_order_status').hide();
		}
		else if(selected_state_id == 5)
		{
			$('#update_order_status_shipping').hide();
			$('#update_order_status_delivary').show();
			$('#update_order_status').hide();
		}
		else
		{
			$('#update_order_status_shipping').hide();
			$('#update_order_status_delivary').hide();
			$('#update_order_status').show();
		}
	});	

	$(document).on('click', '#edit_shipping_number_link', function (e) {
		e.preventDefault();
		$('#shipping_number_show').hide();
		$('#edit_shipping_number_link').removeClass();
		$('#edit_shipping_number_link').hide();
		$('#shipping_number_edit').css('display','block');	
	});

	$(document).on('click', '#cancel_shipping_number_link', function (e) {
		e.preventDefault();
		$('#shipping_number_show').show();
		$('#edit_shipping_number_link').addClass('btn');
		$('#edit_shipping_number_link').addClass('btn-default');
		$('#edit_shipping_number_link').addClass('button');
		$('#edit_shipping_number_link').addClass('button-small');
		$('#edit_shipping_number_link').show();
		$('#shipping_number_edit').css('display','none');
	});

	$(document).on('click', '#submit_shipping_number', function () {
		var id_order = $('#id_order_tracking').val();
		var tracking_number = $('#tracking_number').val();
		var id_order_carrier = $('#id_order_carrier').val();
		if(tracking_number != "")
		{
			$.ajax({
				type: "POST",
				async: true,
				url: update_tracking_number_link,
				data: { 
					id_order: id_order,
					tracking_number: tracking_number,
					id_order_carrier : id_order_carrier
				},
				success: function(data)
				{
					$('#shipping_number_show').text(tracking_number);
					$('#tracking_number').val(tracking_number);
					$('#tracking_number_update_success_message').fadeIn(200).delay(2500).fadeOut(1000);
				},
				fail: function()
				{
					$('#tracking_number_update_fail_message').fadeIn(200).delay(2500).fadeOut(1000);
				}
			});
			$('#shipping_number_show').show();
			$('#edit_shipping_number_link').addClass('btn btn-default button button-small');
			$('#edit_shipping_number_link').show();
			$('#shipping_number_edit').css('display','none');		
		}
	});

	$(document).on('click', '#edit_shipping', function(){
		$('#edit_textarea_shipping_description').show();
		$('#label_shipping_description').hide();
		$('#text_shipping_date').show();
		$('#label_shipping_date').hide();
	});

	$(document).on('click', '#edit_delivery', function(){
		$('#text_delivery_date').show();
		$('#label_delivery_date').hide();
		$('#edit_text_received_by').show();
		$('#label_received_by').hide();
	});
});