<div class="main_block">
	{hook h="DisplayMpmenuhook"}
	<div class="dashboard_content">
		<div class="page-title">
			<span>{l s='Add New Banner' mod='mpshopbanner'}</span>
		</div>
		<div class="wk_right_col">
		<div class="banner_error"></div>
		{if $error}
			<div class="banner_img_error" style="display:block;">
				{l s='Image is Required Field' mod='mpshopbanner'}
			</div>
		{/if}
		<form onsubmit="return checkBannerForm();" method='post' class="banner_form" enctype="multipart/form-data" action="{$banner_process_link}" id="banner_form">
			<input id="shop" name="shop" value="{$id_shop}" type="hidden"/>			
			<div class="form-group">
				<label for="supp_name" class="control-label col-lg-3 required">{l s='Name' mod='mpshopbanner'}</label>
				<input class="reg_sel_input form-control" type="text" id="banner_name" name="banner_name"/>
			</div>
			
			<div class="form-group">
				<label for="supp_name" class="control-label col-lg-3 required">{l s='Image' mod='mpshopbanner'}</label>
				<input class="reg_sel_input form-control" type="file" name="file" id="file">			
			</div>
			
			<div class="form-group">
				<label for="supp_name" class="control-label col-lg-3">{l s='Status' mod='mpshopbanner'}</label>
				<div>
				<input type="radio" name="group1" value="1" checked>
				<img alt="Enabled" title="Enabled" class="mp_blog_change_post_status" src="{$img_ps_dir}admin/enabled-2.gif">
				
				<input type="radio" name="group1" value="0">
				<img alt="Disabled" title="Disabled" class="mp_blog_change_post_status" src="{$img_ps_dir}admin/disabled.gif">
				</div>
			</div>			
			<div class="form-group" style="text-align:center;">
				<button type="submit" id="banner_save" class="btn btn-default button button-medium">
					<span>{l s='Add Now' mod='mpshopbanner'}</span>
				</button>		
			</div>
		</form>
		</div>
	</div>
</div>