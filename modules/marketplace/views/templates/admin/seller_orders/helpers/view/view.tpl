<div class="panel">
  <div class="panel-heading">
    <i class="icon-shopping-cart"></i> {l s='Order Details' mod='marketplace'}
  </div>
  <div class="row">
    <table class="table">
      <thead>
        <tr>
          <th>{l s='Seller Name' mod='marketplace'}</th>
          <th>{l s='Product' mod='marketplace'}</th>
          <th>{l s='Price' mod='marketplace'}</th>
          <th>{l s='Quantity' mod='marketplace'}</th>
          <th>{l s='Admin Commission' mod='marketplace'}</th>
        </tr>
      </thead>
      <tbody>
        {if isset($mp_order_details) && !empty($mp_order_details)}
          {foreach $mp_order_details as $order}
            <tr>
              <td>{$order.customer_name}</td>
              <td>{$order.product_name}</td>
              <td>{convertPrice price=$order.price}</td>
              <td>{$order.quantity}</td>
              <td>{convertPrice price=$order.commision}</td>
            </tr>
          {/foreach}
        {else}
          <tr><td colspan="5" align="center">{l s='No records found' mod='marketplace'}</td></tr>
        {/if}
      </tbody>
    </table>
  </div>
</div>