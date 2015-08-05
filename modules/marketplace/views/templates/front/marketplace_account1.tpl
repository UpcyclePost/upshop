<style type="text/css">
.color_one {
	float:left;
	width:100%;
}

</style>
{if $logic==2}
<style type="text/css">
  .wk_right_col{
    float: left;
    width: 100%;
    border-top: 0px;
    padding: 15px;
    background: #fff;
    -webkit-border-radius: 0;
    -moz-border-radius: 0;
    border-radius: 0;
    -webkit-box-shadow: none ;
    -moz-box-shadow: none ;
    box-shadow: none ;
	border-bottom: 1px solid #D5D5D5;
	margin-bottom: 10px;
  }
  
@media(max-width:768px){
	  #checkimages{
		  display:none;
		  }
  }
</style>
{/if}

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
	{capture name=path}{l s='My Dashboard'}{/capture}
	<div class="dashboard_content login-panel">
			<div class="dashboard">
				<div class="page-title login-panel-header">
					<h1>{l s='My Dashboard' mod='marketplace'}</h1>
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
						<h2>{l s='Recent Orders Received' mod='marketplace'}</h2>
						</div>
						<div class="box-head-right">
						<a class="btn btn-default button button-small" href="{$link->getModuleLink('marketplace','marketplaceaccount',['shop'=>{$id_shop|escape:'html':'UTF-8'},'l'=>4])|escape:'html':'UTF-8'}"><span>{l s='View All' mod='marketplace'}</span></a>
						</div>
					</div>
					<div class="wk_border_line"></div>
					<div class="box-content" >
					<div class="wk_order_table">
						<table class="table table-bordered data-table footab" id="my-orders-table" style="width:100%;">
							<thead>
								<tr class="first last">
								<th data-sort-ignore="true">{l s='View Order' mod='marketplace'}</th>
								<th>{l s='Order' mod='marketplace'} #</th>
								<th data-type="numeric">{l s='Date' mod='marketplace'}</th>
								<th data-hide="phone,tablet">{l s='Ship To' mod='marketplace'}</th>
								<th data-hide="phone">
									<span class="nobr">{l s='Order Total' mod='marketplace'}</span>
								</th>
								<th data-hide="phone,tablet">{l s='Status' mod='marketplace'}</th>
								</tr>
							</thead>
							<tbody>
								{assign var=i value=0}
								{while $i != $count}
									<tr class="even">
										<td><a class="btn btn-default button button-small" href="{$link->getModuleLink('marketplace','marketplaceaccount',['flag'=>1,'shop'=>{$id_shop|escape:'html':'UTF-8'},'l'=>6,id_order=>{$dashboard[$i]['id_order']|escape:'html':'UTF-8'}])|escape:'html':'UTF-8'}"><span>View</span></a></td>
										<td>{$dashboard[$i]['ref']|escape:'html':'UTF-8'}</td>
										<td data-value="{$dashboard[$i]['date_add']|regex_replace:"/[\-\:\ ]/":""}}"><span class="nobr">{$dashboard[$i]['date_add']|date_format:"%D %l:%M %p"|escape:'html':'UTF-8'}</span></td>
										<!-- <td>{$dashboard[$i]['name']|escape:'html':'UTF-8'}</td> -->
										<td>{$order_by_cus[$i]['firstname']|escape:'html':'UTF-8'}</td>
										<td data-value="{$dashboard[$i]['total_price']}"><span class="price">{$currency->prefix}{$dashboard[$i]['total_price']|string_format:"%.2f"}{$currency->suffix}</span></td>
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
     <script type="text/javascript">
	  $(document).ready(function(e) {
        $('body').on('click', 'input#update_bank',function() {
            if ($(this).prop('checked')==true){ 
			  $('input#bank, input#routing').removeAttr('disabled');
             }else
			 $('#bank, #routing').attr('disabled','disabled');
        });
    });
     function show_load_msg()
	 {
					$('#update_profile').attr('disabled','disabled');
					$('#loadin_msg').show();				
	}
	</script>
	{capture name=path}
        <a href="{$account_dashboard|addslashes}">
                {l s='My Dashboard'}
        </a>
        <span class="navigation-pipe">{$navigationPipe}</span>
        <span class="navigation_page">{l s='Edit Seller Profile' mod='marketplace'}</span>
	{/capture}
	<div class="dashboard_content login-panel">
		{if $is_profile_updated == 1}
			<p class="alert alert-success">{l s='Profile information successfully updated.' mod='marketplace'}</p>
		{/if}
		{if $shop_img_size_error == 1}
			<p class="alert alert-danger">{l s='Shop logo image minimum size must be 200 x 200px' mod='marketplace'}</p>
		{/if}
		{if $seller_img_size_error==1}
			<p class="alert alert-danger">{l s='Seller image minumum size must be 200 x 200px' mod='marketplace'}</p>
		{/if}
        {if $stripe_error!=''}
			<p class="alert alert-danger">{$stripe_error}</p>
		{/if}
		<div class="dashboard">
			<div class="page-title login-panel-header">
				<h1>{l s='Edit Seller Profile' mod='marketplace'}</h1>
			</div>
			<div class="wk_right_col">
			<div class="col-sm-12">
				<div class="col-sm-6">
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
											{l s='an error occured while updating your profile.' mod='marketplace'}
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
								<div class="row-info-left">{l s='Address' mod='marketplace'}</div>
								<div class="row-info-right">{$marketplace_seller_info['address']|escape:'html':'UTF-8'}</div>
							</div>
							{hook h="DisplayMpshopviewfooterhook"}
						</div>
					{else if $edit==1}
						<form action="{$editprofile|escape:'html':'UTF-8'}" method="post"   enctype="multipart/form-data" accept-charset="UTF-8,ISO-8859-1,UTF-16" onsubmit="javascript:show_load_msg();">
							<input type="hidden" value="{$id_shop|escape:'html':'UTF-8'}" name="update_id_shop" />
							<div class="container">
								<fieldset>
								<div class="form-group">
									<div class="update_error">
									  {hook h='displayMpUpdateSellerProfileHeaderhook'}
									</div>
								</div>
								<p><sup style="color:#f00;">*</sup> {l s='Required field' mod='marketplace'}</p>
								<input class="required form-control" type="hidden" value="{$marketplace_seller_info['seller_name']|escape:'html':'UTF-8'}" name="update_seller_name" id="update_seller_name"/>
								<input class="required form-control" type="hidden" value="{$marketplace_seller_info['business_email']|escape:'html':'UTF-8'}" name="update_business_email" id="update_business_email"/>

								<div class="required form-group">
									<label for="update_shop_name" class="control-label required">{l s='Shop Name' mod='marketplace'}</label>
									<input class="required form-control" type="text" value="{$marketplace_seller_info['shop_name']|escape:'html':'UTF-8'}" name="update_shop_name" id="update_shop_name"/>
								</div>
								<div class="required form-group">
									<label for="update_phone" class="control-label required">{l s='Phone' mod='marketplace'}</label> {l s='10 digits, no separators' mod='marketplace'}
									<input class="required form-control" type="text" value="{$marketplace_seller_info['phone']|escape:'html':'UTF-8'}" name="update_phone" id="update_phone" maxlength="{$phone_digit}"/>
								</div>
								<div class="form-group">
									<label for="update_address" class="control-label">{l s='Address' mod='marketplace'}</label>
									<textarea class="required form-control"  name="update_address" id="update_address">{$marketplace_address|escape:'html':'UTF-8'}</textarea>
								</div>
                                 {if $stripestatus=='verified'}
                                  <p class="alert alert-success">{l s='Account Verified.' mod='marketplace'}</p>
                                   <div id="update_bank" class="form-group" >
                                  <label class="control-label" for="update_bank">{l s='Check box to add a new bank account:' mod='marketplace'}</label>
                                  <input class="form-control" type="checkbox" name="update_bank" id="update_bank" value="1" />
                                  </div>
                                {/if}
                                 <fieldset style="">
                                 <div id="bank" class="form-group" >
                                    <label for="bank" class="control-label required">{l s='Bank Account Number' mod='marketplace'} {if $bank_data.bank_name!=''}({$bank_data.bank_name}){/if}</label>
                                    <input class="reg_sel_input form-control"  type="text" name="bank" id="bank" value="{if $bank_data.last4!=''}********{$bank_data.last4}{/if}" {if $stripestatus=='verified'}disabled="disabled"{/if} />
                                    {l s='e.g.' mod='marketplace'} 000123456789
                                </div>
                                    
                                <div id="routing" class="form-group" >
                                    <label for="routing" class="control-label required">{l s='Routing Number' mod='marketplace'}</label>
                                    <input class="reg_sel_input form-control"  type="text" name="routing" id="routing" value="{$bank_data.routing_number}"  {if $stripestatus=='verified'}disabled="disabled"{/if} />
                                    {l s='e.g.' mod='marketplace'} 110000000
                                </div>
                                
                                <fieldset style="">
                                <div id="type" class="form-group" >
                                    <label for="type" class="control-label required">{l s='Entity Type' mod='marketplace'}</label>
                                    <select name="type" id="type" {if $stripestatus=='verified'}disabled="disabled"{/if}><option value="individual" {if $type=='individual'}selected="selected"{/if}>{l s='Individual' mod='marketplace'}</option><option value="company" {if $type=='company'}selected="selected"{/if}>{l s='Company' mod='marketplace'}</option></select>
                                </div>
                                <div id="fname" class="form-group" >
                                    <label for="fname" class="control-label required">{l s='First Name' mod='marketplace'}</label>
                                    <input class="form-control"  type="text" name="fname" id="fname" style="width:100px;display: inline;" value="{$fname}" {if $stripestatus=='verified'}disabled="disabled"{/if} />&nbsp;&nbsp;
                                    <label for="lname" class="control-label required">{l s='Last Name' mod='marketplace'}</label>
                                    <input class="form-control"  type="text" name="lname" id="lname" style="width:100px;display: inline;" value="{$lname}" {if $stripestatus=='verified'}disabled="disabled"{/if} />
                                </div>
                                <div id="ssn" class="form-group" >
                                    <label for="ssn" class="control-label required">{l s='SSN last 4 digits' mod='marketplace'}</label>
                                    <input class="form-control"  type="text" name="ssn" id="ssn" style="width:50px;display: inline;" value="{if $type!=''}****{/if}" maxlength="4" {if $stripestatus=='verified'}disabled="disabled"{/if} />
                                </div>
                                <div id="routing" class="form-group" >
                                    <label for="routing" class="control-label required">{l s='Date of birth' mod='marketplace'}</label>
                                    <input {if $stripestatus=='verified'}disabled="disabled"{/if} class="form-control"  type="text" name="month" id="month" style="width:30px;display: inline;" value="{$dob.month}"  maxlength="2"/> /
                                    <input {if $stripestatus=='verified'}disabled="disabled"{/if} class="form-control"  type="text" name="day" id="day" style="width:30px;display: inline;" value="{$dob.day}"  maxlength="2"/> /
                                    <input {if $stripestatus=='verified'}disabled="disabled"{/if} class="form-control"  type="text" name="year" id="year" style="width:50px;display: inline;" value="{$dob.year}"  maxlength="4"/>&nbsp;
                                    {l s='e.g.' mod='marketplace'} 12/31/1988
                                </div>
                                </fieldset>
                                </fieldset>
								{hook h="DisplayMpshopaddfooterhook"}
							</div>
						</div>			
					</div>
					
					<div class="col-sm-6" id="checkimages">
						<div class="container" style="margin-top:20px;">
						<h4>{l s='Bank account and Routing number information' mod='marketplace'}</h4>
						<div>
							For <strong>U.S.</strong> Bank accounts the routing number and account information can be found on your check as shown below.
							<img src="{$img_dir}US_check.png" height="116" width="321" style="border:solid 1px #0187d0;padding:5px;margin:10px 0 10px 0;"></img>
						</div>
						<!--
						<div>
							For <strong>Canadian</strong> Bank accounts the routing number and account information can be found on your check as shown below.
							<img src="{$img_dir}Canadian_check.png" height="121" width="321" style="border:solid 1px #0187d0;padding:5px;margin:10px 0 0 0;"></img>
						</div>
						-->
						</div>
					</div>
				</div>
			</div>
			<div class="submit-button" style="padding-bottom:15px;">
					<button type="submit" id="update_profile" class="btn btn-default button button-medium">
						<span>{l s='Update' mod='marketplace'}</span>
					</button>&nbsp;&nbsp;
	                <span id="loadin_msg" style="display:none;margin-top: 12px;font-size: 15px;color: orangered;position: absolute;">{l s='Please wait while we validate your banking information...' mod='marketplace'}</span>
				</div>
				</fieldset>
			</div>							
				</form>
					{/if}
				</div>
			</div>
	{else if $logic==3}
        {capture name=path}
        <a href="{$account_dashboard|addslashes}">
                {l s='My Dashboard'}
        </a>
        <span class="navigation-pipe">{$navigationPipe}</span>
        <span class="navigation_page">{l s='Product List' mod='marketplace'}</span>
        {/capture}
			<div class="dashboard_content login-panel">
				{if $is_deleted == 1}
					<p class="alert alert-success">{l s='Deleted Successful' mod='marketplace'}</p>
				{else if $is_edited == 1}
					<p class="alert alert-success">{l s='Updated Successful' mod='marketplace'}</p>
				{/if}
                 {if $duplicate_conf == 1}
					<p class="alert alert-success">{l s='Duplicated Successfully.' mod='marketplace'}</p>
				{/if}
				<div class="page-title login-panel-header">
					<h1>{l s='Product List' mod='marketplace'}</h1>
				</div>
				{if $product_lists|@count < 3}
				<div class="page-title login-panel-header">
					<p>{l s='Your shop will appear in the Shop Gallery after you have loaded at least three products.
' mod='marketplace'}</p>
				</div>
				{/if}
				<div class="wk_right_col">
					<div class="wk_product_list">
						<div class="left full">
							{hook h="DisplayMpproductdetailheaderhook"}
						</div>
						<table class="data-table footab" id="my-orders-table" style="width:100%;">
							<thead>
								<tr class="first last">
									<th data-sort-ignore="true">{l s='Edit' mod='marketplace'}</th>
                                    <th data-hide="phone" data-sort-ignore="true">{l s='Duplicate' mod='marketplace'}</th>
									<th data-sort-ignore="true">{l s='Image' mod='marketplace'}</th>
									<th>{l s='Name' mod='marketplace'}</th>
									<!--<th>{l s='Description' mod='marketplace'}</th>-->
									<th data-hide="phone" data-type="numeric">{l s='Price' mod='marketplace'}</th>
									<th data-hide="phone" data-type="numeric">{l s='Quantity' mod='marketplace'}</th>
									<th data-hide="phone,tablet" data-sort-ignore="true">{l s='Shipping' mod='marketplace'}</th>
									<th data-hide="phone,tablet">{l s='Status' mod='marketplace'}</th>
									<th data-hide="phone,tablet">{l s='Views' mod='marketplace'}</th>
									<th data-hide="phone" data-sort-ignore="true">{l s='Delete' mod='marketplace'}</th>
								</tr>
							</thead>
						<tbody>
						{if $product_lists!=0}
							{assign var=i value=1} 
							{foreach $product_lists as $product}
								<tr class="even">
									<td>
										<a id="{$product['id']|escape:'html':'UTF-8'}" class="btn btn-default button button-small edit_img"><span>Edit</span></a>
									</td>
                                     <td>
                                    <form action="" method="post">
										 <input type="hidden" name="id_product" value="{$product['id']}" />
                                        <input class="btn btn-default button button-small" type="submit" name="duplicate" value="Duplicate" style="background-color:#89c226;padding:3px 8px;"/>
                                        </form>
									</td>
									<td>
										{if isset($product.unactive_image)} <!--product is not activated yet-->
												<img class="img-thumbnail" width="45" height="45" src="{$modules_dir}marketplace/img/product_img/{$product.unactive_image|escape:'html':'UTF-8'}.jpg">
										{else if isset($product.cover_image)} <!--product is atleast one time activated-->
												<img class="img-thumbnail" width="45" height="45" src="{$link->getImageLink($product.obj_product->link_rewrite, $product.cover_image, 'small_default')}">
										{else if isset($product.id_product)}
											<img class="img-thumbnail" width="45" height="45" src="{$link->getImageLink($product.obj_product->link_rewrite, $product.lang_iso|cat : '-default', 'small_default')}">
										{else}
											<img class="img-thumbnail" width="45" height="45" src="{$modules_dir}marketplace/img/product_img/no_img.jpg">
										{/if}
									</td>
									<td>
										{$product['product_name']|escape:'html':'UTF-8'}
									</td>
									<!--
									<td>
										{$product['short_description']|strip_tags|truncate:30|escape:'html':'UTF-8'}
									</td>
									-->
									<td nowrap data-value="{$product['price']}">
										{$currency->prefix}{$product['price']|string_format:"%.2f"}{$currency->suffix}
									</td>
									<td>
										{$product['quantity']|escape:'html':'UTF-8'}
									</td>
									<td align="center" style="text-align:center;">
											{if $product['shipping']}
												<!--{l s='Assigned' mod='marketplace'}-->
												<i class="fa fa-check-circle fa-2x" style="color:#89c226;"></i>
											{else}
												<!--{l s='Not Assigned' mod='marketplace'}-->
												<i class="fa fa-exclamation-circle fa-2x" style="color:#f3515c;" title="A shipping profile must be assigned to this product, click the edit button to assign one."></i>
											{/if}
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
									{ProductController::getTotalViewed($product['id_product'])}
									</td>
									<td>
										<img id="{$product['id']|escape:'html':'UTF-8'}" class="delete_img" src="{$img_ps_dir|escape:'html':'UTF-8'}admin/delete.gif"/>
										{hook h="PriceDisplay" id_product=$product['id']|escape:'html':'UTF-8'}
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
        <a href="{$account_dashboard|addslashes}">
                {l s='My Dashboard'}
        </a>
        <span class="navigation-pipe">{$navigationPipe}</span>
        <span class="navigation_page">{l s='My Orders' mod='marketplace'}</span>
        {/capture}

		<div class="dashboard_content login-panel">
		<div class="dashboard">
			<div class="page-title login-panel-header">
				<h1>{l s='My Orders' mod='marketplace'}</h1>
			</div>
			<div class="wk_right_col">
				<div class="box-account box-recent">
					<div class="box-head">
						<h2>{l s='Recent Orders Received' mod='marketplace'}</h2>
						<div class="wk_border_line"></div>
					</div>
					<div class="box-content">
					<div class="wk_order_table">		
					<table class="data-table footab" id="my-orders-table">
						<thead>
							<tr class="first last">
								<th data-sort-ignore="true">{l s='View Order' mod='marketplace'}</th>
								<th>{l s='Order #' mod='marketplace'}</th>
								<th data-type="numeric">{l s='Date' mod='marketplace'}</th>
								<th data-hide="phone,tablet">{l s='Ship To' mod='marketplace'}</th>
								<th data-hide="phone" data-type="numeric"><span class="nobr">{l s='Order Total' mod='marketplace'}</span></th>
								<th data-hide="phone,tablet">{l s='Status' mod='marketplace'}</th>
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
										<td><a class="btn btn-default button button-small" href="{$link->getModuleLink('marketplace','marketplaceaccount',['flag'=>1,'shop'=>{$id_shop|escape:'html':'UTF-8'},'l'=>6,id_order=>{$dashboard[$i]['id_order']|escape:'html':'UTF-8'}])|escape:'html':'UTF-8'}"><span>View</span></a></td>
										<td>{$dashboard[$i]['ref']|escape:'html':'UTF-8'}</td>
										<td data-value="{$dashboard[$i]['date_add']|regex_replace:"/[\-\:\ ]/":""}}"><span class="nobr">{$dashboard[$i]['date_add']|date_format:"%D %l:%M %p"|escape:'html':'UTF-8'}</span></td>
										<td>{$order_by_cus[$i]['firstname']|escape:'html':'UTF-8'}</td>
										<td data-value="{$dashboard[$i]['total_price']}"><span class="price">{$currency->prefix}{$dashboard[$i]['total_price']|string_format:"%.2f"}{$currency->suffix}</span></td>
										<td><em>{$dashboard[$i]['order_status']|escape:'html':'UTF-8'}</em></td>
									</tr>
							{assign var=i value=$i+1}
							{/while}
						</tbody>
					</table>
				</div>
				</div>
				</div>
				<!--
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
				-->
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
        <a href="{$account_dashboard|addslashes}">
                {l s='My Dashboard'}
        </a>
        <span class="navigation-pipe">{$navigationPipe}</span>
        <span class="navigation_page">{l s='Payment Details' mod='marketplace'}</span>
        {/capture}

	<div class="dashboard_content">
		<div class="dashboard">
			<div class="page-title login-panel-header">
				<h1>{l s='Payment Details' mod='marketplace'}</h1>
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
        {capture name=path}
        <a href="{$account_dashboard|addslashes}">
                {l s='My Dashboard'}
        </a>
        <span class="navigation-pipe">{$navigationPipe}</span>
        <a href="{$my_order|escape:'html':'UTF-8'}">
                {l s='My Orders'}
        </a>
        <span class="navigation-pipe">{$navigationPipe}</span>
        <span class="navigation_page">{l s='Order Details' mod='marketplace'}</span>
        {/capture}
	<div class="dashboard_content">
		<div class="dashboard">
			<div class="page-title login-panel-header">
				<h1>{l s='Order Details' mod='marketplace'}</h1>		
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
							Order Reference - {$dashboard[0]['ref']}
							<a target="_blank" class="btn btn-gray" style="float:right;margin:-15px 0 0 0" href="{$link->getPageLink('pdf-invoice', true)}?id_order={$dashboard[0]['id_order']|intval}&amp;secure_key={$dashboard[0]['secure_key']|escape:'html':'UTF-8'}"><i class="fa fa-file-pdf-o icon-only"></i> {l s='Download invoice as a PDF file.'}</a>
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
