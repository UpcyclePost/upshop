<div class="wk_order_customer_status">
<h2>{l s='Order Status' mod='marketplace'}</h2>
<div class="wk_row">
	<label>{l s='Order Status -' mod='marketplace'}</label>
	<span>{$dashboard[0]['order_status']|escape:'html':'UTF-8'}</span>
</div>
<div class="wk_row">
	<label>{l s='Shipping Address -' mod='marketplace'}</label>
	<span>{$dashboard[0]['address1']|escape:'html':'UTF-8'}</span>
</div>
<div class="wk_row">
	<label>{l s='Postal Code -' mod='marketplace'}</label>
	<span>{$dashboard[0]['postcode']|escape:'html':'UTF-8'}</span>
</div>
<div class="wk_row">
	<label>{l s='City -' mod='marketplace'}</label>
	<span>{$dashboard[0]['city']|escape:'html':'UTF-8'}</span>
</div>
<div class="wk_row">
	<label>{l s='State -' mod='marketplace'}</label>
	<span>{$dashboard_state|escape:'html':'UTF-8'}</span>
</div>
<div class="wk_row">
	<label>{l s='Country -' mod='marketplace'}</label>
	<span>{$dashboard[0]['country']|escape:'html':'UTF-8'}</span>
</div>
<div class="wk_row">
	<label>{l s='Payment Mode -' mod='marketplace'}</label>
	<span>{$dashboard[0]['payment_mode']|escape:'html':'UTF-8'}</span>
</div>
</div>