<style type="text/css">
.img_validate{
  clear: both;
  color: #7F7F7F;
  font-family: Georgia,Arial,'sans-serif';
  font-size: 11px;
  font-style: italic;
  text-align: left;
  width: 500px;
}
#update_sucess 
{
	float: left;
	width: 100%;
	background-color: #DFFAD3;
	border: 1px solid #72CB67;
	padding: 10px;
	margin-bottom: 5px;
}

#otherimages div
{
  margin-bottom: 10px;
}

.wkChildDivClass
{
	display: inline-flex;
}

#otherimages div input
{
  display: inline !important;
  float: left;
}

#otherimages div a
{
  color: #0000ff;
}

#wk_prod_other_images div a
{
	color: #0000ff;
	margin-left: 50px;
}

#add_img
{
    color: #0000FF;
    float: left;
    font-family: times new roman;
    margin-bottom: 10px;
    margin-left:0;
    margin-top: 10px;
    width: 100%;
    cursor: pointer;
}

div.uploader
{
    width: 33%;
    display: inline-block;
}

#add_img:hover
{
	text-decoration:underline;
}
</style>

{capture name=path}{l s='Product Update' mod='marketplace'}{/capture}
<span id="error">{l s='Field Should not be Empty.' mod='marketplace'}</span>
	{if $is_main_er==1}
		<div class="alert alert-danger">
			{l s='Product name is required field.' mod='marketplace'}
		</div>
	{else if $is_main_er==2}
		<div class="alert alert-danger">
			{l s='Product name must not have Invalid characters <>;=#{}' mod='marketplace'}
		</div>
	{else if $is_main_er==3}
		<div class="alert alert-danger">
			{l s='Short description have not valid data.' mod='marketplace'}
		</div>
	{else if $is_main_er==4}
		<div class="alert alert-danger">
			{l s='Product description have not valid data' mod='marketplace'}
		</div>
	{else if $is_main_er==5}
		<div class="alert alert-danger">
			{l s='product price should be numeric' mod='marketplace'}
		</div>
	{else if $is_main_er==6}
		<div class="alert alert-danger">
			{l s='product quantity should be greater than 0' mod='marketplace'}
		</div>
	{else if $is_main_er==7}
		<div class="alert alert-danger">
			{l s='You have not selected any category' mod='marketplace'}
		</div>
	{else if $is_main_er==8}
		<div class="alert alert-danger">
			{l s='Invalid image extensions,only jpg,jpeg and png are allowed.' mod='marketplace'}
		</div>
	{/if}

{if isset($added)}
	<p class="alert alert-success">
		{l s='Product added sucessfully you can edit other detail now' mod='marketplace'}
	</p>
{/if}
{hook h='DisplayMpupdateproductheaderhook'}
<div class="main_block">
{hook h="DisplayMpmenuhook"}
<div class="dashboard_content">
	<div class="page-title">
		<span>{l s='Update Product' mod='marketplace'}</span>
	</div>
	<div class="wk_right_col">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#information" data-toggle="tab">
					<i class="icon-info-sign"></i>
					{l s='Information' mod='marketplace'}
				</a>
			</li>
			{hook h='DisplayMpUpdateProductOption'}
		</ul>
	<form action="{$edit_pro_link|escape:'html':'UTF-8'}&edited=1&id={$id|escape:'html':'UTF-8'}" method="post"  enctype="multipart/form-data" accept-charset="UTF-8,ISO-8859-1,UTF-16">
		<div class="tab-content panel collapse in">
			<div class="tab-pane active" id="information">
				<div class="wk_product_form">
				{hook h='displayMpUpdateProductBodyHeaderOption'}
					<div class="required form-group">
						<label for="product_name">{l s='Product Name :' mod='marketplace'}<sup>*</sup></label>
						<input type="text" id="product_name" name="product_name" value="{$pro_info['product_name']|escape:'html':'UTF-8'}" class="form-control" />
					</div>

					<div class="form-group">
						<label for="prod_short_desc">
							{l s='Short Description :' mod='marketplace'}
						</label>
						<textarea class="short_description wk_tinymce form-control" id="short_description" name="short_description" value="{$pro_info['description']|escape:'html':'UTF-8'}">{$pro_info['short_description']|escape:'html':'UTF-8'}</textarea>
					</div>
					
					<div class="form-group">
						<label for="prod_desc">
							{l s='Description :' mod='marketplace'}
						</label>
						<textarea class="product_description wk_tinymce form-control" id="product_description" name="product_description" value="{$pro_info['description']|escape:'html':'UTF-8'}">{$pro_info['description']|escape:'html':'UTF-8'}</textarea>
					</div>

					<div class="form-group">
						<label for="prod_price">{l s='Price :' mod='marketplace'}<sup>*</sup></label>
						<div class="input-group">
							<input type="text" id="product_price" name="product_price" value="{$pro_info['price']|escape:'html':'UTF-8'}"  class="form-control" />
							<span class="input-group-addon">{$currency_sign|escape:'html':'UTF-8'}</span>
						</div>
					</div>

					{hook h='DisplayMpaddproductpricehook'}

					<div class="form-group">
						<label for="prod_quantity">{l s='Quantity :' mod='marketplace'}<sup>*</sup></label>
						<input type="text" id="product_quantity" name="product_quantity" value="{$pro_info['quantity']|escape:'html':'UTF-8'}"  class="form-control"/>
					</div> 
					
					<div class="form-group">
						<label for="prod_category">{l s='Category :' mod='marketplace'}<sup>*</sup></label>
						<div>{$categoryTree|escape:'intval'}</div>
					</div>
					{hook h="DisplayMpupdateproductfooterhook"}
                    <div class="form-group">
                            <label for="upload_image" style="display:block">
                                    {l s='Upload Image :' mod='marketplace'}
                            </label>
                            <input type="file" id="product_image" name="product_image" value="" class="account_input form-control" size="chars" />
                            <img style="display:none;" id="testImg" src="#" alt="" height="40px" width="40px" />
                            <p class="img_validate">{l s='Valid image extensions are jpg, jpeg, and png.' mod='marketplace'}</p>
                    </div>
					<div class="form-group">
						<a onclick="showOtherImage(); return false;" class="btn btn-default button button-small">
								<span>{l s='Add another image' mod='marketplace'}</span>
							</a>
						<div id="wk_prod_other_images"></div>
					</div>				
				</div>
			</div>

			<label for="prod_quantity">{l s='Product Images ' mod='marketplace'}</label>
			<div style="float:left;width:100%;margin-bottom:20px;">
				{if $is_approve==1}
			    <div id="image_details">
					<table>
					 <tr>
					 	<th>{l s='Image' mod='marketplace'}</th>
						  <th>{l s='Position' mod='marketplace'}</th>
						  <th>{l s='Cover' mod='marketplace'}</th>
						  <th>{l s='Action' mod='marketplace'}</th>
					 </tr>
					 {if {$count} > 0}
					  {foreach from=$img_info item=foo}
					   <tr class="unactiveimageinforow{$foo.id_image|escape:'html':'UTF-8'}">
					    <td><a class="fancybox" href="http://{$foo.image_link|escape:'html':'UTF-8'}">
					    <img title="15" width="45" height="45" alt="15" src="http://{$foo.image_link|escape:'html':'UTF-8'}" />
					   </a>
					   </td>
					    <td>{$foo.position|escape:'html':'UTF-8'}</td>
					   <td>
					    {if {$foo.cover} == 1}
						 <img class="covered" id="changecoverimage{$foo.id_image|escape:'html':'UTF-8'}" alt="{$foo.id_image|escape:'html':'UTF-8'}" src="{$img_ps_dir|escape:'html':'UTF-8'}admin/enabled.gif" is_cover="1"  id_pro="{$id_product|escape:'html':'UTF-8'}" />
						{else}
						 <img class="covered" id="changecoverimage{$foo.id_image|escape:'html':'UTF-8'}" alt="{$foo.id_image|escape:'html':'UTF-8'}" src="{$img_ps_dir|escape:'html':'UTF-8'}admin/forbbiden.gif" is_cover="0"  id_pro="{$id_product|escape:'html':'UTF-8'}" />
						{/if} 
					   </td>
					   <td>
					   {if {$foo.cover} == 1}
					     <img title="Delete this image" is_cover="1" class="delete_pro_image" alt="{$foo.id_image|escape:'html':'UTF-8'}"  src="{$img_ps_dir|escape:'html':'UTF-8'}admin/delete.gif" id_pro="{$id_product|escape:'html':'UTF-8'}" />
					   {else}
					     <img title="Delete this image" is_cover="0" class="delete_pro_image" alt="{$foo.id_image|escape:'html':'UTF-8'}"  src="{$img_ps_dir|escape:'html':'UTF-8'}admin/delete.gif" id_pro="{$id_product|escape:'html':'UTF-8'}" />
			           {/if}		   
					   </td>
					   </tr>
					  {/foreach}
					 {/if}
					</table>
			    </div>
				{else}
					<div id="image_details" style="float:left;margin-top:10px;">
						<table>
						<tr><th>Image</th></tr>
						{if isset($mp_pro_image) && $mp_pro_image}
						{foreach $mp_pro_image as $mp_pro_ima}
						<tr>
							<td>
							<a class="fancybox" href="{$modules_dir|escape:'html':'UTF-8'}/marketplace/img/product_img/{$mp_pro_ima['seller_product_image_id']|escape:'html':'UTF-8'}.jpg">
								<img title="15" width="45" height="45" alt="15" src="{$modules_dir|escape:'html':'UTF-8'}/marketplace/img/product_img/{$mp_pro_ima['seller_product_image_id']|escape:'html':'UTF-8'}.jpg" />
							</a>
							</td>	
						</tr>
						{/foreach}
						{else}
						<tr>
							<td>
							<img class="img-thumbnail" width="45" height="45" src="{$modules_dir}marketplace/img/product_img/no_img.jpg">
							</td>	
						</tr>
						{/if}
						</table>
					</div>
				{/if}
			</div>


			{hook h="DisplayMpupdateproducttabhook"}
			<div class="form-group" style="text-align:center;" id="update_product_submit_div">
				<button type="submit" id="SubmitCreate" class="btn btn-default button button-medium">
					<span>{l s='Update' mod='marketplace'}</span>
				</button>
			</div>
		</div>
	</form>
</div>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#tree1').checkboxTree({
			initializeChecked: 'expanded',
			initializeUnchecked: 'collapsed'
		});
	});
	$('.fancybox').fancybox();		
	var ajax_urlpath = '{$imageediturl|escape:'html':'UTF-8'}';
	var space_error = '{l s='Space is not allowed.' js=1 mod='marketplace'}';
	var confirm_delete_msg = '{l s='Do you want to delete the photo?' js=1 mod='marketplace'}';
	var delete_msg = '{l s='Deleted.' js=1 mod='marketplace'}';
	var error_msg = '{l s='An error occurred.' js=1 mod='marketplace'}';
	var src_forbidden = '{$img_ps_dir|escape:'html':'UTF-8'}admin/forbbiden.gif';
	var src_enabled = '{$img_ps_dir|escape:'html':'UTF-8'}admin/enabled.gif';	
	var req_prod_name = '{l s='Product name is required.' js=1 mod='marketplace'}';
	var char_prod_name = '{l s='Product name cannot contain special characters.' js=1 mod='marketplace'}';
	var char_prod_name_length = '{l s='Product name should be less than 120 characters.' js=1 mod='marketplace'}';
	var req_price = '{l s='Product price is required.' js=1 mod='marketplace'}';
	var num_price = '{l s='Product price should be numeric.' js=1 mod='marketplace'}';
	var req_qty = '{l s='Product quantity is required.' js=1 mod='marketplace'}';
	var num_qty = '{l s='Product quantity should be numeric.' js=1 mod='marketplace'}';
	var req_catg = '{l s='Please select at least one category.' js=1 mod='marketplace'}';
	var img_remove = '{l s='Remove' js=1 mod='marketplace'}';

	var i = 2;
	function showOtherImage()
	{
	    var newdiv = document.createElement('div');
	    newdiv.setAttribute("id", "childDiv" + i);
	    newdiv.setAttribute("class", "wkChildDivClass");
	    newdiv.innerHTML = "<input type='file' class=\"btn\" onchange=\"changeEvent(this,"+i+")\" id='images"+i+"' name='images[]' /><img id='showimg"+i+"' style=\"display:none\" src=\"#\" height=\"40px\" width=\"40px\" onload=\"loadEvent("+i+")\"><a style=\"height:27px\" class=\"btn btn-default button button-small\" href=\"javascript:;\" onclick=\"removeEvent('childDiv"+i+"')\"><span style=\"color:#FFF\">Remove</span></a>";		
	    var ni = document.getElementById('wk_prod_other_images');
	    ni.appendChild(newdiv);
	    i++;
	}

	function changeEvent(obj,i)
	{
		getotherImgSize(obj,i);
	}

	function loadEvent(i)
	{
		$('#showimg'+i).css('display', 'block');
	}

	function getotherImgSize(input,i){
	    if (input.files && input.files[0])
	    {
	        var reader = new FileReader();
	        reader.onload = function (e)
	        {
	            $('#showimg'+i).attr('src', e.target.result);
	        }
	        reader.readAsDataURL(input.files[0]);
	    }
	}

	function removeEvent(divNum)
	{
	    var d = document.getElementById('wk_prod_other_images');
	    var olddiv = document.getElementById(divNum);
	    d.removeChild(olddiv);
	    i--;
	}

	function getImgSize(input){
	    if (input.files && input.files[0])
	    {
	        var reader = new FileReader();
	        reader.onload = function (e){
	            $('#testImg').attr('src', e.target.result);
	        }
	        reader.readAsDataURL(input.files[0]);
	    }
	}

	$('#testImg').on('load',function(){
		$('#testImg').css('display', 'inline-block');
	});

	$("#product_image").change(function(){
	    getImgSize(this);
	});
</script>

