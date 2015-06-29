<!-- Block user information module NAV  -->
<div class="header_user_info">
	<div class="buttons hidden-xs hidden-sm">
		{if !$is_logged}
			<a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/profile/login" class="btn btn-none"><i class="fa fa-sign-in fa-fw"></i> Sign In</a>
			<a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/profile/register" class="btn btn-none"><i class="fa fa-sign-in fa-fw fa-rotate-270"></i> Sign Up</a>
		{/if}

		<div class="btn-group">
			<button type="button" class="btn btn-user dropdown-toggle" data-toggle="dropdown">
				<span>Browse</span> <i class="fa fa-camera"></i>
			</button>
			<ul class="dropdown-menu dropdown-menu-right" role="menu">
				<li><a href="/shops"><i class="fa up-shop-1"></i> Shops</a></li>
				<li><a href="/gallery"><i class="fa fa-fw fa-lightbulb-o"></i> Ideas</a></li>
				<li><a href="/search/users"><i class="fa fa-fw fa-users"></i> Users</a></li>
			</ul>
		</div>
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
		{/if}
		{if !$PS_CATALOG_MODE}
		<div class="pull-right text-center cart">
			<a style="margin: 0; padding: 0" href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/shop/quick-order"><i class="fa fa-fw fa-shopping-cart"></i><br>Cart{if $cart_qties > 0}<span class="items">{$cart_qties}</span>{/if}</a>
		</div>
		{/if}
	</div>
	<a class="slide-menu fa fa-bars hidden-lg hidden-md" href="#mobile-slide-menu"></a>
	<!-- <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='View my customer account' mod='blockuserinfo'}" class="account" rel="nofollow"><span>You</span></a> -->
</div>
<!-- /Block usmodule NAV -->
