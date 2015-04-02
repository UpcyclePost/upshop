<link rel="stylesheet" type="text/css" href="{$this_path|escape:'htmlall'}css/admin.css">
<div class="form" style="margin-top:30px;">
	{if isset($error)}{$error}{/if}
	{if isset($success)}{$success}{/if}
	
 	{if $adminemail}
	    <form action="{$action_url|addslashes}" method="post">
	        <fieldset>
	        	<legend><img src="../img/admin/contact.gif" alt="" title="" />{l s='Email Setting' mod='marketplace'}</legend>
	            <table border="0" width="500" cellpadding="0" cellspacing="0">
	            <tr>
	                <td width="250" style="vertical-align: top;">{l s='SuperAdmin Email' mod='marketplace'}</td>
	                <td style="padding-bottom:15px;">
	                    <input type="text" name="superadmin_email" value="{$superadminemail|escape:'html':'UTF-8'}" />
	                </td>
	            </tr>
	            {if !$superadminemail}
	    			<tr>
	                    <td width="250" style="vertical-align: top;">{l s='Default SuperAdmin email' mod='marketplace'}</td>
	                    <td style="padding-bottom:15px;">{$employee_mailid|escape:'html':'UTF-8'}</td>
	                </tr>
				{/if}
				</table>
	            <input type="submit" name="submitsuperadminemail" value="{l s='Save' mod='marketplace'}" class="button" />
	        </fieldset>
	    </form>
	{/if}

	{if $defaultapprove}
		<form action="{$action_url|addslashes}" method="post">
            <fieldset>
	            <legend><img src="../img/admin/contact.gif" alt='setting'/>{l s='Approval Setting' mod='marketplace'}</legend>
	            <label class="wk_admin_label">{l s='Product Need To Be Approved By Admin' mod='marketplace'}</label>
	            <div class="margin-form">
	                <input type="radio" name="admin_product_approve" id="admin_approve_on" value="admin" {if $product_approve == 'admin'}checked{/if}/>
	            </div>
	                
	            <label class="wk_admin_label">{l s='By Default Approved' mod='marketplace'}</label>
	            <div class="margin-form">
	                <input type="radio" name="admin_product_approve"  id="default_approve_on" value="default" {if $product_approve == 'default'}checked{/if}/>
	            </div>
	             <label class="wk_admin_label">{l s='Seller Need To Be Approved By Admin' mod='marketplace'}</label>
	            <div class="margin-form">
	                <input type="radio" name="admin_seller_approve" id="admin_approve_on" value="admin" {if $seller_approve == 'admin'}checked{/if}/>
	            </div>
	                
	            <label class="wk_admin_label">{l s='By Default Approved' mod='marketplace'}</label>
	            <div class="margin-form">
	                <input type="radio" name="admin_seller_approve"  id="default_approve_on" value="default" {if $seller_approve == 'default'}checked{/if}/>
	            </div>
	            <input type="submit" name="submitapproval" value="{l s='Save' mod='marketplace'}" class="button" />
            </fieldset>
        </form>
    {/if}

    {if isset($mpthemesetting)}
    	<form action="{$action_url|addslashes}" method="post">
	        <fieldset>
	        	<legend><img src="../img/admin/contact.gif" alt="" title="" />{l s='Theme Setting' mod='marketplace'}</legend>
	            <table border="0" width="500" cellpadding="0" cellspacing="0">
	            <tr>
	                <td width="250" style="vertical-align: top;">{l s='Page Title Background Color' mod='marketplace'}</td>
	                <td style="padding-bottom:15px;">
	                    <input type="color" name="mp_title_color" value="{if $mp_title_color}{$mp_title_color}{/if}" />
	                </td>
	            </tr>
	            <tr>
	                <td width="250" style="vertical-align: top;">{l s='Page Title Text Color' mod='marketplace'}</td>
	                <td style="padding-bottom:15px;">
	                    <input type="color" name="mp_title_text_color" value="{if $mp_title_text_color}{$mp_title_text_color}{/if}" />
	                </td>
	            </tr>
				</table>
	            <input type="submit" name="submitthemesetting" value="{l s='Save' mod='marketplace'}" class="button" />
	        </fieldset>
	    </form>
    {/if}

    {if isset($submitphonesetting)}
    	<form action="{$action_url|addslashes}" method="post">
	        <fieldset>
	        	<legend><img src="../img/admin/contact.gif" alt="" title="" />{l s='Phone Setting' mod='marketplace'}</legend>
	            <table border="0" width="500" cellpadding="0" cellspacing="0">
	            <tr>
	                <td width="250" style="vertical-align: top;">{l s='Number of digit allowed for seller phone' mod='marketplace'}</td>
	                <td style="padding-bottom:15px;">
	                    <input type="number" name="mp_phone_digit" value="{if $mp_phone_digit}{$mp_phone_digit}{/if}" maxlength="2"/>
	                </td>
	            </tr>
				</table>
	            <input type="submit" name="submitphonesetting" value="{l s='Save' mod='marketplace'}" class="button" />
	        </fieldset>
	    </form>
    {/if}
</div>