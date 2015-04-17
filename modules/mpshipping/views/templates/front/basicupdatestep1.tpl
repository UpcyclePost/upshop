		<div class="left full form-group">
			<label>{l s='Shipping Name' mod='mpshipping'}<sup>*</sup></label>
			{if $mp_shipping_id!=0}
				<input type="hidden" name="mpshipping_id" value="{$mp_shipping_id}" />
			{/if}
			<input type="text" name="shipping_name" id="shipping_name" class="form-control" value="{$mp_shipping_name}">
			<p class="preference_description">
				{l s='Carrier name displayed during checkout' mod='mpshipping'}
			</p>
		</div>
		<div class="left full form-group">
			<label>{l s='Transit time' mod='mpshipping'}<sup>*</sup></label>
			<input type="text" name="transit_time" id="transit_time" class="form-control" value="{$transit_delay}">
			<p class="preference_description">
				{l s='Estimated delivery time will be displayed during checkout.' mod='mpshipping'}
			</p> 
		</div>
		<div class="left full form-group">
			<label>{l s='Speed grade' mod='mpshipping'}<sup>*</sup></label>
			<input type="text" name="grade" id="grade" class="form-control" value="{$grade}">
			<p class="preference_description">
				{l s='Enter "0" for a longest shipping delay, or "9" for the shortest shipping delay.' mod='mpshipping'}
			</p> 
		</div>
				
		<div class="left full form-group">
			<label>{l s='Logo' mod='mpshipping'}</label>
			<input type="file" name="shipping_logo" id="shipping_logo" class="form-control" />
			<p class="preference_description">
				{l s='Image size not exceed 125*125' mod='mpshipping'}
			</p>
			<img id="testImg" style="display:none;" src="#" alt="" />
		</div>
		<div class="left full form-group">
			<label>{l s='Tracking URL' mod='mpshipping'}</label>
			<input type="text" name="tracking_url" id="tracking_url" class="form-control" value="{$tracking_url}">
			<p class="preference_description">
				{l s="Delivery tracking URL: Type '@' where the tracking number should appear. It will then be automatically replaced by the tracking number." mod='mpshipping'}
			</p> 
		</div>
		<!-- <input type="hidden" value="{$shipping_method}" name="shipping_method" /> -->
	