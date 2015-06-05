<style type="text/css">
.page-title{
	background-color: {$title_bg_color|escape:'html':'UTF-8'} !important;
}
.page-title span{
	color: {$title_text_color|escape:'html':'UTF-8'} !important;
}
.fcevrt{}
</style>

{capture name=path}
<a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
        {l s='Marketplace account'}
</a>
<span class="navigation-pipe">{$navigationPipe}</span>
<span class="navigation_page">{l s='Seller Registration' mod='marketplace'}</span>
{/capture}

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
    {else if $mp_error == 6}
		<div class="alert alert-danger">{l s='Bank Account number is required field.' mod='marketplace'}</div>
	{else if $mp_error == 7}
		<div class="alert alert-danger">{l s='Routing is requird field.' mod='marketplace'}</div>
    {else if $mp_error !=''}
		<div class="alert alert-danger">{$mp_error}</div>
	{/if}
{/if}
{hook h='displayMpAddSellerHeaderHook'}
{if isset($is_seller)}
<div class="container">
	<div class="page-title">
		<span>{l s='Create a Shop' mod='marketplace'}</span>
	</div>
	<div class="wk_right_col">

	{if $login == 0}
		<div class="alert alert-info">
			<p>{l s='You have to login to Create a Shop.' mod='marketplace'}</p>
		</div>
	{else}
		{if $is_seller == 0}
			<div class="alert alert-info">
				<p>{l s='Your request has been sent to admin. Please wait till the approval from admin' mod='marketplace'}</p>
			</div>
		{else}
			<p>
				<h3>{l s='Your request to create a shop has been approved.    ' mod='marketplace'}</h3>
				<a class="btn btn-default button button-medium" style="padding:3px 8px 3px 8px;" href="{$link->getModuleLink('marketplace','addproduct')|escape:'html':'UTF-8'}">
					{l s='Add your first product' mod='marketplace'}
				</a>
			</p>
		{/if}
	{/if}
	</div>
</div>
{else}
<div class="seller_registration_form">
	<div class="container">
		<div class="page-title">
			<span>{l s='Create a Shop' mod='marketplace'}</span>
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
				<div id="person_name" class="required form-group" >
					<label for="person_name">{l s='Seller Name' mod='marketplace'}<sup>*</sup></label>
					<input class="form-control"  type="text" name="person_name" id="person_name1" />
				</div>
				
				<div class="required form-group">
					<label for="phone1">{l s='Phone' mod='marketplace'}<sup>*</sup></label>
					<input class="form-control" type="text" name="phone" id="phone1" maxlength="{$phone_digit|escape:'html':'UTF-8'}" />
				</div>		 
				<div class="form-group">	
					<label for="business_email_id1">{l s='Business Email' mod='marketplace'}<sup>*</sup></label>
					<input class="form-control" type="text" name="business_email_id" id="business_email_id1" />
				</div>
                <div class="form-group">	
					<label for="address">{l s='Address' mod='marketplace'}</label>
					<textarea name="address" class = "form-control"></textarea>
				</div>
                 <fieldset style="border:2px dotted #999;padding: 10px">
                <legend style="border: 1px solid #999;padding: 8px;background: #fbfbfb;width:auto;"><i class="icon-money"></i>&nbsp;{l s='So that we can get you your money' mod='marketplace'}</legend>
                 <div id="bank" class="form-group" >
					<label for="bank">{l s='Bank Account Number' mod='marketplace'}<sup>*</sup></label>
					<input class="reg_sel_input form-control"  type="text" name="bank" id="bank" />
                    {l s='e.g.' mod='marketplace'} 000123456789
				</div>
					
				<div id="routing" class="form-group" >
					<label for="routing">{l s='Routing Number' mod='marketplace'}<sup>*</sup></label>
					<input class="reg_sel_input form-control"  type="text" name="routing" id="routing" />
                    {l s='e.g.' mod='marketplace'} 110000000
				</div>
                <fieldset style="border:2px dotted #cdcdcd;padding: 10px">
                <legend style="border: 1px solid #cdcdcd;padding: 8px;background: #fbfbfb;width:auto;font-size:15px;"><i class="icon-user"></i>&nbsp;{l s='Legal Entities' mod='marketplace'}</legend>
                <div id="type" class="form-group" >
					<label for="type">{l s='Entity Type' mod='marketplace'}<sup>*</sup></label>
					<select name="type" id="type"><option value="individual">{l s='Individual' mod='marketplace'}</option><option value="company">{l s='Company' mod='marketplace'}</option></select>
				</div>
                <div id="fname" class="form-group" >
					<label for="fname">{l s='First Name' mod='marketplace'}<sup>*</sup></label>
					<input class="form-control"  type="text" name="fname" id="fname" style="width:100px;display: inline;" />&nbsp;&nbsp;
                    <label for="lname">{l s='Last Name' mod='marketplace'}<sup>*</sup></label>
					<input class="form-control"  type="text" name="lname" id="lname" style="width:100px;display: inline;" />
				</div>
                <div id="ssn" class="form-group" >
					<label for="ssn">{l s='SSN last 4 digits' mod='marketplace'}<sup>*</sup></label>
					<input class="form-control"  type="text" name="ssn" id="ssn" style="width:50px;display: inline;" />
				</div>
                <div id="routing" class="form-group" >
					<label for="routing">{l s='Date of birth' mod='marketplace'}<sup>*</sup></label>
                    <input class="form-control"  type="text" name="month" id="month" style="width:30px;display: inline;" /> /
					<input class="form-control"  type="text" name="day" id="day" style="width:30px;display: inline;" /> /
                    <input class="form-control"  type="text" name="year" id="year" style="width:50px;display: inline;" />&nbsp;
                    {l s='e.g.' mod='marketplace'} 12/31/1988 (mm/dd/yyyy)
				</div>
                </fieldset>
                </fieldset>
				{hook h="DisplayMpshoprequestfooterhook"}
			</fieldset>
			<div class="form-group" style="text-align:center;">
				<button type="submit" id="seller_save" class="btn btn-default button button-medium">
					<span>{l s='Register' mod='marketplace'}<i class="icon-chevron-right right"></i></span>
				</button><br><br>
                By registering your account, you agree to our <a href="{$link->getCMSLink(3)|escape:'html':'UTF-8'}" class="iframe" rel="nofollow" target="_blank">Terms of Service</a> and the <a href="https://stripe.com/connect/account-terms" class="iframe" rel="nofollow" target="_blank">Stripe Connected Account Agreement</a>. 
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
