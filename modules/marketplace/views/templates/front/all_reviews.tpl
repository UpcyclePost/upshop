{if $cust == 0}
<style>
.dashboard_content{
	width:100% !important;
}
</style>
{/if}

{capture name=path}{l s='All Reviews' mod='marketplace'}{/capture}
<div class="main_block">
	{if $cust != 0}
		{hook h="DisplayMpmenuhook"}
	{/if}
	<div class="dashboard_content">
	<div class="page-title">
		<span>{l s='All Reviews' mod='marketplace'} ({$reviews_count|escape:'html':'UTF-8'})</span>
	</div>
	<div class="wk_right_col">
		<div class="box-account">
			<div class="box-head">
			</div>
			<div class="box-content">
				{if $reviews_count != 0}
					{assign var=l value=1}
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
					 {assign var=l value=$l+1}
					{/foreach}
				{else}
					<p>{l s='No reviews available' mod='marketplace'}</p>
				{/if}
			</div>	
		</div>
	</div>
</div>