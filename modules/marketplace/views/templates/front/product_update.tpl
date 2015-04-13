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

					<div class="form-group">
						<label for="upload_image">
							{l s='Upload Image :' mod='marketplace'}
						</label>
						<input type="file" id="product_image" name="product_image" value="" class="account_input form-control" size="chars" />	
						<p class="img_validate">{l s='Valid image extensions are jpg,jpeg and png.' mod='marketplace'}</p>		
					</div>
					{hook h="DisplayMpupdateproductfooterhook"}
				</div>
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
</script>

<script language="javascript" type="text/javascript">
var req_prod_name = '{l s='Product name is required.' js=1 mod='marketplace'}';
var char_prod_name = '{l s='Product name should be character.' js=1 mod='marketplace'}';
var req_price = '{l s='Product price is required.' js=1 mod='marketplace'}';
var num_price = '{l s='Product price should be numeric.' js=1 mod='marketplace'}';
var req_qty = '{l s='Product quantity is required.' js=1 mod='marketplace'}';
var num_qty = '{l s='Product quantity should be numeric.' js=1 mod='marketplace'}';
var req_catg = '{l s='Please select atleast one category.' js=1 mod='marketplace'}';


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
</script>