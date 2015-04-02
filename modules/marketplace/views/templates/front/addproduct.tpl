<style type="text/css">
.page-title{
	background-color: {$title_bg_color|escape:'html':'UTF-8'} !important;
}
.page-title span{
	color: {$title_text_color|escape:'html':'UTF-8'} !important;
}
.img_validate{
  clear: both;
  color: #7F7F7F;
  font-family: Georgia,Arial,'sans-serif';
  font-size: 11px;
  font-style: italic;
  text-align: left;
  width: 500px;
}
</style>

<script type="text/javascript">
$(document).ready(function() {
	$('#tree1').checkboxTree();
});
</script>

{if $login == 1}
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

{capture name=path}{l s='Add Product' mod='marketplace'}{/capture}
{if $product_upload}
	{if $product_upload==1}
		<p class="alert alert-success">{l s='Your product uploaded successfully' mod='marketplace'}</p>
	{else if $product_upload==2}
		<p class="alert alert-success">{l s='There was some error occurs while uploading your product' mod='marketplace'}</p>		
	{/if}
{/if}

{hook h='DisplayMpaddproductheaderhook'}
<div class="main_block">
{hook h="DisplayMpmenuhook"}
<div class="dashboard_content">
	<div class="page-title">
		<span>{l s='Add Product' mod='marketplace'}</span>
	</div>
	<div class="wk_right_col">
		<div class="option_tab">
			<ul>
				<li><span id="id_product_info" class="tab_active tab1">{l s='Information' mod='marketplace'}</span></li>
				{hook h='DisplayMpProductOption'}
			</ul>
		</div>
		<form action="{$new_link4|escape:'html':'UTF-8'}" method="post" id="create-account" class="std contact-form-box" enctype="multipart/form-data" accept-charset="UTF-8,ISO-8859-1,UTF-16">
			<div class="add_product_div">
				<div class="form-group">	
					<label for="product_name" class="control-label required">{l s='Product Name :' mod='marketplace'}</label>
					<input type="text" id="product_name" name="product_name" value="{$c_mp_product_name|escape:'html':'UTF-8'}" class="form-control" />
				</div>

		        <div class="form-group">	
					<label for="short_description" class="control-label">{l s='Short Description :' mod='marketplace'}</label>
					<textarea name="short_description" id="short_description" cols="2" rows="3" class="wk_tinymce form-control">{$c_mp_short_description|escape:'intval'}</textarea>
				</div>

				 <div class="form-group">	
					<label for="product_description" class="control-label">{l s='Description :' mod='marketplace'}</label>
				  	<textarea class="wk_tinymce form-control" id="product_description" name="product_description" value="">{$c_mp_product_description|escape:'intval'}</textarea>
				</div>

				<div class="form-group">
					<label for="product_price" class="control-label required">{l s='Price :' mod='marketplace'}</label>
				  	<input type="text" id="product_price" name="product_price" value="{$c_mp_product_price|escape:'html':'UTF-8'}"  class="account_input form-control"/>
				</div>
				{hook h='DisplayMpaddproductpricehook'}
				<div class="form-group">
					<label for="product_quantity" class="control-label required">{l s='Quantity :' mod='marketplace'}</label>
				   	<input type="text" id="product_quantity" name="product_quantity" value="{$c_mp_product_quantity|escape:'html':'UTF-8'}"  class="account_input form-control"  />
				</div>	

				<div class="form-group">
					<label for="product_category" class="control-label required" >{l s='Category :' mod='marketplace'}</label>
					<div>{$categoryTree|escape:'intval'}</div>
				</div>

				<div class="form-group">   
					<label for="product_image">{l s='Upload Image :' mod='marketplace'}</label>
					<input type="file" id="product_image" name="product_image" value="" class="account_input form-control" size="chars"  />
					<p class="img_validate">{l s='Valid image extensions are jpg,jpeg and png.' mod='marketplace'}</p>
				</div>

				<div class="row-info-right1 form-group" style="margin-top:5px;width:100%;"> 
					<a onclick="showOtherImage(); return false;">
						<div id="add_img">{l s='Add Other Image' mod='marketplace'}</div>
					</a>
					<div id="otherimages" style="margin-left:0px;float:left;"> </div>
		        </div>    
		        {hook h="DisplayMpaddproductfooterhook"}	
		    </div>
	    	{hook h="DisplayMpaddproducttabhook"}
			<div class="form-group" style="text-align:center;">
				<button type="submit" id="SubmitProduct" class="btn btn-default button button-medium">
					<span>{l s='Add Product' mod='marketplace'}</span>
				</button>
			</div>
		</form>
	</div>
</div>
</div>
{else}
	<div class="alert alert-info">
		{l s='You are logged out.Please login to add product.' mod='marketplace'}
	</div>
{/if}
</div>




<script type="text/javascript">
var req_prod_name = '{l s='Product name is required.' js=1 mod='marketplace'}';
var char_prod_name = '{l s='Product name should be character.' js=1 mod='marketplace'}';
var req_price = '{l s='Product price is required.' js=1 mod='marketplace'}';
var num_price = '{l s='Product price should be numeric.' js=1 mod='marketplace'}';
var req_qty = '{l s='Product quantity is required.' js=1 mod='marketplace'}';
var num_qty = '{l s='Product quantity should be numeric.' js=1 mod='marketplace'}';
var req_catg = '{l s='Please select atleast one category.' js=1 mod='marketplace'}';


var i = 2;
function showOtherImage() {
    var newdiv = document.createElement('div');
    newdiv.setAttribute("id", "childDiv" + i);
    newdiv.innerHTML = "<input type='file' id='images" + i + "' name='images[]'/><a style='color:#0000FF;' href=\"javascript:;\" onclick=\"removeEvent('childDiv" + i + "')\">Remove</a>";
    var ni = document.getElementById('otherimages');
    ni.appendChild(newdiv);
    i++;
}
function removeEvent(divNum) {
    var d = document.getElementById('otherimages');
    var olddiv = document.getElementById(divNum);
    d.removeChild(olddiv);
    i--;
}
</script>