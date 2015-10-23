<style type="text/css">

@media (min-width: 1200px){
.container {
  max-width: 1180px;
  margin-left:auto;
  margin-right:auto;
}
}

@media (min-width:768px){
	#center_column.col-sm-9 {
		width:100%;
	}
}
@media(max-width:768px){
	  #checkimages{
		  display:none;
		  }
  }

.fcevrt{}
select{
	padding: 3px 5px;
    height: 30px;
    -webkit-box-shadow: none;
    box-shadow: none;
    border-radius: 4px;
    border: 1px solid #999;
    color: #9c9b9b;
	width:100%;
}
select:focus{
	outline: 0;
    -webkit-box-shadow: 0 0 8px rgba(103, 174, 233, 0.6);
    box-shadow: 0 0 8px rgba(103, 174, 233, 0.6);
}
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
				<img alt="Sell" src="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/img/sell_sm.png" style="float:right;"></img>
				<h3>{l s='Well done! Your banking information has now been verified and you can continue to build your shop by creating your first shipping profile.' mod='marketplace'}</h3>
				<p>&nbsp;</p>
				<h4 style="line-height:24px">Adding shipping profiles allows you to specify the amount of shipping you want to charge, you can add more than one if you have products of different sizes.</h4>
				<h4 style="line-height:24px">After you have set up your shipping profiles you will then be able to add your products.</h4>
				<p>&nbsp;</p>
				<div style="text-align:center;margin-left:auto;margin-right:auto;">
				<a class="btn btn-default button button-medium" style="padding:3px 8px 3px 8px;" href="{$link->getModuleLink('mpshipping','addnewshipping')|escape:'html':'UTF-8'}">
					<span>{l s='Add shipping profile' mod='marketplace'}&nbsp;<i class="icon-chevron-right right"></i></span>
				</a>
				</div>
			</p>
			<!-- Mixpanel Tracking -->
			{assign var=shop_name value=$cookie->c_mp_shop_name}
			<script type="text/javascript">
				mixpanel.track('Shop Created', {
					'Shop Name': "{$shop_name}"
					});
			</script>
			<!-- End Mixpanel Tracking -->
			<!-- Google Code for Open a Shop Conversion Page --> 
			<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion_async.js" charset="utf-8"></script>
			<script type="text/javascript">
			/* <![CDATA[ */
			window.google_trackConversion({
				var google_conversion_id = 1034553725,
				var google_conversion_language = "en",
				var google_conversion_format = "3",
				var google_conversion_color = "ffffff",
				var google_conversion_label = "OVX0COfcmWAQ_ZKo7QM", 
				var google_remarketing_only = false
			});
			/* ]]> */
			</script>
			<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
			</script>
			<noscript>
			<div style="display:inline;">
			<img height="1" width="1" style="border-style:none;" alt=""  
			src="//www.googleadservices.com/pagead/conversion/1034553725/?label=OVX0COfcmWAQ_ZKo7QM&amp;guid=ON&amp;script=0"/>
			</div>
			</noscript>
			<!-- End Google Code for Open a Shop Conversion Page -->
			<!-- Facebook Conversion Code for Open store -->
			{literal}
			<script>(function() {
			  var _fbq = window._fbq || (window._fbq = []);
			  if (!_fbq.loaded) {
			    var fbds = document.createElement('script');
			    fbds.async = true;
			    fbds.src = '//connect.facebook.net/en_US/fbds.js';
			    var s = document.getElementsByTagName('script')[0];
			    s.parentNode.insertBefore(fbds, s);
			    _fbq.loaded = true;
			  }
			})();
			window._fbq = window._fbq || [];
			window._fbq.push(['track', '6030575472399', {'value':'0.00','currency':'USD'}]);
			</script>
			<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6030575472399&amp;cd[value]=0.00&amp;cd[currency]=USD&amp;noscript=1" /></noscript>
			{/literal}
			<!-- End Facebook Conversion Code for Open store -->

		{/if}
	{/if}
	</div>
</div>
{else}
<div class="seller_registration_form">
	<div class="container login-panel">
		<div class="page-title login-panel-header">
			<h1>{l s='Create a Shop' mod='marketplace'}</h1>
		</div>
		<div class="wk_right_col">
			<div class="col-sm-12">
				<div class="col-sm-6">
		<p>We require the following information for you to successfully process transactions on the site, and to receive payment for the purchases made in your shop. Upmod does not store any of your banking information and collects the minimum amount of data required by the payment processing industry.</p>	
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
					<label for="phone1"><sup>*</sup>{l s='Phone' mod='marketplace'}</label>&nbsp;{l s='10 digits, no separators' mod='marketplace'}
					<input class="form-control" type="text" name="phone" id="phone1" maxlength="{$phone_digit|escape:'html':'UTF-8'}" placeholder="Enter your phone number"/>
				</div>		 
                <div class="form-group">	
					<label for="address">{l s='Address' mod='marketplace'}</label>
					<textarea name="address" class = "form-control"  placeholder="Enter your business address"></textarea>
				</div>
                 <fieldset style="">
                 <div id="bank" class="form-group" >
					<label for="bank"><sup>*</sup>{l s='Bank Account Number' mod='marketplace'}</label>&nbsp;{l s='e.g.' mod='marketplace'} 000123456789
					<input class="reg_sel_input form-control"  type="text" name="bank" id="bank" placeholder="Enter your bank account number"/>
				</div>
					
				<div id="routing" class="form-group" >
					<label for="routing"><sup>*</sup>{l s='Routing Number' mod='marketplace'}</label>&nbsp;{l s='e.g.' mod='marketplace'} 110000000
					<input class="reg_sel_input form-control"  type="text" name="routing" id="routing" placeholder="Enter your bank routing number"/>
				</div>
                <div id="type" class="form-group" >
					<label for="type"><sup>*</sup>{l s='Entity Type' mod='marketplace'}</label>
					<select name="type" id="type" style="width:100%;height:30px;"><option value="individual">{l s='Individual' mod='marketplace'}</option><option value="company">{l s='Company' mod='marketplace'}</option></select>
				</div>
                <div id="fname" class="form-group" >
					<label for="fname"><sup>*</sup>{l s='First Name' mod='marketplace'}</label>
					<input class="form-control"  type="text" name="fname" id="fname" />
				</div>
                <div id="lname" class="form-group" >
                    <label for="lname"><sup>*</sup>{l s='Last Name' mod='marketplace'}</label>
					<input class="form-control"  type="text" name="lname" id="lname" />
				</div>
                <div id="ssn" class="form-group" >
					<label for="ssn"><sup>*</sup>{l s='SSN/EIN last 4 digits' mod='marketplace'}</label>
					<input class="form-control"  type="text" name="ssn" id="ssn" maxlength="4"/>
				</div>
                <div id="routing" class="form-group" >
					<label for="routing"><sup>*</sup>{l s='Date of birth' mod='marketplace'}</label>&nbsp;{l s='e.g.' mod='marketplace'} 12/31/1988 (mm/dd/yyyy)
                    <p>
					<input class="form-control"  type="text" name="month" id="month" style="width:30px;display: inline;"  maxlength="2"/> /
					<input class="form-control"  type="text" name="day" id="day" style="width:30px;display: inline;"  maxlength="2"/> /
                    <input class="form-control"  type="text" name="year" id="year" style="width:50px;display: inline;"  maxlength="4"/>&nbsp;
                    </p>
				</div>
                </fieldset>
				{hook h="DisplayMpshoprequestfooterhook"}
			</fieldset>


			</div>
			<div class="col-sm-6" id="checkimages">
				<div class="container" style="margin-top:20px;">
				<h4>{l s='Bank account and Routing number information' mod='marketplace'}</h4>
				<div>
					For <strong>U.S.</strong> Bank accounts the routing number and account information can be found on your check as shown below.
					<img src="{$img_dir}US_check.png" height="116" width="321" style="border:solid 1px #0187d0;padding:5px;margin:10px 0 10px 0;"></img>
				</div>
				<!--
				<div>
					For <strong>Canadian</strong> Bank accounts the routing number and account information can be found on your check as shown below.
					<img src="{$img_dir}Canadian_check.png" height="121" width="321" style="border:solid 1px #0187d0;padding:5px;margin:10px 0 0 0;"></img>
				</div>
				-->
				</div>
			</div>

	</div>
		</div>
			<div class="form-group" style="text-align:center;margin-bottom:50px">
				<button type="submit" id="seller_save" class="btn btn-default button button-medium">
					<span>{l s='Continue' mod='marketplace'}<i class="icon-chevron-right right"></i></span>
				</button>&nbsp;&nbsp;
                                    <span id="loadin_msg" style="display:none;margin-top: 12px;font-size: 15px;color: orangered;position: absolute;">{l s='Please wait while we validate your banking information...' mod='marketplace'}</span><br><br>
                By registering your account, you agree to our <a href="{$link->getCMSLink(3)|escape:'html':'UTF-8'}" class="iframe" rel="nofollow" target="_blank">Terms of Service</a> and the <a href="https://stripe.com/connect/account-terms" class="iframe" rel="nofollow" target="_blank">Stripe Connected Account Agreement</a>. 
			</div>
		</form>


	</div>
		
	</div>

{/if}

<script type="text/javascript">
{if isset($phone_digit)}
var phone_digit = '{$phone_digit|escape:'html':'UTF-8'}';
{else}
var phone_digit = '10';
{/if}
var req_seller_name = '{l s='Seller name is required.' js=1 mod='marketplace'}';
var inv_seller_name = '{l s='Invalid Seller name.' js=1 mod='marketplace'}';
var req_shop_name = '{l s='Shop name is required.' js=1 mod='marketplace'}';
var inv_shop_name = '{l s='Invalid Shop name.' js=1 mod='marketplace'}';
var req_email = '{l s='Email Id is required.' js=1 mod='marketplace'}';
var inv_email = '{l s='Invalid email address' js=1 mod='marketplace'}';
var req_phone = '{l s='Phone is required.' js=1 mod='marketplace'}';
var inv_phone = '{l s='Invalid phone number.' js=1 mod='marketplace'}'; 
</script>