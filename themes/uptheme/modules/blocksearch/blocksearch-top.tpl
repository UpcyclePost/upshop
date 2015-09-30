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
<!-- block search mobile -->
{if isset($hook_mobile)}
{else}
<!-- Block search module TOP -->

<div class="col-xs-8 col-sm-6 col-md-4 col-lg-5 search-container hidden-xs" style="padding:5px 0">
<form class="search-form form-inline" method="post" action="http://{$smarty.server.SERVER_NAME}/gallery">
<input type="search" name="term" class="form-control search" placeholder="Search the world's largest upcycle hand-crafted community">
</form>
</div>	
<!-- /Block search module TOP -->
{/if}
