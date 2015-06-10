<form role="form" id="step_carrier_pricezone" class="defaultForm form-horizontal" enctype="multipart/form-data" method="post" action="{$mpshippingprocess_link_step2}">
	
	<!-- shipping handling charge set disable by default because handling apply global and seller do not change or apply handling charge for his/her shipping method so shipping handling charge set default disabled -->
	<!-- <input type="hidden" name="shipping_handling" value="0"> -->
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s=' Add handling costs' mod='mpshipping'}
		</label>
		<div class="col-lg-9">
			<span class="switch prestashop-switch fixed-width-lg">
				<input id="shipping_handling_on" type="radio" value="1" name="shipping_handling" >
				<label  for="shipping_handling_on">{l s='Yes' mod='mpshipping'}</label>
				<input id="shipping_handling_off" type="radio" value="0" name="shipping_handling" checked="checked">
				<label for="shipping_handling_off">{l s='No' mod='mpshipping'}</label>
				<a class="slide-button btn"></a>
			</span>
		</div>
	</div>
	<div class="form-group" id="shipping_handling_charge">
		<label class="control-label col-lg-3">
			{l s='Handling Charge' mod='mpshipping'}
		</label>
		<div class="input-group fixed-width-md col-lg-2">
			<span class="input-group-addon">&nbsp; {$currency_sign}</span>
			<input type="text" class="form-control" value="{$shipping_handling_charge}" disabled="disabled" />
		</div>
		<div class="col-lg-7"></div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Free Shipping' mod='mpshipping'}
		</label>
		<div class="col-lg-9">
			<span class="switch prestashop-switch fixed-width-lg">
				<input type="radio" value="1" id="is_free_on" name="is_free">
				<label for="is_free_on">{l s='Yes' mod='mpshipping'}</label>
				<input type="radio" checked="checked" value="0" id="is_free_off" name="is_free">
				<label for="is_free_off">{l s='No' mod='mpshipping'}</label>
				<a class="slide-button btn"></a>
			</span>
		</div>
	</div>
	<div class="left full form-group">
		<div class="left full pricezoneheader">
			{l s='Default Shipping Price according to zone' mod='mpshipping'}
		</div>
	</div>
	<input type="hidden" value="" name="button_click" class="button_click"/>
	<input type="hidden" name="mpshipping_id" value="{$mp_shipping_id}">
	<div class="left full row">
		<script>var zones_nbr = {$zones|count +3} ; /*corresponds to the third input text (max, min and all)*/</script>
		<div style="float:left" id="zone_ranges">
			<table cellpadding="5" cellspacing="0" id="zones_table">
				<tr class="range_inf">
					<td class="range_type"></td>
					<td class="border_left border_bottom range_sign">>=</td>
					{foreach from=$ranges key=r item=range}
						<td class="border_bottom center">
							<div class="input-group fixed-width-md">
								<span class="input-group-addon price_unit">&nbsp; {$currency_sign}</span>
								<input name="range_inf[{$range.id_range|intval}]" type="text" class="form-control edit_price_value_lower" value="{$range.delimiter1|string_format:"%.6f"}" />
								<span class="input-group-addon weight_unit">&nbsp; {$PS_WEIGHT_UNIT}</span>
							</div>
						</td>
					{foreachelse}
						<td class="border_bottom center">
							<div class="input-group fixed-width-md">
								<span class="input-group-addon price_unit">&nbsp; {$currency_sign}</span>
								<input name="range_inf[{$range.id_range|intval}]" type="text" class="form-control edit_price_value_lower"/>
								<span class="input-group-addon weight_unit">&nbsp; {$PS_WEIGHT_UNIT}</span>
							</div>
						</td>
					{/foreach}
				</tr>
				<tr class="range_sup">
					<td class="center range_type"></td>
					<td class="border_left range_sign"><</td>
					{foreach from=$ranges key=r item=range}
						<td class="center">
							<div class="input-group fixed-width-md">
								<span class="input-group-addon price_unit">&nbsp; {$currency_sign}</span>
								<input name="range_sup[{$range.id_range|intval}]" class="form-control edit_price_value_upper" type="text" {if isset($form_id) && !$form_id} value="" {else} value="{if isset($change_ranges) && $range.id_range == 0} {else}{$range.delimiter2|string_format:"%.6f"}{/if}" {/if}/>
								<span class="input-group-addon weight_unit">&nbsp; {$PS_WEIGHT_UNIT}</span>
							</div>
						</td>
					{foreachelse}
						<td class="center">
							<div class="input-group fixed-width-md">
								<span class="input-group-addon price_unit">&nbsp; {$currency_sign}</span>
								<input name="range_sup[{$range.id_range|intval}]" class="form-control edit_price_value_upper" type="text" />
								<span class="input-group-addon weight_unit">&nbsp; {$PS_WEIGHT_UNIT}</span>
							</div>
						</td>
					{/foreach}
				</tr>
				<tr class="fees_all">
					<td class="border_top border_bottom border_bold">
						<span class="fees_all" {if $ranges|count == 0}style="display:none" {/if}>{l s='All' mod='mpshipping'}</span>
					</td>
					<td>
						<input type="checkbox" onclick="checkAllZones(this);" >
					</td>
					{foreach from=$ranges key=r item=range}
						<td class="center border_top border_bottom {if $range.id_range != 0} validated {/if}"  >
							<div class="input-group fixed-width-md">
								<span class="input-group-addon currency_sign" {if $range.id_range == 0}  {/if}>&nbsp; {$currency_sign}</span>
								<input class="form-control" type="text" {if isset($form_id) &&  !$form_id} disabled="disabled"{/if} {if $range.id_range == 0} {/if} />
							</div>
						</td>
					{foreachelse}
						<td class="center border_top border_bottom">
							<div class="input-group fixed-width-md">
								<span class="input-group-addon">&nbsp; {$currency_sign}</span>
								<input style="display:none" type="text" class="form-control" /><span class="currency_sign" style="display:none"></span>
							</div>
						</td>
					{/foreach}
				</tr>
				{foreach from=$zones key=i item=zone}
				<tr class="fees {if $i is odd}alt_row{/if}" data-zoneid="{$zone.id_zone}">
					<td><label for="zone_{$zone.id_zone}">{$zone.name}</label></td>
					<td class="zone">
						<input class="input_zone" onclick="enableTextField(this);" id="zone_{$zone.id_zone}" name="zone_{$zone.id_zone}" value="1" type="checkbox" {if isset($fields_value['zones'][$zone.id_zone]) && $fields_value['zones'][$zone.id_zone]} checked="checked"{/if}/>
					</td>
					{foreach from=$ranges key=r item=range}
						<td class="center">
							<div class="input-group fixed-width-md">
							<span class="input-group-addon">&nbsp; {$currency_sign}</span>
							<input name="fees[{$zone.id_zone|intval}][{$range.id_range|intval}]" id="input_zone_{$zone.id_zone}" class="form-control" type="text" 
							{if !isset($fields_value['zones'][$zone.id_zone]) || (isset($fields_value['zones'][$zone.id_zone]) && !$fields_value['zones'][$zone.id_zone])} disabled="disabled"{/if} {if isset($price_by_range[$range.id_range][$zone.id_zone]) && $price_by_range[$range.id_range][$zone.id_zone] && isset($fields_value['zones'][$zone.id_zone]) && $fields_value['zones'][$zone.id_zone]} value="{$price_by_range[$range.id_range][$zone.id_zone]|string_format:'%.6f'}" {else} value="" {/if} />
							</div>
						</td>
					{/foreach}
				</tr>
				{/foreach}
				<tr class="delete_range">
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					{foreach from=$ranges name=ranges key=r item=range}
						{if $smarty.foreach.ranges.first}
							<td class="center">&nbsp;</td>
						{else}
							<td class="center"><button class="btn btn-default">{l s='Delete' mod='mpshipping'}</button</td>
						{/if}
					{/foreach}
				</tr>
			</table>
		</div>
		<div class="new_range">
			<a id="add_new_range" class="btn btn-default" onclick="add_new_range();" href="#">
			<span>{l s='Add new range' mod='mpshipping'}</span></a>
		</div>
	</div>
</form>
	