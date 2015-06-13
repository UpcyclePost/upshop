{if $mpmenu==0}
	<li class="lnk_wishlist">
		<a title="Shipping" href="{$sellershippinglist}">
			<i class="icon-truck"></i>
			<span>{l s='Shipping Profiles' mod='mpshipping'}</span>
		</a>
	</li>
{else}
	<li {if $logic=='shipping_method_list'}class="menu_active"{/if}>
		<span>
			<a title="Mp Shipping" href="{$sellershippinglist}">
				{l s='Shipping Profiles' mod='mpshipping'}
			</a>
		</span>
	</li>
{/if}