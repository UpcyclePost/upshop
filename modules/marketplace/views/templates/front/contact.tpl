<style>
.Shop_signature {
	color: {$cont_color|escape:'html':'UTF-8'};
    font-size: {$cont_size|escape:'html':'UTF-8'}px;
	font-family:{$cont_font_family|escape:'html':'UTF-8'};
	text-align:center;
    font-weight: bold;
    line-height: 1;
margin:19px 10% 5px 12.5%;
    width: 76%;
}
.contact_info{
font-size:{$cont_head_info_size|escape:'html':'UTF-8'}px;
font-weight:normal;
  font-family:{$cont_head_info_font_family|escape:'html':'UTF-8'};
  color:{$cont_head_info_color|escape:'html':'UTF-8'};
}

.contact_head
{
font-size:{$cont_head_size|escape:'html':'UTF-8'}px;
  font-weight:bold;
  line-height:20px;
  list-style:none;
  margin:0 0 0 20px;
  font-family:{$cont_head_font_family|escape:'html':'UTF-8'};
  color:{$cont_head_color|escape:'html':'UTF-8'};
}
#contactus ul
{
list-style:none;
}

.about_us h1 {
color:{$cont_heading_color|escape:'html':'UTF-8'};
font-family:{$cont_heading_font_family|escape:'html':'UTF-8'};
font-size:{$cont_heading_size|escape:'html':'UTF-8'}px;
border-bottom: 1px dotted #737739;
}
#wrapper {
    border: 1px solid {$cont_border_color|escape:'html':'UTF-8'};
    float: left;
    width: 100%;
}
</style>
<div id="wrapper">
	<div class="inner_wraper">
		<div class="header_container">
			<div class="logo1">
				{if $id_product}
					<a href="{$link_store|escape:'html':'UTF-8'}"><img src="{$base_dir|escape:'html':'UTF-8'}/modules/marketplace/img/shop_img/{$seller_id|escape:'html':'UTF-8'}-{$shop_name|escape:'html':'UTF-8'}.jpg" alt="{$shop_name|escape:'html':'UTF-8'}" width="100" height="100"></a>
				{else}
					<a href="{$link_store|escape:'html':'UTF-8'}"><img src="{$base_dir|escape:'html':'UTF-8'}/modules/marketplace/img/shop_img/{$seller_id|escape:'html':'UTF-8'}-{$shop_name|escape:'html':'UTF-8'}.jpg" alt="{$shop_name|escape:'html':'UTF-8'}" width="100" height="100"></a>
				{/if}
			</div>
			<div class="header_right">
				<div class="Shop_signature">
				{$shop_name|escape:'html':'UTF-8'} Shop
				</div>
				<div class="links" id="lin1"></div>
				<div class="links" id="lin" style="">
					
					<ul>
						{if $id_product}
							<li class="lin_header1" style="width: 78px;">
								<a href="{$link_collection|escape:'html':'UTF-8'}" style="">Collection</a>
							</li>
						{else}
							<li class="lin_header1" style="width: 78px;">
								<a href="{$link_collection|escape:'html':'UTF-8'}" style="">Collection</a>
							</li>
						{/if}
						<li class="head_sep"> <img src="{$base_dir|escape:'html':'UTF-8'}/modules/marketplace/img/NavSepLine.png"  height="35"/></li>
						{if $id_product}
							<li class="lin_header1" style="width:98px;">
								<a href="{$Seller_profile|escape:'html':'UTF-8'}" style="">Seller Profile</a>
							</li>
						{else}
							<li class="lin_header1" style="width:98px;">
								<a href="{$Seller_profile|escape:'html':'UTF-8'}" style="">Seller Profile</a>
							</li>
						{/if}
						<li class="head_sep"> <img src="{$base_dir|escape:'html':'UTF-8'}/modules/marketplace/img/NavSepLine.png"  height="35"/></li>
						{if $id_product}
					
						
							<li class="lin_header" style="width:65px;">
							<a href="{$link_conatct|escape:'html':'UTF-8'}" style="color:white !important;">Contact</a>
						</li>
						{else}
						
							<li class="lin_header" style="width:65px;">
							<a href="{$link_conatct|escape:'html':'UTF-8'}" style="color:white !important;">Contact</a>
						</li>
						{/if}
						
					</ul>
				</div>
				<div class="links" id="lin2"></div>
			</div>
		</div>
		<div class="header_bar_line"></div>
		<div class="store_container">
			<div class="about_us">
				<h1>Contact us</h1>
				
				<div class="about_us_description" id="contactus">
				<ul>
					<li><span class="contact_head">Business Mail :</span><span class="contact_info">{$business_email|escape:'html':'UTF-8'}</span></li>
					<li><span class="contact_head">Fax :</span><span class="contact_info">{$fax|escape:'html':'UTF-8'}</span></li>
					<li><span class="contact_head">Phone : </span><span class="contact_info">{$phone|escape:'html':'UTF-8'}</span></li>
					<li><span class="contact_head">Address :</span><span class="contact_info">{$address|escape:'html':'UTF-8'}</span></li>
					<li><span class="contact_head">Facebook :</span><span class="contact_info">{$facebook_id|escape:'html':'UTF-8'}</span></li>
					<li><span class="contact_head">Twitter :</span><span class="contact_info">{$twitter_id|escape:'html':'UTF-8'}</span></li>
				</ul>
				</div>
			</div>
		</div>
	</div>
</div>