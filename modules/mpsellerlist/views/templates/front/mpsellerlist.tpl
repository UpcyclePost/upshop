<div class="left full sellelistcontainer">
	<div id="wk_mp_header">
		<h1>{l s="Market Place" mod='mpsellelist'}</h1>
		<p>
			<a class="button" href="{$gotoshop_link}">{l s="Go To Shop" mod='mpsellelist'}</a>
		</p>
	</div>
	<div class="wk_profiledata">
		<p>
			{$mp_seller_text}		
		</p>
	</div>
	<div class="list_seller_product">
		<div class="label">{l s="Seller List" mod='mpsellelist'}</div>
		{if $total_active_seller==0}
			{l s="No Shop Available" mod='mpsellelist'}
		{else}
			{assign var=k value=0}
			{foreach $all_active_seller as $act_seller}
				<a href="{$shop_store_link}&shop={$act_seller['mp_shop_id']}">
					<img src="{$modules_dir}marketplace/img/shop_img/{$shop_img[{$k}]}" title="{$act_seller['shop_name']}" alt="{$act_seller['shop_name']}">
					<div class="mp_landing_hover">
						<span>{$act_seller['shop_name']}</span>
					</div>
				</a>
				{assign var=k value=$k+1}
			{/foreach}
			{if $total_active_seller>3}
				<a href="{$viewmorelist_link}">
					<img src="{$modules_dir}mpsellerlist/img/plus.jpg" title="Luke Adrian" alt="view more">
					<div class="mp_landing_hover">
						<span>{l s="View more" mod='mpsellelist'}</span>
					</div>
				</a>
			{/if}
		{/if}		
	</div>
	<div class="list_seller_product">
		<div class="label">{l s="Latest Product Added" mod='mpsellelist'}</div>
		{if $active_seller_product==0}
			{l s="No Product Available" mod='mpsellelist'}
		{else}
			{assign var=k value=0}
			{foreach $seller_product_info as $seller_prod}
				<a href="{$product_link}&id_product={$seller_prod['main_id_product']}">
					<img src="{$product_img_info[$k]}" title="{$seller_prod['product_name']}" alt="{$seller_prod['product_name']}">
					<div class="mp_landing_hover">
						<span>{$seller_prod['product_name']}</span>
					</div>
				</a>
				{assign var=k value=$k+1}
			{/foreach}
		{/if}
	</div>
</div>