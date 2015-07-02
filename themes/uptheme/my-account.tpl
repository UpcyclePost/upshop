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
{capture name=path}{l s='My Account'}{/capture}

<div class="col-xs-12 col-sm-6 col-lg-5" style="padding-right:20px;">
<br>
<div class="login-panel">
    <div class="login-panel-header">  
    <h1 class="">{l s='You'}</h1>
    </div>
        {if isset($account_created)}
        <p class="alert alert-success">
                {l s='Your account has been created.'}
        </p>
        {/if}

        <h5 style="padding:15px;">{l s='Manage your personal information and view purchases.'}</h5>
		<ul class="myaccount-link-list">
            {if $returnAllowed}
               <li><a href="{$link->getPageLink('order-follow', true)|escape:'html':'UTF-8'}" title="{l s='Merchandise returns'}"><i class="icon-refresh"></i><span>{l s='My merchandise returns'}</span></a></li>
            {/if}
            <li><a href="{$link->getPageLink('history', true)|escape:'html':'UTF-8'}" title="{l s='Orders'}"><i class="fa fa-fw fa-tags"></i><span>{l s='My Purchases'}</span></a></li>
			<li><a href="http://{$smarty.server.SERVER_NAME}/profile/view"><i class="fa fa-fw fa-user"></i><span>Profile</span></a></li>
			<li><a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/profile/settings"><i class="fa fa-fw fa-gears"></i><span>Account</span></a></li>
			<li><a href="http://{$smarty.server.SERVER_NAME}/profile/messages">
				<i class="fa fa-fw fa-envelope" {if $m_number_messages > 0}style="color:orange;"{/if}></i> <span>Messages {if $m_number_messages > 0}( {$m_number_messages} new){/if}</span></a></li>
			<li><a href="http://{$smarty.server.SERVER_NAME}/profile/feed"><i class="fa fa-fw fa-rss"></i> <span>Feed</span></a></li>

            <li><a href="{$link->getPageLink('addresses', true)|escape:'html':'UTF-8'}" title="{l s='Addresses'}"><i class="icon-building"></i><span>{l s='My addresses'}</span></a></li>


{if $voucherAllowed || isset($HOOK_CUSTOMER_ACCOUNT) && $HOOK_CUSTOMER_ACCOUNT !=''}
{if $voucherAllowed}<li><a href="{$link->getPageLink('discount', true)|escape:'html':'UTF-8'}" title="{l s='Vouchers'}"><i class="icon-barcode"></i><span>{l s='My vouchers'}</span></a></li>{/if}
</div>
<!-- leave this div alone to we get two columns -->
</div>
<div class="col-xs-12 col-sm-6 col-lg-5">
<br>
<div class="login-panel">
    <div class="login-panel-header">  
    <h1 class="">{l s='Your Shop'}</h1>
    </div>
 <h5 style="padding:15px;">{l s='Manage your shop and view orders received.'}</h5>
<ul class="myaccount-link-list">
{if $is_seller==1}
	<li class="lnk_wishlist">
		<a title="My Dashboard" href="{$account_dashboard|addslashes}">
			<i class="icon-dashboard"></i>
			<span>{l s='My Dashboard' mod='marketplace'}</span>
		</a>
	</li>
	<li class="lnk_wishlist">
		<a title="Add product" href="{$add_product|addslashes}">
			<i class="icon-plus"></i>
			<span>{l s='Add Product' mod='marketplace'}</span>
		</a>
	</li>
	<li class="lnk_wishlist">
		<a title="Product List" href="{$product_list|addslashes}">
			<i class="icon-list"></i>
			<span>{l s='Product List' mod='marketplace'}</span>
		</a>
	</li>
	<li class="lnk_wishlist">
		<a title="Shipping" href="{$sellershippinglist}">
			<i class="icon-truck"></i>
			<span>{l s='Shipping Profiles' mod='mpshipping'}</span>
		</a>
	</li>
	<li class="lnk_wishlist">
		<a title="My orders received" href="{$my_order|addslashes}">
			<i class="fa fa-fw fa-tasks"{if $m_number_orders > 0} style="color:orange;"{/if}></i>
			<span>{l s='My Orders Received' mod='marketplace'}{if $m_number_orders > 0 } <font class="items"> ( {$m_number_orders} new ) </font>{/if}</span></a>
	</li>
	<li class="lnk_wishlist">
		<a href="http://{$smarty.server.SERVER_NAME}/shops/my/customize"><i class="fa fa-fw fa-pencil"></i> 
			<span>Customize Shop</span>
		</a>
	</li>
	<li class="lnk_wishlist">
		<a href="{$edit_profile|addslashes}"><i class="fa fa-fw fa-gears"></i> 
			<span>Shop Profile</span>
		</a>
	</li>
	<li class="lnk_wishlist">
		<a href="{$link_store|escape:'html':'UTF-8'}"><i class="fa fa-fw fa-eye"></i> 
			<span>View Shop</span>
		</a>
	</li>
	
{else if $is_seller==0}
	<li class="lnk_wishlist">
		<a href="">
			<i class="icon-envelope"></i>
			<span>{l s='Your Request has been already sent to Admin.Wait For Admin Approval' mod='marketplace'}</span>
		</a>
	</li>	
{else if $is_seller==-1}
	<li class="lnk_wishlist">
		<a title="{l s='Click Here to create a shop' mod='marketplace'}" href="{$new_link1}">
			<i class="icon-mail-reply-all"></i>
			<span>{l s='Click Here to create a shop' mod='marketplace'}</span>
		</a>
	</li>
{/if}

</ul>
{/if}
</div>
</div>

<div style="clear:left;">
<ul class="footer_links clearfix">
<li><a class="btn btn-default button button-medium" href="{$base_dir}" title="{l s='Home'}"><span><i class="icon-chevron-left"></i> {l s='Home'}</span></a></li>
</div>
</ul>