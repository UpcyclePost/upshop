{if $mpmenu==0}
	<li class="lnk_wishlist">
		<a title="Shipping" href="{$sellershippinglist}">
			<i class="icon-truck"></i>
			<span>{l s='Shipping Method' mod='mpshipping'}</span>
		</a>
	</li>
{else}
	<li {if $logic=='shipping_method_list'}class="menu_active"{/if}>
		<span>
			<a title="Mp Shipping" href="{$sellershippinglist}" target="_blank">
				{l s='Shipping Method' mod='mpshipping'}
			</a>
		</span>
	</li>
{/if}