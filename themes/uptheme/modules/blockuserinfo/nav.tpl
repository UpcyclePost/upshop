<!-- Block user information module NAV  -->
{if $is_logged}
	<div class="header_user_info">
		<a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='View my customer account' mod='blockuserinfo'}" class="account" rel="nofollow"><span>You</span></a>
	</div>
{/if}
<!-- /Block usmodule NAV -->
