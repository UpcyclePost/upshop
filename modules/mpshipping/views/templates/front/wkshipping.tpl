<style>
.dashboard_content {
	float: left;
	width: 78%;
}
.wk_right_col{
	background: #fff;
	padding: 15px;
}
.btn-default {
  color: #555;
  background-color: #fff;
  border-color: #555;
}
.table{
	margin-bottom: 10px;
}
.form-control {
    height: 30px;
}
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
.input-sm, .input-group-sm > .form-control, .input-group-sm > .input-group-addon, .input-group-sm > .input-group-btn > .btn {
    height: 30px;
    padding: 5px 10px;
    font-size: 14px;
    line-height: 1;
    border-radius: 3px;
}

.button.lnk_view span{
	padding:6px 12px;
}
.info_description {
    clear: both;
    color: #7F7F7F;
    font: inherit;
    font-size: 12px;
    font-style: italic;
    font-weight: normal;
	text-align: left;
    width: 100%;
}
</style>

<div class="error wizard_error" {if $is_main_error==-1}style="display:none;"{/if}>
	{if $is_main_error==1}
		<div class="alert alert-danger">
			{l s='Shipping name must not have Invalid characters /^[^<>;=#{}]*$/u' mod='mpshipping'}
		</div>
	{else if $is_main_error==2}
		<div class="alert alert-danger">
			{l s='Transit time must not have Invalid characters /^[^<>={}]*$/u' mod='mpshipping'}
		</div>
	{else if $is_main_error==3}
		<div class="alert alert-danger">
			{l s='Only jpg,png,jpeg image allow and image size should not exceed 125*125' mod='mpshipping'}
		</div>
	{else if $is_main_error==4}
		<div class="alert alert-danger">
			{l s='The grade field is invalid.' mod='mpshipping'}
		</div>
	{else if $is_main_error==5}
		<div class="alert alert-danger">
			{l s='Invalid Tracking Url' mod='mpshipping'}
		</div>
	{else if $is_main_error==6}
		<div class="alert alert-danger">
			{l s='Invalid Shipping Price.' mod='mpshipping'}
		</div>
	{else if $is_main_error==7}
		<div class="alert alert-danger">
			{l s='The max width field is invalid.' mod='mpshipping'}
		</div>
	{else if $is_main_error==8}
		<div class="alert alert-danger">
			{l s='The max depth field is invalid.' mod='mpshipping'}
		</div>
	{else if $is_main_error==9}
		<div class="alert alert-danger">
			{l s='The max weight field is invalid.' mod='mpshipping'}
		</div>
	{/if}
</div>
<div class="main_block">
	{hook h="DisplayMpmenuhook"}
	<div class="dashboard_content login-panel">
		<div class="page-title login-panel-header">
			{if isset($mp_shipping_id)}
			<h1>{l s='Edit Shipping Profile' mod='mpshipping'}</h1>
			{else}
			<h1>{l s='Add Shipping Profile' mod='mpshipping'}</h1>
			{/if}
		</div>
		<div class="wk_right_col">
			<div class="row no_margin">
				<div class="col-sm-12">
				<form method="POST" action="{$link->getModuleLink('mpshipping', 'wkshippingprocess')|escape:'html':'UTF-8'}" id="form_shipping" id="form_shipping">
					{if isset($mp_shipping_id)}
						<input type="hidden" value="{$mp_shipping_id}" name="mpshipping_id">
					{/if}
					<div class="row">
						<h2 class="pro_head_text">{l s='Profile Details' mod='mpshipping'}</h2>
						<p >{l s='A shipping profile is the shipping cost, time it takes to ship, the tracking URL for an order, and any policies that you would like your buyers to know about.' mod='mpshipping'}</p>
						<p >{l s='For example if you have different sized products you can create seprate profiles for each size (Small, Medium, and Large).' mod='mpshipping'}</p>
					</div>
					<p><sup style="color:#f00;">*</sup> Required field</p>
					<div class="required form-group">
						<label for="shipping_name"><sup style="color:#f00;">*&nbsp;</sup>{l s='Profile Name : (64 characters max)' mod='mpshipping'}</label>
						<input type="text" maxlength="64" class="form-control" name="shipping_name" id="shipping_name" {if isset($mp_shipping_id)}value="{$mp_shipping_name}"{/if}>
					</div>
					<div class="form-group">
						<label for="transit_time">{l s='Shipping Time :' mod='mpshipping'}</label>
						<Select name="transit_time" id="ship_transit_time" class="">
							<option value="" {if !isset($mp_shipping_id)}selected{/if}>Select Shipping Time</option>
							<option value="1 Business day" {if $transit_delay=="1 Business day"}selected{/if}>1 Business day</option>
							<option value="1-2 Business days" {if $transit_delay=="1-2 Business days"}selected{/if}>1-2 Business days</option>
							<option value="3-5 Business days" {if $transit_delay=="3-5 Business days"}selected{/if}>3-5 Business days</option>
							<option value="1-2 Weeks" {if $transit_delay=="1-2 Weeks"}selected{/if}>1-2 Weeks</option>
						</Select>
					</div>
					<div class="form-group">
						<label for="n_america_ship"><sup style="color:#f00;">*&nbsp;</sup>{l s='Shipping cost : ' mod='mpshipping'}</label>&nbsp;Numbers and decimal point only (e.g. 1234.56)
						<div class="input-group input-group-sm">
							<span class="input-group-addon" id="sizing-addon1">{$currency_details['sign']}</span>
							<input type="text" class="form-control ship_cost" name="n_america_ship" id="n_america_ship" {if isset($mp_shipping_id)}value="{$price_by_range[$ranges['0']['id_range']]['2']|number_format:2:'.':''}"{/if} aria-describedby="sizing-addon1"  onblur="javascript:this.value=Number(this.value).toFixed(2)">
						</div>
						<div class="info_description">{l s='If you have any handling charges include those as part of your shipping cost. If you would like to offer free shipping set the cost to 0.' mod='mpshipping'}</div>
					</div>
					<div class="form-group">
						<label for="tracking_url">{l s='Tracking URL :' mod='mpshipping'}</label>
						<Select name="tracking_url" id="ship_tracking_url" class="">
							<option value="" {if !isset($mp_shipping_id) || $tracking_url==""}selected{/if}>Select Carrier</option>
							<option value="https://www.usps.com/search.htm?q=@" {if $tracking_url=="https://www.usps.com/search.htm?q=@"}selected{/if}>USPS</option>
							<option value="https://www.fedex.com/apps/fedextrack/?cntry_code=us&tracknumbers=@" {if $tracking_url=="https://www.fedex.com/apps/fedextrack/?cntry_code=us&tracknumbers=@"}selected{/if}>Fedex</option>
							<option value="https://wwwapps.ups.com/WebTracking/processInputRequest?AgreeToTermsAndConditions=yes&loc=en_US&tracknum=@" {if $tracking_url == "https://wwwapps.ups.com/WebTracking/processInputRequest?AgreeToTermsAndConditions=yes&loc=en_US&tracknum=@"}selected{/if}>UPS</option>
						</Select>
						<div class="info_description">{l s="If you would like the buyer to receive a link to the tracking number for the carrier you are using, select the carrier here" mod="mpshipping"}.</div>
						<div class="info_description">{l s="This field is optional and should only be used if this shipping profile always uses the same carrier" mod="mpshipping"}.</div>
					</div>
					<div class="form-group">
						<label for="ship_policy">{l s='Shipping Policies :' mod='mpshipping'}</label>
						<textarea class="form-control" name="ship_policy">{if isset($mp_shipping_id)}{$shipping_policy}{/if}</textarea>
						<div class="info_description">{l s="Specify any policies that you would like the buyer to know about regarding your shipping." mod="mpshipping"}</div>
					</div>
					<div class="form-group"  style="text-align:center;padding-top:10px;">
					<button type="submit" id="buttonNext" name="buttonNext" class="btn btn-default button button-medium">
						<span>{l s=' Save ' mod='mpshipping'}&nbsp;<i class="icon-chevron-right "></i></span>			
					</button>
				</div>
				</form>
			</div>
		</div>
	</div>

	</div>
</div>
