$(document).ready(function() {
	$("#buttonNext").on('click', function(e){
		var shipping_name_length = $('#shipping_name').val().length;
		var shipping_time = $('#ship_transit_time').val();
		var shipping_cost = $('#n_america_ship').val();	
		var shipping_cost_length = $('#n_america_ship').val().length;
		
		var shipping_name_error = 'Shipping Profile name is required';
		var shipping_time_error = 'Shipping Time is required';		
		var shipping_cost_error = 'Shipping cost is required or is not a valid amount';

		if(shipping_name_length == 0)
		{
			e.preventDefault();
			alert(shipping_name_error);
			$('#shipping_name').focus();
			return false;
		}

		if(shipping_time == 'Select a value')
		{
			e.preventDefault();
			$('#ship_transit_time').focus();
			alert(shipping_time_error);
			return false;
		}

		if(shipping_cost_length == 0 || isNaN(shipping_cost))
		{
			e.preventDefault();
			$('#n_america_ship').focus();
			alert(shipping_cost_error);
			return false;
		}
		
	});
});


	$(document).ready(function() {

		$('.tran_val').on('click', function(e)
		{
			e.preventDefault();
			
			var delay_msg = $(this).html();
			$('.ship_trans_text').html(delay_msg);
			$('#ship_transit_time').val(delay_msg);
		});


		$(".buttonNext").click(function(e){
			var is_error = false;
			var message = '';
			var shipping_name = $('#shipping_name').val();
			var transit_time = $('#transit_time').val();
			var rel = $('#carrier_wizard .selected a').attr('rel');
			var logo = $("#shipping_logo").val();
			var exts = ['jpg','png','jpeg','gif'];

			if(shipping_name == '')
			{
				alert(shipping_name_error);
				is_error = true;
			}
			else if(transit_time == '')
			{
				alert(transit_time_error);
				is_error = true;
			}
			else if (logo)
			{
				var get_ext = logo.split('.');  // split file name at dot
        		get_ext = get_ext.reverse(); // reverse name to check extension
        		if ( $.inArray ( get_ext[0].toLowerCase(), exts ) <= -1 )
        		{
        			alert(invalid_logo_file_error);
        			return false;
        		}
			}
			
			if(is_error==true)
			{
				$('.wizard_error').fadeIn(1000);
				$('.wizard_error ul').append(message)
				return false;
			}
			else
			{
				if(rel==1)
					$('#step_carrier_general').submit();
				else if(rel==2)
				{
					var price_value_lower = $(".edit_price_value_lower").val();
					var price_value_upper = $(".edit_price_value_upper").val();
					var is_free_check = $("#is_free_off").is(":checked");
					//alert($.isNumeric(price_value_lower));
					//return false;
					//alert(error_message);
					shipping_charge_error_message = "";
					if(is_free_check)
					{
						if(price_value_lower == "" || !$.isNumeric(price_value_lower))
							shipping_charge_error_message = shipping_charge_error_message.concat(shipping_charge_lower_limit_error1);
						else if(price_value_lower < 0)
							shipping_charge_error_message = shipping_charge_error_message.concat(shipping_charge_lower_limit_error2);
						else if(price_value_upper == "" || !$.isNumeric(price_value_upper))
							shipping_charge_error_message = shipping_charge_error_message.concat(shipping_charge_upper_limit_error1);
						else if(price_value_upper < 0)
							shipping_charge_error_message = shipping_charge_error_message.concat(shipping_charge_upper_limit_error2);
						else if (parseFloat(price_value_upper) < parseFloat(price_value_lower))
							shipping_charge_error_message = shipping_charge_error_message.concat(shipping_charge_limit_error);
						else if (parseFloat(price_value_upper) === parseFloat(price_value_lower))
							shipping_charge_error_message = shipping_charge_error_message.concat(shipping_charge_limit_equal_error);
						
					}
					
					if(shipping_charge_error_message == "")
					{
						$('.button_click').attr('value','next');
						$('#step_carrier_pricezone').submit();
					}
					else
					{
						alert(shipping_charge_error_message);
						return false;
					}
				}
				else if(rel==3)
				{
					$('.button_click').attr('value','next');
					$('#step_carrier_size_weight').submit();
				}
				else if(rel==4)
				{

				}
			}
		});
		
		$('.buttonPrevious').click(function(e) {
			var rel = $('#carrier_wizard .selected a').attr('rel');
			$('.button_click').attr('value','prev');
			if(rel==2)
			{
				$('#step_carrier_pricezone').submit();
			}
			else if(rel==3)
			{
				$('#step_carrier_size_weight').submit();
			}
			else if(rel==4)
			{
			}
		});
		$('.buttonFinish').click(function(e) {
			var class_name = $(this).attr('class');
			if(class_name=='buttonFinish') {
				var rel = $('#carrier_wizard .selected a').attr('rel');
				$('.button_click').attr('value','finish');
				if(rel==1) {
					$('#step_carrier_general').submit();
				}
				else if(rel==2) {
					$('#step_carrier_pricezone').submit();
				} else if(rel==3) {
					$('#step_carrier_size_weight').submit();
				} else if(rel==4) {
					$('#step_carrier_impact_country').submit();				
				}
			}
			else {
				alert(finish_error);
			}
		});
		
		
		$('#hideError').click(function(e) {
			e.preventDefault();
			$('.wizard_error').fadeOut(1000);
			$('.wizard_error ul').html('');
		});
		$('.selected').click(function(e) {
			e.preventDefault();
			var rel = $(this).attr('href');
		});
		
		$('#step4_zone').live('change', function (event) {
			var id_zone = $(this).attr('value');
			if(id_zone==-1) {
				$('#step4_country').html("<option value='-1'>"+select_country+"</option>");
				$('#country_container').css('display','none');
				$('#state_container').css('display','none');
			} else {
				$('#country_container').css('display','none');
				$('#state_container').css('display','none');
				findCountry(id_zone);
				
			}
		});
		
		$('#step4_country').live('change', function (event) {
			var id_country = $(this).attr('value');
			if(id_country==-1) {
				$('#step4_state').html("<option value='0'>"+select_state+"</option>");
				$('#state_container').css('display','none');
			} else {
				findState(id_country);
				
			}
		});
		
		$('#impactprice_button').live('click', function (e) {
			e.preventDefault();
			findRange();
		});
		
		$('#close_popup').live('click', function (e) {
			e.preventDefault();
			closePopup();
		});
		
		$('#step_carrier_range').on('submit', function (e) {
			e.preventDefault();
			$.ajax({
				url:shipping_ajax_range_link,
				ajax: 1,
				type: 'POST',
				data: $('#step_carrier_range').serialize(),
				dataType: 'json',
				success: function(data, status, xhr)
				{
					if(data==0) {
						alert(message_impact_price_error);
					} else {
						alert(message_impact_price);
						closePopup();
					}
				}
			});
		});
	});

	
	$(document).ready(function(){
		function getImgSize(input){
		    if (input.files && input.files[0])
		    {
		        var reader = new FileReader();
		        reader.onload = function (e){
		            $('#testImg').attr('src', e.target.result);
		        }
		        reader.readAsDataURL(input.files[0]);
		    }
		}

		$('#testImg').on('load',function(){
			if ($(this).width() > 125 || $(this).height() > 125)
			{
				$('#testImg').css('display', 'none');
				alert(invalid_logo_size_error+$(this).width()+'*'+$(this).height());
				$("#shipping_logo").attr('value','');
				$(".filename").text('No file selected');
				$('#testImg').attr('src', '');
				return false;
			}
			else
				$('#testImg').css('display', 'block');
		})

		$("#shipping_logo").change(function(){
		    getImgSize(this);
		});
	});
	$(document).ready(function() {
		bind_inputs();
		initCarrierWizard();
		if (parseInt($('input[name="is_free"]:checked').val()))
			is_freeClick($('input[name="is_free"]:checked'));
		displayRangeType();
		
	});
	function closePopup() {
		
		$('#header').css('display','block');
		$('#impact_price_block').css('display','none');
		$('#range_info_detail').html('');
		$('#newbody').fadeOut(1000);
	}

	function findRange() 
	{
		var id_zone = $('#step4_zone').attr('value');
		var id_country = $('#step4_country').attr('value');
		var id_state = $('#step4_state').attr('value');
		var shipping_method = $('.step4_shipping_method').attr('value');
		var mpshipping_id = $('.mpshipping_id').attr('value');
		
		var is_error = false;
		if(id_zone==-1) {
			alert(zone_error)
			is_error = true;
			return false;
		}
		if(id_country==-1) {
			alert(zone_error)
			is_error = true;
			return false;
		}
		
		if(is_error==false) {
			var data1 = {	
						ajax: 1,
						id_zone:id_zone ,
						id_country:id_country ,
						id_state:id_state ,
						shipping_method:shipping_method ,
						mpshipping_id:mpshipping_id ,
						fun:'find_range'
					} 
			$.ajax(shipping_ajax_link, {
				type: 'POST',
				'data': data1,
				dataType: 'json',
				success: function(data, status, xhr)
				{
					if(data!=0) {
						$('#range_mpshipping_id').attr('value',mpshipping_id);
						$('#range_mpshipping_id_zone').attr('value',id_zone);
						$('#range_mpshipping_id_country').attr('value',id_country);
						$('#range_mpshipping_id_state').attr('value',id_state);
						$('#range_shipping_method').attr('value',shipping_method);
						$('#range_info_detail').append('<div class="range_head"><div class="range_head_left">'+ranges_info+'('+currency_sign+')</div><div class="range_head_right">Impact Price</div></div>');
						$.each(data, function() {     
							$('#range_info_detail').append('<div class="range_data"><div class="range_head_left">'+this.delimiter1+' - '+this.delimiter2+'</div><div class="range_head_right"><input type="text" class="form-control" name="delivery'+this.id+'" id="delivery'+this.id+'" value="'+this.impact_price+'"></div></div>');
						});
						$('#header').css('display','none');
						$('#impact_price_block').css('display','block');
						$('#newbody').fadeIn(1000);
					} else {
						alert(no_range_available_error);
					}
				},
				error: function(xhr, status, error) {
					
				}
			});
			
			
		}
	}
	function findCountry(id_zone) {
		var data1 = {	ajax: 1,
						id_zone:id_zone ,
						fun:'find_country'
					}
					 
		$.ajax(shipping_ajax_link, {
			type: 'POST',
			'data': data1,
			dataType: 'json',
			success: function(data, status, xhr)
			{
				$('#step4_country').html('');
				$('#country_container').css('display','block');
				$('#step4_country').append($("<option></option>").text(select_country).val('-1'))
				$.each(data, function() {     
					$('#step4_country').append(
						$("<option></option>").text(this.name).val(this.id_country)
					);
				});
			},
			error: function(xhr, status, error) {
				
			}
		});
	}

	function findState(id_country) {
		var data1 = {	ajax: 1,
						id_country:id_country ,
						fun:'find_state'
					}
					 
		$.ajax(shipping_ajax_link, {
			type: 'POST',
			'data': data1,
			dataType: 'json',
			success: function(data, status, xhr)
			{
				$('#step4_state').html('');
				$('#state_container').css('display','block');
				$('#step4_state').append($("<option></option>").text(select_state).val('0'))
				$.each(data, function() {     
					$('#step4_state').append(
						$("<option></option>").text(this.name).val(this.id_state)
					);
				});
			},
			error: function(xhr, status, error) {
				
			}
		});
	}

function initCarrierWizard()
{
	
	displayRangeType();
}

function displayRangeType()
{
	return;
	if (shipping_method == 1)
	{
		string = string_weight;
		$('.weight_unit').show();
		$('.price_unit').hide();
	}
	else if (shipping_method == 2)
	{
		string = string_price;
		$('.price_unit').show();
		$('.weight_unit').hide();
	}
	is_freeClick($('input[name="is_free"]:checked'));
	$('.range_type').html(string);
}

function onShowStepCallback()
{
	
}



function onLeaveStepCallback(obj, context)
{
	
}

function displaySummary()
{
	
}

function validateSteps(fromStep, toStep)
{
	
}

function displayError(errors)
{
	
	var str_error1='';
	str_error = '<div class="error wizard_error" style="display:none"><span style="float:right"><a id="hideError" href="#">X</a></span><ul>';
	for (var error in errors)
	{
		$('input[name="'+error+'"]').addClass('field_error');
		str_error1 += '<li>'+errors[error]+'</li>';
	}
		$('.wizard_error').fadeIn(1000);
		$('.wizard_error ul').append(str_error1)
	
}

function resizeWizard()
{
	
}

function bind_inputs()
{
	$('input').focus( function () {
		$(this).removeClass('field_error');
		$('.wizard_error').fadeOut('fast');
	});
	
	$('tr.delete_range td button').off('click').on('click', function () {
		if (confirm(delete_range_confirm))
		{
			index = $(this).parent('td').index();
			$('tr.range_sup td:eq('+index+'), tr.range_inf td:eq('+index+'), tr.fees_all td:eq('+index+'), tr.delete_range td:eq('+index+')').remove();
			$('tr.fees').each( function () {
				$(this).children('td:eq('+index+')').remove();
			});
			rebuildTabindex();
		}

		return false;
	});
	
	$('tr.fees td input:checkbox').off('change').on('change', function () {
		if($(this).is(':checked'))
		{
			$(this).closest('tr').children('td').each( function () {
				index = $(this).index();
				if ($('tr.fees_all td:eq('+index+')').hasClass('validated'))
					$(this).children('input:text').removeAttr('disabled');
			});
		}
		else
			$(this).closest('tr').children('td').children('input:text').attr('disabled', 'disabled');
		return false;
	});
	
	$('tr.range_sup td input:text, tr.range_inf td input:text').focus( function () {
		$(this).removeClass('field_error');
	});
	
	$('tr.range_sup td input:text, tr.range_inf td input:text').keypress( function (evn) {
		index = $(this).parent('td').index();
		if (evn.keyCode == 13)
		{
			if (validateRange(index))
				enableRange(index);
			else
				disableRange(index);
			return false;
		}
	});
	
	$('tr.fees_all td input:text').keypress( function (evn) {
		index = $(this).parent('td').index();
		if (evn.keyCode == 13)
			return false;
	});
	
	$('tr.range_sup td input:text, tr.range_inf td input:text').typeWatch({
		captureLength: 0,
		highlight: false,
		wait: 1000,
		callback: function() { 

			index = $(this.el).parent('td').index();
			range_sup = $('tr.range_sup td:eq('+index+')').children('input:text').val().trim();
			range_inf = $('tr.range_inf td:eq('+index+')').children('input:text').val().trim();
			if (range_sup != '' && range_inf != '')
			{
				if (validateRange(index))
					enableRange(index);
				else
					disableRange(index);
			}
		}
	});
	
	$(document.body).off('change', 'tr.fees_all td input').on('change', 'tr.fees_all td input', function() {
		index = $(this).parent('td').index();
		val = $(this).val();
		$(this).val('');
		$('tr.fees').each( function () {
			$(this).find('td:eq('+index+') input:text:enabled').val(val);
		});
		
		return false;
	});
	
	$('input[name="is_free"]').off('click').on('click', function() {
		is_freeClick(this);
	});
		
	/*$('input[name="shipping_method"]').off('click').on('click', function() {
		$.ajax({
			type:"POST",
			url : validate_url,
			async: false,
			dataType: 'html',
			data : 'id_carrier='+parseInt($('#id_carrier').val())+'&shipping_method='+parseInt($(this).val())+'&action=changeRanges&ajax=1',
			success : function(data) {
				$('#zone_ranges').replaceWith(data);
				displayRangeType();
				bind_inputs();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				jAlert("TECHNICAL ERROR: \n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
			}
		});
	});*/
	
	$('#zones_table td input[type=text]').off('change').on('change', function () {
		checkAllFieldIsNumeric();
	});	
}

function is_freeClick(elt)
{
	var is_free = $(elt);
	if (parseInt(is_free.val()))
		hideFees();
	else
		showFees();
}

function hideFees()
{
	$('tr.range_inf td, tr.range_sup td, tr.fees_all td, tr.fees td').each( function () {
		if ($(this).index() >= 2)
		{
			$(this).find('input:text, button').val('').attr('disabled', 'disabled').css('background-color', '#999999').css('border-color', '#999999');
			$(this).css('background-color', '#999999');
		}
	});
}

function showFees()
{
	$('tr.range_inf td, tr.range_sup td, tr.fees_all td, tr.fees td').each( function () {
		if ($(this).index() >= 2)
		{
			//enable only if zone is active
			tr = $(this).parent('tr');
			validate = $('tr.fees_all td:eq('+$(this).index()+')').hasClass('validated');
			if ($(tr).index() > 2 && $(tr).find('td:eq(1) input').attr('checked') && validate || !$(tr).hasClass('range_sup') || !$(tr).hasClass('range_inf'))
					$(this).find('input:text').removeAttr('disabled');
			
			$(this).find('input:text, button').css('background-color', '').css('border-color', '');
			$(this).find('button').css('background-color', '').css('border-color', '').removeAttr('disabled');
			$(this).css('background-color', '');
		}
	});
}

function validateRange(index)
{
	
	//reset error css
	$('tr.range_sup td input:text').removeClass('field_error');
	$('tr.range_inf td input:text').removeClass('field_error');
	
	is_ok = true;
	range_sup = parseFloat($('tr.range_sup td:eq('+index+')').children('input:text').val().trim());
	range_inf = parseFloat($('tr.range_inf td:eq('+index+')').children('input:text').val().trim());
	
	if (isNaN(range_sup) || range_sup.length === 0)
	{
		$('tr.range_sup td:eq('+index+')').children('input:text').addClass('field_error');
		is_ok = false;
		displayError([invalid_range]);
	}
	else if (is_ok && (isNaN(range_inf) || range_inf.length === 0))
	{
		$('tr.range_inf td:eq('+index+')').children('input:text').addClass('field_error');
		is_ok = false;
		displayError([invalid_range]);
	}
	else if (is_ok && range_inf >= range_sup)
	{
		$('tr.range_sup td:eq('+index+')').children('input:text').addClass('field_error');
		$('tr.range_inf td:eq('+index+')').children('input:text').addClass('field_error');
		is_ok = false;
		displayError([invalid_range]);
	}
	else if (is_ok && index > 2) //check range only if it's not the first range
	{	
		$('tr.range_sup td').not('.range_type, .range_sign, tr.range_sup td:last').each( function () 
		{
			if ($('tr.fees_all td:eq('+index+')').hasClass('validated'))
			{
				is_ok = false;
				curent_index = $(this).index();
	
				current_sup = $(this).find('input').val();
				current_inf = $('tr.range_inf td:eq('+curent_index+') input').val();
				
				if ($('tr.range_inf td:eq('+curent_index+1+') input').length)
					next_inf = $('tr.range_inf td:eq('+curent_index+1+') input').val();
				else
					next_inf = false;
				
				//check if range already exist
				//check if ranges is overlapping
				if ((range_sup != current_sup && range_inf != current_inf) && ((range_sup > current_sup || range_sup <= current_inf) && (range_inf < current_inf || range_inf >= current_sup)))
					is_ok = true;
			}
			
		});

		if (!is_ok)
		{
			$('tr.range_sup td:eq('+index+')').children('input:text').addClass('field_error');
			$('tr.range_inf td:eq('+index+')').children('input:text').addClass('field_error');
			displayError([range_is_overlapping]);
		}
		else
			checkRangeContinuity();
	}
	return is_ok;
}

function enableZone(index)
{
	$('tr.fees').each( function () {
		if ($(this).find('td:eq(1)').children('input[type=checkbox]:checked').length)	
			$(this).find('td:eq('+index+')').children('input').removeAttr('disabled');
	});
}

function disableZone(index)
{
	$('tr.fees').each( function () {
		$(this).find('td:eq('+index+')').children('input').attr('disabled', 'disabled');
	});
}

function enableRange(index)
{
	$('tr.fees').each( function () {
		//only enable fees for enabled zones
		if ($(this).children('td').children('input:checkbox').attr('checked') == 'checked')
			enableZone(index);
	});
	$('tr.fees_all td:eq('+index+')').addClass('validated').removeClass('not_validated');
	if ($('.zone input[type=checkbox]:checked').length)
		enableGlobalFees(index);
	bind_inputs();
}

function enableGlobalFees(index)
{
	$('span.fees_all').show();
	$('tr.fees_all td:eq('+index+')').children('input').show().removeAttr('disabled');
	$('tr.fees_all td:eq('+index+')').children('.currency_sign').show();
	
}

function disableRange(index)
{
	$('tr.fees').each( function () {
		//only enable fees for enabled zones
		if ($(this).children('td').children('input:checkbox').attr('checked') == 'checked')
			disableZone(index);
	});
	$('tr.fees_all td:eq('+index+')').children('input').attr('disabled', 'disabled');
	$('tr.fees_all td:eq('+index+')').removeClass('validated').addClass('not_validated');
}

function add_new_range()
{
	if (!$('tr.fees_all td:last').hasClass('validated'))
	{
		alert(need_to_validate);
		return false;
	}
	
	last_sup_val = $('tr.range_sup td:last input').val();
	//add new rand sup input
	$('tr.range_sup td:last').after('<td class="center"><div class="input-group fixed-width-md"><span class="input-group-addon price_unit" style="display: none;">&nbsp; '+currency_sign+'</span><input class="form-control" name="range_sup[]" type="text" /><span class="weight_unit" style="display: none;">&nbsp; '+PS_WEIGHT_UNIT+'</span></div></td>');
	//add new rand inf input
	$('tr.range_inf td:last').after('<td class="border_bottom center"><div class="input-group fixed-width-md"><span class="input-group-addon price_unit" style="display: none;">&nbsp; '+currency_sign+'</span><input class="form-control" name="range_inf[]" type="text" value="'+last_sup_val+'" /><span class="weight_unit" style="display: none;">&nbsp; '+PS_WEIGHT_UNIT+'</span></div></td>');
	
	$('tr.fees_all td:last').after('<td class="center border_top border_bottom"><div class="input-group fixed-width-md"><span class="currency_sign" style="display:none" >&nbsp;'+currency_sign+'</span><input style="display:none" class="form-control" type="text" /></div></td>');

	$('tr.fees').each( function () {
		$(this).children('td:last').after('<td class="center"><div class="input-group fixed-width-md"><span class="input-group-addon" >&nbsp;'+currency_sign+'</span><input disabled="disabled" name="fees['+$(this).data('zoneid')+'][]" class="form-control" type="text" /></div></td>');
	});
	$('tr.delete_range td:last').after('<td class="center"><button class="btn btn-default">'+labelDelete+'</button</td>');
	
	bind_inputs();
	rebuildTabindex();
	displayRangeType();
	resizeWizard();
	return false;
}

function delete_new_range()
{
	if ($('#new_range_form_placeholder').children('td').length = 1)
		return false;
}

function checkAllFieldIsNumeric()
{
	$('#zones_table td input[type=text]').each( function () {
		if (!$.isNumeric($(this).val()) && $(this).val() != '')
			$(this).addClass('field_error');
	});
}

function rebuildTabindex()
{
	i = 1;
	$('#zones_table tr').each( function () 
	{	
		j = i;
		$(this).children('td').each( function () 
		{
			
			j = zones_nbr + j;
			if ($(this).index() >= 2 && $(this).find('input'))
				$(this).find('input').attr('tabindex', j);
		});
		i++;
	});
}

function repositionRange(current_index, new_index)
{
	$('tr.range_sup, tr.range_inf, tr.fees_all, tr.fees, tr.delete_range ').each(function () {
		$(this).find('td:eq('+current_index+')').each( function () {
			$(this).parent('tr').find('td:eq('+new_index+')').after(this.outerHTML);
			$(this).remove();
		});
	});
}

function checkRangeContinuity(reordering)
{
	return true;
	reordering = typeof reordering !== 'undefined' ? reordering : false;
	res = true;

	$('tr.range_sup td').not('.range_type, .range_sign').each( function () 
	{
		index = $(this).index();
		if (index > 2)
		{
			range_sup = parseFloat($('tr.range_sup td:eq('+index+')').children('input:text').val().trim());
			range_inf = parseFloat($('tr.range_inf td:eq('+index+')').children('input:text').val().trim());
			prev_index = index-1;
			prev_range_sup = parseFloat($('tr.range_sup td:eq('+prev_index+')').children('input:text').val().trim());
			prev_range_inf = parseFloat($('tr.range_inf td:eq('+prev_index+')').children('input:text').val().trim());
			if (range_inf < prev_range_inf || range_sup < prev_range_sup)
			{
				res = false;
				if (reordering)
				{
					new_position = getCorrectRangePosistion(range_inf, range_sup);
					if (new_position)
						repositionRange(index, new_position);
				}
			}	
		}
	});
	if (res)
		$('.ranges_not_follow').fadeOut();
	else
		$('.ranges_not_follow').fadeIn();
	resizeWizard();
}

function getCorrectRangePosistion(current_inf, current_sup)
{
	new_position = false;
	$('tr.range_sup td').not('.range_type, .range_sign').each( function () 
	{
		index = $(this).index();
		range_sup = parseFloat($('tr.range_sup td:eq('+index+')').children('input:text').val().trim());
		next_range_inf = 0
		if ($('tr.range_inf td:eq('+index+1+')').length)
			next_range_inf = parseFloat($('tr.range_inf td:eq('+index+1+')').children('input:text').val().trim());
		if (current_inf >= range_sup && current_sup < next_range_inf)
			new_position = index;
	});
	return new_position;
}

function checkAllZones(elt)
{

	if($(elt).is(':checked'))
	{
		$('.input_zone').attr('checked', 'checked');
		$('.fees input:text').each( function () {
			index = $(this).closest('td').index();
			enableGlobalFees(index);
			if ($('tr.fees_all td:eq('+index+')').hasClass('validated'))
			{
				$(this).removeAttr('disabled');
				$('.fees_all td:eq('+index+') input:text').removeAttr('disabled');
			}
		});
		$('.fees input:text, .fees_all input:text').removeAttr('disabled');
		$('.zone').children().removeClass('checker');
	}
	else
	{
		$('.input_zone').removeAttr('checked');
		$('.fees input:text, .fees_all input:text').attr('disabled', 'disabled');
		$('.zone').children().addClass('checker');
		$('.zone').children().children().removeClass('checked');
	}
}
function enableTextField(checkbox_obj)
{
	var text_id = "#input_"+$(checkbox_obj).attr('id');
	if($(checkbox_obj).is(':checked'))
		$(text_id).removeAttr('disabled');
	else
		$(text_id).attr('disabled', 'disabled');
}
$(document).ready(function(){
	$("input[name='shipping_handling']").on('change',function(){
		if($("#shipping_handling_on").is(":checked"))
			$("#shipping_handling_charge").show();
		else
			$("#shipping_handling_charge").hide();
	});
	$("#shipping_handling_charge").hide();
});