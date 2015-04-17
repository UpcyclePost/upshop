<div class="main_error">
	{if $is_main_er=='width_error'}
		<div class="warn">
			{l s='Package width should be numeric.' mod='mpshipping'}
		</div>
	{else if $is_main_er=='height_error'}
		<div class="warn">
			{l s='Package height should be numeric.' mod='mpshipping'}
		</div>
	{else if $is_main_er=='depth_error'}
		<div class="warn">
			{l s='Package depth should be numeric.' mod='mpshipping'}
		</div>
	{else if $is_main_er=='weight_error'}
		<div class="warn">
			{l s='Package weight should be numeric.' mod='mpshipping'}
		</div>
	{/if}
</div>