<style>
.info_description {
    clear: both;
    color: #7F7F7F;
    font-family: Georgia,Arial,'sans-serif';
    font-size: 11px;
    font-style: italic;
    text-align: left;
    width: 100%;
}
</style>
{if !empty($mp_shipping_data)}
<div class="shipping_div">
	<!--
	<div class="form-group">
		<div>
			<label>{l s='Package width :' mod='mpshipping'}</label>
		</div>
		<div class="input-group" style="width:40%;">
		  	<span class="input-group-addon">cm</span>
		  	<input id="width" type="text" class="form-control" onkeyup="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.');" value="{$width}" name="width" maxlength="14">
		</div>	
	</div>
	<div class="form-group">
		<div>
			<label>{l s='Package height :' mod='mpshipping'}</label>
		</div>
		<div class="input-group" style="width:40%;">
		  	<span class="input-group-addon">cm</span>
		  	<input id="height" type="text" class="form-control" onkeyup="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.');" value="{$height}" name="height" maxlength="14">
		</div>		
	</div>

	<div class="form-group">
		<div>
			<label>{l s='Package depth :' mod='mpshipping'}</label>
		</div>
		<div class="input-group" style="width:40%;">
		  	<span class="input-group-addon">cm</span>
		  	<input id="depth" type="text" class="form-control" onkeyup="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.');" value="{$depth}" name="depth" maxlength="14">
		</div>		
	</div>
	<div class="form-group">
		<div>
			<label>{l s='Package weight :' mod='mpshipping'}</label>
		</div>
		<div class="input-group" style="width:40%;">
		  	<span class="input-group-addon">cm</span>
		  	<input id="weight" type="text" class="form-control" onkeyup="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.');" value="{$weight}" name="weight" maxlength="14">
		</div>		
	</div>
	-->
	<div class="form-group">
		<div>
			<label class="add_one"><sup style="color:#f00;">*&nbsp;</sup>{l s='Shipping Profile ' mod='mpshipping'}</label>
		</div>
		<div class="">
			<select id="carriers_restriction" style="height:75px;width:300px;" size="4" multiple="multiple" name="carriers[]">

				{foreach $mp_shipping_data as $shipping_data}
				{if !empty($mp_shipping_product_map_details)}
				<option value="{$shipping_data['id']}" {foreach $mp_shipping_product_map_details as $mp_selected_shipping} {if $mp_selected_shipping['mp_shipping_id'] == $shipping_data['id_reference']}selected="selected"{/if}{/foreach}>{$shipping_data['mp_shipping_name']}</option>
				
				{else}
				<option value="{$shipping_data['id']}">{$shipping_data['mp_shipping_name']}</option>
				{/if}
				
				{/foreach}
			</select>
			<div class="info_description">{l s='If no profile selected, the first shipping profile will be used as default shipping method.' mod='mpshipping'}</div>
		</div>
	</div>
</div>
{/if}