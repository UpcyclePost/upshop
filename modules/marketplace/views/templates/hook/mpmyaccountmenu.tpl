<!-- leave this div alone to we get two columns -->
</div>
<div class="col-xs-12 col-sm-6 col-lg-4" style="border-left: solid #d6d4d4 2px;margin-left:20px;padding-left:20px;">
<ul class="myaccount-link-list">
<br>

<h1 class="page-heading">{l s='My Shop Account' mod='marketplace'}</h1>
{if $is_seller==1}
<a href="{$link_store|escape:'html':'UTF-8'}" class="btn btn-default button button-medium" title="Visit Shop" style="float:right; white-space:nowrap;z-index:10;margin-top:-10px;">
<i class="fa fa-shopping-cart icon-only"></i>Visit Shop
</a>
{/if}
<p class="info-account">{l s='Manage your shop.' mod='marketplace'}</p>
{if $is_seller==1}
	<li class="lnk_wishlist">
		<a title="Product List" href="{$product_list|addslashes}">
			<i class="icon-list"></i>
			<span>{l s='Product List' mod='marketplace'}</span>
		</a>
	</li>
	<li class="lnk_wishlist">
		<a title="Add product" href="{$add_product|addslashes}">
			<i class="icon-plus"></i>
			<span>{l s='Add Product' mod='marketplace'}</span>
		</a>
	</li>
	<li class="lnk_wishlist">
		<a title="My orders received" href="{$my_order|addslashes}">
			<i class="icon-gift"></i>
			<span>{l s='My orders Received' mod='marketplace'}</span>
		</a>
	</li>
	<li class="lnk_wishlist">
		<a title="Account Dashboard" href="{$account_dashboard|addslashes}">
			<i class="icon-dashboard"></i>
			<span>{l s='Account Dashboard' mod='marketplace'}</span>
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
		<a title="{l s='Click Here to create a shop' mod='marketplace'}" href="{$new_link1}">
			<i class="icon-mail-reply-all"></i>
			<span>{l s='Click Here to create a shop' mod='marketplace'}</span>
		</a>
	</li>
{/if}
