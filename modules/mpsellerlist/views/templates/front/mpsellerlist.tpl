<div class="left full sellelistcontainer">
	<div id="wk_mp_header">
		<h1>{l s="Marketplace" mod='mpsellerlist'}</h1>
		<p>
			<a class="btn btn-primary" href="{$gotoshop_link}">{l s="Go To Shop" mod='mpsellerlist'}</a>
		</p>
	</div>
	<div class="wk_profiledata">
		<p>
			{$mp_seller_text}		
		</p>
	</div>
	<div class="list_seller_product">
		<div class="list_label">
			{l s="Seller List" mod='mpsellerlist'}
		</div>
		{if isset($all_active_seller)}
			{foreach $all_active_seller as $key=>$act_seller}
				<div class="sellerlist_cont">
					<a href="{$link->getModuleLink('marketplace','shopstore',['shop'=>{$act_seller['mp_shop_id']}])}">
						<img src="{$modules_dir}marketplace/img/shop_img/{$shop_img[{$key}]}" title="{$act_seller['shop_name']}" alt="{$act_seller['shop_name']}">
						<div class="mp_landing_hover mp_landing_hover_seller">
							<span>{$act_seller['shop_name']}</span>
						</div>
					</a>
				</div>
			{/foreach}
			<div class="sellerlist_cont">
				<a href="{$viewmorelist_link}">
					<img src="{$modules_dir}mpsellerlist/img/plus.jpg" alt="view more">
					<div class="mp_landing_hover mp_landing_hover_seller">
						<span>{l s="View more" mod='mpsellerlist'}</span>
					</div>
				</a>
			</div>
		{else}
			{l s="No shop found" mod='mpsellerlist'}
		{/if}		
	</div>
	<div class="list_seller_product">
		<div class="list_label">{l s="Latest Products" mod='mpsellerlist'}</div>
		{if isset($seller_product_info)}
			{foreach $seller_product_info as $key=>$seller_prod}
				<div class="sellerlist_cont">
					<a href="{$product_info[$key][3]}">
						{if $product_info[$key][1] != ""}
							<img class="replace-2x img-responsive" src="{$link->getImageLink($product_info[$key][0], $product_info[$key][1], 'small_default')|escape:'html':'UTF-8'}"  title="{$seller_prod['product_name']}" alt="{$seller_prod['product_name']}" />
						{else}
							<img class="replace-2x img-responsive" src="{$link->getImageLink($product_info[$key][0], $product_info[$key][2]|cat : '-default', 'small_default')|escape:'html':'UTF-8'}"  title="{$seller_prod['product_name']}" alt="{$seller_prod['product_name']}" />
						{/if}
						<div class="mp_landing_hover">
							<span>{$seller_prod['product_name']}</span>
						</div>
					</a>
				</div>
			{/foreach}
		{else}
			{l s="No product found" mod='mpsellerlist'}
		{/if}
	</div>
</div>