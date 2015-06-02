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
{if !isset($content_only) || !$content_only}
					</div><!-- #center_column -->
					{if isset($right_column_size) && !empty($right_column_size)}
						<div id="right_column" class="col-xs-12 col-sm-{$right_column_size|intval} column">{$HOOK_RIGHT_COLUMN}</div>
					{/if}
					</div><!-- .row -->
				</div><!-- #columns -->
			</div><!-- .columns-container -->
			{if isset($HOOK_FOOTER)}
				<!-- Footer -->
				<div class="footer-container">
					<footer id="footer"  class="container">
						<div class="row">{$HOOK_FOOTER}</div>
					</footer>
				</div><!-- #footer -->
			{/if}
		</div><!-- #page -->
{/if}

<nav id="mobile-slide-menu" class="hidden">
	<ul>
		<li><a href="/"><i class="fa fa-home fa-fw"></i> Home</a></li>
		<li><a href="/post/idea"><i class="fa fa-camera fa-fw"></i> Post Ideas</a></li>
		<li><a href="/gallery"><i class="fa fa-search fa-fw"></i> Find Inspiration</a>
			<ul>
				<li><a href="/gallery/art">Art</a></li>
				<li><a href="/gallery/automotive">Automotive</a></li>
				<li><a href="/gallery/construction">Construction</a></li>
				<li><a href="/gallery/crafts">Crafts</a></li>
				<li><a href="/gallery/electronics">Electronics</a></li>
				<li><a href="/gallery/fashion">Fashion</a></li>
				<li><a href="/gallery/furniture">Furniture</a></li>
				<li><a href="/gallery/glass">Glass</a></li>
				<li><a href="/gallery/hardware">Hardware</a></li>
				<li><a href="/gallery/holidays">Holidays</a></li>
				<li><a href="/gallery/home">Home</a></li>
				<li><a href="/gallery/jewelry">Jewelry</a></li>
				<li><a href="/gallery/metal">Metal</a></li>
				<li><a href="/gallery/musical">Musical</a></li>
				<li><a href="/gallery/office">Office</a></li>
				<li><a href="/gallery/outdoors">Outdoors</a></li>
				<li><a href="/gallery/paper">Paper</a></li>
				<li><a href="/gallery/pets">Pets</a></li>
				<li><a href="/gallery/plastic">Plastic</a></li>
				<li><a href="/gallery/sporting-goods">Sporting Goods</a></li>
				<li><a href="/gallery/toys">Toys</a></li>
				<li><a href="/gallery/vintage">Vintage</a></li>
				<li><a href="/gallery/wood">Wood</a></li>
				<li><a href="/gallery/yard">Yard</a></li>
			</ul>
		</li>
		<li><a href="/gallery"><i class="fa fa-camera fa-fw"></i> Browse</a>
			<ul>
				<li><a href="/gallery"><i class="fa fa-fw fa-lightbulb-o"></i> Ideas</a></li>
				<li><a href="/shops"><i class="fa fa-fw fa-tags"></i> Shops</a></li>
				<li><a href="/search/users"><i class="fa fa-fw fa-users"></i> Users</a></li>
			</ul>
		</li>
		<li><a href="/profile/login"><i class="fa fa-sign-in fa-fw"></i> Login</a></li>
		<li><a href="/profile/register"><i class="fa fa-sign-in fa-rotate-270 fa-fw"></i> Sign Up</a></li>
		<li><a href="/blog"><i class="fa fa-rss fa-fw"></i> Blog</a></li>
	</ul>
</nav>


{include file="$tpl_dir./global.tpl"}
	<!-- Horizontal Dropdown Menu -->
	<script src="{if Tools::usingSecureMode()}{$base_dir_ssl}{else}{$base_dir}{/if}themes/uptheme/js/libraries/horizontal-menu/cbpHorizontalMenu.js"></script>
	<!-- Mobile Slide Menu -->
	<script src="{if Tools::usingSecureMode()}{$base_dir_ssl}{else}{$base_dir}{/if}themes/uptheme/js/libraries/mobile-slide-menu/jquery.mmenu.min.js"></script>

	<script src="{if Tools::usingSecureMode()}{$base_dir_ssl}{else}{$base_dir}{/if}themes/uptheme/js/up/site.js"></script>
	</body>
</html>