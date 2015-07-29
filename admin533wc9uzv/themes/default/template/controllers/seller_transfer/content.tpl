{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<script type="text/javascript">
		$(document).ready(function()  {
			
		/*	var redirect = 'index.php?controller=AdminSellerTransfer&token={$token}';
			$("#id_seller").change(function(){
				if(this.value!=''){
					 redirect = redirect+'&id_seller='+this.value;
					}
					
				window.location = redirect;
				
			});*/
		});
	</script>
 {if isset($confirmation) && $confirmation}
        <p class="alert alert-success">
            {l s='Payment has been successfully transferred.'}
        </p>
    {/if}

<div id="ajax_confirmation" class="alert alert-success hide"></div>
{* ajaxBox allows*}
<div id="ajaxBox" style="display:none"></div>

<div class="row">
    <div class="col-lg-12">
<div id="fieldset_0" class="panel">
        <div class="panel-heading">{l s='Seller Payment Transfer'}</div>
             <div class="form-wrapper" style="width:100%;float: left;">
             <center><h3>{l s='Enter any value from following search fields to get the seller info and related orders.'}</h2></center>
     <form action="index.php?controller=AdminSellerTransfer&token={$token}" method="post" class="defaultForm form-horizontal AdminSellerWithdrawals">
     <fieldset>
              <div class="form-group">
				<label class="control-label col-lg-2">{l s='Search Seller Email'}:</label>
                   	<div class="col-lg-2">
	                             <div class="row">
										<div class="input-group col-lg-12">
                                       <input type="text" name="seller_email" value="{$smarty.post.seller_email}" />
                                       	</div>
									</div>
                               </div>
                               <label class="control-label col-lg-2">{l s='Search Seller Name'}:</label>
                   	<div class="col-lg-2 ">
	                             <div class="row">
										<div class="input-group col-lg-12">
                                       <input type="text" name="seller_name" value="{$smarty.post.seller_name}" />
                                       	</div>
									</div>
                               </div>
                               <label class="control-label col-lg-2">{l s='Search Shop Name'}:</label>
                   	<div class="col-lg-2 ">
	                             <div class="row">
										<div class="input-group col-lg-12">
                                       <input type="text" name="shop_name" value="{$smarty.post.shop_name}" />
                                       	</div>
									</div>
                               </div>
						    </div>
                
             </fieldset>
              <fieldset>
                    <div class="form-group">
				<label class="control-label col-lg-2">{l s='Search Order ID'}:</label>
                   	<div class="col-lg-2">
	                             <div class="row">
										<div class="input-group col-lg-12">
                                       <input type="text" name="id_order" value="{$smarty.get.id_order}{$smarty.post.id_order}" />
                                       	</div>
									</div>
                               </div>
                               <label class="control-label col-lg-2">{l s='Search Order Reference'}:</label>
                   	<div class="col-lg-2">
	                             <div class="row">
										<div class="input-group col-lg-12">
                                       <input type="text" name="reference" value="{$smarty.post.reference}" />
                                       	</div>
									</div>
                               </div>
						   
				<label class="control-label col-lg-2">{l s='All Sellers'}:</label>
							<div class="col-lg-2 ">
	                             <div class="row">
										<div class="input-group col-lg-12">
                                        <select name="id_seller" id="id_seller">
                                          <option value="">{l s='choose a seller...'}</option>
											{foreach from=$sellers item=v}
                                               <option value="{$v.id}" {if $smarty.post.id_seller==$v.id}selected="selected"{/if}>{$v.shop_name} {if $v.seller_name!=''} - {$v.seller_name}{/if}</option>
                                           {/foreach}
                                           </select>
										</div>
									</div>
                               </div>
						    </div>
                            
               </fieldset>
                <fieldset>
              <div class="form-group"> 
                <label class="control-label col-lg-2">&nbsp;</label>
                              <div class="col-lg-2 ">
	                                <div class="row">
										<div class="input-group col-lg-5"> 
                                          <button type="submit" name="search" class="btn btn-default">
                                                 <i class="process-icon-save"></i>{l s='Search'}
                                              </button>
                                            </div>
									</div>
                                   </div>
                </div> 
                    </fieldset>
    </form>
     <div class="panel-heading" style="clear: both;">&nbsp;</div>
  <div class="panel-heading" style="clear: both;"><b>({count($orders)})</b> {l s='Orders Result for'} <b>"{$shop_name}"</b></div>
  <div class="table-responsive clearfix" {if count($orders)>3}style="height:200px;overflow: auto;"{/if}>
  
		<table id="table-buyer" class="table buyer">
        <thead>
              <tr class="">
                  <td style="border-bottom:1px solid #ccc">{l s='ID'}</td>
                  <td style="border-bottom:1px solid #ccc">{l s='Reference'}</td>
                  <td style="border-bottom:1px solid #ccc">{l s='Customer'}</td>
                  <td style="border-bottom:1px solid #ccc">{l s='Shipping'}</td>
                  <td style="border-bottom:1px solid #ccc">{l s='Total'}</td>
                  <td style="border-bottom:1px solid #ccc">{l s='Comission'}</td>
                  <td style="border-bottom:1px solid #ccc">{l s='Status'}</td>
                  <td style="border-bottom:1px solid #ccc">{l s='Due Seller'}</td>
                  <td style="border-bottom:1px solid #ccc">{l s='Date'}</td>
              </tr>
           </thead>

        <tbody>
           {foreach from=$orders key=k item=v}
             <tr class="{if $k % 2 == 0}odd{/if}">
                <td class="pointer">{$v.id_order}</td>
                <td class="pointer">{$v.reference}</td>
                <td class="pointer">{$v.customer}</td>
                <td class="pointer">${$v.shipping_amt}</td>
                <td class="pointer">${$v.total_products}</td>
                <td class="pointer">${$v.commission}</td>
                <td class="pointer">{$v.status}</td>
                <td class="pointer">${$v.due}</td>
                <td class="pointer">{$v.date_add}</td>
            </tr>
           {/foreach}
           
           {if count($orders)==0}
              <tr class="odd">
                <td class="pointer" style="border-top:thin solid #ededed;" colspan="8">{l s='No record found.'}</td>
              </tr>
           {/if}
           
        </tbody>

	</table>
   
</div>     
    <hr>    
    <div style="width: 33%; float: left;border:1px solid #aaa">
           <div style="padding:5px; background:#EDECEC; border-bottom:1px solid #aaa"><b>{l s='Commissions for'} "{$shop_name}"</b></div>
             <div style="padding:5px;">
             
           {if $id_seller!=''}
           <table style="width: 100%;">
            <tr><td>{l s='Total amount with this seller:'}</td><td align="right"> <strong>{$turnover} {$currency}</strong></td></tr>
             <tr><td>{l s='Total commission with this seller:'}</td><td align="right"> <strong>{$commission} {$currency}</strong></td></tr>
             <tr><td colspan="2"><hr color="#999999"></td></tr>
             <tr><td>{l s='Seller amount after commission:'} </td><td align="right"><strong>{$seller_turnover} {$currency}</strong></td></tr>
             <tr><td> {l s='Total amount transferred:'}</td><td align="right"> <strong>{$transferred} {$currency}</strong></td></tr>
             <tr><td colspan="2"><hr></td></tr>
             <tr><td>{l s='Current money owned:'} </td><td align="right"><strong>{$available_amt} {$currency}</strong></td></tr>
             </table>
            {else}
            {l s='No seller selected.'}
            {/if}
            </div>
         </div>
    <form action="index.php?controller=AdminSellerTransfer&token={$token}{if $id_seller!=''}&id_seller={$id_seller}{/if}" method="post" class="defaultForm form-horizontal AdminSellerWithdrawals">
     <input type="hidden" name="available_amt" value="{$available_amt}">
     <input type="hidden" name="orderIDs" value="{$orderIDs}">
     <fieldset>
                <div class="form-group">
				<label class="control-label col-lg-3">{l s='Transfer amount'}:</label>
							<div class="col-lg-5 ">
	                             <div class="row">
										<div class="input-group col-lg-4">
                                       <input style="width:120px;" type="text" name="amount" value="{if $id_seller!=''}{$orders_amt}{else}0.00{/if}" {if $id_seller=='' OR  $orders_amt <= 0.50}disabled="disabled"{/if}> {if $id_seller!=''}<b>&nbsp;{$currency}</b>{/if}
										</div>
									</div>
                               </div>
						    </div>
               
                <div class="form-group"> 
                <label class="control-label col-lg-3">&nbsp;</label>
                              <div class="col-lg-5 ">
	                                <div class="row">
										<div class="input-group col-lg-4">  
                                          <button type="submit" name="transfer" class="btn btn-default" {if $id_seller=='' OR  $available_amt <= 0.50}disabled="disabled"{/if}>
                                                 <i class="process-icon-download"></i>{l s='Transfer'}
                                              </button>
                                            </div>
									</div>
                                   </div>
                </div> 
                
            
         </div>
         
 
  <div class="panel-heading" style="clear: both;">&nbsp;</div>
  <div class="panel-heading" style="clear: both;">{l s='Payment transfer history'}</div>
  <div class="table-responsive clearfix">
  
		<table id="table-buyer" class="table buyer">
        <thead>
              <tr class="">
                  <td>{l s='ID'}</td>
                  <td>{l s='Seller'}</td>
                  <td>{l s='Customer'}</td>
                  <td>{l s='Seller account'}</td>
                  <td>{l s='Transaction ID'}</td>
                  <td>{l s='Amount'}</td>
                  <td>{l s='Status'}</td>
                  <td>{l s='Date'}</td>
              </tr>
           </thead>

        <tbody>
           {foreach from=$transfers key=k item=v}
             <tr class="{if $k % 2 == 0}odd{/if}">
                <td class="pointer">{$v.id_transfer}</td>
                <td class="pointer">{$v.id_seller}</td>
                <td class="pointer">{$v.id_customer}</td>
                <td class="pointer">{$v.destination}</td>
                <td class="pointer">{$v.transaction_id}</td>
                <td class="pointer">{$v.amount} {$v.currency}</td>
                <td class="pointer">{$v.status}</td>
                <td class="pointer">{$v.date_add}</td>
            </tr>
           {/foreach}
           
           {if count($transfers)==0}
              <tr class="odd">
                <td class="pointer" style="border-top:thin solid #ededed;" colspan="8">{l s='No record found.'}</td>
              </tr>
           {/if}
           
        </tbody>

	</table>
   
</div>
  
       </div>
      </fieldset>
    </form>
 </div>
 </div>
