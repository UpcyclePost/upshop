<h1 class="page-heading">{l s='Marketplace Account' mod='marketplace'}</h1>
<p class="info-account">{l s='View your shop.' mod='marketplace'}</p>
{if $is_seller==1}
        <li class="lnk_wishlist">
                <a title="View Shop" href="{$link_store|addslashes}">
                        <i class="icon-shopping-cart"></i>
                        <span>{l s='View Shop' mod='marketplace'}</span>
                </a>
        </li>
        <li class="lnk_wishlist">
                <a title="Seller Profile" href="{$seller_profile|addslashes}">
                        <i class="icon-file"></i>
                        <span>{l s='View Seller Profile' mod='marketplace'}</span>
                </a>
        </li>
        <li class="lnk_wishlist">
        <!--
	<li class="lnk_wishlist">
                <a title="View Collection" href="{$link_collection|addslashes}">
                        <i class="icon-tags"></i>
                        <span>{l s='View Collection' mod='marketplace'}</span>
                </a>
        </li>
	-->
</div>
{/if}

{if $is_seller==1}
<div class="col-xs-12 col-sm-6 col-lg-4">
<ul class="myaccount-link-list">
<br>
<h1 class="page-heading">{l s='Marketplace Account' mod='marketplace'}</h1>
<p class="info-account">{l s='Manage your shop.' mod='marketplace'}</p>
	<li class="lnk_wishlist">
		<a title="Account Dashboard" href="{$account_dashboard|addslashes}">
			<i class="icon-dashboard"></i>
			<span>{l s='Account Dashboard' mod='marketplace'}</span>
		</a>
	</li>
	<li class="lnk_wishlist">
		<a title="Edit Profile" href="{$edit_profile|addslashes}">
			<i class="icon-pencil"></i>
			<span>{l s='Edit Seller Profile' mod='marketplace'}</span>
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
		<a title="My order" href="{$my_order|addslashes}">
			<i class="icon-gift"></i>
			<span>{l s='My orders' mod='marketplace'}</span>
		</a>
	</li>
	<li class="lnk_wishlist">
		<a title="Payment Detail" href="{$payment_details|addslashes}">
			<i class="icon-money"></i>
			<span>{l s='Payment Detail' mod='marketplace'}</span>
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
		<a title="{l s='Click Here for Seller Request' mod='marketplace'}" href="{$new_link1}">
			<i class="icon-mail-reply-all"></i>
			<span>{l s='Click Here for Seller Request' mod='marketplace'}</span>
		</a>
	</li>
{/if}
