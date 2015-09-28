{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div class="login-panel">
	<div class="login-panel-header">
	<h1 class="">{l s='Order confirmation'}</h1>
	</div>

{assign var='current_step' value='payment_complete'}

{include file="$tpl_dir./errors.tpl"}

{$HOOK_ORDER_CONFIRMATION}
{$HOOK_PAYMENT_RETURN}
{if $is_guest}
	<p>{l s='Your order ID is:'} <span class="bold">{$id_order_formatted}</span> . {l s='Your order ID has been sent via email.'}</p>
    <p class="cart_navigation exclusive">
	<a class="button-exclusive btn btn-default" href="{$link->getPageLink('guest-tracking', true, NULL, "id_order={$reference_order|urlencode}&email={$email|urlencode}")|escape:'html':'UTF-8'}" title="{l s='Follow my order'}"><i class="icon-chevron-left"></i>{l s='Follow my order'}</a>
    </p>
{else}
<div id="order-detail-content" class="table_block table-responsive" style="padding:10px 15px 50px">
<table id="cart_summary" class="table table-bordered">
	<thead>
	<tr>
		<th class="cart_description first_item text-left">Product Name</th>
		<th class="cart_description item">Seller</th>
		<th class="cart_unit item text-right">Unit price</th>
		<th class="cart_quantity item text-center">Qty</th>
		<th class="cart_total last_item text-right">Total Price</th>	
	</tr>
	</thead>
	<tbody>
{foreach $orderitems as $orderitem}	
	{assign var='odd' value=$orderitem@iteration%2}
	<tr id="" class="cart_item {if $odd}odd{else}even{/if}">
		<td class="cart_description" data-title="Name">{$orderitem['ordered_product_name']}</td>
		<td class="cart_description" data-title="Seller">{$orderitem['shop_name']}</td>
		<td class="cart_unit" data-title="Unit price">{displayWtPriceWithCurrency price=$orderitem['unit_price'] currency=$currency }</td>
		<td class="cart_quantity text-center" data-title="Qty">{$orderitem['qty']}</td>
		<td class="cart_total" data-title="Total">{displayWtPriceWithCurrency price=$orderitem['total_price'] currency=$currency }</td>
	</tr>
{/foreach}
	</tbody>
	<tfoot>
	<tr class="cart_total_price">
		<td colspan="4" class="text-right"><strong>Total Products</strong></td>
		<td class="price" id="total_product">{displayWtPriceWithCurrency price=$orderheader[0]['total_products'] currency=$currency }</td>
	</tr>
	<tr class="cart_total_delivery">
		<td colspan="4" class="text-right"><strong>Total Shipping</strong></td>
		<td class="price" id="total_shipping">{displayWtPriceWithCurrency price=$orderheader[0]['total_shipping'] currency=$currency }</td>
	</tr>
	<tr class="cart_total_price">
		<td colspan="4" class="text-right total_price_container"><span>Total</span></td>
		<td class="price total_price_container" id="total_price_container">{displayWtPriceWithCurrency price=$orderheader[0]['total_paid'] currency=$currency }</td>
	</tr>
	</tfoot>
</table>
	<div class="order-confirmation-share">
		<ul>
		<li>
	<a class="button lnk_view btn btn-default" href="{$link->getPageLink('history', true)|escape:'html':'UTF-8'}" title="{l s='Go to your order history page'}">
		<span>{l s='Order history'}
		<i class="fa fa-fw fa-tasks"></i>
		</span>
	</a>
		</li>
		<li>
	<a class="button button-medium" href="/shops" title="{l s='Browse Shops'}">
		<span>{l s='Browse Shops'}
		<i class="fa up-shop-1"></i>
		</span>
	</a>
		</li>
		</ul>
	</div>	

</div>

	

{/if}
	</div>
</div>
<!-- Mixpanel Tracking -->
<script type="text/javascript">
	mixpanel.track('Order Confirmation', {
		'Order Id': '{$order->id}',
		'Order Reference': '{$order->reference}'
		});
</script>
<!-- End Mixpanel Tracking -->
