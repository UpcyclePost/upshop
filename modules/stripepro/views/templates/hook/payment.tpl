{*
*Copyright (c) 2015. All rights reserved NTS - Nexus Total Solution
*You are NOT allowed to modify the software. 
*It is also not legal to do any changes to the software and distribute it in your own name / brand. 
*}
<div class="payment_module {if $stripe_ps_version < '1.5'}stripe-payment-15{/if}{if $stripe_ps_version > '1.5'}stripe-payment-16{/if}">
	<h3 class="stripe_title">{l s='Pay by credit / debit card' mod='stripepro'} {if $stripe_allow_btc}{l s=' / Bitcoin' mod='stripepro'}{/if} <img alt="secure" src="{$module_dir|escape:htmlall:'UTF-8'}views/img/secure-icon.png" /></h3>
<button id="stripe-proceed-button">{l s='Proceed to Pay' mod='stripepro'} {convertPrice price=$cart_total/100}</button>

<div style="display: none; width: auto; height: auto;text-align: center;" class="fancybox-overlay fancybox-overlay-fixed" id="fancybox_loadin"><div style="opacity: 1; overflow: visible; height: auto; width: 397px; position: absolute; top: 175px; left: 514px;" class="fancybox-wrap fancybox-desktop fancybox-type-inline fancybox-opened" tabindex="-1"><div style="padding: 15px; width: auto; height: auto;" class="fancybox-skin"><div class="fancybox-outer"><div style="overflow: auto; width: 367px;" class="fancybox-inner"><div id="data" style="font-size: 16px; padding: 5px; display: block;">{l s='Do not press' mod='stripepro'} <b>{l s='BACK' mod='stripepro'}</b> {l s='or' mod='stripepro'} <b>{l s='REFRESH' mod='stripepro'}</b>  {l s='while processing...' mod='stripepro'}<br><br>
<img src="{$module_dir|escape:htmlall:'UTF-8'}views/img/ajax-loader.gif" alt="" /></div></div></div>

<script>
$(document).ready(function() {

	$("a#inline").fancybox({
		'hideOnContentClick': false
	});
	
});

  var handler = StripeCheckout.configure({
	  closed: function(){
		$('#stripe-proceed-button').html("{l s='Proceed to Pay' mod='stripepro'} {convertPrice price=$cart_total/100}");
		},
    key: '{$stripe_pk}',
    image: '{$img_dir}payment-64x64.png',
	currency:'{$currency}',
	email:'{$cu_email}',
	bitcoin:{if $stripe_allow_btc}true{else}false{/if},
	allowRememberMe:{if $stripe_save_tokens_ask}true{else}false{/if},
	{if $stripe_allow_btc}refund_mispayments:true,{/if}
    token: function(token) {
		$("#fancybox_loadin").show();
		window.location.href = "{$validation_url}&stripeToken="+token.id;
    }
  });

  $('#stripe-proceed-button').on('click', function(e) {
	   $("#stripe-proceed-button").html("{l s='Processing...' mod='stripepro'}");
    // log to mixpanel
    var amount = $('#total_price').text();
    mixpanel.track("Pay Clicked",{
  		'Amount': amount
  	});
    
    // Open Checkout with further options
    handler.open({
      name: '{$shop_name}',
      description: "{l s='Complete your transaction' mod='stripepro'}",
      amount: {$cart_total}
    });
    e.preventDefault();
  });

  // Close Checkout on page navigation
  $(window).on('popstate', function() {
    handler.close();
  });
</script>
</div>
