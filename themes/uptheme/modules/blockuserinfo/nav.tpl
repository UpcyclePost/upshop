<!-- Block user information module NAV  -->

<div class="header_user_info col-xs-2 col-sm-2 col-md-5 col-lg-4" style="padding:0;">
	<button class="hamburger-menu" id="hamburger" {if !$is_logged && $cart_qties==0}style="margin-left:80px;"{/if}>
        <i class="fa fa-bars"></i>
    </button>
	<div class="buttons hidden-xs hidden-sm">
		{if !$is_logged}
			<div class="signup">
			{if !$PS_CATALOG_MODE}
				{if $cart_qties > 0}
				<span class="cart text-center" style="float:right;">
					<a id="cart-link" style="margin: 0; padding: 0" href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/shop/quick-order"><i class="fa fa-fw fa-shopping-cart"></i><br>Cart{if $cart_qties > 0}<span id="header_cart_items" class="items">{$cart_qties}</span>{/if}</a>
				</span>
				{/if}
			{/if}

				<a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/profile/login" class="">Sign In</a>
				<a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/profile/register" class="button button-medium">Sign Up</a>
			</div>
		{/if}
		{if $is_logged}
			{if $is_seller == 1}
				<div class="btn-group">
					<button type="button" class="btn btn-user dropdown-toggle" data-toggle="dropdown">
						<span>Your Shop{if $m_number_orders > 0 } <font class="items">{$m_number_orders}</font>{/if}</span> <i class="fa up-shop-1"></i>
					</button>
					<ul class="dropdown-menu dropdown-menu-right" role="menu">
						<li><a href="{$account_dashboard|addslashes}"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a></li>
						<li class="divider"></li>
						<li><a href="{$add_product|addslashes}"><i class="fa fa-fw fa-plus"></i> Add Product</a></li>
						<li><a href="{$product_list|addslashes}"><i class="fa fa-fw fa-list"></i> Product List</a></li>
						<li><a href="{$my_order|addslashes}"><i class="fa fa-fw fa-tasks"></i> Orders{if $m_number_orders > 0 } <font class="items pull-right">{$m_number_orders}</font>{/if}</a></li>
						<li class="divider"></li>
						<li><a href="http://{$smarty.server.SERVER_NAME}/shops/my/customize"><i class="fa fa-fw fa-pencil"></i> Customize Shop</a></li>
						<li><a href="{$edit_profile|addslashes}"><i class="fa fa-fw fa-gears"></i> Shop Profile</a></li>
						<li><a href="{$my_shop|addslashes}"><i class="fa fa-fw fa-eye"></i> View Shop</a></li>
					</ul>
				</div>
			{/if}
			<div class="btn-group">
				<button type="button" class="btn btn-user dropdown-toggle" data-toggle="dropdown">
					<span>You</span> <i class="fa fa-user"></i>
				</button>
				<ul class="dropdown-menu dropdown-menu-right" role="menu">
					<li><a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/shop/order-history"><i class="fa fa-fw fa-tags"></i> Purchases</a></li>
					<li><a href="http://{$smarty.server.SERVER_NAME}/profile/view"><i class="fa fa-fw fa-user"></i> Profile</a></li>
					<li><a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/profile/settings"><i class="fa fa-fw fa-gears"></i> Account</a></li>
					<li><a href="http://{$smarty.server.SERVER_NAME}/profile/messages">
						<i class="fa fa-fw fa-envelope" {if $m_number_messages > 0}style="color:orange;"{/if}></i> Messages {if $m_number_messages > 0}({$m_number_messages}){/if}</a></li>
					<li><a href="http://{$smarty.server.SERVER_NAME}/profile/feed"><i class="fa fa-fw fa-rss"></i> Feed</a></li>
					<li class="divider"></li>
					<li><a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/profile/logout"><i class="fa fa-fw fa-sign-out"></i> Sign Out</a></li>
				</ul>
			</div>

			{if !$PS_CATALOG_MODE}
				{if $cart_qties > 0}
				<div class="cart pull-right text-center">
					<a id="cart-link" style="margin: 0; padding: 0" href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/shop/quick-order"><i class="fa fa-fw fa-shopping-cart"></i><br>Cart{if $cart_qties > 0}<span id="header_cart_items" class="items">{$cart_qties}</span>{/if}</a>
				</div>
				{/if}
			{/if}
		{/if}
	</div>
	<!-- <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='View my customer account' mod='blockuserinfo'}" class="account" rel="nofollow"><span>You</span></a> -->
</div>
<!-- /Block usmodule NAV -->
