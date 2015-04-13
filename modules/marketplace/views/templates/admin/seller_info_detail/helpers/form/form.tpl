<script language="javascript" type="text/javascript">
	var iso = '{$iso|escape:'html':'UTF-8'}';
	var pathCSS = '{$smarty.const._THEME_CSS_DIR_|addslashes}';
	var ad = '{$ad|addslashes}';
	$(document).ready(function(){
		{block name="autoload_tinyMCE"}
			tinySetup({
				editor_selector :"about_business",
			});
		{/block}
	});
</script>
{block name="other_fieldsets"}
	<div id = "fieldset_0" class="panel">  
    <form id="{$table}_form" class="defaultForm {$name_controller} form-horizontal" action="{$current}&{if !empty($submit_action)}{$submit_action}{/if}&token={$token}" method="post" enctype="multipart/form-data" {if isset($style)}style="{$style}"{/if}>
	{if $form_id}
		<input type="hidden" name="{$identifier|escape:'html':'UTF-8'}" id="{$identifier|escape:'html':'UTF-8'}" value="{$form_id|escape:'html':'UTF-8'}" />
	{/if}
	<input type="hidden" name="set" id="set" value="{$set|escape:'html':'UTF-8'}" />
			{if {$set}==1}
				<div class="form-group">	
					<label class="col-lg-3 control-label required">{l s='Choose Customer' mod='marketplace'}</label>	
					<div class="col-lg-5">
						<select name="shop_customer">
							{foreach $customer_info as $cusinfo}
								<option value="{$cusinfo['id_customer']|escape:'html':'UTF-8'}">{$cusinfo['email']|escape:'html':'UTF-8'}</option>
							{/foreach}
						</select>
					</div>	
				</div>
			{else}
				<input type="hidden" value="{$market_place_seller_id|escape:'html':'UTF-8'}" name="market_place_seller_id" />
				<input type="hidden" value="{$shop_name|escape:'html':'UTF-8'}" name="pre_shop_name" />
			{/if}
			<div class="form-group">	
				<label class="col-lg-3 control-label required">{l s=' Shop Name' mod='marketplace'} </span></label>	
				<div class="col-lg-5">
				<input type="text" id="shop_name1" name="shop_name" {if {$set}==0}value="{$shop_name}"{/if}/>
				</div>	
			</div>	
			<div class="form-group">
				<label class="col-lg-3 control-label">{l s='Shop Description' mod='marketplace'}</label>
				<div class="col-lg-5">
					<textarea name="about_business" class="about_business" >{if {$set}==0}{$about_shop}{/if}</textarea>
				</div>
			</div>
			 
			<div id="person_name" class="form-group" >
				<label class="col-lg-3 control-label required">
					<span class="label-tooltip" "="" ?{}_$%:=" title="" data-html="true" data-toggle="tooltip" data-original-title=" Invalid characters 0-9!&lt;&gt;,;?=+()@#">{l s='Seller Name' mod='marketplace'}</span></label>
				<div class="col-lg-5">
				<input type="text" name="person_name" id="person_name1" {if {$set}==0}value="{$seller_name}"{/if}/></div>
			</div>
			
			<div class="form-group">
				<label class="col-lg-3 control-label required">{l s='Phone' mod='marketplace'}</label>
				<div class="col-lg-5">
				<input type="text" name="phone" id="phone1" maxlength="{$phone_digit}" {if {$set}==0}value="{$phone}"{/if}/>
				<label class="errors" id="phone_error"></label></div>
			</div> <!-- phone-->
				 
					
		<div class="form-group">
			<label class="col-lg-3 control-label">{l s='Fax' mod='marketplace'}</label>
			<div class="col-lg-5"><input class="form-control-static" type="text" name="fax"  id="fax1" maxlength="10" {if {$set}==0}value="{$fax}"{/if}/><label class="errors" id="fax_error"></label></div>
		</div> <!-- fax--> 
		
		<div class="form-group">	
			<label class="col-lg-3 control-label required">{l s='Business Email' mod='marketplace'}</label>
			<div class="col-lg-5"><input class="reg_sel_input form-control-static" type="text" name="business_email_id" id="business_email_id1"  {if {$set}==0}value="{$business_email}"{/if} style="height:25px;"/></div>
		</div>  <!-- siteurl--> 
					
		<div class="form-group">
			<label class="col-lg-3 control-label">{l s='Address' mod='marketplace'}</label>
			<div class="col-lg-5"><textarea name="address" rows="6" cols="35" >{if {$set}==0}{$address}{/if}</textarea></div>
		</div>
		
		<div id="facebook" class="form-group" >
				<label class="col-lg-3 control-label">{l s='Facebook Id' mod='marketplace'}</label>
				<div class="col-lg-5"><input class="reg_sel_input form-control-static"  type="text" name="fb_id" id="fb_id1" {if {$set}==0}value="{$facebook_id}"{/if}/></div>
			</div>
			
		<div id="twitter" class="form-group" >
				<label class="col-lg-3 control-label">{l s='Twitter Id' mod='marketplace'}</label>
				<div class="col-lg-5"><input class="reg_sel_input form-control-static"  type="text" name="tw_id" id="tw_id1" {if {$set}==0}value="{$twitter_id}"{/if}/></div>
		</div>	
		{if {$add} == 0}
		{if {$set}==0}
				<div class="form-group">  
					<label class="col-lg-3 control-label">{l s='Previous Shop Logo' mod='marketplace'}</label>
					<div class="prev_image col-lg-5" style="float:left;">
						<img src="{$shopimagepath|escape:'html':'UTF-8'}" width="100" height="100" />
					</div>
				</div>
			{/if}
		{/if}	
		<div class="form-group">  
			<div id="upload_logo" class="sell_row">
				{if {$set}==0}
					<label class="col-lg-3 control-label">{l s='Change Shop Logo' mod='marketplace'}</label>
				{else}
					<label class="col-lg-3 control-label">{l s='Upload Shop Logo' mod='marketplace'}</label>
				{/if}
					<div class="col-lg-5"><input class="reg_sel_input form-control-static" type="file"  name="upload_logo" /></div>
			</div> <!--upload_logo-->
		</div>	
		{if {$add} == 0}
		<div class="form-group">  
					<label class="col-lg-3 control-label">{l s='Previous Seller Logo' mod='marketplace'}</label>
					<div class="prev_image col-lg-5" style="float:left;">
						<img src="{$sellerimagepath|escape:'html':'UTF-8'}" width="100" height="100" />
					</div>
		</div>
		{/if}
		<div class="form-group">  
			<div id="upload_logo" class="sell_row">
				{if {$set}==0}
					<label class="col-lg-3 control-label">{l s='Change Seller Logo' mod='marketplace'}</label>
				{else}
					<label class="col-lg-3 control-label">{l s='Upload Seller Logo' mod='marketplace'}</label>
				{/if}
					<div class="col-lg-5"><input class="reg_sel_input form-control-static" type="file"  name="upload_seller_logo" /></div>
			</div> 
		</div>
		{if $set==0}
			{hook h="DisplayAddBadgeToSeller"}
		{/if}
		<div class="panel-footer">
			<a href="{$link->getAdminLink('AdminSellerInfoDetail')|escape:'html':'UTF-8'}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel' mod='marketplace'}</a>
			<button type="submit" name="submitAddmarketplace_seller_info" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='marketplace'}</button>
		</div>
		{if $set==1}
			{hook h="DisplayMpshoprequestfooterhook"}
		{else}
			{hook h="DisplayMpshopaddfooterhook"}
		{/if}
	<!--outer_container--> 	
</form>
</div>

{/block}
{block name=script}
	<script type="text/javascript">
		$(document).ready(function() {	
			var error = 0;
			var space =  /\s/g;
			$("#fax1").focusin(function() {
				$("#fax_error").html('');
				error = 0;
			});
			$("#fax1").focusout(function() {
				var numeric = /^[0-9]+$/;
				var fax = $("#fax1").val();
				if(fax=='') {
					$("#fax_error").html('');
					error = 0;
				} else {
					if(space.test(fax)) {
						$("#fax_error").css("display","block");
						$("#fax_error").html('Space are not allowed.');
						error = 1;
					} else {
						if(fax.match(numeric)) {
							$("#fax_error").html('');
							error = 0;
						}
						else {
							$("#fax_error").css("display","block");
							$("#fax_error").html('Must be in integer.');
							error = 1;
						}
					}
				}
			});
		
			$("#phone1").focusin(function() {
				$("#phone_error").html('');
				error = 0;
			});
			
			$("#phone1").focusout(function() {
				var numeric = /^[0-9]+$/;
				var phone = $("#phone1").val();
				if(phone=='') {
					$("#phone_error").css("display","block");
					$("#phone_error").html('Required field');
					error = 1;
				} else {
					if(space.test(phone)) {
						$("#phone_error").css("display","block");
						$("#phone_error").html('Space are not allowed.');
						error = 1;
					} else {
						if(phone.match(numeric)) {
							$("#phone_error").html('');
							error = 0;
						} else {
							$("#phone_error").css("display","block");
							$("#phone_error").html('Must be in integer.');
							error = 1;
						}
					}
				}
			});
		
		
		
		$("#person_name1").focusin(function() {
			$("#person_name_error").css("display","none");
			error = 0;
		});
		
		$("#person_name1").focusout(function() {
			if($("#person_name1").val()=='') {
				$("#person_name_error").css("display","block");
				error = 1;
			} else {
				$("#person_name_error").css("display","none");
				error = 0;
			}
		});
		$("#shop_name1").focusin(function() {
			$("#shop_name_error").css("display","none");
			error = 0;
		});
		$("#shop_name1").focusout(function() {
			if($("#shop_name1").val()=='') {
				$("#shop_name_error").css("display","block");
				error = 1;
			} else {
				$("#shop_name_error").css("display","none");
				error = 0;
			}
		});
		
		$("#business_email_id1").change(function() {
			var email= $("#business_email_id1").val();
			var mail =/^[a-zA-Z]*$/;
			var reg = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
			if(!reg.test(email)){         
				$("#business_email_id_error").css("display","block");
				error = 1;
			}else{   
				$("#business_email_id_error").css("display","none");
				error = 0;
			}
			
		});
		
		
		{*$("#marketplace_seller_info_form").submit(function() {
			if($("#phone1").val() == "" || $("#address1").val() == "" || $("#shop_name1").val() == "" || $("#person_name1").val() == "" ) {
				$("#error").css("display","block");
				return false;
			} else if(error == 1) {
				return false;
			} else {
				$("#error").css("display","none");
				return true;
			}
		});*}
	});		 
</script>
{/block}