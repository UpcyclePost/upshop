<style type="text/css">
.page-title{
	background-color: {$title_bg_color|escape:'html':'UTF-8'} !important;
}
.page-title span{
	color: {$title_text_color|escape:'html':'UTF-8'} !important;
}
.fcevrt{}
</style>

{capture name=path}{l s='Seller Registration' mod='marketplace'}{/capture}
{if isset($img_size_error)}
	<div class="alert alert-danger">
		{l s='Invalid image size. Minimum image size must be 200X200.' mod='marketplace'}
	</div>
{/if}

{if isset($mp_error)}
	{if $mp_error == 1}
		<div class="alert alert-danger">{l s='Shop name is required field.' mod='marketplace'}</div>
	{else if $mp_error == 2}
		<div class="alert alert-danger">{l s='Seller name is required field.' mod='marketplace'}</div>
	{else if $mp_error == 3}
		<div class="alert alert-danger">{l s='Phone is required field.' mod='marketplace'}</div>
	{else if $mp_error == 4}
		<div class="alert alert-danger">{l s='Email ID is requird field.' mod='marketplace'}</div>
	{else if $mp_error == 5}
		<div class="alert alert-danger">{l s='Invalid Email ID.' mod='marketplace'}</div>
	{/if}
{/if}

{if isset($is_seller)}
	{if $login == 0}
		<div class="alert alert-info">
			<p>{l s='You have to login to make a seller request.' mod='marketplace'}</p>
		</div>
	{else}
		{if $is_seller == 0}
			<div class="alert alert-info">
				<p>{l s='Your request has been sent to admin. Please wait till the approval from admin' mod='marketplace'}</p>
			</div>
		{else}
			<div class="alert alert-info">
				<p>{l s='You have already made a Seller Request and request has been approved by admin. ' mod='marketplace'}<a href="{$link->getModuleLink('marketplace','addproduct')|escape:'html':'UTF-8'}">{l s='Add Product' mod='marketplace'}</a></p>
			</div>
		{/if}
	{/if}
{else}
<div class="seller_registration_form">
	<div class="container">
		<div class="page-title">
			<span>{l s='Seller Request' mod='marketplace'}</span>
		</div>
		<div class="wk_right_col">
		<p><sup>*</sup> {l s='Required field' mod='marketplace'}</p>
		<form action="{$link->getModuleLink('marketplace', 'registrationprocess')|escape:'htmlall':'UTF-8'}" method="post" id="createaccountform" class="std contact-form-box" enctype="multipart/form-data">
			<fieldset>
				<div class="form-group">	
					<label for="shop_name1">{l s='Shop Name' mod='marketplace'}<sup>*</sup></label>	
					<input class="is_required validate form-control" type="text" id="shop_name1" name="shop_name" />
				</div>

				<div class="form-group">
					<label for="about_business">{l s='Shop Description' mod='marketplace'}</label>
					<textarea name="about_business"  class="about_business wk_tinymce form-control"></textarea>
				</div>
					 
				<div class="form-group">  
					<label for="upload_logo">{l s='Shop Logo' mod='marketplace'}</label>
					<input class="form-control" id="upload_logo1" type="file"  name="upload_logo" />
					<div class="info_description">{l s='Image minimum size must be 200 x 200px' mod='marketplace'}</div>
				</div>
		
				<div id="person_name" class="required form-group" >
					<label for="person_name">{l s='Seller Name' mod='marketplace'}<sup>*</sup></label>
					<input class="form-control"  type="text" name="person_name" id="person_name1" />
				</div>
				
				<div class="required form-group">
					<label for="phone1">{l s='Phone' mod='marketplace'}<sup>*</sup></label>
					<input class="form-control" type="text" name="phone" id="phone1" maxlength="{$phone_digit|escape:'html':'UTF-8'}" />
				</div>		 
					
				<div class="form-group">
					<label for="phone1">{l s='Fax' mod='marketplace'}</label>
					<input class="form-control" type="text" name="fax" id="fax1" maxlength="10" />
					<label class="errors" id="fax_error"></label>
				</div>
				
				<div class="form-group">	
					<label for="business_email_id1">{l s='Business Email' mod='marketplace'}<sup>*</sup></label>
					<input class="form-control" type="text" name="business_email_id" id="business_email_id1" />
				</div>
							
				<div class="form-group">	
					<label for="address">{l s='Address' mod='marketplace'}</label>
					<textarea name="address" class = "form-control"></textarea>
				</div>
				
				<div id="facebook" class="form-group" >
					<label for="fb_id1">{l s='Facebook Id' mod='marketplace'}</label>
					<input class="reg_sel_input form-control"  type="text" name="fb_id" id="fb_id1" />
				</div>
					
				<div id="twitter" class="form-group" >
					<label for="tw_id1">{l s='Twitter Id' mod='marketplace'}</label>
					<input class="reg_sel_input form-control"  type="text" name="tw_id" id="tw_id1" />
				</div>
				{hook h="DisplayMpshoprequestfooterhook"}
			</fieldset>
			<div class="form-group" style="text-align:center;">
				<button type="submit" id="seller_save" class="btn btn-default button button-medium">
					<span>{l s='Register' mod='marketplace'}<i class="icon-chevron-right right"></i></span>
				</button>
			</div>
		</form>
		</div>
	</div>
</div>
{/if}

<script type="text/javascript">
var req_seller_name = '{l s='Seller name is required.' js=1 mod='marketplace'}';
var inv_seller_name = '{l s='Invalid Seller name.' js=1 mod='marketplace'}';
var req_shop_name = '{l s='Shop name is required.' js=1 mod='marketplace'}';
var inv_shop_name = '{l s='Invalid Shop name.' js=1 mod='marketplace'}';
var req_email = '{l s='Email Id is required.' js=1 mod='marketplace'}';
var inv_email = '{l s='Invalid email address' js=1 mod='marketplace'}';
var req_phone = '{l s='Phone is required.' js=1 mod='marketplace'}';
var inv_phone = '{l s='Invalid phone number.' js=1 mod='marketplace'}'; 
</script>