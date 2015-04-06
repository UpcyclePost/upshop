<div id="row">
	<div class="col-lg-12">
		<div class="panel">
			<h3><i class="icon-info"></i> {l s='Detail Information' mod='marketplace'}</h3>
			{if isset($customer_name)}
				<p><strong>{l s='Customer Name' mod='marketplace'} :  </strong>{$customer_name|escape:'html':'UTF-8'}</p>
				<p><strong>{l s='Customer Email' mod='marketplace'} :  </strong>{$review_detail['customer_email']|escape:'html':'UTF-8'}</p>
			{else}
				<p><strong>{l s='Customer' mod='marketplace'} :  </strong>{l s='As a guest' mod='marketplace'}</p>
			{/if}
			
			<p><strong>{l s='Seller Name' mod='marketplace'} :  </strong>{$obj_mp_seller->seller_name|escape:'html':'UTF-8'}</p>
			<p><strong>{l s='Seller Email' mod='marketplace'} :  </strong>{$obj_mp_seller->business_email|escape:'html':'UTF-8'}</p>
			<p>
				<strong>{l s='Rating' mod='marketplace'} :  </strong>
				{for $foo=1 to $review_detail['rating']}
					<img src="../modules/marketplace/img/star-on.png" />
				{/for}
			</p>
			<p><strong>{l s='Customer Review' mod='marketplace'} :  </strong>{$review_detail['review']|escape:'html':'UTF-8'}</p>
			<p><strong>{l s='Time' mod='marketplace'} :  </strong>{$review_detail['date_add']|escape:'html':'UTF-8'}</p>
		</div>
	</div>
</div>
