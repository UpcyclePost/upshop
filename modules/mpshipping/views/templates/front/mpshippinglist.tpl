{hook h="DisplayMpmenuhook"}

<div class="left col-sm-9">
<div class="box-account box-recent">
	<div class="box-head">
		<div class="box-head-left">
			<h2>{l s='Shipping Plan' mod='mpshipping'}</h2>
		</div>
		<div class="box-head-right">
			<a href="{$dash_board_link}">
				<img src="{$modules_dir}mpshipping/img/home.gif">
			</a>
			<a class="btn btn-default button button-small" id="add_new_shipping" href="{$addnew_shipping_link}"><span>{l s='Add New' mod='mpshipping'}</span></a>
		</div>
	</div>
	<div class="wk_border_line"></div>
	<div class="box-content" id="wk_shipping_list">
	<table class="data-table" style="width:100%;">
		<thead>
			<tr class="first last">
			<th>{l s='Shipping Name' mod='mpshipping'}</th>
			<th>{l s='Logo' mod='mpshipping'}</th>
			<th>{l s='Shipping Method' mod='mpshipping'}</th>
			<th>{l s='Status' mod='mpshipping'}</th>
			<th>{l s='Edit' mod='mpshipping'}</th>
			</tr>
		</thead>
		<tbody>
			{if $mp_shipping_detail!=0}
				{foreach $mp_shipping_detail as $mp_sp_det}
					<tr class="even">
						<td>{$mp_sp_det['mp_shipping_name']}</td>
						<td>
							{if $mp_sp_det['image_exist'] == 1}
								<img src="{$modules_dir}mpshipping/img/logo/{$mp_sp_det['id']}.jpg" width="30px" height="30px" alt="{$mp_sp_det['mp_shipping_name']}">
							{else}
								<span>{l s='No Image' mod='mpshipping'}</span>
							{/if}	
						</td>
						
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
							{if {$mp_sp_det['active']}==0}
								{l s='Pending' mod='mpshipping'}
							{else}
								{l s='Active' mod='mpshipping'}
							{/if}
						</td>
						<td>
							<a href="{$link->getModuleLink('mpshipping','addnewshipping',['shop'=>{$mp_id_shop},'id_shipping'=>{$mp_sp_det['id']}])}" id="shipping_basicedit" style="color:blue;">
								{l s='Basic edit' mod='mpshipping'}
							</a>
							{*&nbsp;&nbsp;
							<a href="{$link->getModuleLink('mpshipping','impactpriceedit',['shop'=>{$mp_id_shop},'id_shipping'=>{$mp_sp_det['id']}])}" id="impact_edit" style="color:blue;">
								{l s='Impact edit' mod='mpshipping'}
							</a>*}
							&nbsp;&nbsp;
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
		<div class="full left shippinglistcontent">
			<center>{l s='No data found' mod='mpshipping'}</center>
		</div>
	{/if}
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