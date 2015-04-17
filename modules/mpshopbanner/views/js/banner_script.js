function checkBannerForm() {	
	var banner_name = $("#banner_name").val();
	
	if(banner_name=="") {
		$(".error").html("Banner Name is mandatory!!!");
		$(".error").css('display','block');
		return false;
	}	
	return true;
}

$(document).ready(function () {      
		
		$('.mp_delete_banner').live('click', function (event) {
			var mp_banner_id = $(this).attr('mp_banner_id');
			var banner_front_link = $('#banner_front_link').val();
			var data1 = {	
							ajax: 1,							
							mp_banner_id:mp_banner_id ,
							fun:'delete',
						}
			var r=confirm("Are you sure ,you want to delete this Banner ?")
			if (r==true)
			  {
				$.ajax(banner_front_link, {
					type: 'POST',
					'data': data1,
					dataType: 'json',
					success: function(data, status, xhr)
					{
						if(data==1)
							{
								$('#mp_banner_'+mp_banner_id).remove();								
							}
					},
					error: function(xhr, status, error)
					{
						
					}
				});
			}
        });
	});
	
	
