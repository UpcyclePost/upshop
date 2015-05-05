<!-- Block user information module NAV  -->
<div class="header_user_info">
	{if $is_logged}<a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='View my customer account' mod='blockuserinfo'}" class="account" rel="nofollow"><span>You</span></a>{/if}
</div>
<!-- /Block usmodule NAV -->
