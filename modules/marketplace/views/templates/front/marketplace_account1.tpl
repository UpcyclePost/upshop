<style type="text/css">
.page-title{
	background-color: {$title_bg_color|escape:'html':'UTF-8'} !important;
}
.page-title span{
	color: {$title_text_color|escape:'html':'UTF-8'} !important;
}
.color_one {
	float:left;
	width:100%;
}
</style>

<script type="text/javascript">
	//Validation Valiables
	var phone_digit = '{$phone_digit|escape:'html':'UTF-8'}';
	var req_seller_name = '{l s='Seller name is required.' js=1 mod='marketplace'}';
	var inv_seller_name = '{l s='Invalid Seller Name.' js=1 mod='marketplace'}';
	var req_shop_name = '{l s='Shop name is required.' js=1 mod='marketplace'}';
	var inv_shop_name = '{l s='Invalid shop name.' js=1 mod='marketplace'}';
	var req_email = '{l s='Email is required field' js=1 mod='marketplace'}';
	var inv_email = '{l s='Invalid email address.' js=1 mod='marketplace'}';
	var req_phone = '{l s='Phone number is required.' js=1 mod='marketplace'}';
	var inv_phone = '{l s='Invalid phone number.' js=1 mod='marketplace'}';
</script>

{if $logic==3}
<script type="text/javascript">
	var space_error = '{l s='Space is not allowed.' js=1 mod='marketplace'}';
	var confirm_delete_msg = '{l s='Do you want to delete the photo?' js=1 mod='marketplace'}';
	var delete_msg = '{l s='Deleted.' js=1 mod='marketplace'}';
	var error_msg = '{l s='An error occurred.' js=1 mod='marketplace'}';
	var src_more = '{$img_ps_dir|escape:'html':'UTF-8'}admin/more.png';
	var src_less = '{$img_ps_dir|escape:'html':'UTF-8'}admin/less.png';
	var src_forbidden = '{$img_ps_dir|escape:'html':'UTF-8'}admin/forbbiden.gif';
	var src_enabled = '{$img_ps_dir|escape:'html':'UTF-8'}admin/enabled.gif';
	var ajax_urlpath = '{$imageediturl|escape:'intval'}';
	var id_lang = '{$id_lang|escape:'html':'UTF-8'}';
</script>
{/if}
<div class="main_block" >
	{hook h="DisplayMpmenuhook"}
	{if $logic==1}
	{capture name=path}
        <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
                {l s='Marketplace account'}
        </a>
        <span class="navigation-pipe">{$navigationPipe}</span>
        <span class="navigation_page">{l s='Dashboard' mod='marketplace'}</span>
	{/capture}

	<div class="dashboard_content">
			<div class="dashboard">
				<div class="page-title">
					<span>{l s='My Dashboard' mod='marketplace'}</span>
				</div>
				<div class="wk_right_col">
				<div class="left full">
					{hook h='DisplayMpdashboardtophook'}
				</div>
				<div class="left full">
					{hook h='DisplayMpdashboardbottomhook'}
				</div>
				<div class="box-account box-recent">
					<div class="box-head">
						<div class="box-head-left">
						<h2>{l s='Recent Orders' mod='marketplace'}</h2>
						</div>
						<div class="box-head-right">
						<a class="btn btn-default button button-small" href="{$link->getModuleLink('marketplace','marketplaceaccount',['shop'=>{$id_shop|escape:'html':'UTF-8'},'l'=>4])|escape:'html':'UTF-8'}"><span>{l s='View All' mod='marketplace'}</span></a>
						</div>
					</div>
					<div class="wk_border_line"></div>
					<div class="box-content" >
					<div class="wk_order_table">
						<table class="data-table" id="my-orders-table" style="width:100%;">
							<thead>
								<tr class="first last">
								<th>{l s='Order' mod='marketplace'} #</th>
								<th>{l s='Date' mod='marketplace'}</th>
								<th>{l s='Ship To' mod='marketplace'}</th>
								<th>
									<span class="nobr">{l s='Order Total' mod='marketplace'}</span>
								</th>
								<th>{l s='Status' mod='marketplace'}</th>
								</tr>
							</thead>
							<tbody>
								{assign var=i value=0}
								{while $i != $count}
									<tr class="even">
										<td>{$dashboard[$i]['id_order']|escape:'html':'UTF-8'}</td>
										<td><span class="nobr">{$dashboard[$i]['date_add']|escape:'html':'UTF-8'}</span></td>
										<!-- <td>{$dashboard[$i]['name']|escape:'html':'UTF-8'}</td> -->
										<td>{$order_by_cus[$i]['firstname']|escape:'html':'UTF-8'}</td>
										<td><span class="price">{$currency->prefix}{$dashboard[$i]['total_price']|string_format:"%.2f"}{$currency->suffix}</span></td>
										<td><em>{$dashboard[$i]['order_status']|escape:'html':'UTF-8'}</em></td>
									</tr>
								{assign var=i value=$i+1}
								{/while}
							</tbody>
						</table>
					</div>
					</div>
				</div>	
				<div class="box-account box-recent">
					<div class="box-head">
						<h2>{l s='Orders Graph' mod='marketplace'}</h2>
						<div class="wk_border_line"></div>
					</div>
					<div class="box-content">
					<div class="wk_from_to">
						<div class="wk_from">
							<div class="labels">
								{l s='From' mod='marketplace'}
							</div>
							<div class="input_type">
								<input id="graph_from" class="datepicker form-control" type="text" style="text-align: center" value="{$from_date|escape:'html':'UTF-8'}" name="graph_from">
							</div>
						</div>
						<div class="wk_to">
							<div class="labels">
								{l s='To' mod='marketplace'}
							</div>
							<div class="input_type">
								<input id="graph_to" class="datepicker1 form-control" type="text" style="text-align: center" value="{$to_date|escape:'html':'UTF-8'}" name="graph_to">
							</div>
						</div>
					</div>
					<div id="chart_div" style="width:100%; height: 500px;overflow:hidden;"></div>
				</div>
					<script type="text/javascript" src="https://www.google.com/jsapi"></script>
					<script type="text/javascript">
						var order = '{l s='order' js=1 mod='marketplace'}';
						var order_value = '{l s='value' js=1 mod='marketplace'}';
						google.load("visualization", "1", {
										  packages:["corechart"]
									});
						
						google.setOnLoadCallback(drawChart);  
						function drawChart()
						{
							{assign var=i value={$loop_exe}}
							var data = google.visualization.arrayToDataTable([
							['date_add', order, order_value],
							{while $i>0}
							{if $i>1}
								['{$newdate[$i]|escape:'html':'UTF-8'}',{$count_order_detail[$i]|escape:'html':'UTF-8'},{$product_price_detail[$i]|escape:'html':'UTF-8'}],
							{else}
								['{$newdate[$i]|escape:'html':'UTF-8'}',{$count_order_detail[$i]|escape:'html':'UTF-8'},{$product_price_detail[$i]|escape:'html':'UTF-8'}],
							{/if}
							{assign var=i value=$i-1}
							{/while}
							]);
							var options = {
							  title: 'Premium income',

							  pointSize:3,
							  vAxis: {
									minValue: 1
									}
							};
							var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
							chart.draw(data, options);
						}
						//for reqponsiveness
						$(window).resize(function(){
						  	drawChart();
						});
					</script>
				</div>
				</div>	
			</div>
			<script type="text/javascript">
				$('.datepicker').datepicker({
					dateFormat: 'yy-mm-dd',
					defaultDate: -30
				});
				$('.datepicker1').datepicker({
					dateFormat: 'yy-mm-dd'
				});
				$('#graph_to').change(function(e) {
					 var from_date = $('#graph_from').val();
					var to_date = $(this).val();
					document.location.href="{$base_dir|escape:'html':'UTF-8'}index.php?fc=module&module=marketplace&controller=marketplaceaccount&shop=1&l=1&from_date="+from_date+"&to_date="+to_date;
				});
			</script>
	</div>
	{else if $logic==2}
	{capture name=path}
        <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
                {l s='Marketplace account'}
        </a>
        <span class="navigation-pipe">{$navigationPipe}</span>
        <span class="navigation_page">{l s='Edit Profile' mod='marketplace'}</span>
	{/capture}
	<div class="dashboard_content">
		{if $is_profile_updated == 1}
			<p class="alert alert-success">{l s='Profile information successfully updated.' mod='marketplace'}</p>
		{/if}
		{if $shop_img_size_error == 1}
			<p class="alert alert-danger">{l s='Shop logo image minimum size must be 200 x 200px' mod='marketplace'}</p>
		{/if}
		{if $seller_img_size_error==1}
			<p class="alert alert-danger">{l s='Seller image minumum size must be 200 x 200px' mod='marketplace'}</p>
		{/if}
		<div class="dashboard">
			<div class="page-title">
				<span>{l s='Edit Profile' mod='marketplace'}</span>
			</div>
			<div class="wk_right_col">
				<div class="profile_content">
					<div class="profile_content_heading">
						{if $edit==0}
							<div class="heading_name">{l s='Account information' mod='marketplace'}</div>
							<div class="heading_option">
								<a href="{$edit_profile|escape:'html':'UTF-8'}" class="btn btn-default button button-small">
									<span>{l s='Edit' mod='marketplace'}</span>
								</a>
							</div>
						{/if}
					</div>
					{if $edit==0}
						<div class="account-detail">
							{if $update}
								{if $update==1}	
									<div class="row-info">
										<div class="update_success">
											{l s='Profile information successfully updated.' mod='marketplace'}
										</div>
									</div>
								{else $update==0}
									<div class="row-info">
										<div class="update_error">
											{l s='some error occurs while updating your profile.' mod='marketplace'}
										</div>
									</div>
								{/if}
							{/if}
							<div class="row-info">
								<div class="row-info-left">{l s='Seller Name' mod='marketplace'}</div>
								<div class="row-info-right">{$marketplace_seller_info['seller_name']|escape:'html':'UTF-8'}</div>
							</div>
							<div class="row-info">
								<div class="row-info-left">{l s='Shop Name' mod='marketplace'}</div>
								<div class="row-info-right">{$marketplace_seller_info['shop_name']|escape:'html':'UTF-8'}</div>
							</div>
							<div class="row-info">
								<div class="row-info-left">{l s='Business email' mod='marketplace'}</div>
								<div class="row-info-right">{$marketplace_seller_info['business_email']|escape:'html':'UTF-8'}</div>
							</div>
							<div class="row-info">
								<div class="row-info-left">{l s='Phone' mod='marketplace'}</div>
								<div class="row-info-right">{$marketplace_seller_info['phone']|escape:'html':'UTF-8'}</div>
							</div>
							<div class="row-info">
								<div class="row-info-left">{l s='Fax' mod='marketplace'}</div>
								<div class="row-info-right">{$marketplace_seller_info['fax']|escape:'html':'UTF-8'}</div>
							</div>
							<div class="row-info">
								<div class="row-info-left">{l s='Facebook Id' mod='marketplace'}</div>
								<div class="row-info-right">
									{if $marketplace_seller_info['facebook_id']==''}
										{l s='Not found' mod='marketplace'}
									{else}
										{$marketplace_seller_info['facebook_id']|escape:'html':'UTF-8'}
									{/if}
								</div>
							</div>
							<div class="row-info">
								<div class="row-info-left">{l s='Twitter Id' mod='marketplace'}</div>
								<div class="row-info-right">
									{if $marketplace_seller_info['twitter_id']==''}
										{l s='Not found' mod='marketplace'}
									{else}
										{$marketplace_seller_info['twitter_id']|escape:'html':'UTF-8'}
									{/if}
								</div>
							</div>
							<div class="row-info">
								<div class="row-info-left">{l s='Address' mod='marketplace'}</div>
								<div class="row-info-right">{$marketplace_seller_info['address']|escape:'html':'UTF-8'}</div>
							</div>
							<div class="row-info">
								<div class="row-info-left">{l s='About Shop' mod='marketplace'}</div>
								<div class="row-info-right">
									{if $market_place_shop['about_us']==''}
										{l s='Please write some information about your shop' mod='marketplace'}
									{else}
										{$market_place_shop['about_us']|escape:'intval'}
									{/if}
								</div>
							</div>
							<div class="row-info">
								<div class="row-info-left">{l s='Shop Logo' mod='marketplace'}</div>
								<div class="row-info-right"><img src='{$logo_path|escape:'html':'UTF-8'}' width="100" height="100"  alt={$marketplace_seller_info['shop_name']|escape:'html':'UTF-8'}></div>
							</div>
							{hook h="DisplayMpshopviewfooterhook"}
						</div>
					{else if $edit==1}
						<form action="{$editprofile|escape:'html':'UTF-8'}" method="post"   enctype="multipart/form-data" accept-charset="UTF-8,ISO-8859-1,UTF-16">
							<input type="hidden" value="{$id_shop|escape:'html':'UTF-8'}" name="update_id_shop" />
							<div class="container">
								<fieldset>
								<div class="form-group">
									<div class="update_error">
									  <!-- {if $shop_img_size_error == 1}
										{l s='Shop logo image minimum size must be 200 x 200px' mod='marketplace'}
									  {/if}
									  {if $seller_img_size_error==1}
										{l s='Seller image minumum size must be 200 x 200px' mod='marketplace'}
									  {/if} -->
									  {hook h='displayMpUpdateSellerProfileHeaderhook'}
									</div>
								</div>
								<div class="required form-group">	
									<label for="update_seller_name" class="control-label required">{l s='Seller Name' mod='marketplace'}</label>
									<input class="required form-control" type="text" value="{$marketplace_seller_info['seller_name']|escape:'html':'UTF-8'}" name="update_seller_name" id="update_seller_name"/>
								</div>
								<div class="required form-group">
									<img src="{$old_seller_logo_path|escape:'html':'UTF-8'}" width="100" height="100"><br />
									<label class="control-label">{l s='Seller Profile Image' mod='marketplace'}</label>
									<input class="required form-control" type="file" name="update_seller_logo" id="update_seller_logo"/>
									<div class="info_description">{l s='Image minimum size must be 200 x 200px' mod='marketplace'}</div>
								</div>
								<div class="required form-group">
									<label for="update_shop_name" class="control-label required">{l s='Shop Name' mod='marketplace'}</label>
									<input class="required form-control" type="text" value="{$marketplace_seller_info['shop_name']|escape:'html':'UTF-8'}" name="update_shop_name" id="update_shop_name"/>
								</div>
								<div class="required form-group">
									<label for="update_business_email" class="control-label required">{l s='Business email' mod='marketplace'}</label>
									<input class="required form-control" type="text" value="{$marketplace_seller_info['business_email']|escape:'html':'UTF-8'}" name="update_business_email" id="update_business_email"/>
								</div>
								<div class="required form-group">
									<label for="update_phone" class="control-label required">{l s='Phone' mod='marketplace'}</label>
									<input class="required form-control" type="text" value="{$marketplace_seller_info['phone']|escape:'html':'UTF-8'}" name="update_phone" id="update_phone" maxlength="{$phone_digit}"/>
								</div>
								<div class="form-group">
									<label for="update_fax" class="control-label">{l s='Fax' mod='marketplace'}</label>
									<input class="required form-control" type="text" value="{$marketplace_seller_info['fax']|escape:'html':'UTF-8'}" name="update_fax" id="update_fax"/>
								</div>
								<div class="form-group">
									<label for="update_facbook_id" class="control-label">{l s='Facebook Id' mod='marketplace'}</label>
									<input class="required form-control" type="text" value="{$marketplace_seller_info['facebook_id']|escape:'html':'UTF-8'}" name="update_facbook_id" id="update_facbook_id"/>
								</div>
								<div class="form-group">
									<label for="update_twitter_id" class="control-label">{l s='Twitter Id' mod='marketplace'}</label>
									<input class="required form-control" type="text" value="{$marketplace_seller_info['twitter_id']|escape:'html':'UTF-8'}" name="update_twitter_id" id="update_twitter_id"/>
								</div>
								<div class="form-group">
									<label for="update_address" class="control-label">{l s='Address' mod='marketplace'}</label>
									<textarea class="required form-control"  name="update_address" id="update_address">{$marketplace_address|escape:'html':'UTF-8'}</textarea>
								</div>
								<div class="form-group">
									<label for="update_about_shop" class="control-label">{l s='About Shop' mod='marketplace'}</label>
									<textarea name="update_about_shop" id="update_about_shop" class="update_about_shop_detail wk_tinymce form-control">{$market_place_shop['about_us']|escape:'html':'UTF-8'}</textarea>
								</div>
								<div class="form-group">
									<img src="{$old_shop_logo_path|escape:'html':'UTF-8'}" width="100" height="100"><br />
									<label for="update_shop_logo" class="control-label">{l s='Shop Logo' mod='marketplace'}</label>
									<input class="required form control" type="file" name="update_shop_logo" id="update_shop_logo"/>
									<div class="info_description">{l s='Image minimum size must be 200 x 200px' mod='marketplace'}</div>
								</div>
								{hook h="DisplayMpshopaddfooterhook"}
								<div class="submit-button">
									<button type="submit" id="update_profile" class="btn btn-default button button-medium">
										<span>{l s='Update' mod='marketplace'}</span>
									</button>
								</div>
								</fieldset>
							</div>							
						</form>
					{/if}
				</div>
			</div>
		</div>
	</div>
	{else if $logic==3}
        {capture name=path}
        <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
                {l s='Marketplace account'}
        </a>
        <span class="navigation-pipe">{$navigationPipe}</span>
        <span class="navigation_page">{l s='Product List' mod='marketplace'}</span>
        {/capture}
			<div class="dashboard_content">
				{if $is_deleted == 1}
					<p class="alert alert-success">{l s='Deleted Successful' mod='marketplace'}</p>
				{else if $is_edited == 1}
					<p class="alert alert-success">{l s='Updated Successful' mod='marketplace'}</p>
				{/if}
				<div class="page-title">
					<span>{l s='Product List' mod='marketplace'}</span>
				</div>
				<div class="wk_right_col">
					<!-- sorting code start -->
					{if $product_lists!=0}
						<div id="refine_search">
							<div class="sortPagiBar clearfix">
								<form id="productsSortForm" action="{$sorting_link|escape:'html':'UTF-8'}&p={$page_no|escape:'html':'UTF-8'}">
									<label for="selectPrductSort">{l s='Sort by' mod='marketplace'}</label>
									<select id="selectPrductSort" class="selectSortProduct">
										<option value="position:asc" selected="selected">--</option>
										<option value="price:asc">{l s='Price: lowest first' mod='marketplace'}</option>
										<option value="price:desc">{l s='Price: highest first' mod='marketplace'}</option>
										<option value="name:asc">{l s='Product Name: A to Z' mod='marketplace'}</option>
										<option value="name:desc">{l s='Product Name: Z to A' mod='marketplace'}</option>
										<option value="date_add:asc">{l s='Creation Date: asc' mod='marketplace'}</option>
										<option value="date_add:desc">{l s='Creation Date: desc' mod='marketplace'}</option>
									</select>
								</form>

								<script type="text/javascript">
									var min_item = 'Please select at least one product';
									var max_item = "You cannot add more than 3 product(s) to the product comparison";
									$(document).ready(function(){
									$('.selectSortProduct').change(function()
										{
										
											var requestSortProducts = '{$sorting_link|escape:'intval'}';
											var splitData = $(this).val().split(':');
											
											document.location.href = requestSortProducts + ((requestSortProducts.indexOf('?') < 0) ? '?' : '&') + 'orderby=' + splitData[0] + '&orderway=' + splitData[1];
											
										});
									});
								</script>
							</div>
						</div>
					{/if}
					<!-- sorting code end -->
					<div class="wk_product_list">
						<div class="left full">
							{hook h="DisplayMpproductdetailheaderhook"}
						</div>
						<table class="data-table" id="my-orders-table" style="width:100%;">
							<thead>
								<tr class="first last">
									<th>{l s='Name' mod='marketplace'}</th>
									<th>{l s='Description' mod='marketplace'}</th>
									<th>{l s='Price' mod='marketplace'}</th>
									<th>{l s='Quantity' mod='marketplace'}</th>
									<th>{l s='Status' mod='marketplace'}</th>
									<th>{l s='Action' mod='marketplace'}</th>
									<th>{l s='Image' mod='marketplace'}</th>
								</tr>
							</thead>
						<tbody>
						{if $product_lists!=0}
							{assign var=i value=1} 
							{foreach $product_lists as $product}
								<tr class="even">
									<td>
										<a href="{$product_details_link|escape:'html':'UTF-8'}&id={$product['id']|escape:'html':'UTF-8'}">
										{$product['product_name']|escape:'html':'UTF-8'}
										</a>
									</td>
									<td>
										<a href="{$product_details_link|escape:'html':'UTF-8'}&id={$product['id']|escape:'html':'UTF-8'}">
										{$product['description']|truncate:30|strip_tags}
										</a>
									</td>
									<td>
										<a href="{$product_details_link|escape:'html':'UTF-8'}&id={$product['id']|escape:'html':'UTF-8'}">
										{$currency->prefix}{$product['price']|string_format:"%.2f"}{$currency->suffix}
										</a>
									</td>
									<td>
										<a href="{$product_details_link|escape:'html':'UTF-8'}&id={$product['id']|escape:'html':'UTF-8'}">
										{$product['quantity']|escape:'html':'UTF-8'}
										</a>
									</td>
									{if $product['active'] == 0}
										<td>
											{l s='Pending' mod='marketplace'}
										</td>
									{else}
										<td>
											{l s='Approved' mod='marketplace'}
										</td>
									{/if}
									<td>
										<img id="{$product['id']|escape:'html':'UTF-8'}" class="edit_img" src="{$img_ps_dir|escape:'html':'UTF-8'}admin/edit.gif"/>
										<img id="{$product['id']|escape:'html':'UTF-8'}" class="delete_img" src="{$img_ps_dir|escape:'html':'UTF-8'}admin/delete.gif"/>
										{hook h="PriceDisplay" id_product=$product['id']|escape:'html':'UTF-8'}
									</td>
									<td>
										<a href="" class="edit_seq"  alt="1"  product-id="{$product['id']|escape:'intval'}" id="">
											<img class="img_detail" alt="Details" id="edit_seq{$product['id']|escape:'html':'UTF-8'}" src="{$img_ps_dir|escape:'html':'UTF-8'}admin/more.png">
										</a>
										<input type="hidden" id="urlimageedit" value="{$imageediturl|escape:'html':'UTF-8'}"/>
									</td>
								</tr>
								<div  class="row_info">
									<div id="content{$product['id']|escape:'html':'UTF-8'}" class="content_seq">
									</div>
								</div>
								{assign var=i value=$i+1}
							{/foreach}
							{* Pagination Code Start*}
							{if isset($no_follow) AND $no_follow}
								{assign var='no_follow_text' value='rel="nofollow"'}
							{else}
								{assign var='no_follow_text' value=''}
							{/if}
							{if isset($p) AND $p}
								{if isset($smarty.get.id_category) && $smarty.get.id_category && isset($category)}
									{if !isset($current_url)}
										{assign var='requestPage' value=$link->getPaginationLink('category', $category, false, false, true, false)}
									{else}
										{assign var='requestPage' value=$current_url}
									{/if}
										{assign var='requestNb' value=$link->getPaginationLink('category', $category, true, false, false, true)}
									{elseif isset($smarty.get.id_manufacturer) && $smarty.get.id_manufacturer && isset($manufacturer)}
										{assign var='requestPage' value=$link->getPaginationLink('manufacturer', $manufacturer, false, false, true, false)}
										{assign var='requestNb' value=$link->getPaginationLink('manufacturer', $manufacturer, true, false, false, true)}
									{elseif isset($smarty.get.id_supplier) && $smarty.get.id_supplier && isset($supplier)}
										{assign var='requestPage' value=$link->getPaginationLink('supplier', $supplier, false, false, true, false)}
										{assign var='requestNb' value=$link->getPaginationLink('supplier', $supplier, true, false, false, true)}
									{else}
										{assign var='requestPage' value=$pagination_link}
										{assign var='requestNb' value=$pagination_link}
									{/if}
							<!-- Pagination -->
									<div id="pagination{if isset($paginationId)}_{$paginationId}{/if}" class="pagination">
									{if $start!=$stop}
										<ul class="pagination">
											{if $p != 1}
								
											{assign var='p_previous' value=$p-1}
												<li id="pagination_previous{if isset($paginationId)}_{$paginationId}{/if}" class="pagination_previous"><a {$no_follow_text} href="{$link->goPage($requestPage, $p_previous)}">&laquo;&nbsp;{l s='Previous' mod='marketplace'}</a></li>
											{else}
												<li id="pagination_previous{if isset($paginationId)}_{$paginationId}{/if}" class="disabled pagination_previous"><span>&laquo;&nbsp;{l s='Previous' mod='marketplace'}</span></li>
											{/if}
											{if $start==3}
								
												<li><a {$no_follow_text|escape:'html':'UTF-8'}  href="{$link->goPage($requestPage, 1)|escape:'html':'UTF-8'}">1</a></li>
												<li><a {$no_follow_text|escape:'html':'UTF-8'}  href="{$link->goPage($requestPage, 2)|escape:'html':'UTF-8'}">2</a></li>
											{/if}
											{if $start==2}
												<li><a {$no_follow_text|escape:'html':'UTF-8'}  href="{$link->goPage($requestPage, 1)|escape:'html':'UTF-8'}">1</a></li>
											{/if}
											{if $start>3}
												<li><a {$no_follow_text|escape:'html':'UTF-8'}  href="{$link->goPage($requestPage, 1)|escape:'html':'UTF-8'}">1</a></li>
												<li class="truncate">...</li>
											{/if}
											{section name=pagination start=$start loop=$stop+1 step=1}
												{if $p == $smarty.section.pagination.index}
													<li class="current"><span>{$p|escape:'htmlall':'UTF-8'}</span></li>
												{else}
													<li><a {$no_follow_text} href="{$link->goPage($requestPage, $smarty.section.pagination.index)}">{$smarty.section.pagination.index|escape:'htmlall':'UTF-8'}</a></li>
												{/if}
											{/section}
											{if $pages_nb>$stop+2}
												<li class="truncate">...</li>
												<li><a href="{$link->goPage($requestPage, $pages_nb)}">{$pages_nb|intval}</a></li>
											{/if}
											{if $pages_nb==$stop+1}
												<li><a href="{$link->goPage($requestPage, $pages_nb)}">{$pages_nb|intval}</a></li>
											{/if}
											{if $pages_nb==$stop+2}
												<li><a href="{$link->goPage($requestPage, $pages_nb-1)}">{$pages_nb-1|intval}</a></li>
												<li><a href="{$link->goPage($requestPage, $pages_nb)}">{$pages_nb|intval}</a></li>
											{/if}
											{if $pages_nb > 1 AND $p != $pages_nb}
												{assign var='p_next' value=$p+1}
													<li id="pagination_next{if isset($paginationId)}_{$paginationId}{/if}" class="pagination_next"><a {$no_follow_text} href="{$link->goPage($requestPage, $p_next)}">{l s='Next' mod='marketplace'}&nbsp;&raquo;</a></li>
												{else}
													<li id="pagination_next{if isset($paginationId)}_{$paginationId}{/if}" class="disabled pagination_next"><span>{l s='Next' mod='marketplace'}&nbsp;&raquo;</span></li>
												{/if}
											</ul>
										{/if}
										{if $nb_products > $products_per_page}
											<form action="{if !is_array($requestNb)}{$requestNb}{else}{$requestNb.requestUrl}{/if}" method="get" class="pagination">
												<p>
													{if isset($search_query) AND $search_query}<input type="hidden" name="search_query" value="{$search_query|escape:'htmlall':'UTF-8'}" />{/if}
													{if isset($tag) AND $tag AND !is_array($tag)}<input type="hidden" name="tag" value="{$tag|escape:'htmlall':'UTF-8'}" />{/if}
													<input type="submit" class="button_mini" value="{l s='OK' mod='marketplace'}" />
													<label for="nb_item">{l s='items:' mod='marketplace'}</label>
													<select name="n" id="nb_item">
														{assign var="lastnValue" value="0"}
														{foreach from=$nArray item=nValue}
															{if $lastnValue <= $nb_products}
																<option value="{$nValue|escape:'htmlall':'UTF-8'}" {if $n == $nValue}selected="selected"{/if}>{$nValue|escape:'htmlall':'UTF-8'}</option>
															{/if}
															{assign var="lastnValue" value=$nValue}
														{/foreach}
													</select>
													{if is_array($requestNb)}
														{foreach from=$requestNb item=requestValue key=requestKey}
															{if $requestKey != 'requestUrl'}
																<input type="hidden" name="{$requestKey|escape:'htmlall':'UTF-8'}" value="{$requestValue|escape:'htmlall':'UTF-8'}" />
															{/if}
														{/foreach}
													{/if}
												</p>
											</form>
										{/if}
									</div>
									{* Pagination code end *}
								{/if}
							{/if}
						</tbody>
						</table>
						{if $product_lists == 0}
							<div class="color_one"  name="dash" style="text-align:center;">
								{l s='No data found' mod='marketplace'}
							</div>
						{/if}
					</div>
			</div>
			</div>
			<div class="left full">
				{hook h="DisplayMpproductdetailfooterhook"}
			</div>
	{else if $logic==4}
        {capture name=path}
        <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
                {l s='Marketplace account'}
        </a>
        <span class="navigation-pipe">{$navigationPipe}</span>
        <span class="navigation_page">{l s='My Orders' mod='marketplace'}</span>
        {/capture}

		<div class="dashboard_content">
		<div class="dashboard">
			<div class="page-title">
				<span>{l s='My Orders' mod='marketplace'}</span>
			</div>
			<div class="wk_right_col">
				<div class="box-account box-recent">
					<div class="box-head">
						<h2>{l s='Recent Orders' mod='marketplace'}</h2>
						<div class="wk_border_line"></div>
					</div>
					<div class="box-content">
					<div class="wk_order_table">		
					<table class="data-table" id="my-orders-table">
						<thead>
							<tr class="first last">
								<th>{l s='Order #' mod='marketplace'}</th>
								<th>{l s='Date' mod='marketplace'}</th>
								<th>{l s='Ship To' mod='marketplace'}</th>
								<th>{l s='Status' mod='marketplace'}</th>
								<th>{l s='Payment Mode' mod='marketplace'}</th>
							</tr>
						</thead>
						<tbody>
							<input type="hidden" id="id_shop_order" name="id_shop_order" value="{$id_shop|escape:'html':'UTF-8'}" />
							<input type="hidden" id="order_link" name="order_link" value="{$order_view_link|escape:'html':'UTF-8'}" />
							{assign var=i value=0}
							{while $i != $count}	
								{if $i % 2 == 0}
									<tr class="even order_tr" is_id_order="{$dashboard[$i]['id_order']|escape:'html':'UTF-8'}" is_id_order_detail="{$dashboard[$i]['id_order_detail']|escape:'html':'UTF-8'}">
								{else}
									<tr class="odd order_tr" is_id_order="{$dashboard[$i]['id_order']|escape:'html':'UTF-8'}" is_id_order_detail="{$dashboard[$i]['id_order_detail']|escape:'html':'UTF-8'}">
								{/if}
										<td><a href="{$link->getModuleLink('marketplace','marketplaceaccount',['flag'=>1,'shop'=>{$id_shop|escape:'html':'UTF-8'},'l'=>6,id_order=>{$dashboard[$i]['id_order']|escape:'html':'UTF-8'}])|escape:'html':'UTF-8'}">{$dashboard[$i]['id_order']|escape:'html':'UTF-8'}</a></td>

										<td><a href="{$link->getModuleLink('marketplace','marketplaceaccount',['flag'=>1,'shop'=>{$id_shop|escape:'html':'UTF-8'},'l'=>6,id_order=>{$dashboard[$i]['id_order']|escape:'html':'UTF-8'}])|escape:'html':'UTF-8'}"><span class="nobr">{$dashboard[$i]['date_add']|escape:'html':'UTF-8'}</span></a></td>

										<td><a href="{$link->getModuleLink('marketplace','marketplaceaccount',['flag'=>1,'shop'=>{$id_shop|escape:'html':'UTF-8'},'l'=>6,id_order=>{$dashboard[$i]['id_order']|escape:'html':'UTF-8'}])|escape:'html':'UTF-8'}">{$order_by_cus[$i]['firstname']|escape:'html':'UTF-8'}</a></td>

										<td><a href="{$link->getModuleLink('marketplace','marketplaceaccount',['flag'=>1,'shop'=>{$id_shop|escape:'html':'UTF-8'},'l'=>6,id_order=>{$dashboard[$i]['id_order']|escape:'html':'UTF-8'}])|escape:'html':'UTF-8'}"><em>{$dashboard[$i]['order_status']|escape:'html':'UTF-8'}</em></a></td>
										<td><a href="{$link->getModuleLink('marketplace','marketplaceaccount',['flag'=>1,'shop'=>{$id_shop|escape:'html':'UTF-8'},'l'=>6,id_order=>{$dashboard[$i]['id_order']|escape:'html':'UTF-8'}])|escape:'html':'UTF-8'}"><em>{$dashboard[$i]['payment_mode']|escape:'html':'UTF-8'}</em></a></td>
									</tr>
							{assign var=i value=$i+1}
							{/while}
						</tbody>
					</table>
				</div>
				</div>
				</div>
				<div class="box-account box-recent">
					<div class="box-head">
						<h2>{l s='Customer Feedback' mod='marketplace'}</h2>
						<div class="wk_border_line"></div>
					</div>
					
					<div class="box-content">
					{assign var=i value=0}
					{while $i != $count_msg}
					<div id="feedback_box">
							<div id="feedback_by" class="feedback_inner_box"><h4>{l s='FeedBack By' mod='marketplace'}</h4><span>{$message[$i]['firstname']|escape:'html':'UTF-8'}</span></div>
							<div id="product_name" class="feedback_inner_box"><h4>{l s='Product Name' mod='marketplace'}</h4><span>{$message[$i]['product_name']|escape:'html':'UTF-8'}</span></div>
							<div id="feedback" class="feedback_inner_box"><h4>{l s='FeedBack' mod='marketplace'}</h4><span>{$message[$i]['message']|escape:'html':'UTF-8'}</span></div>
							<div id="date_add" class="feedback_inner_box"><h4>{l s='Date Add' mod='marketplace'}</h4><span>{$message[$i]['date_add']|escape:'html':'UTF-8'}</span></div>
					</div>
					{assign var=i value=$i+1}
					{/while}
					</div>
				</div>
			</div>
		</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				$(".even").click(function()
				{
					var id_order =  $(this).attr('is_id_order');
					var order_link =  $('#order_link').val();
					var id_shop_order =  $('#id_shop_order').val();
					window.location.href = "{$base_dir|escape:'html':'UTF-8'}index.php?fc=module&module=marketplace&controller=marketplaceaccount&flag=1&shop="+id_shop_order+"&l=6&id_order="+id_order;
					window.location.href = "{$link->getModuleLink('marketplace','marketplaceaccount',['flag'=>1,'shop'=>"+id_shop_order+",'l'=>6,id_order=>id_order])|escape:'html':'UTF-8'}";
					window.location.href = order_link+'&shop='+id_shop_order+'&l=6&id_order='+id_order;
					
				});
				
				$(".odd").click(function()
				{
					 var id_order =  $(this).attr('is_id_order');
					var order_link =  $('#order_link').val();
					var id_shop_order =  $('#id_shop_order').val();
					window.location.href = "{$base_dir|escape:'html':'UTF-8'}index.php?fc=module&module=marketplace&controller=marketplaceaccount&flag=1&shop="+id_shop_order+"&l=6&id_order="+id_order;
					window.location.href = order_link+'&shop='+id_shop_order+'&l=6&id_order='+id_order;
					
				});
				});
		</script>
	{else if $logic==5}
        {capture name=path}
        <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
                {l s='Marketplace account'}
        </a>
        <span class="navigation-pipe">{$navigationPipe}</span>
        <span class="navigation_page">{l s='Payment Details' mod='marketplace'}</span>
        {/capture}

	<div class="dashboard_content">
		<div class="dashboard">
			<div class="page-title">
				<span>{l s='Payment Details' mod='marketplace'}</span>
			</div>
			<div class="wk_right_col">
				<form action="{$payPro_link|escape:'html':'UTF-8'}&pay=1" method="post" class="contact-form-box" enctype="multipart/form-data" id="pay_form" accept-charset="UTF-8,ISO-8859-1,UTF-16">
				{if isset($seller_payment_details)}
					<div class="row-info">
						<div  style="width:100px;float:right;">
							<a href="" class="edit_payment_details" style="color:blue;">edit</a>
							<a href="" class="back_payment_details" style="color:blue;display:none;">back</a>
						</div>
					</div>
					<div class="row-info">
						<div class="row-info-left">
							<label class="pay" class="control-label">{l s='Payment Mode :' mod='marketplace'}</label>
						</div>
						<div class="row-info-right">
							<label id="label_payment_mode" class="control-label">{$seller_payment_mode|escape:'html':'UTF-8'}</label>
							<select id="payment_mode" name="payment_mode" class="account_input" style="display:none;">
								{foreach $pay_mode as $pay_mode1}
									<option id="{$pay_mode1['id']|escape:'html':'UTF-8'}" value="{$pay_mode1['id']|escape:'html':'UTF-8'}">{$pay_mode1['payment_mode']|escape:'html':'UTF-8'}</option>
								{/foreach}
							</select>
						</div>
					</div>
					<div class="row-info">
						<div class="row-info-left">
							<label class="pay" class="control-label">{l s='Payment Details :' mod='marketplace'}</label>
						</div>
						<div class="row-info-right">
							<label id="label_payment_mode_details" class="control-label">{$seller_payment_details['payment_detail']|escape:'html':'UTF-8'}</label>
							<textarea id="payment_detail" name="payment_detail" value="" class="account_input" style="display:none;" rows="4" cols="50">{$seller_payment_details['payment_detail']|escape:'html':'UTF-8'}</textarea>
						</div>
					</div>
					<div class="row-info">
						<input id="submit_payment_details" type="submit" name="edit_payment_details" value="Submit" style="display:none;margin-left:200px;" />
					</div>
				{else}
					<div class="form-group">
						<label for="payment_mode" class="control-label required">{l s='Payment Mode :' mod='marketplace'}</label>
						<select id="payment_mode" name="payment_mode" class="account-input">
							<option value="">{l s='Select' mod='marketplace'}</option>
							{foreach $pay_mode as $pay_mode1}
								<option id="{$pay_mode1['id']|escape:'html':'UTF-8'}" value="{$pay_mode1['id']|escape:'html':'UTF-8'}">{$pay_mode1['payment_mode']|escape:'html':'UTF-8'}</option>
							{/foreach}
						</select>
					</div>
					<div class="form-group">
						<label for="payment_detail">{l s='Payment Detail :' mod='marketplace'}</label>
						<textarea id="payment_detail" name="payment_detail" value="" class="form-control" rows="4" cols="50"></textarea>
					</div>
					<input type="hidden" id="customer_id" name="customer_id" value="{$customer_id|escape:'html':'UTF-8'}" class="account_input"/>
					<div class="row-info" style="text-align:center;">
						<button type="submit" id="submit_payment_details" class="btn btn-default button button-medium">
							<span>{l s='Add' mod='marketplace'}</span>
						</button>
					</div>
				{/if}
				</form>
				<div class="left full">
					{hook h="DisplayMppaymentdetailfooterhook"}
				</div>
			</div>
		</div>
	</div>
	{else if $logic==6}
	<div class="dashboard_content">
		<div class="dashboard">
			<div class="page-title">
				<span>{l s='Order Details' mod='marketplace'}</span>
			</div>
			<div class="wk_right_col">
				<div class="box-account box-recent">
					<div class="box-head">
						<div class="box-head-left">
							<h2>{l s='Product Details' mod='marketplace'}</h2>
						</div>
						<div class="box-head-right">
						<a class="btn btn-default button button-small" href="{$link->getModuleLink('marketplace','marketplaceaccount',['shop'=>{$id_shop|escape:'html':'UTF-8'},'l'=>4])|escape:'html':'UTF-8'}">
							<span>{l s='Back to orders' mod='marketplace'}</span>
						</a>
						</div>
						<div class="wk_border_line"></div>
					</div>
					<div class="box-content">
						{hook h='DisplayMpbottomorderproductdetailhook'}
					</div>
				</div>

				<div class="box-account box-recent">
					<div class="box-head">
						<h2>{l s='Order Details' mod='marketplace'}</h2>
						<div class="wk_border_line"></div>
					</div>
					<div class="box-content">
						<div class="wk_detail_customer">
							{hook h='DisplayMpbottomordercustomerhook'}
						</div>
						<div class="wk_detail_order">
							{hook h='DisplayMpbottomorderstatushook'}
						</div>
					</div>
				</div>
				{hook h='DisplayMpordershippingrighthook'}
				{hook h='DisplayMpordershippinghook'}
			</div>
		</div>
	</div>
	{/if}
</div>
{if $logic ==3}
<script type="text/javascript">
$(document).ready(function(){
	$(".edit_img").click(function()
	{
		var id=$(this).attr("id");
		var url = '{$pro_upd_link|escape:'intval'}&id='+id+'&editproduct=1';
		window.location.href = url;
	});
	$(".delete_img").click(function()
	{			
		var confirm_msg = '{l s='Are you sure?' js=1 mod='marketplace'}';  
		var con = confirm(confirm_msg);
		if(con == false)
		{}
		else
		{
			var id=$(this).attr("id");
			var url = '{$pro_upd_link|escape:'intval'}&id='+id+'&deleteproduct=1';
			window.location.href = url;
		}
	});
});
</script>
{/if}
<script type="text/javascript">
var req_payment_mode = '{l s='Payment mode is required.' js=1 mod='marketplace'}';
$('.edit_payment_details').click(function(e)
{
	e.preventDefault();
	$(this).hide();
	$('.back_payment_details').show();
	$('#submit_payment_details').show();
	$('#label_payment_mode').hide();
	$('#label_payment_mode + select').show();
	
	$('#label_payment_mode_details').hide();
	$('#label_payment_mode_details + textarea').show();
	
});
$('.back_payment_details').click(function(e)
{
	e.preventDefault();
	$(this).hide();
	$('.edit_payment_details').show();
	$('#label_payment_mode').show();
	$('#label_payment_mode + select').hide();
	$('#submit_payment_details').hide();
	$('#label_payment_mode_details').show();
	$('#label_payment_mode_details + textarea').hide();
});
</script>
