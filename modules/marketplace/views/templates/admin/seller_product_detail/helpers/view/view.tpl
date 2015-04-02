<style type="text/css">
.row{
	margin-bottom: 20px;
}
input{
	padding:20px;
}
.add_one {
	font-size:{$add_size|escape:'html':'UTF-8'}px;
	font-weight:bold;
	color:{$add_color|escape:'html':'UTF-8'} !important;
	width:150px!important;
	margin-left:10px !important;
	font-family:{$add_font_family|escape:'html':'UTF-8'};
}

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
    color: {$add_color|escape:'html':'UTF-8'} !important;
    float: left;
    font-family: {$add_font_family|escape:'html':'UTF-8'};
    font-size: {$add_size|escape:'html':'UTF-8'}px;
    font-weight: bold;
    width: 24%;
}

.row-info-right {
    color: #404040;
    float: left;
    font-size: 15px;
    width: 76%;
}

.row-info-right input{
	padding:6px;
	width:25%;
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
.wk_prod_desc{
	border: 1px solid #D7D7D7;
	border-radius: 3px;
	background-color: #EEEEEE;
	padding:5px 10px !important;
	min-height: 100px;
}
</style>

<div class="leadin">{block name="leadin"}{/block}</div>
{block name="override_tpl"}
	<div id="fieldset_0" class="panel">
	    <h3>{l s='View Product' mod='marketplace'}</h3>
		<form class="form-horizontal">
			<div class="row">	
				<label class="col-lg-3 control-label" for="product_name" >{l s='Product Name :' mod='marketplace'}</label>
				<div class="col-lg-5">
					<input type="text" value="{$pro_info['product_name']|escape:'html':'UTF-8'}" disabled/>
				</div>
			</div>

			<div class="row">	
				<label class="col-lg-3 control-label" for="product_description">{l s='Product Description :' mod='marketplace'}</label>
				<div class="col-lg-5 wk_prod_desc">
					{$pro_info['description']|escape:'intval'}
				</div>
			</div>

			<div class="row">	
				<label class="col-lg-3 control-label" for="product_price">
					{l s='Product Price :' mod='marketplace'}
				</label>
				<div class="col-lg-5">
					<input type="text" name="product_price" value="{$pro_info['price']|escape:'html':'UTF-8'}" disabled/>
				</div>
			</div>
			<div class="row">
				<label class="col-lg-3 control-label" for="product_quantity">
					{l s='Product Quantity :' mod='marketplace'}
				</label>
				<div class="col-lg-5">
					<input type="text" value="{$pro_info['quantity']|escape:'html':'UTF-8'}" disabled/>
				</div> 
			</div>
			
			
			
			{if {$set}==0}
				{if {$is_product_onetime_activate}==1}
					{if {$is_image_found}==1}
						<div class="row">
							<div class="row-info-left"> 
								<span id="add_img">{l s='Active Image for Product' mod='marketplace'}</a></span><br />
							</div>
							
						</div>
						<div class="row">
							<table id="imageTable" cellspacing="0" cellpadding="0" class="table">
								<tr>
									<th>{l s='Image' mod='marketplace'}</th>
									<th>{l s='Position' mod='marketplace'}</th>
									<th>{l s='Cover' mod='marketplace'}</th>
										
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
										
									</tr>
									{assign var=j value=$j+1}
								{/foreach}
							</table>
						</div>
					{/if}
				{/if}
				{if {$is_unactive_image}==1}
					<div class="row">
					<div class="row-info-left"> 
							<span id="add_img">{l s='Unactive Image for Product' mod='marketplace'}</a></span><br />
						</div>						
					</div>
					<div class="row">
						<table id="imageTable" cellspacing="0" cellpadding="0" class="table">
							<tr>
								<th>{l s='Image' mod='marketplace'}</th>	
							</tr>
							{foreach $unactive_image as $unactive_image1}
								<tr class="unactiveimageinforow{$unactive_image1['id']|escape:'html':'UTF-8'}">
									<td>
										<a class="fancybox" href="../modules/marketplace/img/product_img/{$unactive_image1['seller_product_image_id']|escape:'html':'UTF-8'}.jpg">
											<img title="15" width="45" height="45" alt="15" src="../modules/marketplace/img/product_img/{$unactive_image1['seller_product_image_id']|escape:'html':'UTF-8'}.jpg" />
										</a>
									</td>
								</tr>
							{/foreach}
						</table>
					</div>
				{/if}
			{/if}						
	</form>	
</div>
{/block}

<script type="text/javascript">
	$('.fancybox').fancybox();
</script>