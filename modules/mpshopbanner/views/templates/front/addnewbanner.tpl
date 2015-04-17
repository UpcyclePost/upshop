<div class="left banner_request_container">
	<div class="left header_link">		
		<h1>{l s='Add New Banner' mod='mpshopbanner'}</h1>		
	</div>	
	<div class="back_link">
		<div class="home_link">
			<a href="{$dash_board_link}"><img src="{$img_dir}icon/home.gif" style="margin-top:11px;"></a> 
		</div>
		<div class="home_link" style="margin-top:18px;">
			<span class="navigation-pipe">></span>
		</div>
		<div id="back_button">
			<a href="{$back_link}"><img src="{$modules_dir}/mpblog/img/back.png"></a>
		</div>
	</div>
	{if $error}
	<div class="full left error">
		{l s='Image is Required Field' mod='mpshopbanner'}
	</div>
	{/if}
	<div class="banner_form_div">
		<form onsubmit="return checkBannerForm();" method='post'  enctype="multipart/form-data" action="{$banner_process_link}" id="banner_form">
			<input id="shop" name="shop" value="{$id_shop}" type="hidden"/>
			<div class="error" style="display:none; margin:2%;"></div>			
			<div class="full left padding_ten">
				<div class="row-info-left">{l s='Banner Name: ' mod='mpshopbanner'}<sup class="red">*</sup></div>
				<div class="row-info-right">
					<input class="reg_sel_input" type="text" id="banner_name" name="banner_name" required/>
				</div>
			</div>
			
			<div class="full left padding_ten">	
				<div class="row-info-left">{l s='Banner Image: ' mod='mpshopbanner'}<sup class="red">*</sup></div>
				<div class="row-info-right">
					<input class="reg_sel_input" type="file" name="file" id="file">			
				</div>
			</div>
			
			<div class="full left padding_ten">
				<div class="row-info-left">{l s='Active: ' mod='mpshopbanner'}</div>
				<div class="row-info-right">
				
					<input type="radio" name="group1" value="1" checked>
					<img alt="Enabled" title="Enabled" class="mp_blog_change_post_status" src="{$img_ps_dir}admin/enabled-2.gif">
					
					<input type="radio" name="group1" value="0">
					<img alt="Disabled" title="Disabled" class="mp_blog_change_post_status" src="{$img_ps_dir}admin/disabled.gif">					
				</div>
			</div>			
			<div class="" style="text-align:center;margin-bottom: 5%;">				
				<input type="submit" value="{l s='Add Now' mod='mpshopbanner'}" class="save_button button_large"  id="banner_save"/>
			</div>
		</form>
	</div>
	
</div>