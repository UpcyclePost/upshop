<div class="wk_order_customer_status">
<h2>{l s='Customer Detail' mod='marketplace'}</h2>
<div class="wk_row">
	<label>{l s='Name -' mod='marketplace'}</label>
	<span>{$dashboard[0]['name']|escape:'html':'UTF-8'}</span>
</div>
<div class="wk_row">
	<label>{l s='Address -' mod='marketplace'}</label>
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
	<label>{l s='Contact Number -' mod='marketplace'}</label>
	<span>
		{if $dashboard[0]['mobile'] != ""}
			{$dashboard[0]['mobile']|escape:'html':'UTF-8'}
		{else}
			{$dashboard[0]['phone']|escape:'html':'UTF-8'}
		{/if}
	</span>
</div>
<div class="wk_row">
	<label>{l s='Email Address -' mod='marketplace'}</label>
	<span>{$dashboard[0]['email']|escape:'html':'UTF-8'}</span>
</div>
</div>