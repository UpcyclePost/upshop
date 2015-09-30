<script type="text/javascript">
if ( $.mobile ) {
   $( "#list_content-collapsible" ).collapsible();
   $( "#menutitle-desktop" ).hide();
   $( "#menutitle-mobile" ).show();   
} 
</script>

<div class="menu_item">
	{if $is_seller==-1}
		<div class="block_content">
			<ul class="bullet">
				<li><a href="{$new_link|escape:'html':'UTF-8'}" title="seller request">{l s='Seller Request' mod='marketplace'}</a></li>
			</ul>
		</div>
	{else if $is_seller==0}
		<div class="block_content">
			<h3>{l s='Your request to create a shop has been sent for approval' mod='marketplace'}</h3>
		</div>
	{else if $is_seller==1}
		<div class="list_content" id="list_content-collapsible" data-role="collapsible" data-collapsed="true" data-inset="false">
			<h3 id="menutitle-mobile" style="display:none;"><span class="menutitle">{l s='Your Shop' mod='marketplace'}</span></h3>
			<ul>
				<li id="menutitle-desktop"><span class="menutitle">{l s='Your Shop' mod='marketplace'}</span></li>
				<li {if $logic==1}class="menu_active"{/if}>
					<span>
						<a href="{$account_dashboard|escape:'html':'UTF-8'}" title="My Dashboard">{l s='My Dashboard' mod='marketplace'}</a>
					</span>
				</li>

				<li style="border-top: solid 2px #0187d0" {if $logic==3}class="menu_active"{/if}>
					<span>
						<a href="{$product_list|escape:'html':'UTF-8'}" title="Product List">{l s='Product List' mod='marketplace'}</a>
					</span>
				</li>
				
				<li {if $logic=='add_product'}class="menu_active"{/if}>
					<span>
						<a href="{$add_product|escape:'html':'UTF-8'}" title="Add Product">{l s='Add Product' mod='marketplace'}</a>
					</span>
				</li>

				<li style="border-top: solid 2px #0187d0" {if $logic==4}class="menu_active"{/if}>
					<span>
						<a href="{$my_order|escape:'html':'UTF-8'}" title="My Orders Received">{l s='My Orders Received' mod='marketplace'}</a>
					</span>
				</li>
				<!--
				<li {if $logic==5}class="menu_active"{/if}>
					<span>
						<a href="{$payment_details|escape:'html':'UTF-8'}" title="Payment Detail">{l s='Payment Detail' mod='marketplace'}</a>
					</span>
				</li>
				-->					
				{hook h="DisplayMpmenuhookext"}
				<li {if $logic==2}class="menu_active"{/if}>
					<span>
						<a href="{$edit_profile|escape:'html':'UTF-8'}" title="Edit Seller Profile">{l s='Edit Seller Profile' mod='marketplace'}</a>
					</span>
				</li>

                <li style="border-top: solid 2px #0187d0">
                        <span>
                                <a href="{$link_store|escape:'html':'UTF-8'}" title="View Shop">{l s='View Shop' mod='marketplace'}</a>
                        </span>
                </li>
			</ul>
		</div>
	{/if}
</div>