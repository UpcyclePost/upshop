{capture name=path}
<a href="{$link->getModuleLink('marketplace', 'marketplaceaccount')|addslashes}">
        {l s='My Dashboard'}
</a>
<span class="navigation-pipe">{$navigationPipe}</span>
<span class="navigation_page">{l s='Shipping Profiles' mod='marketplace'}</span>
{/capture}

{hook h="DisplayMpmenuhook"}

<div class="left col-sm-9">
<div class="box-account box-recent login-panel">
	<div class="login-panel-header">
			<h1>{l s='Shipping Profiles' mod='mpshipping'}</h1>
	</div>
	<div class="wk_right_col">
	<div class="box-content" id="wk_shipping_list" style="text-align:center;">
	<table class="data-table" style="width:90%;">
		<thead>
			<tr class="first last">
			<th>{l s='Edit' mod='mpshipping'}</th>
			<th>{l s='Shipping Profile' mod='mpshipping'}</th>
			<th>{l s='Shipping Method' mod='mpshipping'}</th>
			<th>{l s='Shipping time' mod='mpshipping'}</th>
			<th>{l s='Delete' mod='mpshipping'}</th>
			</tr>
		</thead>
		<tbody>
			{if $mp_shipping_detail!=0}
				{foreach $mp_shipping_detail as $mp_sp_det}
					<tr class="even">
						<td>
							<a href="{$link->getModuleLink('mpshipping','addnewshipping',['shop'=>{$mp_id_shop},'id_shipping'=>{$mp_sp_det['id']}])}" id="shipping_basicedit" class="btn btn-default button button-small">
								<span>{l s='Edit' mod='mpshipping'}</span>
							</a>
						</td>
						<td>{$mp_sp_det['mp_shipping_name']}</td>
						<td>
							{if $mp_sp_det['is_free'] == 1}
								{l s='Free Shipping' mod='mpshipping'}
							{else}
								{if $mp_sp_det['shipping_method'] == 2}
									{l s='Shipping charge on price' mod='mpshipping'}
								{elseif $mp_sp_det['shipping_method'] == 1}
									{l s='Shipping charge on weight' mod='mpshipping'}
								{/if}
							{/if}
						</td>
						<td>
							{$mp_sp_det['transit_delay']}
						</td>
						<td>
							<a href="{$link->getModuleLink('mpshipping','sellershippinglist',['shop'=>{$mp_id_shop},'id_shipping'=>{$mp_sp_det['id']},'delete'=>1])}" id="delete_shipping">
								<img title="{l s='Delete' mod='mpshipping'}" src="{$img_ps_dir|escape:'html':'UTF-8'}admin/delete.gif"/>
							</a>
						</td>
					</tr>
				{/foreach}
				{/if}
		</tbody>
	</table>
	{if $mp_shipping_detail==0}
		<div class="full left shippinglistcontent" style="padding:15px">
			<center>{l s='No data found' mod='mpshipping'}</center>
		</div>
	{/if}
	<div class="" style="text-align:center;padding:15px">
		{if $mp_shipping_detail!=0}
		<a class="btn btn-default button button-medium" id="add_product" href="{$add_product}"><span>{l s='Add Product' mod='mpshipping'}</span></a>&nbsp;&nbsp;&nbsp;
		{/if}
		<a class="btn btn-default button button-medium" id="add_new_shipping" href="{$addnew_shipping_link}"><span>{l s='New Shipping Profile' mod='mpshipping'}</span></a>
	</div>
</div>		
</div>
</div>
</div>

<script type="text/javascript">
$("#delete_shipping").on("click", function(){
	if (!confirm("Are you sure?"))
	{
        return false;
    }
});
</script>