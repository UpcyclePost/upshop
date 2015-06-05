<!-- Block user information module NAV  -->
<div class="header_user_info">
	<div class="buttons hidden-xs hidden-sm">
		{if !$is_logged}
			<a href="/profile/login" class="btn btn-none"><i class="fa fa-sign-in fa-fw"></i> Sign In</a>
			<a href="/profile/register" class="btn btn-none"><i class="fa fa-sign-in fa-fw fa-rotate-270"></i> Sign Up</a>
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
						<span>Your Shop</span> <i class="fa up-shop-1"></i>
					</button>
					<ul class="dropdown-menu dropdown-menu-right" role="menu">
						<li><a href="{$account_dashboard|addslashes}"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a></li>
						<li class="divider"></li>
						<li><a href="/shop/module/marketplace/addproduct?shop={$id_shop}"><i class="fa fa-fw fa-plus"></i> Add Product</a></li>
						<li><a href="/shop/module/marketplace/marketplaceaccount?shop={$id_shop}&l=3"><i class="fa fa-fw fa-list"></i> Product List</a></li>
						<li><a href="/shop/module/marketplace/marketplaceaccount?shop={$id_shop}&l=4"><i class="fa fa-fw fa-tasks"></i> Orders</a></li>
						<li class="divider"></li>
						<li><a href="/shop/module/marketplace/marketplaceaccount?shop={$id_shop}&l=2&edit-profile=1"><i class="fa fa-fw fa-gears"></i> Shop Profile</a></li>
						<li><a href="{$link->getModuleLink('marketplace','shopstore',['shop'=>{$id_shop}])}"><i class="fa fa-fw fa-eye"></i> View Shop</a></li>
					</ul>
				</div>
			{/if}
			<div class="btn-group">
				<button type="button" class="btn btn-user dropdown-toggle" data-toggle="dropdown">
					<span>You</span> <i class="fa fa-user"></i>
				</button>
				<ul class="dropdown-menu dropdown-menu-right" role="menu">
					<li><a href="/shop/order-history"><i class="fa fa-fw fa-tags"></i> Purchases</a></li>
					<li><a href="/profile/view"><i class="fa fa-fw fa-user"></i> Profile</a></li>
					<li><a href="/profile/settings"><i class="fa fa-fw fa-gears"></i> Account</a></li>
					<li><a href="/profile/messages"><i class="fa fa-fw fa-envelope"></i> Messages</a></li>
					<li><a href="/profile/feed"><i class="fa fa-fw fa-rss"></i> Feed</a></li>
					<li class="divider"></li>
					<li><a href="/profile/logout"><i class="fa fa-fw fa-sign-out"></i> Sign Out</a></li>
				</ul>
			</div>
		{/if}

		<div class="pull-right text-center cart">
			<a style="margin: 0; padding: 0" href="/shop/quick-order"><i class="fa fa-fw fa-shopping-cart"></i><br>Cart{if $cart_qties > 0}<span class="items">{$cart_qties}</span>{/if}</a>
		</div>
	</div>
	<a class="slide-menu fa fa-bars hidden-lg hidden-md" href="#mobile-slide-menu"></a>
	<!-- <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='View my customer account' mod='blockuserinfo'}" class="account" rel="nofollow"><span>You</span></a> -->
</div>
<!-- /Block usmodule NAV -->
