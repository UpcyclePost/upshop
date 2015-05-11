{if $mpmenu==0}
	<li class="lnk_wishlist">
		<a title="Shipping" href="{$sellershippinglist}">
			<i class="icon-truck"></i>
			<span>{l s='Shipping Methods' mod='mpshipping'}</span>
		</a>
	</li>
{else}
	<li>
		<span>
			<a title="Mp Shipping" href="{$sellershippinglist}">
				{l s='Shipping Methods' mod='mpshipping'}
			</a>
		</span>
	</li>
{/if}