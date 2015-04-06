<style type="text/css">
#left_column{
	display:none;
}
</style>
{capture name=path}{l s='Shop' mod='marketplace'}{/capture}
<div class="main_block">
<div class="wk_left_sidebar">
	<div style="margin-bottom:10px;">
		{if $no_shop_img == 0}
			<img class="left_img" src="{$modules_dir|escape:'html':'UTF-8'}marketplace/img/shop_img/{$seller_id|escape:'html':'UTF-8'}-{$shop_name|escape:'html':'UTF-8'}.jpg" alt="Seller Image"/>
		{else}
			<img class="left_img" src="{$modules_dir|escape:'html':'UTF-8'}marketplace/img/shop_img/defaultshopimage.jpg" alt="Seller Image"/>
		{/if}
	</div>
	<div style="float:left;width:100%;">
	<a class="button btn btn-default button-medium" href="{$link->getModuleLink('marketplace','shopcollection',['shop'=>{$id_shop|escape:'html':'UTF-8'},'shop_name'=>{$name_shop|escape:'html':'UTF-8'}])|escape:'html':'UTF-8'}">
		<span>{l s='View Collection' mod='marketplace'}</span>
	</a>
	</div>
	{hook h='DisplayMpshoplefthook'}
</div>
<div class="dashboard_content">
	<div class="page-title">
		<span>{l s='Shop' mod='marketplace'}</span>
	</div>
	<div class="wk_right_col">
		<div class="box-account">
			<div class="box-head">
				<h2>{l s='About Shop' mod='marketplace'}</h2>
				<div class="wk_border_line"></div>
			</div>
			<div class="box-content" style="background-color:#F6F6F6;border-bottom: 3px solid #D5D3D4;">
				<div class="seller_name">{$shop_name|escape:'html':'UTF-8'}</div>
				<div>{$about_us|escape:'intval'}</div>
			</div>
		</div>


		<div class="box-account">
			<div class="box-head">
				<h2>{l s='About Seller' mod='marketplace'}</h2>
				<div class="wk_border_line"></div>
			</div>
			<div class="box-content" style="background-color:#F6F6F6;border-bottom: 3px solid #D5D3D4;">
				<div class="wk-left-label">
					<div class="wk_row">
						<label class="wk-person-icon">{l s='Seller Name -' mod='marketplace'}</label>
						<span>{$seller_name|escape:'html':'UTF-8'}</span>
					</div>
					<div class="wk_row">
						<label class="wk-mail-icon">{l s='Business Email -' mod='marketplace'}</label>
						<span>{$business_email|escape:'html':'UTF-8'}</span>
					</div>
					<div class="wk_row">
						<label class="wk-rating-icon">{l s='Seller Rating -' mod='marketplace'}</label>
						<span class="avg_rating"></span>
					</div>
				</div>
			</div>
		</div>	
		<div class="box-account">
			<div class="box-head">
				<h2>{l s='Recent Products' mod='marketplace'}</h2>
				<div class="wk_border_line"></div>
			</div>
			<div class="box-content">
				{if $count_product != 0}
					<div id="product-slider_block_center" class="wk-product-slider">
						<ul class="mp-prod-slider">
							{assign var=j value=0}
							{while $j != $count_product}
								<a href="{$base_dir|escape:'html':'UTF-8'}index.php?id_product={$product_id[$j]|escape:'html':'UTF-8'}&controller=product&id_lang={$image_link[$j][3]}" class="product_img_link" title="{$product_name[$j]|escape:'html':'UTF-8'}">
									<li>
										<div class="wk-slider-product-img">
											{if $image_link[$j][1] != ""}
												<img class="replace-2x img-responsive" src="{$link->getImageLink($image_link[$j][0], $image_link[$j][1], 'home_default')|escape:'html':'UTF-8'}" />
											{else}
												<img class="replace-2x img-responsive" src="{$link->getImageLink($image_link[$j][0], $image_link[$j][2]|cat : '-default', 'home_default')|escape:'html':'UTF-8'}" />
											{/if}
										</div>
										<div class="wk-slider-product-info">
											<div style="margin-bottom:5px;">{$product_name[$j]|truncate:45:'...'|escape:'html':'UTF-8'}</div>
											<div style="font-weight:bold;">{convertPrice price=$product_price[$j]}</div>
										</div>
									</li>
								</a>
								{assign var=j value=$j+1}
							{/while}
						</ul> 
					</div>
				{else}
					<p class="alert alert-info">
						{l s="No item found" mod='marketplace'}
					</p>
				{/if}
			</div>
		</div>
		{hook h='DisplayMpshopcontentbottomhook'}
	</div>
</div>
</div>			
<script type="text/javascript">
$(document).ready(function()
{
	$('.avg_rating').raty(
	{							
		path: '{$modules_dir|escape:'html':'UTF-8'}marketplace/rateit/lib/img',
		score: {$avg_rating|escape:'html':'UTF-8'},
		readOnly: true,
	});
});			
</script>