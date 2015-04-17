<form role="form" id="step_carrier_general" class="defaultForm form-horizontal" enctype="multipart/form-data" method="post" action="{$mpshippingprocess_link_step1}">
	<div class="form-group">
		<label class="control-label required">{l s='Shipping Name' mod='mpshipping'}</label>
		{if $mp_shipping_id!=0}
			<input type="hidden" name="mpshipping_id" value="{$mp_shipping_id}">
		{/if}
		<input type="text" name="shipping_name" id="shipping_name" class="form-control" value="{$mp_shipping_name}">
		<p class="help-block">
			{l s='Carrier name displayed during checkout' mod='mpshipping'}
		</p>
	</div>
	<div class="form-group">
		<label class="control-label required">{l s='Transit time' mod='mpshipping'}</label>
		<input type="text" name="transit_time" id="transit_time" class="form-control" value="{$transit_delay}">
		<p class="help-block">
			{l s='Estimated delivery time will be displayed during checkout.' mod='mpshipping'}
		</p> 
	</div>
	<div class="form-group">
		<label class="control-label">{l s='Speed grade' mod='mpshipping'}</label>
		<input type="text" name="grade" id="grade" class="form-control" value="{$grade}">
		<p class="help-block">
			{l s='Enter "0" for a longest shipping delay, or "9" for the shortest shipping delay.' mod='mpshipping'}
		</p> 
	</div>
			
	<div class="form-group">
		<label class="control-label">{l s='Logo' mod='mpshipping'}</label>
		<input type="file" name="shipping_logo" id="shipping_logo" class="form-control"/>
		<p class="help-block">
			{l s='Image size not exceed 125*125' mod='mpshipping'}
		</p>
		<img style="display:none;" id="testImg" src="#" alt="" />
	</div>
	<div class="form-group">
		<label class="control-label">{l s='Tracking URL' mod='mpshipping'}</label>
		<input type="text" name="tracking_url" id="tracking_url" class="form-control" value="{$tracking_url}">
		<p class="help-block">
			{l s="Delivery tracking URL: Type '@' where the tracking number should appear. It will then be automatically replaced by the tracking number." mod='mpshipping'}
		</p> 
	</div>
	
	<div class="form-group">
		<label class="control-label">{l s='Shipping method' mod='mpshipping'}</label>
		<br/>
		<input id="billing_price" type="radio" value="2" name="shipping_method"  {if $shipping_method==2}checked="checked"{/if}>
		<label class="t" for="billing_price">{l s='According to total price' mod='mpshipping'}</label>
		<br/>
		<input id="billing_weight" type="radio" value="1" name="shipping_method" {if $shipping_method==1}checked="checked"{/if}>
		<label class="t" for="billing_weight">{l s='According to total weight' mod='mpshipping'}</label>
		<br />
	</div>
</form>