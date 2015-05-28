<!-- Block user information module NAV  -->
<div class="header_user_info">
	{if $is_logged}
	<div class="buttons">
		<div class="btn-group">
			<button type="button" class="btn btn-user dropdown-toggle" data-toggle="dropdown">
				<span>You</span> <i class="fa fa-chevron-down"></i>
			</button>
			<ul class="dropdown-menu dropdown-menu-right" role="menu">
				{if $is_seller==1}
				<li><a href="{$account_dashboard|addslashes}"><i class="fa fa-fw fa-dashboard"></i> My Shop</a></li>
				<li class="divider"></li>
				{/if}
				<li><a href="http://test.upcyclepost.com/profile/view"><i class="fa fa-fw fa-user"></i> Profile</a></li>
				<li><a href="http://test.upcyclepost.com/profile/settings"><i class="fa fa-fw fa-gears"></i> Account</a></li>
				<li><a href="http://test.upcyclepost.com/profile/messages"><i class="fa fa-fw fa-envelope"></i> Messages</a></li>
				<li><a href="http://test.upcyclepost.com/profile/feed"><i class="fa fa-fw fa-rss"></i> Feed</a></li>
				<li class="divider"></li>
				<li><a href="http://test.upcyclepost.com/profile/logout"><i class="fa fa-fw fa-sign-out"></i> Log Out</a></li>
			</ul>
		</div>
	</div>
	{/if}
	<!-- <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='View my customer account' mod='blockuserinfo'}" class="account" rel="nofollow"><span>You</span></a> -->
</div>
<!-- /Block usmodule NAV -->
