$(document).ready(function()
{
	//Add product and update product form validation
	$('#SubmitProduct,#SubmitCreate').click(function()
	{
		var product_name = $('#product_name').val().trim();
		var short_description = tinymce.get('short_description').getContent();
		var product_description = tinymce.get('product_description').getContent();
		var product_price = $('#product_price').val().trim();
		var product_quantity = $('#product_quantity').val().trim();
		var checkbox_length = $('.product_category:checked').length;
		var hasUploadImageLength = $('#testImg').attr('src').length;
		var seo_title = $('#meta_title').val().trim();
		var seo_description = $('#meta_desc').val().trim();	
		
		// Other image
		var hasOtherImage = false;
		if (typeof $('#showimg2').attr('src') != 'undefined')
			hasOtherImage = true;
		 	
		//product_image <= 1 
		var hasUploadImage;
		if (hasUploadImageLength <= 1)
			{
			hasUploadImage = false;
			}
		else
			{
			hasUploadImage = true;
			}

		//alert ('hasUploadImage : '+ hasUploadImage);		
		//alert ('hasOtherImage : '+ hasOtherImage);
		//alert ('alreadyHasImage : '+ alreadyHasImage);
		
		var special_char = /^[^<>;=#{}]*$/;
		if(product_name == '')
		{
			alert(req_prod_name);
			$('#product_name').focus();
			return false;
		}
		else if(!isNaN(product_name) || !special_char.test(product_name))
		{
			alert(char_prod_name + ' : "' + special_char + '"');
			$('#product_name').focus();
			return false;
		}
		else if(product_name.length > 120)
		{
			alert(char_prod_name_length + ' : Currently ' + product_name.length + ' characters');
			$('#product_name').focus();
			return false;
		}
		else if(short_description.length > 600)
		{
			alert(char_prod_short_desc_length + ' : Currently ' + short_description.length + ' characters');
			$('#short_description').focus();
			return false;
		}
		else if(product_description.length > 1500)
		{
			alert(char_prod_desc_length + ' : Currently ' + product_description.length + ' characters');
			$('#product_description').focus();
			return false;
		}

		else if(product_price == '')
		{
			alert(req_price);
			$('#product_price').focus();
			return false;
		}
		else if(isNaN(product_price))
		{
			alert(num_price);
			$('#product_price').focus();
			return false;
		}
		else if(product_price < 5)
		{
			alert(base_price);
			$('#product_price').focus();
			return false;
		}
		else if(product_quantity == '')
		{
			alert(req_qty);
			$('#product_quantity').focus();
			return false;
		}
		else if(isNaN(product_quantity))
		{
			alert(num_qty);
			$('#product_quantity').focus();
			return false;
		}
		else if(checkbox_length == 0)
		{
			alert(req_catg);
			$('#check').focus();
			return false;
		}
		else if(seo_title.length > 120)
		{
			alert(char_seo_title_length + ' : Currently ' + seo_title.length + ' characters');
			$('#meta_title').focus();
			return false;
		}
		else if(seo_description.length > 120)
		{
			alert(char_seo_description_length + ' : Currently ' + seo_description.length + ' characters');
			$('#meta_description').focus();
			return false;
		}
		else if (!(hasUploadImage || hasOtherImage || alreadyHasImage ))
			{
				alert(req_img);
				$('#uploader').focus();
				return false;
			}
	});

	function disablecontinue(){
		$('#update_profile').attr('disabled','disabled');
		$('#loadin_msg').show();	
	};

	function enablecontinue(){
		$('#update_profile').attr('enabled','enabled');
		$('#loadin_msg').hide();	
	};

	//Seller registration form validation
	$('#seller_save').click(function(e)
	{
		e.stopPropagation();
		disablecontinue();
		var shop_name = $('#shop_name1').val().trim();
		var person_name = $('#person_name1').val().trim();
		var phone = $('#phone1').val().trim();
		//var fax = $('#fax1').val().trim();
		var business_email = $('#business_email_id1').val().trim();
		//var fb_id = $('#fb_id1').val().trim();
		//var tw_id = $('#tw_id1').val().trim();
		var reg = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
		var special_char = /^[^<>;=#{}]*$/;
		if(shop_name == '')
		{
			alert(req_shop_name);
			$('#shop_name1').focus();
			enablecontinue();
			return false;
		}
		else if(!isNaN(shop_name) || !special_char.test(shop_name))
		{
			alert(inv_shop_name);
			$('#shop_name1').focus();
			enablecontinue();
			return false;
		}
		else if(person_name == '')
		{
			alert(req_seller_name);
			$('#person_name1').focus();
			enablecontinue();
			return false;
		}
		else if(!isNaN(person_name) || !special_char.test(person_name))
		{
			alert(inv_seller_name);
			$('#person_name1').focus();
			enablecontinue();
			return false;
		}
		else if(phone == '')
		{
			alert(req_phone);
			$('#phone1').focus();
			enablecontinue();
			return false;
		}
		else if(isNaN(phone))
		{
			alert(inv_phone);
			$('#phone1').focus();
			enablecontinue();
			return false;
		}
		else if(phone.length != phone_digit)
		{
			alert(inv_phone);
			$('#update_phone').focus();
			enablecontinue();
			return false;
		}
		else if(business_email == '')
		{
			alert(req_email);
			$('#business_email_id1').focus();
			enablecontinue();
			return false;
		}
		else if(!reg.test(business_email))
		{
			alert(inv_email);
			$('#business_email_id1').focus();
			enablecontinue();
			return false;
		}
		else 
		{
			$('#createaccountform').submit();
		}
	});

	//Seller Profile form validation
	$('#update_profile').click(function()
	{
		var update_seller_name = $('#update_seller_name').val().trim();
		var update_shop_name = $('#update_shop_name').val().trim();
		var update_business_email = $('#update_business_email').val().trim();
		var update_phone = $('#update_phone').val().trim();
		//var update_fax = $('#update_fax').val().trim();
		//var update_facbook_id = $('#update_facbook_id').val().trim();
		//var update_twitter_id = $('#update_twitter_id').val().trim();
		var reg = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
		var special_char = /^[^<>;=#{}]*$/;
		if(update_seller_name == '')
		{
			alert(req_seller_name);
			$('#update_seller_name').focus();
			return false;
		}
		else if(!isNaN(update_seller_name) || !special_char.test(update_seller_name))
		{
			alert(inv_seller_name);
			$('#update_seller_name').focus();
			return false;
		}
		else if(update_shop_name == '')
		{
			alert(req_shop_name);
			$('#update_shop_name').focus();
			return false;
		}
		else if(!isNaN(update_shop_name) || !special_char.test(update_shop_name))
		{
			alert(inv_shop_name);
			$('#update_seller_name').focus();
			return false;
		}
		else if(update_business_email == '')
		{
			alert(req_email);
			$('#update_business_email').focus();
			return false;
		}
		else if(!reg.test(update_business_email))
		{
			alert(inv_email);
			$('#update_business_email').focus();
			return false;
		}
		else if(update_phone == '')
		{
			alert(req_phone);
			$('#update_phone').focus();
			return false;
		}
		else if(isNaN(update_phone))
		{
			alert(inv_phone);
			$('#update_phone').focus();
			return false;
		}
		else if(update_phone.length > phone_digit)
		{
			alert(inv_phone);
			$('#update_phone').focus();
			return false;
		}
	});

	$('#submit_payment_details').click(function(){
		var payment_mode = $('#payment_mode').val();
		if(payment_mode == "")
		{
			alert(req_payment_mode)
			$('#payment_mode').focus();
			return false;
		}
	});

	$(document).on('click', '[href="#information"]', function(){
		$('#update_product_submit_div').show();
	});
	$(document).on('click', '[href="#information"]', function(){
		$('#SubmitProduct_div_id').show();
	});
});