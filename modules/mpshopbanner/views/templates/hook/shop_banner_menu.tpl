<link media="all" type="text/css" rel="stylesheet" href="{$module_dir}views/css/bannermenu.css" />
{if $mpmenu==0}
	<li class="lnk_wishlist">
		<a title="Shop Banner" href="{$viewbannerlist}">
			<i class="icon-flag"></i>
			<span>{l s='Banner' mod='mpshopbanner'}</span>
		</a>
	</li>
{else}
	<li {if $logic=='banner'}class="menu_active"{/if}>
		<span>
			<a title="Shop Banner" href="{$viewbannerlist}">
				{l s='Banner' mod='mpshopbanner'}
			</a>
		</span>
	</li>
{/if}