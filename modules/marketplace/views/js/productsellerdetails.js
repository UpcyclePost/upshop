$(function(){
	$('.open-question-form').fancybox({
		width: 550,
	    height: 340,
	    autoSize : false,
	    maxWidth : '100%',
		'hideOnContentClick': false
	});
	$(document).on('click', '.closefb', function(e){
		e.preventDefault();
		$.fancybox.close();
	});

	$('#askbtn').click(function(e){
		e.preventDefault();
		var controller_path = $('#controller_path').val();
		var mp_guest_name = $('#mp_guest_name').val();
		var mp_guest_email = $('#mp_guest_email').val();
		var query_subject = $('#query_subject').val();
		var query_desc = $('#query_desc').val();
		var seller_id = $('#seller_id').val();
		var seller_email = $('#seller_email').val();
		var id_customer = $('#id_customer').val();
		var id_product = $('#product_id').val();

		//validate functions are defined in prestashop js/validate.js file
		if (!validate_isName(mp_guest_name))
		{
			alert(inv_name);
			return false;
		}
		else if (!validate_isEmail(mp_guest_email))
		{
			alert(inv_email);
			return false;
		}
		else if (!validate_isMessage(query_subject) || !validate_isMessage(query_desc))
		{
			alert(inv_subject);
			return false;
		}
		else
		{
			$.ajax({
				url: controller_path,
				type: 	'POST',
				dataType: 'json',
				data:{
					mp_guest_name:mp_guest_name,
					mp_guest_email:mp_guest_email,
					query_subject:query_subject,
					query_desc:query_desc,
					seller_id:seller_id,
					seller_email:seller_email,
					id_customer:id_customer,
					id_product:id_product
				},
				success:function(result)
				{
					if (result.status == 'ok')
					{
						alert(result.msg);
						location.reload(true);
					}
					else
						alert (result.msg);
				}
			});
		}
	});
});