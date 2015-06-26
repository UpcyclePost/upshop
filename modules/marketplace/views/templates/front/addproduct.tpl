<style type="text/css">
.page-title{
	background-color: {$title_bg_color|escape:'html':'UTF-8'} !important;
}
.page-title span{
	color: {$title_text_color|escape:'html':'UTF-8'} !important;
}
.demo{}


#otherimages div{
  margin-bottom: 10px;
}

.wkChildDivClass
{
	display: inline-flex;
}

#otherimages div input{
  display: inline !important;
  float: left;
}

#otherimages div a{
  color: #0000ff;
}

#wk_prod_other_images div a
{
	color: #0000ff;
	margin-left: 50px;
}

#add_img {
    color: #0000FF;
    float: left;
    font-family: times new roman;
    margin-bottom: 10px;
    margin-left:0;
    margin-top: 10px;
    width: 100%;
    cursor: pointer;
}

div.uploader {

    width: 33%;
    display: inline-block;
}

#add_img:hover {
	text-decoration:underline;
}
</style>

<script type="text/javascript">
$(document).ready(function() {
	$('#tree1').checkboxTree({
		initializeChecked: 'expanded',
		initializeUnchecked: 'collapsed'
	});
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

        {capture name=path}
        <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
                {l s='Marketplace account'}
        </a>
        <span class="navigation-pipe">{$navigationPipe}</span>
        <span class="navigation_page">{l s='Add Product' mod='marketplace'}</span>
        {/capture}

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
<div class="dashboard_content login-panel">
	<div class="page-title login-panel-header">
		<h1>{l s='Add Product' mod='marketplace'}</h1>
	</div>
	<div class="wk_right_col">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#information" data-toggle="tab">
					<i class="icon-info-sign"></i>
					{l s='Information' mod='marketplace'}
				</a>
			</li>
			{hook h='displayMpProductOption'}
		</ul>
		<form action="{$new_link4|escape:'html':'UTF-8'}" method="post" id="create-account" class="std contact-form-box" enctype="multipart/form-data" accept-charset="UTF-8,ISO-8859-1,UTF-16">
			<div class="tab-content panel collapse in">
				<div class="tab-pane active" id="information">
					<div class="add_product_div">
						<div class="form-group">	
							<label for="product_name" class="control-label required">{l s='Product Name :' mod='marketplace'}</label>
							<input type="text" id="product_name" name="product_name" value="{$c_mp_product_name|escape:'html':'UTF-8'}" class="form-control" placeholder="{l s='Enter product name' mod='marketplace'}"/>
						</div>

				        <div class="form-group">	
							<label for="short_description" class="control-label">
							{l s='Short Description (600 characters max) : ' mod='marketplace'}
							</label>&nbsp;Basic details
							<div name="short_description_length" id="short_description_length" class="short_description_length">
								<span id="max_char_string" style="{if $c_mp_short_description|@strlen > 600}color:#F00;font-weight:bold{/if}">
								{l s='HTML Character Count: ' mod='marketplace'}{$c_mp_short_description|@html_entity_decode|@strlen}/600
								</span>
							</div>
							<textarea maxlength="600" name="short_description" id="short_description" cols="2" rows="3" class="short_description wk_tinymce form-control">{$c_mp_short_description|escape:'intval'}</textarea>
						</div>
						<div class="form-group">	
							<label for="product_description" class="control-label">
								{l s='Description (1500 characters max) : ' mod='marketplace'}
								</label>&nbsp;Provide more specifics for product page
								<div name="product_description_length" id="product_description_length" class="product_description_length">
									<span id="max_char_string" style="{if $c_mp_product_description|@strlen > 1500}color:#F00;font-weight:bold{/if}">
										{l s='HTML Character Count: ' mod='marketplace'}{$c_mp_product_description|@html_entity_decode|@strlen}/1500
									</span>
								</div>
							  	<textarea maxlength="1500" class="product_description wk_tinymce form-control" id="product_description" name="product_description" value="">{$c_mp_product_description|escape:'intval'}</textarea>
						</div>

						<div class="form-group">
							<label for="product_price" class="control-label required">{l s='Price :' mod='marketplace'}</label>&nbsp;Numbers and decimal point only (e.g. 1234.56)
							<div class="input-group">
						  		<input type="text" id="product_price" name="product_price" value="{$c_mp_product_price|escape:'html':'UTF-8'}"  class="account_input form-control" placeholder="{l s='Enter product price' mod='marketplace'}"/>
						  		<span class="input-group-addon">{$currency_sign|escape:'html':'UTF-8'}</span>
						  	</div>
						</div>

						{hook h='DisplayMpaddproductpricehook'}
						<div class="form-group">
							<label for="product_quantity" class="control-label required">{l s='Quantity :' mod='marketplace'}</label>
						   	<input type="text" id="product_quantity" name="product_quantity" value="{$c_mp_product_quantity|escape:'html':'UTF-8'}"  class="account_input form-control" placeholder="{l s='Enter quantity available' mod='marketplace'}" />
						</div>	

						<div class="form-group">
							<label for="product_category" class="control-label required" >{l s='Category :' mod='marketplace'}</label>
							<div>{$categoryTree|escape:'intval'}</div>
						</div>
				        {hook h="DisplayMpaddproductfooterhook"}
						<div class="form-group">   
							<label for="product_image" style="display:block">{l s='Upload Image :' mod='marketplace'}</label>
							<input type="file" id="product_image" name="product_image" value="" class="account_input form-control" size="chars"  />
							<img style="display:none;" id="testImg" src="#" alt="" height="40px" width="40px" />
							<p class="help-block">{l s='Valid image extensions are jpg, jpeg, and png.' mod='marketplace'}</p>
						</div>

						<div class="form-group">
							<a onclick="showOtherImage(); return false;" class="btn btn-default button button-small">
								<span>{l s='Add another image' mod='marketplace'}</span>
							</a>
							<div id="wk_prod_other_images"></div>
				        </div>   

				    </div>
				</div>
		    	{hook h="DisplayMpaddproducttabhook"}
				<div class="form-group" style="text-align:center;" id="SubmitProduct_div_id">
					<button type="submit" id="SubmitProduct" class="btn btn-default button button-medium">
						<span>{l s='Add Product' mod='marketplace'}</span>
					</button>
				</div>
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

<script type="text/javascript">
	var req_prod_name = '{l s='Product name is required.' js=1 mod='marketplace'}';
	var char_prod_name = '{l s='Product name cannot contain special characters.' js=1 mod='marketplace'}';
	var char_prod_name_length = '{l s='Product name should be less than 120 characters.' js=1 mod='marketplace'}';
	var char_prod_short_desc_length = '{l s='Short description should be less than 600 characters.' js=1 mod='marketplace'}';
	var char_prod_desc_length = '{l s='Description should be less than 1500 characters.' js=1 mod='marketplace'}';
	var req_price = '{l s='Product price is required.' js=1 mod='marketplace'}';
	var num_price = '{l s='Product price should be numeric.' js=1 mod='marketplace'}';
	var base_price = '{l s='Product price should be greater than $5.' js=1 mod='marketplace'}';
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
	    newdiv.innerHTML = "<input class=\"btn\" type='file' onchange=\"changeEvent(this,"+i+")\" id='images"+i+"' name='images[]' /><img id='showimg"+i+"' style=\"display:none\" src=\"#\" height=\"40px\" width=\"40px\" onload=\"loadEvent("+i+")\"><a style=\"height:27px\" class=\"btn btn-default button button-small\" href=\"javascript:;\" onclick=\"removeEvent('childDiv"+i+"')\"><span style=\"color:#FFF\">Remove</span></a>";
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

	function removeEvent(divNum)
	{
	    var d = document.getElementById('wk_prod_other_images');
	    var olddiv = document.getElementById(divNum);
	    d.removeChild(olddiv);
	    i--;
	}
</script>
