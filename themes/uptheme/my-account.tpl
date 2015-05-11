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
{capture name=path}{l s='My account'}{/capture}
<div class="col-xs-12 col-sm-6 col-lg-4">
<br>
    <h1 class="page-heading">{l s='My personal account'}</h1>
        {if isset($account_created)}
        <p class="alert alert-success">
                {l s='Your account has been created.'}
        </p>
        {/if}

        <p class="info-account">{l s='Manage personal information and orders.'}</p>
		<ul class="myaccount-link-list">
            {if $returnAllowed}
               <li><a href="{$link->getPageLink('order-follow', true)|escape:'html':'UTF-8'}" title="{l s='Merchandise returns'}"><i class="icon-refresh"></i><span>{l s='My merchandise returns'}</span></a></li>
            {/if}
            <li><a href="{$link->getPageLink('history', true)|escape:'html':'UTF-8'}" title="{l s='Orders'}"><i class="icon-list-ol"></i><span>{l s='My Orders placed'}</span></a></li>
            <li><a href="{$link->getPageLink('addresses', true)|escape:'html':'UTF-8'}" title="{l s='Addresses'}"><i class="icon-building"></i><span>{l s='My addresses'}</span></a></li>
{if $voucherAllowed || isset($HOOK_CUSTOMER_ACCOUNT) && $HOOK_CUSTOMER_ACCOUNT !=''}
{if $voucherAllowed}<li><a href="{$link->getPageLink('discount', true)|escape:'html':'UTF-8'}" title="{l s='Vouchers'}"><i class="icon-barcode"></i><span>{l s='My vouchers'}</span></a></li>{/if}
{$HOOK_CUSTOMER_ACCOUNT}
</ul>
{/if}
</div>
<div style="clear:left;">
<ul class="footer_links clearfix">
<li><a class="btn btn-default button button-small" href="{$base_dir}" title="{l s='Home'}"><span><i class="icon-chevron-left"></i> {l s='Home'}</span></a></li>
</div>
</ul>