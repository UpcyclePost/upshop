<div class="form-group">
	<label class="control-label" for="meta_title">{l s='Meta Title' mod='mpproductseo'}</label> {l s='Optional : Product name will be used if not provided' mod='mpproductseo'}
   <input type="text" id="meta_title" name="meta_title" {if {isset($meta_info)}}value="{$meta_info['meta_title']}"{else}value=""{/if}  class="account_input form-control" placeholder="{l s='Enter SEO meta title' mod='marketplace'}"/>
</div>
<div class="form-group">
	<label class="control-label" for="meta_desc">{l s='Meta Description' mod='mpproductseo'}</label> {l s='Optional : Short description will be used if not provided' mod='mpproductseo'}
    <input type="text" id="meta_desc" name="meta_desc" {if {isset($meta_info)}}value="{$meta_info['meta_description']}"{else}value=""{/if}  class="account_input form-control" placeholder="{l s='Enter SEO meta description' mod='marketplace'}"/>
</div>
<!--
<div class="form-group">
	<label class="control-label" for="friendly_url">{l s='Friendly Url' mod='mpproductseo'}</label>
    <input type="text" id="friendly_url" name="friendly_url" {if {isset($meta_info)}}value="{$meta_info['friendly_url']}"{else}value=""{/if}  class="account_input form-control" />
</div>
-->
