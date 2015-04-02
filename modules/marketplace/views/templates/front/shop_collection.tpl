{capture name=path}{l s='Collection' mod='marketplace'}{/capture}
<div class="main_block">
	<div id="wk_banner_block">
		{hook h="DisplayMpcollectionbannerhook"}
		<img id="default_banner" src="{$modules_dir|escape:'html':'UTF-8'}marketplace/img/prestashop_logo.jpg" width=100%;/>
	</div>
	<div class="wk_left_col">
		<div class="wk_catg_list">
			<ul>
				<li>
					<span class="wk_catg_head">
						{l s='Seller Category List' mod='marketplace'}
					</span>
				</li>
				{assign var=k value=0}
				{while $k != $count_category}
				<li>
					<span>
						<a href="{$link_collection|escape:'html':'UTF-8'}&shop={$id_shop1|escape:'html':'UTF-8'}&cat_id={$category_id[$k]|escape:'html':'UTF-8'}">{$category_name[$k]|escape:'html':'UTF-8'}({$category_qty[$k]|escape:'html':'UTF-8'})</a>
					</span>
				</li>
				{assign var=k value=$k+1}
				{/while}
			</ul>
		</div>
		{hook h="DisplayMpcollectionlefthook"}
	</div>
	<div class="dashboard_content">
		<div class="wk_refine_search_head">	
			<form id="productsSortForm" action="{$link_collection}{if $cat_id>0}&cat_id={$cat_id}{/if}">
				<label for="selectPrductSort">Sort by</label>
				<select id="selectPrductSort" class="selectProductSort">
					<option value="position:asc" selected="selected">--</option>
					<option value="price:asc">{l s='Price' mod='marketplace'}: {l s='lowest first' mod='marketplace'}</option>
					<option value="price:desc">{l s='Price' mod='marketplace'}: {l s='highest first' mod='marketplace'}</option>
					<option value="name:asc">{l s='Product Name' mod='marketplace'}: {l s='A to Z' mod='marketplace'}</option>
					<option value="name:desc">{l s='Product Name' mod='marketplace'}: {l s='Z to A' mod='marketplace'}</option>
				</select>
			</form>
		</div>
		<div class="wk_product_collection">
			{if $count_product != 0}
				{assign var=j value=0}
				{assign var=i value=1}
				{while $j != $count_product}
				<a href="{$base_dir|escape:'html':'UTF-8'}index.php?id_product={$product_id[$j]|escape:'html':'UTF-8'}&controller=product" class="product_img_link" title="{$product_name[$j]|escape:'html':'UTF-8'}">
					<div class="wk_collection_data" {if $i%3 == 0}style="margin-right:0px;"{/if}>
						<div class="wk_img_block">
							<img src="{$image_link[$j]|escape:'html':'UTF-8'}" alt="{$product_name[$j]|escape:'html':'UTF-8'}"/>
						</div>
						<div class="wk_collecion_details">
							<div>{$product_name[$j]|escape:'html':'UTF-8'}</div>
							<div style="font-weight:bold;">{$currency->prefix|escape:'html':'UTF-8'}{$product_price[$j]|escape:'html':'UTF-8'}{$currency->suffix|escape:'html':'UTF-8'}</div>
							<div>
								<a class="button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product_id[$j]|intval}&amp;token={$token}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='marketplace'}" data-id-product="{$product_id[$j]|intval}"> 
								<span>{l s='Add to cart' mod='marketplace'}</span> </a>
							</div>
						</div>
					</div>
				</a>
				{assign var=j value=$j+1}
				{assign var=i value=$i+1}
				{/while}
			{else}
				<p class="alert alert-info">
					{l s="No item found" mod='marketplace'}
				</p>
			{/if}
		</div>
		{hook h="DisplayMpcollectionfooterhook"}
		 {if $cat_id>0}
			<input type="hidden" value="{$cat_id|escape:'html':'UTF-8'}" id="cat_id_info">
		 {else}
			<input type="hidden" value="0" id="cat_id_info">
		 {/if}
	</div>
</div>

{if $id_product1}
<script type="text/javascript">
var min_item = 'Please select at least one product';
var max_item = "You cannot add more than 3 product(s) to the product comparison";
$(document).ready(function()
{
	$('.selectProductSort').change(function()
	{
		var requestSortProducts = '{$link_collection|escape:'intval'}&id={$id_product1|escape:'intval'}&';
		var splitData = $(this).val().split(':');
		
		document.location.href = requestSortProducts + ((requestSortProducts.indexOf('?') < 0) ? '?' : '&') + 'orderby=' + splitData[0] + '&orderway=' + splitData[1];
		
	});
});
</script>
{else}
<script type="text/javascript">
	$(document).ready(function()
	{
		$('.selectProductSort').change(function()
		{
			var cat_id = $('#cat_id_info').val();
			if(cat_id>0) {
				var requestSortProducts = '{$link_collection|escape:'intval'}&shop={$id_shop1|escape:'intval'}&cat_id={$cat_id|escape:'html':'UTF-8'}';
			} else {
				var requestSortProducts = '{$link_collection|escape:'intval'}&shop={$id_shop1|escape:'intval'}&';
			}
			var splitData = $(this).val().split(':');
			
			document.location.href = requestSortProducts + ((requestSortProducts.indexOf('?') < 0) ? '?' : '&') + 'orderby=' + splitData[0] + '&orderway=' + splitData[1];
			
		});
	});
</script>
{/if}