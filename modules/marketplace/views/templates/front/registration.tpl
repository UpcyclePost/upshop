<style type="text/css">

@media (min-width: 1200px){
.container {
  max-width: 970px;
  margin-left:auto;
  margin-right:auto;
}
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
	<div class="page-title login-panel-header">
		<h1>{l s='Open a Shop' mod='marketplace'}</h1>
	</div>
	<div class="wk_right_col">

	{if $login == 0}
		<div class="alert alert-info">
			<p>{l s='You have to login to open a Shop.' mod='marketplace'}</p>
		</div>
	{else}
		{if $is_seller == 0}
			<div class="alert alert-info">
				<p>{l s='Your request has been sent to admin. Please wait till the approval from admin' mod='marketplace'}</p>
			</div>
		{else}
			<p>
				<h3>{l s='Your banking information has been verified.    ' mod='marketplace'}</h3>
				<p>&nbsp;</p>
				<h5>Next step is to set up your shipping profiles, after that is complete you can add products from your dashboard</h5>
				<p>&nbsp;</p>
				<a class="btn btn-default button button-medium" style="padding:3px 8px 3px 8px;" href="{$link->getModuleLink('mpshipping','addnewshipping')|escape:'html':'UTF-8'}">
					{l s='Add shipping profile' mod='marketplace'}&nbsp;<i class="icon-chevron-right right"></i>
				</a>			
			</p>
		{/if}
	{/if}
	</div>
</div>
{else}
<div class="seller_registration_form">
	<div class="container">
		<div class="page-title login-panel-header">
			<h1>{l s='Create a Shop' mod='marketplace'}</h1>
		</div>
		<div class="wk_right_col">
		<p><sup>*</sup> {l s='Required field' mod='marketplace'}</p>
		<form action="{$link->getModuleLink('marketplace', 'registrationprocess')|escape:'htmlall':'UTF-8'}" method="post" id="createaccountform" class="std contact-form-box" enctype="multipart/form-data">
			<fieldset>
				<input class="form-control"  type="hidden" name="person_name" id="person_name1" value="{$seller_name}"/>
				<input class="form-control" type="hidden" name="business_email_id" id="business_email_id1" value="{$seller_email}"/>
				<div class="form-group">	
					<label for="shop_name1"><sup>*</sup>{l s='Shop Name' mod='marketplace'}</label>	
					<input class="is_required validate form-control" type="text" id="shop_name1" name="shop_name" placeholder="Enter your shop name" maxlength="255"/>
				</div>
				<div class="required form-group">
					<label for="phone1"><sup>*</sup>{l s='Phone' mod='marketplace'}</label>{l s='10 digits, no separators' mod='marketplace'}
					<input class="form-control" type="text" name="phone" id="phone1" maxlength="{$phone_digit|escape:'html':'UTF-8'}" placeholder="Enter your phone number"/>
				</div>		 
                <div class="form-group">	
					<label for="address">{l s='Address' mod='marketplace'}</label>
					<textarea name="address" class = "form-control"  placeholder="Enter your business address"></textarea>
				</div>
                 <fieldset style="">
                 <div id="bank" class="form-group" >
					<label for="bank"><sup>*</sup>{l s='Bank Account Number' mod='marketplace'}</label>
					<input class="reg_sel_input form-control"  type="text" name="bank" id="bank" placeholder="Enter your bank account number"/>
                    {l s='e.g.' mod='marketplace'} 000123456789
				</div>
					
				<div id="routing" class="form-group" >
					<label for="routing"><sup>*</sup>{l s='Routing Number' mod='marketplace'}</label>
					<input class="reg_sel_input form-control"  type="text" name="routing" id="routing" placeholder="Enter your bank routing number"/>
                    {l s='e.g.' mod='marketplace'} 110000000
				</div>
                <fieldset style="">
                <div id="type" class="form-group" >
					<label for="type"><sup>*</sup>{l s='Entity Type' mod='marketplace'}</label>
					<select name="type" id="type"><option value="individual">{l s='Individual' mod='marketplace'}</option><option value="company">{l s='Company' mod='marketplace'}</option></select>
				</div>
                <div id="fname" class="form-group" >
					<label for="fname"><sup>*</sup>{l s='First Name' mod='marketplace'}</label>
					<input class="form-control"  type="text" name="fname" id="fname" style="width:100px;display: inline;" />&nbsp;&nbsp;
                    <label for="lname"><sup>*</sup>{l s='Last Name' mod='marketplace'}</label>
					<input class="form-control"  type="text" name="lname" id="lname" style="width:100px;display: inline;" />
				</div>
                <div id="ssn" class="form-group" >
					<label for="ssn"><sup>*</sup>{l s='SSN last 4 digits' mod='marketplace'}</label>
					<input class="form-control"  type="text" name="ssn" id="ssn" style="width:50px;display: inline;" maxlength="4"/>
				</div>
                <div id="routing" class="form-group" >
					<label for="routing"><sup>*</sup>{l s='Date of birth' mod='marketplace'}</label>
                    <input class="form-control"  type="text" name="month" id="month" style="width:30px;display: inline;"  maxlength="2"/> /
					<input class="form-control"  type="text" name="day" id="day" style="width:30px;display: inline;"  maxlength="2"/> /
                    <input class="form-control"  type="text" name="year" id="year" style="width:50px;display: inline;"  maxlength="4"/>&nbsp;
                    {l s='e.g.' mod='marketplace'} 12/31/1988 (mm/dd/yyyy)
				</div>
                </fieldset>
                </fieldset>
				{hook h="DisplayMpshoprequestfooterhook"}
			</fieldset>
			<div class="form-group" style="text-align:center;">
				<button type="submit" id="seller_save" class="btn btn-default button button-medium">
					<span>{l s='Continue' mod='marketplace'}<i class="icon-chevron-right right"></i></span>
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
