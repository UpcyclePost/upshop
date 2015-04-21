<div class="main_block">
	{hook h="DisplayMpmenuhook"}
	<div class="dashboard_content">
		<div class="page-title">
			<span>{l s='Banner Collection' mod='mpshopbanner'}</span>
		</div>
		<div class="wk_right_col">
			<div class="wk_banner_head">
				<a class="btn btn-default button button-small" href="{$link_add_new}">
					<span>{l s='Add New' mod='mpshopbanner'}</span>
				</a>
			</div>
		<div class="wk_banner_list">
			<table class="data-table" style="width:100%;">
		  	<input type="hidden" id="banner_front_link" value="{$banner_front_link}"/>
		    <thead>
		    	<tr class="first last">
			      <th>{l s='Banner Name' mod='mpshopbanner'}</th>
			      <th>{l s='Image' mod='mpshopbanner'}</th>
			      <th>{l s='Action' mod='mpshopbanner'}</th>
			      <th>{l s='Status' mod='mpshopbanner'}</th>
		      	</tr>
		    </thead>
		    <tbody>
			{if $banner == 1}	
			{foreach $banner_list as $list}		
				<tr class="even" id="mp_banner_{$list['id']}">
				<td>{$list['name']}</td>
				<td>
					<a class="fancybox" href="{$modules_dir}/mpshopbanner/img/banner_image/{$list['id']}.jpg">
						<img width="100" height="50" src="{$modules_dir}/mpshopbanner/img/banner_image/{$list['id']}.jpg">
					</a>
				</td>
				<td>
					{if !$list['is_active']}					
						<img alt="Delete" title="Delete" style="cursor:pointer;" class="mp_delete_banner" mp_banner_id="{$list['id']}" src="{$img_ps_dir}admin/delete.gif">
					{/if}			
				</td>
				<td>
					{if $list['is_active']}					
						<img alt="Enabled" title="Enabled" class="mp_banner_status" src="{$img_ps_dir}admin/enabled-2.gif">
					{else}					
						<a href="{$link->getModuleLink('mpshopbanner', 'bannerfrontaction', ['shop' => {$id_shop}, 'mp_banner_id' => {$list['id']}, 'fun' => 'change_status'])}">
							<img alt="Disabled" title="Disabled" class="mp_banner_status"  src="{$img_ps_dir}admin/disabled.gif">
						</a>
					{/if}			
				</td>
				</tr>
			{/foreach}	
			{/if}
			</tbody>
		</table>
		{if $banner == 0}
			<p style="text-align:center;font-weight:bold;">{l s='No data found' mod='mpshopbanner'}</p>
		{/if}	
		</div>
</div>
</div>
</div>
<script type="text/javascript">
  $('.fancybox').fancybox();
</script>