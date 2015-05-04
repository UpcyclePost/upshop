{if $is_seller==1}
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
		<a title="{l s='Open a Shop' mod='marketplace'}" href="{$new_link1}">
			<i class="icon-mail-reply-all"></i>
			<span>{l s='Open a Shop' mod='marketplace'}</span>
		</a>
	</li>
{/if}
