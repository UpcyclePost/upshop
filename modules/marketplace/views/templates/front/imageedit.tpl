<style type="text/css">
	.middle_container 
	{
		float:left;
		width:100%;
	}	
	.table 
	{
		border: 0 none;
		border-spacing: 0;
		empty-cells: show;
		font-size: 100%;
		width:100%;
	}
	.table tr 
	{
		padding:5px;
	}
	.table tr th 
	{
		background: -moz-linear-gradient(center top , #F9F9F9, #ECECEC) repeat-x scroll left top #ECECEC;
		color: #333333;
		font-size: 13px;
		padding: 4px 6px;
		text-align: left;
		text-shadow: 0 1px 0 #FFFFFF;
		text-align:center;
	}
	.table tr td 
	{
		border-bottom: 1px solid #CCCCCC;
		color: #333333;
		font-size: 12px;
		padding: 4px 4px 4px 6px;
		text-align:center;
	}
</style>
<script type="text/javascript">
	$('.fancybox').fancybox();
</script>

<div class="middle_container">
{if isset($product_activated)}
	<div style="float:left;width:100%;">{l s='Active Image' mod='marketplace'}</div>
		<table id="imageTable" cellspacing="0" cellpadding="0" class="table">
		<tr>
			<th>{l s='Image' mod='marketplace'}</th>
			<th>{l s='Position' mod='marketplace'}</th>
			<th>{l s='Cover' mod='marketplace'}</th>
			<th>{l s='Action' mod='marketplace'}</th>		
		</tr>
		{if isset($id_image)}
			{assign var=j value=0}
			{foreach $id_image as $id_image1}
				<tr class="imageinforow{$id_image1|escape:'html':'UTF-8'}">
					<td>
						<a class="fancybox" href="http://{$image_link[$j]|escape:'html':'UTF-8'}">
							<img title="15" width="45" height="45" alt="15" src="http://{$image_link[$j]|escape:'html':'UTF-8'}"/>
						</a>
					</td>
					<td>
						{$position[$j]|escape:'html':'UTF-8'}
					</td>
					<td>
						{if $is_cover[$j]==1 }
							<img class="covered" id="changecoverimage{$id_image1|escape:'html':'UTF-8'}" alt="{$id_image1|escape:'html':'UTF-8'}" src="{$img_ps_dir|escape:'html':'UTF-8'}enabled.gif" is_cover="1" id_pro="{$id_product|escape:'html':'UTF-8'}"/>
						{else}
							<img class="covered" id="changecoverimage{$id_image1|escape:'html':'UTF-8'}" alt="{$id_image1|escape:'html':'UTF-8'}" src="{$img_ps_dir|escape:'html':'UTF-8'}forbbiden.gif" is_cover="0" id_pro="{$id_product|escape:'html':'UTF-8'}"/>
						{/if}
					</td>
					<td>
						{if $is_cover[$j]==1} 
							<img title="Delete this image" class="delete_pro_image" alt="{$id_image1|escape:'html':'UTF-8'}" src="{$img_ps_dir|escape:'html':'UTF-8'}delete.gif" is_cover="1" id_pro="{$id_product|escape:'html':'UTF-8'}"/>
						{else}
							<img title="Delete this image" class="delete_pro_image" alt="{$id_image1|escape:'html':'UTF-8'}" src="{$img_ps_dir|escape:'html':'UTF-8'}delete.gif" is_cover="0" id_pro="{$id_product|escape:'html':'UTF-8'}"/>
						{/if}
					</td>
				</tr>
			{assign var=j value=$j+1}	
			{/foreach}	
		{else}
				<tr>
					<td></td>
					<td colspan="2">{l s='No Image has been uploaded yet' mod='marketplace'}</td>
					<td></td>
				</tr>
		{/if}	
		</table>
		{if isset($unactive_image)}
		<div style="float:left;width:100%;">Unactive Image</div>
		<table id="imageTable" cellspacing="0" cellpadding="0" class="table">
			<tr>
				<th>Image</th>
				<th>Action</th>		
			</tr>
			{foreach $unactive_image as $unactive_image1}
				<tr class="unactiveimageinforow{$unactive_image1['id']|escape:'html':'UTF-8'}">
					<td>
						<a class="fancybox" href="{$modules_dir|escape:'html':'UTF-8'}marketplace/img/product_img/{$unactive_image1['seller_product_image_id']|escape:'html':'UTF-8'}.jpg">
						<img title="15" width="45" height="45" alt="15" src="{$modules_dir|escape:'html':'UTF-8'}marketplace/img/product_img/{$unactive_image1['seller_product_image_id']|escape:'html':'UTF-8'}.jpg" />
						</a>
					</td>
					<td>
						<img title="Delete this image" class="delete_unactive_pro_image" alt="{$unactive_image1['id']|escape:'html':'UTF-8'}" src="{$img_ps_dir|escape:'html':'UTF-8'}delete.gif" img_name="{$unactive_image1['seller_product_image_id']|escape:'html':'UTF-8'}"/>
					</td>
				</tr>	
			{/foreach}
		</table>
		{/if}
{else}
		{if isset($unactive_image_only)}
			<div class="middle_container">
				<div style="float:left;width:100%;">{l s='Unactive Image' mod='marketplace'}</div>
				<table id="imageTable" cellspacing="0" cellpadding="0" class="table">
					<tr>
						<th>Image</th>
						<th>Action</th>		
					</tr>
				{foreach $unactive_image_only as $unactive_image1}
					<tr class="unactiveimageinforow{$unactive_image1['id']|escape:'html':'UTF-8'}">
						<td>
							<a class="fancybox" href="{$modules_dir|escape:'html':'UTF-8'}marketplace/img/product_img/{$unactive_image1['seller_product_image_id']|escape:'html':'UTF-8'}.jpg">
								<img title="15" width="45" height="45" alt="15" src="{$modules_dir|escape:'html':'UTF-8'}marketplace/img/product_img/{$unactive_image1['seller_product_image_id']|escape:'html':'UTF-8'}.jpg" />
							</a>
						</td>
						<td>
							<img title="Delete this image" class="delete_unactive_pro_image" alt="{$unactive_image1['id']|escape:'html':'UTF-8'}" src="{$img_ps_dir|escape:'html':'UTF-8'}delete.gif" img_name="{$unactive_image1['seller_product_image_id']|escape:'html':'UTF-8'}">
						</td>
					</tr>
				{/foreach}
				</table>
			</div>
		{else}
			<div class="middle_container" style="float:left;width:100%;">
				<div style="float:left;width:100%;">{l s='No Image has been uploaded yet.' mod='marketplace'}</div>
			</div>	
		{/if}
{/if}		
</div>














