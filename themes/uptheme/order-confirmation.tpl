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

{capture name=path}{l s='Order confirmation'}{/capture}
<div class="dashboard_content login-panel">
	<div class="login-panel-header">
	<h1 class="">{l s='Order confirmation'}</h1>
	</div>
	<div class="wk_right_col">

{assign var='current_step' value='payment'}
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

<table id="orderitems" class="table table-bordered">
	<thead>
	<tr>
		<th>Product</th>
		<th>Seller</th>
		<th>Quantity</th>
		<th>Unit Price</th>
		<th>Total Price</th>	
	</tr>
	</thead>
{foreach $orderitems as $orderitem}	
	<tr>
		<td>{$orderitem['ordered_product_name']}</td>
		<td>{$orderitem['shop_name']}</td>
		<td>{$orderitem['qty']}</td>
		<td>{displayWtPriceWithCurrency price=$orderitem['unit_price'] currency=$currency }</td>
		<td>{displayWtPriceWithCurrency price=$orderitem['total_price'] currency=$currency }</td>
	</tr>
{/foreach}
	<tfoot>
	<tr>
		<td colspan="4"><strong>Items</strong></td>
		<td>{displayWtPriceWithCurrency price=$orderheader[0]['total_products'] currency=$currency }</td>
	</tr>
	<tr>
		<td colspan="4"><strong>Shipping & Handling</strong></td>
		<td>{displayWtPriceWithCurrency price=$orderheader[0]['total_shipping'] currency=$currency }</td>
	</tr>
	<tr>
		<td colspan="4"><strong>Total</strong></td>
		<td>{displayWtPriceWithCurrency price=$orderheader[0]['total_paid'] currency=$currency }</td>
	</tr>
	</tfoot>
</table>

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
</div>