<div class="wk_order_customer_status">
<a class="btn lnk_view button pull-right" href="http://{$smarty.server.SERVER_NAME}/profile/messages/send/{$dashboard[0]['ws']}"><span><i class="fa fa-envelope icon-only"></i> Contact</a></span>
<h2>{l s='Customer Detail' mod='marketplace'}</h2>
<div class="clearfix"></div>
<table width="100%">
	<tr>
		<td>
			<div class="">
				<label>{l s='Name' mod='marketplace'}</label>
			</div>
		</td>
		<td>
			<div class="wk_row_right">
				<span>{$dashboard[0]['name']|escape:'html':'UTF-8'}</span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="">
				<label>{l s='Address' mod='marketplace'}</label>
			</div>
		</td>
		<td>
			<div class="wk_row_right">
				<span>{$dashboard[0]['address1']|escape:'html':'UTF-8'}</span><br>
				<span>{$dashboard[0]['city']|escape:'html':'UTF-8'}</span><br>
				<span>{$dashboard_state|escape:'html':'UTF-8'}</span><br>
				<span>{$dashboard[0]['postcode']|escape:'html':'UTF-8'}</span><br>
				<span>{$dashboard[0]['country']|escape:'html':'UTF-8'}</span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="">
				<label>{l s='Email Address' mod='marketplace'}</label>
			</div>
		</td>
		<td>
			<div class="wk_row_right">
				<span>{$dashboard[0]['email']|escape:'html':'UTF-8'}</span>
			</div>
		</td>
	</tr>
</table>
</div>