<div id="newbody"></div>
<div id="impact_price_block">
	{include file="$self/../../views/templates/front/rangestep4.tpl"}
</div>
<div class="shipping_list_container left">
	<div class="shipping_heading">
		<div class="left_heading">
			<h1>{l s='Update Impact Price' mod='mpshipping'}</h1>
		</div>
		<div class="right_links">
			<div class="home_link">
				<a href="{$dash_board_link}"><img alt="home" title="Home" src="{$modules_dir}mpshipping/img/home.gif"></a>
				<a class="btn btn-default button button-small" id="add_new_shipping" href="{$sellershippinglist_link}">
					<span>{l s='Shipping list' mod='mpshipping'}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="shipping_add left swMain"  id="carrier_wizard">
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
					<input type="button" class="btn btn-default button button-small" id="impactprice_button" value="{l s='Click to add impact price' mod='mpshipping'}">
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var string_price = '{l s='Will be applied when the price is' js=1 mod='mpshipping'}';
	var string_weight = '{l s='Will be applied when the weight is' js=1 mod='mpshipping'}';
	var invalid_range = '{l s='This range is not valid' js=1 mod='mpshipping'}';
	var need_to_validate = '{l s='Please validate the last range before create a new one.' js=1 mod='mpshipping'}';
	var delete_range_confirm = '{l s='Are you sure to delete this range ?' js=1 mod='mpshipping'}';
	var currency_sign = '{$currency_sign}';
	var PS_WEIGHT_UNIT = '{$PS_WEIGHT_UNIT}';
	var	labelDelete = '{l s='Delete' js=1 mod='mpshipping'}';
	var	labelValidate = '{l s='Validate' js=1 mod='mpshipping'}';
	var range_is_overlapping = '{l s='Ranges are overlapping' js=1 mod='mpshipping'}';
	var select_country = '{l s='Select country' js=1 mod='mpshipping'}';
	var select_state = '{l s='All' js=1 mod='mpshipping'}';
	var zone_error = '{l s='Select Zone' js=1 mod='mpshipping'}';
	var zone_error = '{l s='Select Country' js=1 mod='mpshipping'}';
	var ranges_info = '{l s='Ranges' js=1 mod='mpshipping'}';
	var shipping_method = '{$shipping_method}';
	var shipping_ajax_link = '{$shipping_ajax_link}';
	var shipping_ajax_link2 = '{$shipping_ajax_range_link}';
	var message_impact_price = '{l s='Impact added sucessfully' js=1 mod='mpshipping'}';
	var message_impact_price_error = '{l s='Price should be numeric' js=1 mod='mpshipping'}';
</script>