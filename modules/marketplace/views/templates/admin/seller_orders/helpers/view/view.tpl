<div class="panel">
  <div class="panel-heading">
    <i class="icon-shopping-cart"></i> {l s='Order Details' mod='marketplace'}
  </div>
  <div class="row">
    <table class="table">
      <thead>
        <tr>
          <th>{l s='Id Order' mod='marketplace'}</th>
          <th>{l s='Id Seller' mod='marketplace'}</th>
          <th>{l s='Customer Name' mod='marketplace'}</th>
          <th>{l s='Admin Commission' mod='marketplace'}</th>
          <th>{l s='Date' mod='marketplace'}</th>
          <th>{l s='Status' mod='marketplace'}</th>
          <th>{l s='Payment Mode' mod='marketplace'}</th>
          <th>{l s='Action' mod='marketplace'}</th>
        </tr>
      </thead>
      <tbody>
        {if isset($dashboard) && !empty($dashboard)}
          {assign var=i value=0}
          {while $i != $count}
            <tr class="even">
              <td>{$dashboard[$i]['id_order']|escape:'html':'UTF-8'}</td>
              <td>{$id_seller|escape:'html':'UTF-8'}</td>
              <td>{$order_by_cus[$i]['firstname']|escape:'html':'UTF-8'} {$order_by_cus[$i]['lastname']|escape:'html':'UTF-8'}</td>
              {if isset($admin_commission[$i]) && !empty($admin_commission[$i])}
                <td>{$order_currency[$i]}{$admin_commission[$i]['commisionval']|escape:'html':'UTF-8'}</td>
              {else}
                <td>{$order_currency[$i]}0.000000</td>
              {/if}
              <td>{$dashboard[$i]['date_add']|escape:'html':'UTF-8'}</td>
              <td>{$dashboard[$i]['payment_mode']|escape:'html':'UTF-8'}</td>
              <td>{$dashboard[$i]['order_status']|escape:'html':'UTF-8'}</td>
              <td>
                <a title="View" class="btn btn-default" href="{$link->getAdminLink('AdminOrders')}&id_order={$dashboard[$i]['id_order']|escape:'html':'UTF-8'}&vieworder">
                  <i class="icon-search-plus"></i> {l s='View' mod='marketplace'}
                </a>
              </td>
            </tr>
          {assign var=i value=$i+1}
          {/while}
        {else}
          <tr><td colspan="5" align="center">{l s='No records found' mod='marketplace'}</td></tr>
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