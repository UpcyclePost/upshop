<style>
#mp_main_block
{
 float:left;
}
.mp_info_block
{
 margin-top:5px;
 float:left;
 width:100%;
}
.mp_title
{
 float:left;
 width:200px;
 font-weight:bold;
}
.desc
{
 float:left;

}
</style>

<div id="mp_main_block">
	<div class="mp_info_block">
		<div class="mp_title">{l s='Customer Name' mod='marketplace'} :</div>
		<div class="desc">
		 {$customer_name|escape:'html':'UTF-8'}
		</div> 
	</div>
	<div class="mp_info_block">
		<div class="mp_title">{l s='Customer Email' mod='marketplace'} :</div>
		<div class="desc">
		 {$review_detail['customer_email']|escape:'html':'UTF-8'}
		</div>
	</div>
	<div class="mp_info_block">
		<div class="mp_title">{l s='Rating' mod='marketplace'} :</div>
		<div class="desc">
		 {for $foo=1 to $review_detail['rating']}
		    <img src="../modules/marketplace/img/star-on.png" />
		 {/for}
		</div>
	</div>
	<div class="mp_info_block">
		<div class="mp_title">{l s='Customer Review' mod='marketplace'} :</div>
		<div class="desc">
			{$review_detail['review']|escape:'html':'UTF-8'}
		</div>
	</div>
	<div class="mp_info_block">
		<div class="mp_title">{l s='Time' mod='marketplace'} :</div>
		<div class="desc">
		 {$review_detail['timestamp']|escape:'html':'UTF-8'}
		</div>
	</div>
</div>
