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
{include file="$tpl_dir./order-steps.tpl"}

{include file="$tpl_dir./errors.tpl"}

{$HOOK_ORDER_CONFIRMATION}
{$HOOK_PAYMENT_RETURN}
{if $is_guest}
	<p>{l s='Your order ID is:'} <span class="bold">{$id_order_formatted}</span> . {l s='Your order ID has been sent via email.'}</p>
    <p class="cart_navigation exclusive">
	<a class="button-exclusive btn btn-default" href="{$link->getPageLink('guest-tracking', true, NULL, "id_order={$reference_order|urlencode}&email={$email|urlencode}")|escape:'html':'UTF-8'}" title="{l s='Follow my order'}"><i class="icon-chevron-left"></i>{l s='Follow my order'}</a>
    </p>
{else}
<div id="order-detail-content" class="table_block table-responsive" style="padding:15px">
<table id="orderitems" class="table table-bordered footab" style="margin:15px;max-width:1110px;">
	<thead>
	<tr>
		<th class="cart_product first_item">Product</th>
		<th class="cart_description item">Seller</th>
		<th class="cart_unit item text-right">Unit Price</th>
		<th class="cart_quantity item text-center">Quantity</th>
		<th class="cart_total last_item text-right">Total Price</th>	
	</tr>
	</thead>
	<tbody>
{foreach $orderitems as $orderitem}	
	<tr id="" class="cart_item ">
		<td>{$orderitem['ordered_product_name']}</td>
		<td>{$orderitem['shop_name']}</td>
		<td>{displayWtPriceWithCurrency price=$orderitem['unit_price'] currency=$currency }</td>
		<td>{$orderitem['qty']}</td>
		<td>{displayWtPriceWithCurrency price=$orderitem['total_price'] currency=$currency }</td>
	</tr>
{/foreach}
	</tbody>
	<tfoot>
	<tr>
		<td colspan="4" class="text-right"><strong>Total Products</strong></td>
		<td>{displayWtPriceWithCurrency price=$orderheader[0]['total_products'] currency=$currency }</td>
	</tr>
	<tr>
		<td colspan="4" class="text-right"><strong>Total Shipping</strong></td>
		<td>{displayWtPriceWithCurrency price=$orderheader[0]['total_shipping'] currency=$currency }</td>
	</tr>
	<tr>
		<td colspan="4" class="text-right"><strong>Total</strong></td>
		<td>{displayWtPriceWithCurrency price=$orderheader[0]['total_paid'] currency=$currency }</td>
	</tr>
	</tfoot>
</table>
</div>
<p class="exclusive" style="">
	<a class="button button-medium btn btn-default" href="{$link->getPageLink('history', true)|escape:'html':'UTF-8'}" title="{l s='Go to your order history page'}">
		<i class="fa fa-fw fa-tasks"></i>&nbsp;{l s='View your order history'}
	</a>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a class="button button-medium btn btn-default" href="/shops" title="{l s='Browse Shops'}">
		<i class="fa up-shop-1"></i>&nbsp;{l s='Browse Shops'}
	</a>
</p>

{/if}
	</div>
