{capture name=path}{l s='Product Details' mod='marketplace'}{/capture}
<div class="main_block">
{hook h="DisplayMpmenuhook"}
	<div class="dashboard_content">
		<div class="page-title">
			<span>{l s='Product Details' mod='marketplace'}</span>
		</div>
		<div class="wk_right_col">
			<div class="wk_head">
				<a href="{$link->getModuleLink('marketplace','marketplaceaccount',['shop'=>{$id_shop|escape:'html':'UTF-8'},'l'=>3])|escape:'html':'UTF-8'}" class="btn btn-default button button-small">
				<span>{l s='Back to product list' mod='marketplace'}</span>
				</a>
			</div>
			<div class="wk_product_details">
				<div class="wk_details">
					<div class="wk_row">
						<div class="wk_label">
							{l s='Product Name' mod='marketplace'} -
						</div>
						<div class="wk_value">
							{$name|escape:'html':'UTF-8'}
						</div>
					</div>
					<div class="wk_row">
						<div class="wk_label">
							{l s='Description' mod='marketplace'} -
						</div>
						<div class="wk_value">
							{$description|escape:'intval'}
						</div>
					</div>
					<div class="wk_row">
						<div class="wk_label">
							{l s='Price' mod='marketplace'} -
						</div>
						<div class="wk_value">
							{$currency->prefix}{$price|string_format:"%.2f"}{$currency->suffix}
						</div>
					</div>
					<div class="wk_row">
						<div class="wk_label">
							{l s='Quantity' mod='marketplace'} -
						</div>
						<div class="wk_value">
							{$quantity|escape:'html':'UTF-8'}
						</div>
					</div>
					<div class="wk_row">
						<div class="wk_label">
							{l s='Status' mod='marketplace'} -
						</div>
						<div class="wk_value">
							{if {$status} == 1}
							   {l s='Approved' mod='marketplace'}
							 {else}
					           {l s='Pending' mod='marketplace'}
					         {/if}	
						</div>
					</div>
				</div>
				<div class="wk_image">
					{if $is_approve==1}
						<img src="http://{$image_id|escape:'html':'UTF-8'}"  style="max-width:280px;max-height:200px;"/>
					{else}
						{if $mp_pro_image!=0}
							<img src="{$modules_dir|escape:'html':'UTF-8'}/marketplace/img/product_img/{$cover_img|escape:'html':'UTF-8'}.jpg"  style="max-width:280px;max-height:200px;"/>
						{/if}
					{/if}
				</div>
			</div>
			<div style="float:left;width:100%;margin-bottom:20px;">
				{if $is_approve==1}
			    <div id="image_details">
					<table>
					 <tr>
					 	<th>{l s='Image' mod='marketplace'}</th>
						  <th>{l s='Position' mod='marketplace'}</th>
						  <th>{l s='Cover' mod='marketplace'}</th>
						  <th>{l s='Action' mod='marketplace'}</th>
					 </tr>
					 {if {$count} > 0}
					  {foreach from=$img_info item=foo}
					   <tr class="unactiveimageinforow{$foo.id_image|escape:'html':'UTF-8'}">
					    <td><a class="fancybox" href="http://{$foo.image_link|escape:'html':'UTF-8'}">
					    <img title="15" width="45" height="45" alt="15" src="http://{$foo.image_link|escape:'html':'UTF-8'}" />
					   </a>
					   </td>
					    <td>{$foo.position|escape:'html':'UTF-8'}</td>
					   <td>
					    {if {$foo.cover} == 1}
						 <img class="covered" id="changecoverimage{$foo.id_image|escape:'html':'UTF-8'}" alt="{$foo.id_image|escape:'html':'UTF-8'}" src="{$img_ps_dir|escape:'html':'UTF-8'}admin/enabled.gif" is_cover="1"  id_pro="{$id|escape:'html':'UTF-8'}" />
						{else}
						 <img class="covered" id="changecoverimage{$foo.id_image|escape:'html':'UTF-8'}" alt="{$foo.id_image|escape:'html':'UTF-8'}" src="{$img_ps_dir|escape:'html':'UTF-8'}admin/forbbiden.gif" is_cover="0"  id_pro="{$id|escape:'html':'UTF-8'}" />
						{/if} 
					   </td>
					   <td>
					   {if {$foo.cover} == 1}
					     <img title="Delete this image" is_cover="1" class="delete_pro_image" alt="{$foo.id_image|escape:'html':'UTF-8'}"  src="{$img_ps_dir|escape:'html':'UTF-8'}admin/delete.gif" id_pro="{$id_product|escape:'html':'UTF-8'}" />
					   {else}
					     <img title="Delete this image" is_cover="0" class="delete_pro_image" alt="{$foo.id_image|escape:'html':'UTF-8'}"  src="{$img_ps_dir|escape:'html':'UTF-8'}admin/delete.gif" id_pro="{$id_product|escape:'html':'UTF-8'}" />
			           {/if}		   
					   </td>
					   </tr>
					  {/foreach}
					 {/if}
					</table>
			    </div>
				{else}
					<div id="image_details" style="float:left;margin-top:10px;">
						<table>
						<tr><th>Image</th></tr>
						{foreach $mp_pro_image as $mp_pro_ima}
						<tr>
							<td>
							<a class="fancybox" href="{$modules_dir|escape:'html':'UTF-8'}/marketplace/img/product_img/{$mp_pro_ima['seller_product_image_id']|escape:'html':'UTF-8'}.jpg">
								<img title="15" width="45" height="45" alt="15" src="{$modules_dir|escape:'html':'UTF-8'}/marketplace/img/product_img/{$mp_pro_ima['seller_product_image_id']|escape:'html':'UTF-8'}.jpg" />
							</a>
							</td>	
						</tr>
						{/foreach}
						</table>
					</div>
				{/if}
			</div>
			<div class="left full">
				{hook h="DisplayMpproductdescriptionfooterhook"}
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('.fancybox').fancybox();		
var ajax_urlpath = '{$imageediturl|escape:'html':'UTF-8'}';
var space_error = '{l s='Space is not allowed.' js=1 mod='marketplace'}';
var confirm_delete_msg = '{l s='Do you want to delete the photo?' js=1 mod='marketplace'}';
var delete_msg = '{l s='Deleted.' js=1 mod='marketplace'}';
var error_msg = '{l s='An error occurred.' js=1 mod='marketplace'}';
var src_forbidden = '{$img_ps_dir|escape:'html':'UTF-8'}admin/forbbiden.gif';
var src_enabled = '{$img_ps_dir|escape:'html':'UTF-8'}admin/enabled.gif';	
</script>
