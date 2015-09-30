<div class="wk_order_customer_status">
<h2>{l s='Order Status' mod='marketplace'}</h2>
<table width="100%">
	<tbody>
	<tr>
		<td>
			<div class="">
				<label>{l s='Order Status' mod='marketplace'}</label>
			</div>
		</td>
		<td>
			<div class="wk_row_right">	
				<span>{$dashboard[0]['order_status']|escape:'html':'UTF-8'}</span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="">
				<label>{l s='Shipping Address' mod='marketplace'}</label>
			</div>
		</td>
		<td>
			<div class="wk_row_right">	
				<span>{$dashboard[0]['address1']|escape:'html':'UTF-8'}</span><br>
				<span>{$dashboard[0]['city']|escape:'html':'UTF-8'}</span><br>
				<span>{$dashboard_state|escape:'html':'UTF-8'}</span><br>
				<span>{$dashboard[0]['postcode']|escape:'html':'UTF-8'}</span><br>
				<span>{$dashboard[0]['country']|escape:'html':'UTF-8'}</span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="">
				<label>{l s='Payment Mode' mod='marketplace'}</label>
			</div>
		</td>
		<td>
			<div class="wk_row_right">		
				<span>{$dashboard[0]['payment_mode']|escape:'html':'UTF-8'}</span>
			</div>
		</td>
	</tr>
	</tbody>
</table>
</div>