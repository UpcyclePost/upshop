{*
*Copyright (c) 2015. All rights reserved NTS - Nexus Total Solution
*You are NOT allowed to modify the software. 
*It is also not legal to do any changes to the software and distribute it in your own name / brand. 
*}
<div class="payment_module {if $stripe_ps_version < '1.5'}stripe-payment-15{/if}{if $stripe_ps_version > '1.5'}stripe-payment-16{/if}">
	<h3 class="stripe_title">{l s='Pay by credit / debit card' mod='stripepro'} {if $stripe_allow_btc}{l s=' / Bitcoin' mod='stripepro'}{/if} <img alt="secure" src="{$module_dir|escape:htmlall:'UTF-8'}views/img/secure-icon.png" /></h3>
<div class="cc_btc_img"><img src="{$stripe_cc}" alt="stripe credit/ debit cards">{if $stripe_allow_btc}<img src="{$stripe_btc}" alt="stripe bitcoin accepted">{/if}</div>
<button id="stripe-proceed-button">{l s='Proceed to Pay' mod='stripepro'} {convertPrice price=$cart_total/100}</button>
<script>
  var handler = StripeCheckout.configure({
    key: '{$stripe_pk}',
    image: '{$logo_url}',
	currency:'{$currency}',
	email:'{$cu_email}',
	bitcoin:{if $stripe_allow_btc}true{else}false{/if},
	allowRememberMe:{if $stripe_save_tokens_ask}true{else}false{/if},
	{if $stripe_allow_btc}refund_mispayments:true,{/if}
    token: function(token) {
		window.location.href = "{$validation_url}&stripeToken="+token.id;
    }
  });

  $('#stripe-proceed-button').on('click', function(e) {
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
