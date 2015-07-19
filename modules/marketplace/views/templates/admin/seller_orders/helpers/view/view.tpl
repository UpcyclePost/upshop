<div class="panel">
  <div class="panel-heading">
    <i class="icon-shopping-cart"></i> {l s='Order Details' mod='marketplace'}
  </div>
  <div class="row">
  <table class="table">
      <thead>
        <tr>
          <th>{l s='Seller' mod='marketplace'}</th>
          <th>{l s='Buyer' mod='marketplace'}</th>
        </tr>
        <tr>
          <th>
          {l s='Seller Name' mod='marketplace'}:&nbsp;&nbsp;<strong>{$seller->firstname} {$seller->lastname}</strong><br>
          {l s='Seller Email' mod='marketplace'}:&nbsp;&nbsp;<strong>{$seller->email}</strong><br>
          {l s='View Seller' mod='marketplace'}:&nbsp;&nbsp;<span class="btn-group-action"><span class="btn-group"><a class="btn btn-default" href="{$link->getAdminLink('AdminCustomers')}&amp;viewcustomer&amp;id_customer={$seller->id}">
			<i class="icon-search-plus"></i> &nbsp;{$seller->id}</a></span></span><br>
          {l s='View Seller Orders' mod='marketplace'}:&nbsp;&nbsp;<span class="btn-group-action"><span class="btn-group"><a class="btn btn-default" href="{$link->getAdminLink('AdminSellerOrders')}&amp;submitFiltermarketplace_order_commision=1&amp;marketplace_order_commisionFilter_sllr_c!id_customer={$seller->id}">
			<i class="icon-search-plus"></i> &nbsp;{$seller->id}</a></span></span>
          </th>
          <th>
          {l s='Buyer Name' mod='marketplace'}:<strong>&nbsp;&nbsp;{$buyer->firstname} {$buyer->lastname}</strong><br>
          {l s='Buyer Email' mod='marketplace'}:<strong>&nbsp;&nbsp;{$buyer->email}</strong><br>
          {l s='View Buyer' mod='marketplace'}:&nbsp;&nbsp;<span class="btn-group-action"><span class="btn-group"><a class="btn btn-default" href="{$link->getAdminLink('AdminCustomers')}&amp;viewcustomer&amp;id_customer={$buyer->id}">
			<i class="icon-search-plus"></i> &nbsp;{$buyer->id}</a></span></span><br>
          </th>
        </tr>
      </thead>
    </table>
    <table class="table">
      <thead>
        <tr>
          <th>{l s='Order ID' mod='marketplace'}</th>
          <th>{l s='Order Item ID' mod='marketplace'}</th>
          <th>{l s='Order Date' mod='marketplace'}</th>
          <th>{l s='Reference' mod='marketplace'}</th>
          <th>{l s='Order State' mod='marketplace'}</th>
          <th>{l s='Product ID' mod='marketplace'}</th>
          <th>{l s='Product' mod='marketplace'}</th>
          <th>{l s='Price' mod='marketplace'}</th>
          <th>{l s='Quantity' mod='marketplace'}</th>
          <th>{l s='Item total' mod='marketplace'}</th>
          <th>{l s='Admin Commission' mod='marketplace'}</th>
        </tr>
      </thead>
      <tbody>
        {if count($order_details)>0}
          {foreach from=$order_details key=k item=v}
            <tr class="even">
              <td>{$v['id_order']}</td>
              <td>{$v['id_order_detail']}</td>
              <td>{$order_date}</td>
              <td>{$order_ref}#{$k+1}</td>
              <td>{$order_state}</td>
              <td>{$v['product_id']}</td>
              <td>{$v['product_name']}</td>
              <td>{convertPrice price=$v['unit_price_tax_incl']}</td>
              <td>{$v['product_quantity']}</td>
              <td>{convertPrice price=$v['line_total']}</td>
              <td>{convertPrice price=$v['line_commission']}</td>
            </tr>
          {/foreach}
           <tr><td colspan="9" align="right" style="border-top:2px solid #cdcdcd"><strong>{l s='Sub Total' mod='marketplace'}:</strong></td><td style="border-top:2px solid #cdcdcd"><b>{convertPrice price=$subtotal}</b></td><td style="border-top:2px solid #cdcdcd"><b>{convertPrice price=$total_commission}</b></td></tr>
            <tr><td colspan="9" align="right"><strong>{l s='Tax' mod='marketplace'}:</strong></td><td colspan="2"><b>{convertPrice price=$tax}</b></td></tr>
             <tr><td colspan="9" align="right"><strong>{l s='Shipping' mod='marketplace'}:</strong></td><td colspan="2"><b>{convertPrice price=$shipping}</b></td></tr>
              <tr><td colspan="9" align="right"><strong>{l s='Total' mod='marketplace'}:</strong></td><td><b>{convertPrice price=$total}</b></td><td><b>{convertPrice price=$total_commission}</b></td></tr>
        {else}
          <tr><td colspan="11" align="center">{l s='No records found' mod='marketplace'}</td></tr>
        {/if}
      </tbody>
    </table>
  </div>
  <div class="panel-footer">
    <a href="{$link->getAdminLink('AdminSellerOrders')}" class="btn btn-default" id="desc-attribute-back">
      <i class="process-icon-back "></i> <span>Back to list</span>
  </a>
</div>
</div>