<div class="box-account box-recent">
	<div class="box-head">
		<h3>{l s='Shipping Details' mod='marketplace'}</h3>
		<div class="wk_border_line"></div>
	</div>
	<div class="box-content">
		<!-- success message div for tracking number update -->
		<div class="alert alert-success" id="tracking_number_update_success_message" style="display:none">
			{l s='Tracking number updated successful' mod='mppaymentshippingtracking'}
		</div>

		<!-- fail message div for tracking number update -->
		<div class="alert alert-danger" id="tracking_number_update_fail_message" style="display:none">
			{l s='Tracking number not updated due to some technical problem' mod='mppaymentshippingtracking'}
		</div>

		<!-- success message div for order state update -->
		{if isset($is_order_state_updated)}
			{if $is_order_state_updated == 1}
				<div class="alert alert-success">
					{l s='Order state updated successful' mod='mppaymentshippingtracking'}
				</div>
			{/if}
		{/if}

			<!-- Tab shipping -->
			<div class="tab-pane" id="shipping">
				<h4 class="visible-print">{l s='Shipping' mod='mppaymentshippingtracking'}</h4>
				<!-- Shipping block -->
				{if !$order->isVirtual()}
					<table class="table footab" id="shipping_table">
						<thead>
							<tr>
								<th data-sort-ignore="true">
									<span class="title_box ">{l s='Date' mod='mppaymentshippingtracking'}</span>
								</th>
								<th data-hide="phone" data-sort-ignore="true">
									<span class="title_box ">{l s='Carrier' mod='mppaymentshippingtracking'}</span>
								</th>
								<th data-hide="phone" data-sort-ignore="true">
									<span class="title_box ">{l s='Shipping' mod='mppaymentshippingtracking'}</span>
								</th>
								<th data-sort-ignore="true">
									<span class="title_box ">{l s='Tracking number' mod='mppaymentshippingtracking'}</span>
								</th>
							</tr>
						</thead>
						<tbody>
							{foreach from=$order->getShipping() item=line}
							<tr>
								<td>{$line.date_add|date_format:"%D"|escape:'html':'UTF-8'}</td>								
								<td>{$line.carrier_name}</td>
								<td class="center">
									{if $order->getTaxCalculationMethod() == $smarty.const.PS_TAX_INC}
										{displayPrice price=$line.shipping_cost_tax_incl currency=$currency->id}
									{else}
										{displayPrice price=$line.shipping_cost_tax_excl currency=$currency->id}
									{/if}
								</td>
								<td class="actions">
									<span id="shipping_number_show">
										{if $line.url && $line.tracking_number}
											<a target="_blank" href="{$line.url|replace:'@':$line.tracking_number}">
												{$line.tracking_number}
											</a>
										{else}
											{$line.tracking_number}
										{/if}
									</span>
									{if $line.can_edit}
										<span id="shipping_number_edit" style="display:none;">
											<input type="hidden" name="id_order_carrier" id="id_order_carrier" value="{$line.id_order_carrier|htmlentities}" />
											<input type="hidden" name="id_order_tracking" id="id_order_tracking" value="{$order->id}" />
											<input type="text" class="form-control" name="tracking_number" id="tracking_number" value="{$line.tracking_number|htmlentities}" />
											<button type="submit" class="btn btn-default button button-small" id="submit_shipping_number">
												<span>
												<i class="icon-check"></i>
												{l s='Update' mod='mppaymentshippingtracking'}
												</span>
											</button>
											<button type="submit" class="btn lnk_view button" id="cancel_shipping_number_link">
												<span style="padding:3px 10px;">
												<i class="icon-remove"></i>
												{l s='Cancel' mod='mppaymentshippingtracking'}
												</span>
											</button>
										</span>
										<a href="#" class="btn btn-default button button-small" id="edit_shipping_number_link">
											<span><i class="icon-pencil"></i>
											{l s='Edit' mod='mppaymentshippingtracking'}
											</span>
										</a>
									{/if}
								</td>
							</tr>
							{/foreach}
						</tbody>
					</table>
				{/if}

				{if isset($shipping_description) && isset($shipping_date) && $shipping_description != ''}
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>
										<span class="title_box ">{l s='Shipping Description' mod='mppaymentshippingtracking'}</span>
									</th>
									<th>
										<span class="title_box ">{l s='Date' mod='mppaymentshippingtracking'}</span>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										{if isset($shipping_description)}
											{$shipping_description}
										{/if}
									</td>
									<td>
										{if isset($shipping_date)}
											{$shipping_date}
										{/if}
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				{/if}
			</div>
	</div>
	</div>
<div class="box-account box-recent">
	<div class="box-head">
		<h3>{l s='Order Status' mod='marketplace'}</h3>
		<div class="wk_border_line"></div>
	</div>
	<div class="box-content">

		<!-- Tab content -->
		<div class="tab-content panel">
			<!-- Tab status -->
			<div class="tab-pane active" id="status">
				<h4 class="visible-print">{l s='Status'} <span class="badge">({$history|@count})</span></h4>
				<!-- History of status -->
				<div class="table-responsive">
					<table class="table history-status row-margin-bottom">
						<tbody>
							{foreach from=$history item=row key=key}
								{if ($key == 0)}
									<tr>
										<td style="background-color:{$row['color']}">
											<img src="{$img_url}os/{$row['id_order_state']|intval}.gif" width="16" height="16" alt="{$row['ostate_name']|stripslashes}" /></td>
										<td style="background-color:{$row['color']};color:{$row['text-color']}">
											{$row['ostate_name']|stripslashes}
										</td>
										<td style="background-color:{$row['color']};color:{$row['text-color']}">
										</td>
										<td style="background-color:{$row['color']};color:{$row['text-color']}">
											{$row['date_add']|date_format:"%D"|escape:'html':'UTF-8'}
										</td>
									</tr>
								{else}
									<tr>
										<td>
											<img src="{$img_url}os/{$row['id_order_state']|intval}.gif" width="16" height="16" />
										</td>
										<td>
											{$row['ostate_name']|stripslashes}
										</td>
										<td>
										</td>
										<td>
											{$row['date_add']|date_format:"%D"|escape:'html':'UTF-8'}
										</td>
									</tr>
								{/if}
							{/foreach}
						</tbody>
					</table>
				</div>


				<!-- Change status form -->
				<form action="{$update_url_link}" method="post" class="form-horizontal well" id="change_order_status_form" onsubmit="needToConfirm= false;">
					<div class="row">
						<div class="col-lg-6 form-group" id="select_ele_id">
							<select id="id_order_state" class="chosen form-control" name="id_order_state" onchange="needToConfirm= true;">
							{foreach from=$states item=state}
								{if !$state['hidden']}
								<option value="{$state['id_order_state']|intval}"{if $state['id_order_state'] == $currentState->id} selected="selected" disabled="disabled"{/if}>{$state['name']|escape}</option>
								{/if}
							{/foreach}
							</select>
							<input type="hidden" name="id_order_state_checked" class="id_order_state_checked" value="{$currentState->id}" />
						</div>
						<div class="col-lg-5 pull-right">
							<button type="submit" name="submitState" class="btn btn-default button button-small" id="update_order_status">
								<span>{l s='Update status' mod='mppaymentshippingtracking'}</span>
							</button>
						</div>
					</div>
				</form>
			</div>
			<!-- Tab delivery -->
			<div class="tab-pane" id="delivery">
				<h4 class="visible-print">{l s='Delivery Description' mod='mppaymentshippingtracking'}</h4>
				<!-- delivery block -->
				<div class="table-responsive">
					<table class="table" id="shipping_table">
						<thead>
							<tr>
								<th>
									<span class="title_box ">{l s='Delivery Date' mod='mppaymentshippingtracking'}</span>
								</th>
								<th>
									<span class="title_box ">{l s='Delivery Date' mod='mppaymentshippingtracking'}</span>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									{if isset($received_by)}
										{$received_by}
									{/if}
								</td>
								<td>
									{if isset($delivery_date)}
										{$delivery_date}
									{/if}
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>


		<!-- Shipping fancybox -->
		<form action="{$update_url_link}" method="POST" id="wk_shipping_form" style="display:none;">
			<div class="box-account box-recent" id="shipping_block">
				<div class="box-head">
					<div class="box-head-left">
						<h2 class="form-label">{l s='Shipping Address' mod='mppaymentshippingtracking'}</h2>
					</div>
					<div class="box-head-right">
					<a class="btn btn-primary" id="edit_shipping" href="#">
						<span>{l s='Edit' mod='mppaymentshippingtracking'}</span>
					</a>
					</div>
				</div>
				<div class="wk_divider_line"></div>
				<div class="box-content" id="shipping_address">
					<div id="wk_address_details">
						<div class="address_details">
							<div class="div_title form-label">{l s='Name' mod='mppaymentshippingtracking'} : </div>
							<div class="div_detail">{$dashboard[0]['name']} {$dashboard[0]['lastname']}</div>
						</div>
						<div class="address_details">
							<div class="div_title">{l s='Address' mod='mppaymentshippingtracking'} : </div>
							<div class="div_detail">{$dashboard[0]['address1']}, {$dashboard[0]['address2']}</div>
						</div>
						<div class="address_details">
							<div class="div_title">{l s='Postal Code' mod='mppaymentshippingtracking'} :</div>
							<div class="div_detail">{$dashboard[0]['postcode']}</div>
						</div>
						<div class="address_details">
							<div class="div_title">{l s='City' mod='mppaymentshippingtracking'} : </div>
							<div class="div_detail">{$dashboard[0]['city']}</div>
						</div>
						<div class="address_details">
							<div class="div_title">{l s='State' mod='mppaymentshippingtracking'} : </div>
							<div class="div_detail">{if isset($dashboard[0]['state'])}{$dashboard[0]['state']}{/if}</div>
						</div>
						<div class="address_details">
							<div class="div_title">{l s='Country' mod='mppaymentshippingtracking'} : </div>
							<div class="div_detail">{$dashboard[0]['country']}</div>
						</div>
						<div class="address_details">
							<div class="div_title">{l s='Contact' mod='mppaymentshippingtracking'} : </div>
							<div class="div_detail">{$dashboard[0]['mobile']}</div>
						</div>
					</div>
				</div>

				<div class="box-head">
					<div class="box-head-left">
						<h2>{l s='Shipping Description' mod='mppaymentshippingtracking'}</h2>
					</div>
				</div>
				<div class="wk_divider_line"></div>
				<div id="shipping_desc">
					<div id="desc">
						<textarea id="edit_textarea_shipping_description" style="width:100%;display:none;" class="form-control" name="edit_shipping_description">{if isset($shipping_description)}{$shipping_description}{/if}</textarea>
						<p id="label_shipping_description">
							{if isset($shipping_description) && $shipping_description != ''}
								{$shipping_description}
							{else}
								{l s='No data found' mod='mppaymentshippingtracking'}
							{/if}
						</p>
					</div>
				</div>

				<div class="box-head">
					<div class="box-head-left">
						<h2>{l s='Shipping Date' mod='mppaymentshippingtracking'}</h2>
					</div>
				</div>
				<div class="wk_divider_line"></div>

				<div class="wk_shipping_head">
					<div id="shipping_date">
						<span>
							<input type="hidden" name="id_order_state_checked" class="id_order_state_checked" value="{$currentState->id}" />
							<input type="hidden" name="shipping_info_set" value="1">
							<input id="text_shipping_date" type="text" class="datetime form-control" name="shipping_date" style="display:none;" {if isset($shipping_date)} value="{$shipping_date}" {/if}/>
							<p id="label_shipping_date">
								{if isset($shipping_date)}
									{$shipping_date}
								{else}
									{l s='No data found' mod='mppaymentshippingtracking'}
								{/if}
							</p>
						</span>
					</div>
				</div>

				<div id="submit_block">
					<button name="shipping_info" class="btn btn-primary" type="submit" onclick="needToConfirm = false;">
						<span>{l s='Submit' mod='mppaymentshippingtracking'}</span>
					</button>
				</div>
			</div>	
		</form>

		<!-- Delivery fancybox -->
		<form action="{$update_url_link}" method="post" id="wk_delivery_form" style="display:none;">
			<div class="box-account box-recent" id="delivery_block">
					<div class="box-head">
						<div class="box-head-left">
							<h2>{l s='Shipping Address' mod='mppaymentshippingtracking'}</h2>
						</div>
						<div class="box-head-right">
						<a class="btn btn-primary" id="edit_delivery" href="#">
							<span>{l s='Edit' mod='mppaymentshippingtracking'}</span>
						</a>
						</div>
					</div>
					<div class="wk_divider_line"></div>
					<div class="box-content" id="shipping_address">
						<div id="wk_address_details">
							<div class="address_details">
								<div class="div_title">
									{l s='Name' mod='mppaymentshippingtracking'} : 
								</div>
								<div class="div_detail">
									{$dashboard[0]['name']} {$dashboard[0]['lastname']}
								</div>
							</div>
							<div class="address_details">
								<div class="div_title">
									{l s='Address' mod='mppaymentshippingtracking'} : 
								</div>
								<div class="div_detail">
									{$dashboard[0]['address1']}, {$dashboard[0]['address2']}
								</div>
							</div>
				 
							<div class="address_details">
								<div class="div_title">
									{l s='Postal Code' mod='mppaymentshippingtracking'} : 
								</div>
								<div class="div_detail">
									{$dashboard[0]['postcode']}
								</div>
							</div>
							<div class="address_details">
								<div class="div_title">
									{l s='City' mod='mppaymentshippingtracking'} : 
								</div>
								<div class="div_detail">
									{$dashboard[0]['city']}
								</div>
							</div>
							<div class="address_details">
								<div class="div_title">
									{l s='State' mod='mppaymentshippingtracking'} : 
								</div>
								<div class="div_detail">
									{if isset($dashboard[0]['state'])}
										{$dashboard[0]['state']}
									{/if}
								</div>
							</div>
							<div class="address_details">
								<div class="div_title">
									{l s='Country' mod='mppaymentshippingtracking'} : 
								</div>
								<div class="div_detail">
									{$dashboard[0]['country']}
								</div>
							</div>
							<div class="address_details">
								<div class="div_title">
									{l s='Contact' mod='mppaymentshippingtracking'} : 
								</div>
								<div class="div_detail">
									{$dashboard[0]['mobile']}
								</div>
							</div>
						</div>
					</div>

					<div class="box-head">
						<div class="box-head-left">
							<h2>{l s='Delivery Date' mod='mppaymentshippingtracking'}</h2>
						</div>
					</div>
					<div class="wk_divider_line"></div>
					
					<div id="shipping_date_block">
						<div class="wk_shipping_head">
							<span>
								<input id="text_delivery_date" type="text" class="datetime form-control" name="delivery_date" style="display:none;" {if isset($delivery_date)} value="{$delivery_date}" {/if} />
								<p id="label_delivery_date">
									{if isset($delivery_date)}
										{$delivery_date}
									{else}
										{l s='No data found' mod='mppaymentshippingtracking'}
									{/if} 
								</p>
							</span>
						</div>
					</div>

					<div class="box-head">
						<div class="box-head-left">
							<h2>{l s='Received By' mod='mppaymentshippingtracking'}</h2>
						</div>
					</div>
					<div class="wk_divider_line"></div>
					<div class="wk_received">
						<span>
							<input type="hidden" name="id_order_state_checked" class="id_order_state_checked" value="{$currentState->id}" />
							<input type="hidden" name="delivery_info_set" value="1">
							<input type="text" id="edit_text_received_by" class="form-control"  name="edit_received_by" style="display:none;" {if isset($received_by)} value="{$received_by}" {/if}/>
							<p id="label_received_by">
								{if isset($received_by) && $received_by != ''}
									{$received_by}
								{else}
									{l s='No data found' mod='mppaymentshippingtracking'}
								{/if}
							</p>
						</span>
					</div>
						
					<div id="submit_delivery_block">
						<button name="delivery_info" class="btn btn-primary" type="submit">
							<span>{l s='Submit' mod='mppaymentshippingtracking'}</span>
						</button>
					</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	var update_tracking_number_link = '{$update_tracking_number_link}';
</script>

<script language="JavaScript">
  var needToConfirm = false;

  window.onbeforeunload = confirmExit;
  function confirmExit()
  {
	if (needToConfirm)
      return "You have changed the status of the order please click the Update Status button";
  }
</script>
