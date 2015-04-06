{if isset($review_submitted)}
	<p class="alert alert-success">
		{l s='Thanks for the feedback. Review will be active after admin approval.' mod='marketplace'}
	</p>
{/if}
{capture name=path}{l s='Seller Profile' mod='marketplace'}{/capture}
<div class="main_block">
<div class="wk_left_sidebar">
	<div style="margin-bottom:10px;">
		<img class="left_img" src="{$seller_img_path|escape:'html':'UTF-8'}" alt="Seller Image"/>
	</div>
	<div style="float:left;width:100%;">
	<a class="button btn btn-default button-medium" href="{$link->getModuleLink('marketplace','shopcollection',['shop'=>{$id_shop|escape:'html':'UTF-8'}])|escape:'html':'UTF-8'}">
		<span>{l s='View Collection' mod='marketplace'}</span>
	</a>
	</div>
	{hook h='DisplayMpsplefthook'}
</div>
<div class="dashboard_content">
	<div class="page-title">
		<span>{l s='Seller Profile' mod='marketplace'}</span>
	</div>
	<div class="wk_right_col">
		<div class="box-account">
			<div class="box-head">
				<h2>{l s='About Seller' mod='marketplace'}</h2>
				<div class="wk_border_line"></div>
			</div>
			<div class="box-content" style="background-color:#F6F6F6;border-bottom: 3px solid #D5D3D4;">
				<div class="seller_name">{$market_place_seller_info['seller_name']|escape:'html':'UTF-8'}</div>
				<div class="wk-left-label">
					<div class="wk_row">
						<label class="wk-mail-icon">{l s='Business Email -' mod='marketplace'}</label>
						<span>{$market_place_seller_info['business_email']|escape:'html':'UTF-8'}</span>
					</div>
					<div class="wk_row">
						<label class="wk-phone-icon">{l s='Phone -' mod='marketplace'}</label>
						<span>{$market_place_seller_info['phone']|escape:'html':'UTF-8'}</span>
					</div>
					<div class="wk_row">
						<label class="wk-address-icon">{l s='Address -' mod='marketplace'}</label>
						<span>{$market_place_seller_info['address']|escape:'html':'UTF-8'}</span>
					</div>
					<div class="wk_row">
						<label class="wk-share-icon">{l s='Social Profile -' mod='marketplace'}</label>
						<span class="wk-social-icon">
							{if {$market_place_seller_info['facebook_id']} != ''}
								<a class="wk-facebook-button" target="_blank" href="http://www.facebook.com/{$market_place_seller_info['facebook_id']|escape:'html':'UTF-8'}"></a>
							{/if}
							{if {$market_place_seller_info['twitter_id']} != ''}
							<a class="wk-twitter-button" target="_blank" href="http://www.twitter.com/{$market_place_seller_info['twitter_id']|escape:'html':'UTF-8'}"></a>
							{/if}
						</span>
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
			<div class="box-content wk_slider_padding">
				{if $count_latest_pro}
					<div id="product-slider_block_center" class="wk-product-slider">
						<ul class="mp-prod-slider">
							{assign var=j value=0}
							{while $j != $count_latest_pro}
								<a href="{$base_dir|escape:'html':'UTF-8'}index.php?id_product={$all_product_id[$j]|escape:'html':'UTF-8'}&controller=product&id_lang={$product_image_link[$j][3]}" class="product_img_link" title="{$all_product_name[$j]|escape:'html':'UTF-8'}">
									<li>
										<div class="wk-slider-product-img">
											{if $product_image_link[$j][1] != ""}
												<img class="replace-2x img-responsive" src="{$link->getImageLink($product_image_link[$j][0], $product_image_link[$j][1], 'home_default')|escape:'html':'UTF-8'}" />
											{else}
												<img class="replace-2x img-responsive" src="{$link->getImageLink($product_image_link[$j][0], $product_image_link[$j][2]|cat : '-default', 'home_default')|escape:'html':'UTF-8'}"/>
											{/if}
										</div>
										<div class="wk-slider-product-info">
											<div style="margin-bottom:5px;">{$all_product_name[$j]|truncate:45:'...'|escape:'html':'UTF-8'}</div>
											<div style="font-weight:bold;">{convertPrice price=$all_product_price[$j]}</div>
										</div>
									</li>
								</a>
								{assign var=j value=$j+1}
							{/while}
						</ul> 
					</div>
				{else}
					<p class="alert alert-info">{l s='No item found'}</p>
				{/if}
			</div>
		</div>
		<div class="box-account">
			<div class="box-head">
				<div class="wk_review_head">
					<h2>{l s='Reviews about seller' mod='marketplace'}</h2>
				</div>
				<div class="wk_write_review">
					<a class="btn btn-default button button-small open-review-form forloginuser" href="#wk_review_form">
						<span>{l s='Write a Review' mod='marketplace'} !</span>
					</a>
				</div>
				<div class="wk_border_line"></div>
			</div>
			<div class="box-content">
				{if $reviews_count != 0}
				{foreach from=$reviews_details item=details}
				<div class="wk-reviews">
					<div class="wk-writer-info">
						<div class="wk-writer-details">
							<ul>
								<li class="wk-person-icon">{$details.customer_name|escape:'html':'UTF-8'}</li>
								<li class="wk-mail-icon">{$details.customer_email|escape:'html':'UTF-8'}</li>
								<li class="wk-watch-icon">{$details.time|escape:'html':'UTF-8'}</li>
							</ul>
						</div>
						<div class="wk-seller-rating">
							{assign var=i value=0}
							{while $i != $details.rating}
								<img src="{$modules_dir|escape:'html':'UTF-8'}/marketplace/img/star-on.png" />
							{assign var=i value=$i+1}
							{/while}

						  	{assign var=k value=0}	
						  	{assign var=j value=5-$details.rating}
						  	{while $k!=$j}
						   		<img src="{$modules_dir|escape:'html':'UTF-8'}/marketplace/img/star-off.png" />
						  	{assign var=k value=$k+1}
						 	{/while}
						</div>
					</div>
					<div class="wk_review_content">
						{$details.review|escape:'html':'UTF-8'}
					</div>
				</div>
				<div class="wk_border_line"></div>
				{/foreach}
					<a class="btn btn-default button button-small forloginuser" href="{$all_reviews_links|escape:'html':'UTF-8'}&seller_id={$seller_id|escape:'html':'UTF-8'}">
						<span>{l s='View all reviews' mod='marketplace'}</span>
					</a>
				{/if}
			</div>
		</div>
		{hook h='DisplayMpspcontentbottomhook'}
	</div>
</div>
</div>


<!-- Fancybox -->
<div style="display: none;">
	<div id="wk_review_form">
		<form id="review_submit" method="post" action="{$link->getModuleLink('marketplace', 'sellerprofile', ['shop' => {$id_shop}])}">
			<h2 class="page-subheading">
				{l s='Write a review' mod='marketplace'}
			</h2>
			<div>
				<div class="wk_review_form_content col-xs-12">
					<label for="comment_title">
						{l s='Add Rating' mod='marketplace'}: <sup class="required">*</sup>
					</label>
					 <span id="rating_image"></span>
					<label for="content">
						{l s='Comment' mod='marketplace'}: <sup class="required">*</sup>
					</label>
					<textarea name="feedback"></textarea>
					<input type="hidden" name="seller_id" value="{$seller_id|escape:'html':'UTF-8'}">
					<div id="wk_review_form_footer">
						<p class="fl required"><sup>*</sup> {l s='Required fields' mod='marketplace'}</p>
						<p class="fr">
							<button name="submit_feedback" type="submit" class="btn button button-small">
								<span>{l s='Send' mod='marketplace'}</span>
							</button>&nbsp;
							{l s='or' mod='marketplace'}&nbsp;
							<a class="closefb" href="#">
								{l s='Cancel' mod='marketplace'}
							</a>
						</p>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
$(function()
{
	if($('.best-sell .best-sell-product').length==0)
		$('.best-sell-box').hide();
	
	var wk_slider=$('.best-sell .best-sell-product').length;
	$('.ed_right').click(function() {
		if(wk_slider>3)
		{
		var thisthis=$(this).siblings('.best-sell');
		thisthis.animate(
		{
		"left":"-=480px"
		},1500);
		wk_slider=wk_slider-3;
		}
	});

	$('.ed_left').click(function() {
		var thisthis=$(this).siblings('.best-sell');
		if(wk_slider < $('.best-sell .best-sell-product').length){
			thisthis.animate(
			{
			"left":"+=480px"
			},1500);
			wk_slider=wk_slider+3;
		}
	});
});

//Review form submit
$('#review_submit').submit(function()
{
 var rating_image = $( "input[name='rating_image']" ).val();
 if(rating_image == '' || rating_image == ' ' )
 {
  alert('You have not given any rating');
  return false;
 }
});

//Rating image in review form
var id = 'rating_image';
$('#'+id).raty({
	path: '{$modules_dir|escape:'html':'UTF-8'}/marketplace/rateit/lib/img',
	scoreName: id,
								
});
</script>

{if $avg_rating>0}
<script type="text/javascript">
$('.avg_rating').raty(
{								
	path: '{$modules_dir|escape:'html':'UTF-8'}/marketplace/rateit/lib/img',
	score: {$avg_rating|escape:'html':'UTF-8'},
	readOnly: true,
});
</script>	
{/if}						
		






