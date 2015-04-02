<style>
#addproduct_fieldset {
	width:400px;
	border:1px solid {$add_border_color|escape:'html':'UTF-8'};
}
.row-info {
    float: left;
    padding-left: 2%;
    padding-top: 10px;
    width: 98%;
}
.row-info-left {
    color: {$add_color|escape:'html':'UTF-8'}!important;
    float: left;
    font-family: {$add_font_family|escape:'html':'UTF-8'};
    font-size: {$add_size|escape:'html':'UTF-8'}px;
    font-weight: bold;
    height: 32px;
    width: 24%;
}

.row-info-right {
    float: left;
    font-size: 15px;
    width: 76%;
}

.row-info-right input[type="text"],select{
	width:25%;
	padding:6px;
}

.product_error {
	float:left;
	width:40%;
	color:red;
	margin-left:0px !important;
}

.middle_container {
	float:left;
	width:100%;
}	
.table {
	border: 0 none;
	border-spacing: 0;
	empty-cells: show;
	font-size: 100%;
	width:100%;
}
.table tr {
	padding:5px;
}
.table tr th {
	background: -moz-linear-gradient(center top , #F9F9F9, #ECECEC) repeat-x scroll left top #ECECEC;
	color: #333333;
	font-size: 13px;
	padding: 4px 6px;
	text-align: left;
	text-shadow: 0 1px 0 #FFFFFF;
	text-align:center;
}
.table tr td {
	border-bottom: 1px solid #CCCCCC;
	color: #333333;
	font-size: 12px;
	padding: 4px 4px 4px 6px;
	text-align:center;
}

#tree1 label{
	padding-left:2px;
	font-size:12px !important;
	float:none !important;
	font-weight: normal !important;
}

#tree1
{
	background:none !important;
	border: none !important;
}
</style>
<script language="javascript" type="text/javascript">
	var iso = 'en';
	var pathCSS = '{$smarty.const._THEME_CSS_DIR_|addslashes}';
	var ad = '{$ad|addslashes}';
	$(document).ready(function(){
		{block name="autoload_tinyMCE"}
			tinySetup({
				editor_selector :"short_description",
			});
			tinySetup({
				editor_selector :"product_description",
			});
		{/block}
	});

	$(document).ready(function() {
		$('#tree1').checkboxTree();
	});
</script>

{block name="other_fieldsets"}
<div id = "fieldset_0" class="panel">
    <form id="{$table}_form" class="defaultForm {$name_controller} form-horizontal" action="{$current}&{if !empty($submit_action)}{$submit_action}{/if}&token={$token}" method="post" enctype="multipart/form-data" {if isset($style)}style="{$style}"{/if}>
	{if $form_id}
		<input type="hidden" name="{$identifier|escape:'html':'UTF-8'}" id="{$identifier|escape:'html':'UTF-8'}" value="{$form_id|escape:'html':'UTF-8'}" />
	{/if}
	<input type="hidden" name="set" id="set" value="{$set|escape:'html':'UTF-8'}" />
		<!-- <div class="row-info">
			<div id="error" style="display:none;">All field required</div>
		</div>-->
			{if {$set}==1}
				{hook h='DisplayMpaddproductheaderhook'}
				<div class="required form-group">	
					<label class="required">{l s='Choose Customer' mod='marketplace'}</label>	
						<select name="shop_customer" class="form-control">
							{foreach $customer_info as $cusinfo}
								<option value="{$cusinfo['id_customer']|escape:'html':'UTF-8'}">{$cusinfo['email']|escape:'html':'UTF-8'}</option>
							{/foreach}
						</select>
				</div>
			{else}
				<input type="hidden" value="{$pro_info['id']|escape:'html':'UTF-8'}" name="market_place_product_id" />
			{/if}

			<div class="form-group">	
				<label class="required" for="product_name" >{l s='Product Name :' mod='marketplace'}</label>
				<input type="text" id="product_name" name="product_name" class="form-control" {if {$set}==0}value="{$pro_info['product_name']}"{/if}/> 
				<span id="product_name_error">{l s='Value should be Character ' mod='marketplace'}</span>
			</div>
			
			<div class="form-group">	
				<label for="short_description">{l s='Short Description :' mod='marketplace'}</label>
				<textarea style="width:550px;height:200px;" id="short_description" name="short_description" class="short_description form-control">{if {$set}==0}{$pro_info['short_description']}{/if}</textarea>
				<span id="short_description_error">
					{l s='Value should be Character ' mod='marketplace'}
				</span>
			</div>
			<div class="form-group">	
				<label for="product_description">{l s='Description :' mod='marketplace'}</label>
				<textarea style="width:550px;height:200px;" id="product_description" name="product_description" class="product_description form-control">{if {$set}==0}{$pro_info['description']}{/if}</textarea>
				<span id="product_description_error">
					{l s='Value should be Character ' mod='marketplace'}
				</span>
			</div>
			<div class="form-group">	
				<label class="required" for="product_price">
					{l s='Product Price :' mod='marketplace'}</label>
					<input type="text" id="product_price" name="product_price" {if {$set}==0}value="{$pro_info['price']}"{/if} />
				<div id="product_price_error" class="product_error">
					{l s='Value should be Numeric' mod='marketplace'}
				</div>
			</div>
			{hook h='DisplayMpaddproductpricehook'}
			<div class="form-group">
				<label class="required" for="product_quantity">{l s='Product Quantity :' mod='marketplace'}</label>
				<input type="text" id="product_quantity" name="product_quantity" {if {$set}==0}value="{$pro_info['quantity']}"{/if} />
				<div id="product_quantity_error" class="product_error">
					{l s='Value should be Integer ' mod='marketplace'}
				</div> 
			</div>
			<div class="form-group">
				<label class="required" for="product_category">{l s='Product Category :' mod='marketplace'}</label>
				{$categoryTree|escape:'intval'}
			</div>

			<div class="form-group">
				<label for="product_image">{l s='Upload Image :' mod='marketplace'}</label>
				<input type="file" id="product_image" name="product_image" value="" size="chars" />
			</div>
			{if {$set}==1}
				<div class="form-group">
					<a onclick="showOtherImage(); return false;">
						<span>{l s='Add Other Image' mod='marketplace'}</span>
					</a>
					<div id="otherimages"></div>
				</div>
				{hook h="DisplayMpaddproductfooterhook"}
				{hook h="DisplayMpaddproducttabhook"}
			{/if}
			{if {$set}==0}
				{if {$is_product_onetime_activate}==1}
					{if {$is_image_found}==1}
						<div class="form-group">
							<label for="product_image">{l s='Active Image for Product :' mod='marketplace'}</label>
						</div>
						<div class="form-group">
							<table id="imageTable" class="table">
								<tr>
									<th>{l s='Image' mod='marketplace'}</th>
									<th>{l s='Position' mod='marketplace'}</th>
									<th>{l s='Cover' mod='marketplace'}</th>
									<th>{l s='Action' mod='marketplace'}</th>		
								</tr>
								{assign var=j value=0}
								{foreach $id_image as $id_image1}
									<tr class="imageinforow{$id_image1|escape:'html':'UTF-8'}">
										<td>
											<a class="fancybox" href="http://{$image_link[$j]|escape:'html':'UTF-8'}">
												<img width="45" height="45" alt="15" src="http://{$image_link[$j]|escape:'html':'UTF-8'}">
											</a>
										</td>
										<td>{$position[$j]|escape:'html':'UTF-8'}</td>
										<td>
										
											{if {$is_cover[$j]}==1} 
										
												<img class="covered" id="changecoverimage{$id_image1|escape:'html':'UTF-8'}" alt="{$id_image1|escape:'html':'UTF-8'}" src="../img/admin/enabled.gif" is_cover="1" id_pro="{$id_product|escape:'html':'UTF-8'}">
												
										
											{else}
										
												<img class="covered" id="changecoverimage{$id_image1|escape:'html':'UTF-8'}" alt="{$id_image1|escape:'html':'UTF-8'}" src="../img/admin/forbbiden.gif" is_cover="0" id_pro="{$id_product|escape:'html':'UTF-8'}">
										
											{/if}
										
										</td>
										<td>
										
										{if {$is_cover[$j]}==1 }
										
											<img title="Delete this image" class="delete_pro_image" alt="{$id_image1|escape:'html':'UTF-8'}" src="../img/admin/delete.gif" is_cover="1" id_pro="{$id_product|escape:'html':'UTF-8'}">
									
										{else}
										
											<img title="Delete this image" class="delete_pro_image" alt="{$id_image1|escape:'html':'UTF-8'}" src="../img/admin/delete.gif" is_cover="0" id_pro="{$id_product|escape:'html':'UTF-8'}">
										{/if}
										</td>
									</tr>
									{assign var=j value=$j+1}
								{/foreach}
							</table>
						</div>
					{/if}
				{/if}
				{if {$is_unactive_image}==1}
					<div class="form-group">
						<label for="product_image">{l s='Unactive Image for Product :' mod='marketplace'}</a></label>
					</div>
					<div class="form-group">
						<table id="imageTable" class="table">
							<tr>
								<th>{l s='Image' mod='marketplace'}</th>
								<th>{l s='Action' mod='marketplace'}</th>		
							</tr>
							{foreach $unactive_image as $unactive_image1}
								<tr class="unactiveimageinforow{$unactive_image1['id']|escape:'html':'UTF-8'}">
									<td>
										<a class="fancybox" href="../modules/marketplace/img/product_img/{$unactive_image1['seller_product_image_id']|escape:'html':'UTF-8'}.jpg">
											<img title="15" width="45" height="45" alt="15" src="../modules/marketplace/img/product_img/{$unactive_image1['seller_product_image_id']|escape:'html':'UTF-8'}.jpg" />
										</a>
									</td>
									<td>
										<img title="Delete this image" class="delete_unactive_pro_image" alt="{$unactive_image1['id']|escape:'html':'UTF-8'}" src="../img/admin/delete.gif" img_name="{$unactive_image1['seller_product_image_id']|escape:'html':'UTF-8'}">
									</td>
								</tr>
							{/foreach}
						</table>
					</div>
				{/if}
				{hook h="DisplayMpupdateproductfooterhook"}
				{hook h="DisplayMpupdateproducttabhook"}
			{/if}
		<div class="panel-footer">
			<a href="{$link->getAdminLink('AdminSellerProductDetail')|escape:'html':'UTF-8'}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel' mod='marketplace'}</a>
			<button type="submit" name="submitAddmarketplace_seller_product" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='marketplace'}</button>
		</div>
	</form>
</div>
{/block}
{block name=script}
<script language="javascript" type="text/javascript">
var i=2;
function showOtherImage() 
{
	var newdiv = document.createElement('div');
	newdiv.setAttribute("id","childDiv"+i);
	newdiv.innerHTML = "<input type='file' id='images"+i+"' name='images[]' /><a href=\"javascript:;\" onclick=\"removeEvent('childDiv"+i+"')\">Remove</a>";
	var ni = document.getElementById('otherimages');
	ni.appendChild(newdiv);
	i++;
}

function removeEvent(divNum)
{
	var d = document.getElementById('otherimages');
	var olddiv = document.getElementById(divNum);
	d.removeChild(olddiv);
	i--;
} 

$(document).ready(function()
{	
	var error = 0;
	$("#product_price").change(function() {
	var numeric = /^[0-9]+$/;
	var space =  /\s/g;
	var price_val = $("#product_price").val(); 
	if(space.test(price_val)) 
	{
	$("#product_price_error").css("display","block");
	$("#product_price_error").html('There Should be no space');
	}
	else
	{
			if($("#product_price").val().match(numeric))
			{
			$("#product_price_error").css("display","none");
			error = 0;
			}
			else
			{
				if(parseFloat(price_val) == price_val)
				{
				$("#product_price_error").css("display","none");
				error = 0;
				}
				else
				{
				$("#product_price_error").css("display","block");
				$("#product_price_error").html('Value should be integer');
				error = 1;
				}
			}
	}
	});
	$("#product_quantity").change(function() 
	{
		var numeric = /^[0-9]+$/;
		var space =  /\s/g;
		var quantity_val = $("#product_quantity").val(); 
		if(space.test(quantity_val)) {
		$("#product_quantity_error").css("display","block");
		$("#product_quantity_error").html('There Should be no space');
		}
		else
		{
		if($("#product_quantity").val().match(numeric))
		{
		$("#product_quantity_error").css("display","none");
		 error = 0;
		}
		else
		{
		$("#product_quantity_error").css("display","block");
		$("#product_quantity_error").html('Value should be integer');
		error = 1;
		}
		}
	});
});		 
</script>
<script type="text/javascript">
	$('.fancybox').fancybox();	

	$('.delete_unactive_pro_image').live('click',function(e) 
	{
		e.preventDefault();
		var id_image = $(this).attr('alt');
		var img_name = $(this).attr('img_name');
		var r=confirm("You want to delete image ?");
		if(r==true) 
		{	
			$.ajax({
				url: '{$selfcontrollerlink|addslashes}',
				data:{
					ajax:true,
					action:'deleteUnactiveImage',
					id_image:id_image,
					img_name:img_name
				},
				success: function(data)
				{
					if(data==0) 
						alert("some error occurs");
					else 
					{
						alert("Image Successfully Deleted.");
						$(".unactiveimageinforow"+id_image).remove();
					}
				}
			});
		}
	});
	
	$('.delete_pro_image').live('click',function(e) 
	{
		e.preventDefault();
		var id_image = $(this).attr('alt');
		var is_cover = $(this).attr('is_cover');
		var id_pro = $(this).attr('id_pro');
		var r=confirm("You want to delete image ?");
		if(r==true) 
		{
			$.ajax({
				url: '{$selfcontrollerlink|addslashes}',
				data: {
					ajax:true,
					action: 'deleteActiveImage',
					id_image:id_image,
					is_cover:is_cover,
					id_pro:id_pro,
				},
				success: function(data)
				{
					if(data==0)
						alert("some error occurs");
					else
					{
						alert("Image Successfully Deleted.");
						$(".imageinforow"+id_image).remove();
					}
				}
			});
		}
	});

	$('.covered').live('click',function(e) 
	{
		e.preventDefault();
		var id_image = $(this).attr('alt');
		var is_cover = $(this).attr('is_cover');
		var id_pro = $(this).attr('id_pro');
		if(is_cover==0) 
		{
			$.ajax({
				url:'{$selfcontrollerlink|addslashes}',
				data: {
					ajax:true,
					action:'changeImageCover',
					id_image:id_image,
					is_cover:is_cover,
					id_pro:id_pro
				},
				success: function(data)
				{
					if(data==0)
						alert("Some error occurs");
					else 
					{
						if(is_cover==0) 
						{
							$('.covered').attr('src','../img/admin/forbbiden.gif');
							$('.covered').attr('is_cover','0')
							$('#changecoverimage'+id_image).attr('src','../img/admin/enabled.gif')
							$('#changecoverimage'+id_image).attr('is_cover','1');
						} 
						else{}
					}
				}
			});
		}
	});
</script>
{/block}