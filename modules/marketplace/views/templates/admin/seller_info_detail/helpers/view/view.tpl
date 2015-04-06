<style type="text/css">
.row{
	margin-bottom: 20px;
}
.row-info-right1 textarea{
	background-color: white;
}
.row-info-left {
	color: {$req_text_color|escape:'html':'UTF-8'};
	float: left;
	font-family: myraid pro;
	font-size: {$req_text_size|escape:'html':'UTF-8'}px;
	font-weight: bold;
	height: 32px;
	width: 24%;
	font-family:{$req_text_font_family|escape:'html':'UTF-8'};
}
.inner_form_div {
	border: 1px solid {$req_border_color|escape:'html':'UTF-8'};
	float: left;
	margin-top: 10px;
	padding: 2.5%;
	width: 95%;
}
.page-heading h1 {
	margin-bottom: 4px;
	padding-bottom: 6px;
	padding-top: 4px;
	font-size:{$req_heading_size|escape:'html':'UTF-8'}px;
	font-family:{$req_heading_font_family|escape:'html':'UTF-8'};
	color:{$req_heading_color|escape:'html':'UTF-8'};
}

.wk_shop_desc{
	border: 1px solid #D7D7D7;
	border-radius: 3px;
	background-color: #EEEEEE;
	padding:5px 10px !important;
	min-height: 100px;
}
</style>

<div class="leadin">{block name="leadin"}{/block}</div>
{block name="override_tpl"}
   	<div id="fieldset_0" class="panel">
    <h3>{l s='View Seller' mod='marketplace'}</h3>
	<form class="form-horizontal">
		<div class="row">	
			<label class="col-lg-3 control-label required">{l s='Shop Name' mod='marketplace'}</label>	
			<div class="col-lg-5"><input type="text" id="shop_name1" name="shop_name" value="{$market_place_seller_info['shop_name']|escape:'html':'UTF-8'}" disabled/></div>	
		</div>	
		<div class="row">
			<label class="col-lg-3 control-label">{l s='Shop Description' mod='marketplace'}</label>
			<div class="col-lg-5 wk_shop_desc">
				{$about_shop|escape:'intval'}
			</div>
		</div>
		 
		<div id="person_name" class="row" >
			<label class="col-lg-3 control-label required">{l s=' Seller Name' mod='marketplace'}</label>
			<div class="col-lg-5"><input class="reg_sel_input"  type="text" name="person_name" id="person_name1" value="{$seller_name|escape:'html':'UTF-8'}" disabled/></div>
		</div> 
		
		<div class="row">
			<label class="col-lg-3 control-label required">{l s='Phone' mod='marketplace'}</label>
			<div class="col-lg-5"><input class="reg_sel_input" type="text" name="phone" id="phone1" maxlength="10" value="{$phone|escape:'html':'UTF-8'}" disabled/></div>
		</div>	 
				
		<div class="row">
			<label class="col-lg-3 control-label required">{l s='Fax' mod='marketplace'}</label>
			<div class="col-lg-5"><input class="reg_sel_input" type="text" name="fax"  id="fax1" maxlength="10" value="{$fax|escape:'html':'UTF-8'}" disabled/></div>
		</div>
		
		<div class="row">	
			<label class="col-lg-3 control-label">{l s='Business Email' mod='marketplace'}</label>
			<div class="col-lg-5"><input class="reg_sel_input" type="text" name="business_email_id" id="business_email_id1"  value="{$business_email|escape:'html':'UTF-8'}" style="height:25px;" disabled/></div>
		</div>
					
		<div class="row">	
			<label class="col-lg-3 control-label">{l s='Address' mod='marketplace'}</label>
			<div class="col-lg-5"><input class="reg_sel_input" type="text" name="address" id="address" maxlength="10" value="{$address|escape:'html':'UTF-8'}" disabled/></div>
		</div>
		
		<div id="facebook" class="row" >
				<label class="col-lg-3 control-label">{l s='Facebook Id' mod='marketplace'}</label>
				<div class="col-lg-5"><input class="reg_sel_input"  type="text" name="fb_id" id="fb_id1" value="{$facebook_id|escape:'html':'UTF-8'}" disabled/></div>
			</div>
			
		<div id="twitter" class="row" >
				<label class="col-lg-3 control-label">{l s='Twitter Id' mod='marketplace'}</label>
				<div class="col-lg-5"><input class="reg_sel_input"  type="text" name="tw_id" id="tw_id1" value="{$twitter_id|escape:'html':'UTF-8'}" disabled/></div>
		</div>	
		
		<div class="row">  
					<label class="col-lg-3 control-label">{l s='Shop Logo' mod='marketplace'}</label>
					<div class="prev_image col-lg-5" style="float:left;">
						<img src="../modules/marketplace/img/shop_img/{$market_place_seller_id|escape:'html':'UTF-8'}-{$market_place_seller_info['shop_name']|escape:'html':'UTF-8'}.jpg" width="100" height="100"/>
					</div>
		</div>
		
		<div class="row">  
			<label class="col-lg-3 control-label">{l s='Seller Logo' mod='marketplace'}</label>
			<div class="prev_image col-lg-6" style="float:left;">
				<img src="../modules/marketplace/img/seller_img/{$market_place_seller_id|escape:'html':'UTF-8'}.jpg" width="100" height="100" />
			</div>
		</div>
		{if $payment_detail!=0}
			<div class="row">
				<label class="col-lg-3 control-label"><B>{l s='Payment Detail:' mod='marketplace'}</b></label>
			</div>
			<div class="row">  
				<label class="col-lg-3 control-label">{l s='Payment Mode' mod='marketplace'}</label>
				<div class="col-lg-5" style="float:left;">
					<input class="reg_sel_input"  type="text" name="" id="" value="{$payment_detail['payment_mode']|escape:'html':'UTF-8'}" disabled/>
				</div>
			</div>
			<div class="row">  
				<label class="col-lg-3 control-label">{l s='Payment Detail' mod='marketplace'}</label>
				<div class="col-lg-5" style="float:left;">
					<input class="reg_sel_input"  type="text" name="" id="" value="{$payment_detail['payment_detail']|escape:'html':'UTF-8'}" disabled/>
					
				</div>
			</div>
		{/if}
		{hook h="DisplaySellerBadges"}
	</form>
</div>
{/block}
<script type="text/javascript">
	$('.fancybox').fancybox();
</script>