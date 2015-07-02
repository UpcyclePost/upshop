{if $mpmenu==0}
{else}
	<li {if $logic=='shipping_method_list'}class="menu_active"{/if}>
		<span>
			<a title="Mp Shipping" href="{$sellershippinglist}">
				{l s='Shipping Profiles' mod='mpshipping'}
			</a>
		</span>
	</li>
{/if}