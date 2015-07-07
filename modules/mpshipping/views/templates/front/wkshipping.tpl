<style>
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
	
</style>

{capture name=path}
<a href="{$link->getModuleLink('marketplace', 'marketplaceaccount')|addslashes}">
        {l s='My Dashboard'}
</a>
<span class="navigation-pipe">{$navigationPipe}</span>
<span class="navigation_page">{l s='Create Shipping Profile' mod='marketplace'}</span>
{/capture}

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
<div class="row">
	<div class="col-sm-12">
	<div class="container login-panel">
		<div class="page-title login-panel-header">
			<h1>{l s='Create Shipping Profile' mod='mpshipping'}</h1>
		</div>
		<div class="wk_right_col">
			<div class="row no_margin">
				<div class="col-sm-12">
				<form method="POST" action="{$link->getModuleLink('mpshipping', 'wkshippingprocess')|escape:'html':'UTF-8'}" id="form_shipping" id="form_shipping">
					{if isset($mp_shipping_id)}
						<input type="hidden" value="{$mp_shipping_id}" name="mpshipping_id">
					{/if}
					<div class="row">
						<div class="col-sm-3">
							<h2 class="pro_head_text">{l s='Profile Details' mod='mpshipping'}</h2>
						</div>
						<div class="col-sm-9">
							<p >{l s='Create shipping Profiles for your products; a shipping profile is the shipping cost, the tracking URL for an order, and any policies that you would like your buyers to know about.' mod='mpshipping'}</p>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-3">
							<p class="pro_sub_text">{l s='Profile Name' mod='mpshipping'}</p>
							<p class="">{l s='Required - 64 characters max' mod='mpshipping'}</p>								
						</div>
						<div class="col-sm-9">
							<input type="text" maxlength="64" class="form-control" name="shipping_name" id="shipping_name" {if isset($mp_shipping_id)}value="{$mp_shipping_name}"{/if}>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3">
							<p class="pro_sub_text">{l s='Shipping Time' mod='mpshipping'}</p>
						</div>
						<div class="col-sm-9">
							<input type="hidden" name="transit_time" id="ship_transit_time" {if isset($mp_shipping_id)}value="{$transit_delay}"{else}value="{l s='Select a value' mod='mpshipping'}"{/if}>
							<div class="dropdown">
								<button class="width-100" type="button" id="dropdownMenu1" data-toggle="dropdown">
									<span class="ship_trans_text pull-left">
										{if (isset($mp_shipping_id) && $transit_delay)}
											{$transit_delay}
										{else}
											{l s='Select a value' mod='mpshipping'}
										{/if}
									</span>
									<span class="caret pull-right delay_ddp_caret"></span>
								</button>
								<ul class="dropdown-menu width-100" role="menu" aria-labelledby="dropdownMenu1">
									<li role="presentation"><a role="menuitem" class="tran_val" tabindex="-1" href="#">{l s='1 Business day' mod='mpshipping'}</a></li>
									<li role="presentation"><a role="menuitem" class="tran_val" tabindex="-1" href="#">{l s='1-2 Business days' mod='mpshipping'}</a></li>
									<li role="presentation"><a role="menuitem" class="tran_val" tabindex="-1" href="#">{l s='3-5 Business days' mod='mpshipping'}</a></li>
									<li role="presentation"><a role="menuitem" class="tran_val" tabindex="-1" href="#">{l s='1-2 Weeks' mod='mpshipping'}</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row no_margin margin-top-25">
				<div class="col-sm-12">
					<div class="row">
						<div class="col-sm-3">
							<h2 class=" pro_head_text">{l s='Shipping Costs' mod='mpshipping'}</h2>
						</div>
						<div class="col-sm-9">
							<p >{l s='Specify the shipping costs that you would like added to an order. If you have any handling charges include those as part of your shipping cost.' mod='mpshipping'}</p>
						</div>
					</div>
					<div class="row margin-top-30">
						<div class="col-sm-3">
							<p class="pro_sub_text">{l s='Shipping costs' mod='mpshipping'}</p>
						</div>
						<div class="col-sm-9">
							<div class="ship_cost_cont">
								<div class="table-responsive ship_prim_cont">
									<table class="table">
										<tr class="ship_tb_head">
											<th nowrap class="">{l s='Ship To' mod='mpshipping'}</th>
											<th class="">{l s='Cost' mod='mpshipping'}</th>
										</tr>
										<tr>
											<td nowrap class="color-black">{l s='North America' mod='mpshipping'}</td>
											<td>
												<div class="input-group input-group-sm">
													<span class="input-group-addon" id="sizing-addon1">{$currency_details['sign']}</span>
													<input type="text" class="form-control ship_cost" name="n_america_ship" {if isset($mp_shipping_id)}value="{$price_by_range[$ranges['0']['id_range']]['2']|number_format:2}"{/if} aria-describedby="sizing-addon1"  onblur="javascript:this.value=Number(this.value).toFixed(2)">
												</div>
											</td>
										</tr>
										<!--
										<tr>
											<td class="color-black">{l s='Everywhere Else' mod='mpshipping'}</td>
											<td>
												<div class="input-group input-group-sm">
													<span class="input-group-addon" id="sizing-addon2">{$currency_details['sign']}</span>
													<input type="text" class="form-control ship_cost color-black" name="else_ship" {if isset($mp_shipping_id)}value="{$price_by_range[$ranges['0']['id_range']]['1']}"{/if} aria-describedby="sizing-addon2">
												</div>
											</td>
										</tr>
										-->
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row no_margin margin-top-30">
					<div class="col-sm-3">
						<span class="pro_sub_text">{l s='Tracking URL' mod='mpshipping'}</span>
					</div>
					<div class="col-sm-9">
						<p><em>{l s="If you would like the buyer to receive a link to the tracking number for the carrier you are using, you can specify it here" mod="mpshipping"}. <em></p>
						<p><em>{l s="This field is optional and should only be used if this shipping profile always uses the same carrier" mod="mpshipping"}. <em></p>
						<p><em>{l s="The '@' sign in the URL will be replaced with a tracking number on the order receipt when a buyer purchases a product" mod="mpshipping"}. <em></p>
						<p><em>{l s="Examples are provided for the USPS, Fedex and UPS, you can copy those URL's, including the '@' sign to the field below if you are using one of those carriers" mod="mpshipping"}. <em></p>						
					</div>
					<div class="col-sm-3">
					</div>
					<div class="col-sm-9">
						<input type="text" class="form-control color-black" name="tracking_url" {if isset($mp_shipping_id)}value="{$tracking_url}"{/if}>
						<p><label for="usps-track">{l s="USPS : " mod="mpshipping"}</label>&nbsp;<span name="usps-track">{l s="https://www.usps.com/search.htm?q=@" mod="mpshipping"}</span></p>
						<p><label for="fedex-track">{l s="Fedex : " mod="mpshipping"}</label>&nbsp;<span name="fedex-track">{l s="https://www.fedex.com/apps/fedextrack/?cntry_code=us&tracknumbers=@" mod="mpshipping"}</span></p>
						<p><label for="ups-track">{l s="UPS : " mod="mpshipping"}</label>&nbsp;<span name="ups-track">{l s="https://wwwapps.ups.com/WebTracking/processInputRequest?AgreeToTermsAndConditions=yes&loc=en_US&tracknum=@" mod="mpshipping"}</span></p>
					</div>
				</div>

				<div class="row no_margin margin-top-30">

					<div class="col-sm-3">
						<span class="pro_sub_text">{l s='Shipping Policies' mod='mpshipping'}</span>
					</div>
					<div class="col-sm-9">
						<p><em>{l s="Specify any policies that you would like the buyer to know about regarding your shipping." mod="mpshipping"}<em></p>
					</div>
					<div class="col-sm-3">
					</div>
					<div class="col-sm-9">
						<textarea class="form-control" name="ship_policy">{if isset($mp_shipping_id)}{$shipping_policy}{/if}</textarea>
					</div>

				</div>
				<div class="col-sm-12"  style="text-align:center;padding-top:10px;">
					<a class="button button-medium" href="{$link->getModuleLink('mpshipping','sellershippinglist')|escape:'html':'UTF-8'}">
						<span><i class="icon-chevron-left "></i>&nbsp;{l s=' Back to Shipping Profiles ' mod='mpshipping'}</span>			
					</a>
					&nbsp;&nbsp;&nbsp;
					<button type="submit" id="buttonNext" name="buttonNext" class="button button-medium">
						<span>{l s=' Save ' mod='mpshipping'}&nbsp;<i class="icon-chevron-right "></i></span>			
					</button>
				</div>
				</form>
			</div>
		</div>
	</div>

	</div>
</div>
