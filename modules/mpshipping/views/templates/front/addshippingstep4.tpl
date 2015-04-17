	<form id="step_carrier_impact_country" class="defaultForm " enctype="multipart/form-data" method="post" action="{$mpshippingprocess_link_step4}">
		<input type="hidden" value="" name="button_click" class="button_click"/>
		{if $mp_shipping_id!=0}
			<input type="hidden" name="mpshipping_id" value="{$mp_shipping_id}" class="mpshipping_id" />
			<input type="hidden" name="step4_shipping_method" value="{$shipping_method}" class="step4_shipping_method" />
		{/if}
		<div class="left full row">
			<div class="left lable">
				{l s='Zone' mod='mpshipping'}
			</div>
			
			<div class="left input_label">
				<select name="step4_zone" id="step4_zone">
					<option value="-1">{l s='Select Zone' mod='mpshipping'}</option>
				{foreach $zones as $zon}
					<option value="{$zon['id_zone']}">{$zon['name']}</option>
				{/foreach}
				</select>
			</div>
		</div>
		<div class="left full" id="country_container" style="display:none;">
			<div class="left full row">
				<div class="left lable">
					{l s='Country' mod='mpshipping'}
				</div>
				
				<div class="left input_label">
					<select name="step4_country" id="step4_country">
						<option value="-1">{l s='Select country' mod='mpshipping'}</option>
					</select>
				</div>
			</div>
			<div class="left full" id="state_container" style="display:none;">
				<div class="left full row">
					<div class="left lable">
						{l s='State' mod='mpshipping'}
					</div>
					
					<div class="left input_label">
						<select name="step4_state" id="step4_state">
							<option value="0">{l s='All state' mod='mpshipping'}</option>
						</select>
					</div>
				</div>
				<div class="left full row" style="text-align:center;">
					<input type="button" class="button" id="impactprice_button" value="{l s='Click to add impact price' mod='mpshipping'}">
				</div>
			</div>
		</div>
	</form>