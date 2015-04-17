<style>
#assign_shipping_form{
	float:left;
	width:100%;
}
h5{
	font-style:italic;
	color:#969614;
	margin-bottom:10px;
}
#uniform-shipping_method{
	display:inline-block !important;
}
.assign_shipping{
margin-bottom: 5px;
}
</style>
<a class="btn btn-default button button-small assign_shipping" style="float:right" href="#assign_shipping_form">
<span>{l s='Assign Shipping' mod='mpshipping'}</span>
</a>
<div id="assign_shipping_form" style="display:none;">
	<h5 >{l s='Note: The shipping methods selected by you will get assigned to all the products.' mod='mpshipping'}</h5>
	<form method="post" action="{$ajax_link}" id="shipping_form">
	{foreach $shipping_method as $shipping_data}
	<input type="hidden" value="{$mp_id_seller}" name="mp_id_seller">
	<div style="width:100%;margin-bottom:5px;">
		<input type="checkbox" id="shipping_method" name="shipping_method[]" value="{$shipping_data['id']}"><span>{$shipping_data['mp_shipping_name']}</span>
	</div>
	{/foreach}
	<a class="btn btn-default button button-small" id="assign" style="margin-top:10px;">
		<span>{l s='Submit' mod='mpshipping'}</span>
	</a>
	</form>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.assign_shipping').fancybox();
	$('#assign').click(function(e){
	e.preventDefault();
	var form = $('#shipping_form');
	var check_msg = '{l s='Please select any shipping method first.' js=1 mod='mpshipping'}';
	var success_msg = '{l s='Shipping assigned successfully to all the products.' js=1 mod='mpshipping'}';
	var error_msg = '{l s='Some error occurs while assigning shipping method, try again after some time.' js=1 mod='mpshipping'}';
        if($(':checkbox:checked').length > 0){
        	$.ajax({
				type: 'POST',
				url: form.attr('action'),
				async: true,
				cache: false,
				data:form.serialize(),
				success: function(data1)
					{
						if(data1 == 1){
							parent.$.fancybox.close();
							alert(success_msg);
						}else{
							parent.$.fancybox.close();
							alert(error_msg);
						}
					}
				});
        	}else{
        		alert(check_msg);
        	}
		});
    });
</script>