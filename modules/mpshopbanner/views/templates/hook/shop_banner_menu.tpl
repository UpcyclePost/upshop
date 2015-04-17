<link media="all" type="text/css" rel="stylesheet" href="{$module_dir}views/css/bannermenu.css" />
{if $mpmenu==0}
	<li class="lnk_wishlist blog_view" style="width: 105px;">
		<img class="icon" alt="plan detail" src="{$module_dir}/img/banner.gif">
		<a title="Shop Banner" href="{$viewbannerlist}" target="_blank">
			{l s='Banner' mod='mpshopbanner'}			
		</a>
	</li>
{else}
	<li class="blog_view">
		<span>
			<a title="Shop Banner" href="{$viewbannerlist}" target="_blank">
				{l s='Banner' mod='mpshopbanner'}
			</a>
		</span>
	</li>
{/if}