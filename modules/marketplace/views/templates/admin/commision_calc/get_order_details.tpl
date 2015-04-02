{* Generate HTML code for printing Invoice Icon with link *}
<span style="width:20px; margin-right:5px;">
<a target="_blank" href="{$link->getAdminLink('AdminOrders')|escape:'htmlall':'UTF-8'}&id_order={$order->id}&vieworder" title="{l s='Details' mod='marketplace'}">
	<img src="../img/admin/details.gif" alt="Details" />
</a>
</span>
