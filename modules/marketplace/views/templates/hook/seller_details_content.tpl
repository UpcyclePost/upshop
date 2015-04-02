<link rel="stylesheet" href="{$modules_dir|addslashes}marketplace/views/css/productsellerdetails.css">
<section class="page-product-box">
	<h3 class="idTabHrefShort page-product-heading">{l s='Seller Detail' mod='marketplace'}</h3>

	<div class="partnerdetails">
		<div class="sellerinfo">
			<div class="wk_row">
				<label class="wk-person-icon">{l s='Seller Name' mod='marketplace'} - </label>
				<span>{$mkt_seller_info['seller_name']|escape:'html':'UTF-8'}</span>
			</div>
			<div class="wk_row">
				<label class="wk-shop-icon">{l s='Shop Name' mod='marketplace'} - </label>
				<span>{$mkt_seller_info['shop_name']|escape:'html':'UTF-8'}</span>	
			</div>
			<div class="wk_row">
				<label class="wk-mail-icon">{l s='Seller email' mod='marketplace'} - </label>
				<span>{$seller_email|escape:'html':'UTF-8'}</span>
			</div>
			<div class="wk_row">
				<label class="wk-phone-icon">{l s='Phone' mod='marketplace'} - </label>
				<span>{$mkt_seller_info['phone']|escape:'html':'UTF-8'}</span>
			</div>
			<div class="wk_row">
				<label class="wk-share-icon">{l s='Social Profile' mod='marketplace'} - </label>
				<span class="wk-social-icon">
					{if $facebook_id}
						<a class="wk-facebook-button" target="_blank" title="Facebook" href="http://www.facebook.com/{$facebook_id|addslashes}"></a>
					{/if}
					{if $twitter_id}
					<a class="wk-twitter-button" target="_blank" title="Twitter" href="http://www.twitter.com/{$twitter_id|addslashes}"></a>
					{/if}
				</span>
			</div>
			{hook h='displayMpSellerDetailTabLeft'}
		</div>	
		<div class="sellerlink">
			<ul>
				<li><a id="profileconnect" title="Visit Profile" target="_blank" href="{$link_profile|addslashes}">{l s='View Profile' mod='marketplace'}</a></li> 
				<li>
					<a id="siteconnect" title="Visit Collection" target="_blank" href="{$link_collection|addslashes}">{l s='View Collection' mod='marketplace'}</a>
				</li>
				<li>
					<a id="storeconnect" title="Visit Store" target="_blank" href="{$link_store|addslashes}">{l s='View Store' mod='marketplace'}</a>
				</li>
				<!-- <li>
					<a href="#wk_question_form" class="open-question-form" title="Ask Que">{l s='Ask Question' mod='marketplace'}</a>
				</li> -->
				{hook h='DisplayMpSellerDetailTabRight'}
			</ul>	
		</div>	
	</div>
	{hook h='DisplayMpSellerDetailTabBotttom'}

		
<div id="wk_question_form" style="display: none;">
	<div class="wk_ques_head">
		<h3>{l s='Ask Question' mod='marketplace'}</h3>
	</div>
	<form id="ask-form" method="post" action="#">
		<span class="ques_form_error">{l s='Fill all the fields' mod='marketplace'}</span>
		<div class="form-group">
			<label class="label-control required">{l s='Email' mod='marketplace'}</label>
			<input type="text" name="email" id="email_address" class="form-control"/>
		</div>
		<div class="form-group">
			<label class="label-control required">{l s='Subject' mod='marketplace'}</label>
			<input type="text" name="subject" class="form-control" id="subject"/>
		</div>
		<div class="form-group">
			<label class="label-control required">{l s='Question ' mod='marketplace'}</label>
			<textarea name="question" class="form-control" id="question"></textarea>
		</div>
		<input type="hidden" id="product_name" value="{$product_name|escape:'html':'UTF-8'}"/>
		<input type="hidden" id="seller_id" value="{$seller_id|escape:'html':'UTF-8'}"/>
		<input type="hidden" id="seller_email" value="{$seller_email|escape:'html':'UTF-8'}"/>
		<input type="hidden" id="cust_id" value="{$cust_id|escape:'html':'UTF-8'}"/>
		<input type="hidden" id="product_id" value="{$id_product|escape:'html':'UTF-8'}"/>
		<input type="hidden" value="{$askprocess|addslashes}" id="controller_path"/>
		<div class="wk_ques_form_footer">
			<p class="fl required"><sup>*</sup> {l s='Required fields' mod='marketplace'}</p>
			<p class="fr">
				<button type="submit" class="btn button button-small" id="askbtn">
					<span>{l s='Send' mod='marketplace'}</span>
				</button>&nbsp;
				{l s='or' mod='marketplace'}&nbsp;
				<a class="closefb" href="#">
					{l s='Cancel' mod='marketplace'}
				</a>
			</p>
		</div>
	</form>
</div>

<script type="text/javascript">
$(function()
{
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

	$('#askbtn').click(function(e)
	{
		e.preventDefault();
		var controller_path = $('#controller_path').val();
		var email_address = $('#email_address').val();
		var subject = $('#subject').val();
		var question = $('#question').val();
		var product_name = $('#product_name').val();
		var seller_id = $('#seller_id').val();
		var seller_email = $('#seller_email').val();
		var cust_id = $('#cust_id').val();
		var product_id = $('#product_id').val();
		var success_msg = '{l s='Mail has been successfully sent to this seller.' ja=1 mod='marketplace'}';
		var error_msg = '{l s='There is some error.' ja=1 mod='marketplace'}';
		$.ajax({
			url: controller_path,
			data:{
				email_address:email_address,
				subject:subject,
				question:question,
				product_name:product_name,
				seller_id:seller_id,
				seller_email:seller_email,
				cust_id:cust_id,
				product_id:product_id
			},
			type: 	'POST',
			async: 	true,
			cache: 	false,
			success:function(result)
			{
				if(result == 1)
					alert(success_msg);
				else
				{
					alert (error_msg);
					return false;
				}
			}
		});
	});
});
</script>
</section>