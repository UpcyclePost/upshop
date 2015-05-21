<div class="wk_order_customer_status">
<h2>{l s='Customer Detail' mod='marketplace'}&nbsp;&nbsp;&nbsp; <a class="btn btn-gray" style="float:right;margin:-5px 100px 0 0" href="http://{$smarty.server.SERVER_NAME}/profile/messages/send/{$dashboard[0]['ws']}"><i class="fa fa-envelope icon-only"></i> Contact customer</a></h2>
<table width="80%">
	<tr>
		<td>
			<div class="">
				<label>{l s='Name -' mod='marketplace'}</label>
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
				<label>{l s='Address -' mod='marketplace'}</label>
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
				<label>{l s='Email Address -' mod='marketplace'}</label>
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