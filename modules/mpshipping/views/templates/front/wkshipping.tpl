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
		<form method="POST" action="{$link->getModuleLink('mpshipping', 'wkshippingprocess')|escape:'html':'UTF-8'}">
			{if isset($mp_shipping_id)}
				<input type="hidden" value="{$mp_shipping_id}" name="mpshipping_id">
			{/if}
			<div class="row no_margin heading_cont">
				<p class="text-capitalize heading_text">{l s='Create Shipping Profile' mod='mpshipping'}</p>
			</div>
			<div class="row no_margin margin-top-25">
				<div class="col-sm-12">
					<h2 class="text-capitalize pro_head_text">{l s='Profile Details' mod='mpshipping'}</h2>
					<span>{l s='Manually set your shipping rates' mod='mpshipping'}</span>
					<div class="row margin-top-30">
						<div class="col-sm-3">
							<p class="pro_sub_text">{l s='Profile Name' mod='mpshipping'}</p>
						</div>
						<div class="col-sm-9">
							<input type="text" class="form-control color-black" name="shipping_name" {if isset($mp_shipping_id)}value="{$mp_shipping_name}"{/if}>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3">
							<p class="pro_sub_text">{l s='Processing Times' mod='mpshipping'}</p>
						</div>
						<div class="col-sm-9">
							<input type="hidden" name="transit_time" id="ship_transit_time" {if isset($mp_shipping_id)}value="{$transit_delay}"{else}value="{l s='Ready To Ship In' mod='mpshipping'}"{/if}>
							<div class="dropdown">
								<button class="btn btn-default dropdown-toggle width-100 delay_dropd" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
									<span class="ship_trans_text pull-left">
										{if (isset($mp_shipping_id) && $transit_delay)}
											{$transit_delay}
										{else}
											{l s='Ready To Ship In' mod='mpshipping'}
										{/if}
									</span>
									<span class="caret pull-right delay_ddp_caret"></span>
								</button>
								<ul class="dropdown-menu width-100" role="menu" aria-labelledby="dropdownMenu1">
									<li role="presentation"><a role="menuitem" class="tran_val" tabindex="-1" href="#">{l s='Ready To Ship In' mod='mpshipping'}</a></li>
									<li role="presentation"><a role="menuitem" class="tran_val" tabindex="-1" href="#">{l s='1 Business day' mod='mpshipping'}</a></li>
									<li role="presentation"><a role="menuitem" class="tran_val" tabindex="-1" href="#">{l s='1-2 Business days' mod='mpshipping'}</a></li>
									<li role="presentation"><a role="menuitem" class="tran_val" tabindex="-1" href="#">{l s='1-3 Business days' mod='mpshipping'}</a></li>
									<li role="presentation"><a role="menuitem" class="tran_val" tabindex="-1" href="#">{l s='3-5 Business days' mod='mpshipping'}</a></li>
									<li role="presentation"><a role="menuitem" class="tran_val" tabindex="-1" href="#">{l s='1-2 Weeks' mod='mpshipping'}</a></li>
									<li role="presentation"><a role="menuitem" class="tran_val" tabindex="-1" href="#">{l s='2-3 Weeks' mod='mpshipping'}</a></li>
									<li role="presentation"><a role="menuitem" class="tran_val" tabindex="-1" href="#">{l s='3-4 Weeks' mod='mpshipping'}</a></li>
									<li role="presentation"><a role="menuitem" class="tran_val" tabindex="-1" href="#">{l s='4-6 Weeks' mod='mpshipping'}</a></li>
									<li role="presentation"><a role="menuitem" class="tran_val" tabindex="-1" href="#">{l s='6-8 Weeks' mod='mpshipping'}</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row no_margin margin-top-25">
				<div class="col-sm-12">
					<h2 class="text-capitalize pro_head_text">{l s='Shipping Costs' mod='mpshipping'}</h2>
					<span>{l s='Add the countries you will ship to, the costs and upgrades you offer' mod='mpshipping'}.</span>
					<div class="row margin-top-30">
						<div class="col-sm-3">
							<p class="pro_sub_text">{l s='Shipping costs' mod='mpshipping'}</p>
						</div>
						<div class="col-sm-9">
							<div class="ship_cost_cont">
								<div class="table-responsive ship_prim_cont">
									<table class="table">
										<tr class="ship_tb_head">
											<th class="color-black">{l s='Ship To' mod='mpshipping'}</th>
											<th class="color-black">{l s='Cost' mod='mpshipping'}</th>
										</tr>
										<tr>
											<td class="color-black">{l s='North America' mod='mpshipping'}</td>
											<td>
												<div class="input-group input-group-sm">
													<span class="input-group-addon" id="sizing-addon1">{$currency_details['sign']}</span>
													<input type="text" class="form-control ship_cost color-black" name="n_america_ship" {if isset($mp_shipping_id)}value="{$price_by_range[$ranges['0']['id_range']]['2']}"{/if} aria-describedby="sizing-addon1">
												</div>
											</td>
										</tr>
										<tr>
											<td class="color-black">{l s='Everywhere Else' mod='mpshipping'}</td>
											<td>
												<div class="input-group input-group-sm">
													<span class="input-group-addon" id="sizing-addon2">{$currency_details['sign']}</span>
													<input type="text" class="form-control ship_cost color-black" name="else_ship" {if isset($mp_shipping_id)}value="{$price_by_range[$ranges['0']['id_range']]['1']}"{/if} aria-describedby="sizing-addon2">
												</div>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row no_margin margin-top-30">
					<div class="col-sm-3">
						<span class="pro_sub_text color-black">{l s='Tracking URL' mod='mpshipping'}</span>
					</div>
					<div class="col-sm-9">
						<input type="text" class="form-control color-black" name="tracking_url" {if isset($mp_shipping_id)}value="{$tracking_url}"{/if}>
						<p><em>{l s="For example: 'http://exampl.com/track.php?num=@' with '@' where the tracking number should appear" mod="mpshipping"}. <em></p>
					</div>
				</div>

				<div class="row no_margin margin-top-30">
					<div class="col-sm-12">
						<span class="pro_sub_text">{l s='Shipping Policies' mod='mpshipping'}</span>
						<textarea class="form-control color-black" name="ship_policy">{if isset($mp_shipping_id)}{$shipping_policy}{/if}</textarea>
					</div>
				</div>
			</div>
			
			<div class="col-sm-12">
				<button type="submit" class="btn btn-primary pull-right ship_save_btn"> {l s=' save ' mod='mpshipping'} </button>
			</div>
			
		</form>
	</div>
</div>