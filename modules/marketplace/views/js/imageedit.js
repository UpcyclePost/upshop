$(document).ready(function()
{
		$(".edit_seq").live('click',function(e) {
		e.preventDefault();
		var is_alt = $(this).attr("alt");
		var id_product = $(this).attr("product-id");
		$(".edit_seq").attr("alt","1");
		$(".content_seq").hide();
		$(".content_seq").html("");
		$(".img_detail").attr('src',src_more);
		if(is_alt==1) {
			$("#edit_seq"+id_product).attr('src',src_less);
			$(this).attr("alt","0");
			$("#content"+id_product).show();
			$.ajax({
			type: 	'POST',
			url:	ajax_urlpath,
			async: 	true,
			data: 	'id_product=' + id_product +'&id_lang='+id_lang,
			cache: 	false,
			success: function(data)
			{
				if(data!=0) {
					$('#content'+id_product).html(data);
				}
				else {
					alert(space_error);

				}
			}
		});
			
		} else {
			$(this).attr("alt","1");
			$("#content"+id_product).hide();
			$("#content"+id_product).html("");
		}
	});
	
	$('.delete_pro_image').live('click',function(e) {
		e.preventDefault();
		var id_image = $(this).attr('alt');
		var is_cover = $(this).attr('is_cover');
		var id_pro = $(this).attr('id_pro');
		var r=confirm(confirm_delete_msg);
		
		if(r==true) {
			$.ajax({
				type: 'POST',
				url:	ajax_urlpath,
				async: true,
				data: 'id_image=' + id_image + '&is_cover=' + is_cover + '&id_pro=' + id_pro+'&delete=1',
				cache: false,
				success: function(data)
				{
					if(data==0) {
						alert(error_msg);

					} else {
						alert(delete_msg);
						$(".unactiveimageinforow"+id_image).remove();
						location.reload();
					}
				}
			});
		
		}
	});
	$('.delete_unactive_pro_image').live('click',function(e) {
		e.preventDefault();
		var id_image = $(this).attr('alt');
		var img_name = $(this).attr('img_name');
		var r=confirm(confirm_delete_msg);
		
		if(r==true) {
			$.ajax({
				type: 'POST',
				url:  ajax_urlpath,
				async: true,
				data: 'id_image=' + id_image + '&img_name=' + img_name+'&unactive=1',
				cache: false,
				success: function(data)
				{
					if(data==0) {
						alert(error_msg);

					} else {
						alert(delete_msg);
						$(".unactiveimageinforow"+id_image).remove();
					}
				}
			});
		}
		
	});

	$('.covered').live('click',function(e) 
	{
		e.preventDefault();
		var id_image = $(this).attr('alt');
		var is_cover = $(this).attr('is_cover');
		var id_pro = $(this).attr('id_pro');
		if(is_cover==0) 
		{
			$.ajax({
				type: 'POST',
				url:  ajax_urlpath,
				async: true,
				data: 'id_image='+ id_image+'&is_cover='+is_cover+'&id_pro='+id_pro+'&changecover=1',
				cache: false,
				success: function(data)
				{
					if(data==0) {
						alert(error_msg);

					} else {
						if(is_cover==0) {
							$('.covered').attr('src',src_forbidden);
							$('.covered').attr('is_cover','0')
							$('#changecoverimage'+id_image).attr('src',src_enabled)
							$('#changecoverimage'+id_image).attr('is_cover','1');
						} else {
							
						}
					}
				}
			});
		}

	});
});