{foreach $order_info as $ord_info}
	<div class="wk_ordered_product">
		<div class="wk_row">
			<label>{l s='Product Name -' mod='marketplace'}</label>
			<span>{$ord_info['product_name']|escape:'html':'UTF-8'}</span>
		</div>
		<div class="wk_row">
			<label>{l s='Quantity -' mod='marketplace'}</label>
			<span>{$ord_info['product_quantity']|escape:'html':'UTF-8'}</span>
		</div>
		<div class="wk_row">
			<label>{l s='Price -' mod='marketplace'}</label>
			<span>{$currency->prefix}{$ord_info['total_price_tax_incl']|string_format:"%.2f"}{$currency->suffix}</span>
		</div>
	</div>
{/foreach}