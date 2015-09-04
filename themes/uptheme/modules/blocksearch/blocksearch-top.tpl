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
<div class="col-xs-6 col-md-5 search-container">
<form class="search-form form-inline" method="post" action="http://{$smarty.server.SERVER_NAME}/gallery">
<input type="search" name="term" class="form-control search" placeholder="Find Inspiration">
<button type="submit" class="search-icon"><img src="http://{$smarty.server.SERVER_NAME}/img/icons/search-icon.png" alt="Search"></button>
</form>
</div>	
{else}
<!-- Block search module TOP -->

<div class="col-xs-5 col-md-4 search-container">
<form class="search-form form-inline" method="post" action="http://{$smarty.server.SERVER_NAME}/gallery">
<input type="search" name="term" class="form-control search" placeholder="Search the world's largest upcycle hand-crafted community">
<button type="submit" class="search-icon"><img src="http://{$smarty.server.SERVER_NAME}/img/icons/search-icon.png" alt="Search"></button>
</form>
</div>	
<!-- /Block search module TOP -->
{/if}
