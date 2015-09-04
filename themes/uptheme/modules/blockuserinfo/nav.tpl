<!-- Block user information module NAV  -->

<div class="header_user_info col-sm-4 col-xs-4 col-md-6">
	<a id="mobile-menu" class="mobile-menu fa fa-bars visible-lg visible-md pull-right"></a>
	<a class="slide-menu fa fa-bars hidden-lg hidden-md pull-right" href="#mobile-slide-menu"></a>
	<div class="buttons hidden-xs hidden-sm">
		{if !$is_logged}
			<a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/profile/login" class="">Sign In</a>
			<a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/profile/register" class="button button-medium">Sign Up</a>
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
		{/if}
		{if !$PS_CATALOG_MODE}
		<div class="cart pull-right text-center">
			<a id="cart-link" style="margin: 0; padding: 0" href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/shop/quick-order"><i class="fa fa-fw fa-shopping-cart"></i><br>Cart{if $cart_qties > 0}<span id="header_cart_items" class="items">{$cart_qties}</span>{/if}</a>
		</div>
		{/if}
	</div>
	<!-- <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='View my customer account' mod='blockuserinfo'}" class="account" rel="nofollow"><span>You</span></a> -->
</div>
<!-- Begin Mobile Side Menu -->
<nav id="mobile-slide-menu" class="hidden">
	<ul>
		<li><a href="/"><i class="fa fa-home fa-fw"></i> Home</a></li>
		{if $is_logged && $is_seller ==0}
		<li><a href="http://{$smarty.server.SERVER_NAME}/profile/edit"><i class="fa fa-fw fa-shopping-cart"></i>Create a Shop</a></li>
		{/if}
		<li><a href="http://{$smarty.server.SERVER_NAME}/gallery"><i class="fa fa-camera fa-fw"></i> Browse</a>
			<ul>
				<li><a href="http://{$smarty.server.SERVER_NAME}/shops"><i class="fa fa-fw fa-tags"></i> Shops</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery"><i class="fa fa-fw fa-lightbulb-o"></i> Products</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/search/users"><i class="fa fa-fw fa-users"></i> Users</a></li>
			</ul>
		</li>
		{if $is_logged}
			{if $is_seller == 1}
            	<li><a href="{$my_shop|addslashes}"><i class="fa fa-fw fa-shopping-cart"></i>Your Shop</a>
	                <ul>
						<li><a href="{$account_dashboard|addslashes}"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a></li>
						<li class="divider"></li>
						<li><a href="{$add_product|addslashes}"><i class="fa fa-fw fa-plus"></i> Add Product</a></li>
						<li><a href="{$product_list|addslashes}"><i class="fa fa-fw fa-list"></i> Product List</a></li>
						<li><a href="{$my_order|addslashes}">
							<i class="fa fa-fw fa-tasks" {if $m_number_orders > 0}style="color:orange;"{/if}></i> Orders {if $m_number_orders > 0 }({$m_number_orders}){/if}</a>
						</li>
						<li class="divider"></li>
						<li><a href="http://{$smarty.server.SERVER_NAME}/shops/my/customize"><i class="fa fa-fw fa-pencil"></i> Customize Shop</a></li>
						<li><a href="{$edit_profile|addslashes}"><i class="fa fa-fw fa-gears"></i> Shop Profile</a></li>
						<li><a href="{$my_shop|addslashes}"><i class="fa fa-fw fa-eye"></i> View Shop</a></li>
					</ul>
			{/if}

            <li><a href="http://{$smarty.server.SERVER_NAME}/profile/view"><i class="fa fa-user"></i>You</a>
                <ul>
                    <li><a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/shop/order-history"><i class="fa fa-fw fa-tags"></i> Purchases</a></li>
                    <li class="divider"></li>
            	    <li><a href="http://{$smarty.server.SERVER_NAME}/profile/view"><i class="fa fa-user"></i>Profile</a></li>
                    <li><a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/profile/settings"><i class="fa fa-fw fa-gears"></i> Account</a></li>
                    <li><a href="http://{$smarty.server.SERVER_NAME}/profile/messages">
						<i class="fa fa-fw fa-envelope" {if $m_number_messages > 0}style="color:orange;"{/if}></i> Messages {if $m_number_messages > 0}({$m_number_messages}){/if}</a>
					</li>
                    <li><a href="http://{$smarty.server.SERVER_NAME}/profile/feed"><i class="fa fa-fw fa-rss"></i> Feed</a></li>
                </ul>
            </li>
            <li class="divider"></li>
		{/if}
		<li><a href="http://{$smarty.server.SERVER_NAME}/gallery"><i class="fa fa-search fa-fw"></i> Find Inspiration</a>
			<ul>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/art">Art</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/automotive">Automotive</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/construction">Construction</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/crafts">Crafts</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/electronics">Electronics</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/fashion">Fashion</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/furniture">Furniture</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/glass">Glass</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/hardware">Hardware</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/holidays">Holidays</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/home">Home</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/jewelry">Jewelry</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/metal">Metal</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/musical">Musical</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/office">Office</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/outdoors">Outdoors</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/paper">Paper</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/pets">Pets</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/plastic">Plastic</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/sporting-goods">Sporting Goods</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/toys">Toys</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/vintage">Vintage</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/wood">Wood</a></li>
				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/yard">Yard</a></li>
			</ul>
		</li>
		<li><a href="http://{$smarty.server.SERVER_NAME}/post/idea"><i class="fa fa-camera fa-fw"></i> Post Ideas</a></li>
		<li><a href="http://{$smarty.server.SERVER_NAME}/blog"><i class="fa fa-rss fa-fw"></i> Blog</a></li>
		{if $is_logged}
		<li><a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/profile/logout"><i class="fa fa-sign-out fa-fw"></i> Sign Out</a></li>		
		{else}
		<li><a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/profile/login"><i class="fa fa-sign-in fa-fw"></i> Sign In</a></li>
		<li><a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/profile/register"><i class="fa fa-sign-in fa-rotate-270 fa-fw"></i> Sign Up</a></li>
		{/if}
	</ul>
</nav>
<!-- End Mobile Side Menu -->
<!-- Begin Mobile Footer -->
<nav class="mobile-footer mm-fixed-bottom hidden-lg hidden-md">
    <ul class="clearfix">
        {if !$is_logged}
            <li><a href="http://{$smarty.server.SERVER_NAME}/profile/edit"><i class="fa fa-fw fa-shopping-cart"></i><span>Create a Shop</span></a></li>
            <li><a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/profile/login"><i class="fa fa-sign-in"></i><span>Sign In</span></a></li>
            <li><a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/profile/register"><i class="fa fa-sign-in fa-fw fa-rotate-270"></i><span>Sign Up</span></a></li>
        {else}
            {if $is_seller == 1}
                <li><a href="{$my_shop|addslashes}"><i class="fa fa-fw fa-shopping-cart"></i><span>View Shop</span></a>
                </li>
            {else}
                <li><a href="http://{$smarty.server.SERVER_NAME}/profile/edit"><i class="fa fa-fw fa-shopping-cart"></i><span>Create a Shop</span></a></li>
            {/if}
            <li><a href="http://{$smarty.server.SERVER_NAME}/profile/view"><i class="fa fa-user"></i><span>Profile</span></a></li>
            <li><a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/profile/logout"><i class="fa fa-sign-out fa-fw"></i><span>Sign Out</span></a></li>
        {/if}
        <li><a href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/shop/quick-order"><i class="fa fa-fw fa-shopping-cart"></i><span>Cart</span></a></li>
        <li>&nbsp;</li>
    </ul>
</nav>
<!-- End Mobile Footer -->
<!-- /Block usmodule NAV -->
