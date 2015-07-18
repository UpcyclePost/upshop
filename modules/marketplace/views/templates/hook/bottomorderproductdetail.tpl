<table id="orderitems" class="table table-bordered">
	<thead>
	<tr>
		<th><label>{l s='Product Name -' mod='marketplace'}</label></th>
		<th>Quantity</th>
		<th>Unit Price</th>
		<th>Total Price</th>	
	</tr>
	</thead>
{foreach $order_info as $ord_info}
	<tr>
		<td>{$ord_info['product_name']|escape:'html':'UTF-8'}</td>
		<td>{$ord_info['product_quantity']|escape:'html':'UTF-8'}</td>
		<td>{$currency->prefix}{$ord_info['unit_price_tax_excl']|string_format:"%.2f"}{$currency->suffix}</td>
		<td>{$currency->prefix}{$ord_info['total_price_tax_excl']|string_format:"%.2f"}{$currency->suffix}</td>
	</tr>
{/foreach}
	<tfoot>
	<tr>
		<td colspan="3"><strong>Items</strong></td>
		<td>{displayWtPriceWithCurrency price=$dashboard[0]['total_products'] currency=$currency }</td>
	</tr>
	<tr>
		<td colspan="3"><strong>Shipping & Handling</strong></td>
		<td>{displayWtPriceWithCurrency price=$dashboard[0]['total_shipping'] currency=$currency }</td>
	</tr>
	<tr>
		<td colspan="3"><strong>Total</strong></td>
		<td>{displayWtPriceWithCurrency price=$dashboard[0]['total_paid'] currency=$currency }</td>
	</tr>
	</tfoot>
</table>

