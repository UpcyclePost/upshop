		<input type="hidden" value="" name="button_click" class="button_click"/>
		<div class="left full form-group">
			<label>{if $PS_DIMENSION_UNIT == 'cm' OR $PS_DIMENSION_UNIT == 'CM'}{l s='Maximum package length (cm)' mod='mpshipping'}{else}{l s='Maximum package length (in)' mod='mpshipping'}{/if}</label>
			{if $mp_shipping_id!=0}
				<input type="hidden" name="mpshipping_id" value="{$mp_shipping_id}">
			{/if}
			<input type="text" class="form-control" name="max_height" id="max_height" value="{$max_height}">
			<p class="preference_description">
				{l s='Maximum length managed by this carrier. Set the value to "0", or leave this field blank to ignore. The value must be an integer.' mod='mpshipping'}
			</p>
		</div>
		<div class="left full form-group">
			<label>{if $PS_DIMENSION_UNIT == 'cm' OR $PS_DIMENSION_UNIT == 'CM'}{l s='Maximum package width (cm)' mod='mpshipping'}{else}{l s='Maximum package width (in)' mod='mpshipping'}{/if}</label>
			<input type="text" class="form-control" name="max_width" id="max_width" value="{$max_width}">
			<p class="preference_description">
				{l s='Maximum width managed by this carrier. Set the value to "0", or leave this field blank to ignore. The value must be an integer.' mod='mpshipping'}
			</p> 
		</div>
		<div class="left full form-group">
			<label>{if $PS_DIMENSION_UNIT == 'cm' OR $PS_DIMENSION_UNIT == 'CM'}{l s='Maximum package depth (cm)' mod='mpshipping'}{else}{l s='Maximum package depth (in)' mod='mpshipping'}{/if}</label>
			<input type="text" class="form-control" name="max_depth" id="max_depth" value="{$max_depth}">
			<p class="preference_description">
				{l s='Maximum depth managed by this carrier. Set the value to "0", or leave this field blank to ignore. The value must be an integer. ' mod='mpshipping'}
			</p> 
		</div>
		<div class="left full form-group">
			<label>{if $PS_WEIGHT_UNIT == 'kg' OR $PS_WEIGHT_UNIT == 'KG'}{l s='Maximum package weight (kg)' mod='mpshipping'}{else}{l s='Maximum package weight (lb)' mod='mpshipping'}{/if}</label>
			<input type="text" class="form-control" name="max_weight" id="max_weight" value="{$max_weight}">
			<p class="preference_description">
				{l s='Maximum weight managed by this carrier. Set the value to "0", or leave this field blank to ignore. ' mod='mpshipping'}
			</p> 
		</div>