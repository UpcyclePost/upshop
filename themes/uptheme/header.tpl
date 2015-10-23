{*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<!DOCTYPE HTML>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="{$language_code|escape:'html':'UTF-8'}"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7" lang="{$language_code|escape:'html':'UTF-8'}"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8" lang="{$language_code|escape:'html':'UTF-8'}"><![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9" lang="{$language_code|escape:'html':'UTF-8'}"><![endif]-->
<html lang="{$language_code|escape:'html':'UTF-8'}">
	<head>
		<meta charset="utf-8" />
		<title>{$meta_title|escape:'html':'UTF-8'}</title>
{if isset($meta_description) AND $meta_description}
		<meta name="description" content="{$meta_description|escape:'html':'UTF-8'}" />
{/if}
{if isset($meta_keywords) AND $meta_keywords}
		<meta name="keywords" content="{$meta_keywords|escape:'html':'UTF-8'}" />
{/if}
		<meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}no{/if}follow" />
		<meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}" />
		<link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}" />
		<meta name="google-site-verification" content="nSBkkqUb_O2hPBS0gkOgCydocQDi-M1xHG23fwhNIIo" />
{if isset($css_files)}
	{foreach from=$css_files key=css_uri item=media}
		<link rel="stylesheet" href="{$css_uri|escape:'html':'UTF-8'}" type="text/css" media="{$media|escape:'html':'UTF-8'}" />
	{/foreach}
{/if}
{if isset($js_defer) && !$js_defer && isset($js_files) && isset($js_def)}
	{$js_def}
	{foreach from=$js_files item=js_uri}
	<script type="text/javascript" src="{$js_uri|escape:'html':'UTF-8'}"></script>
	{/foreach}
{/if}
                <link href="{$css_dir}ptmfix.css" rel="stylesheet" type="text/css" media="screen" />
				<link href="{$css_dir}styles.min.css" type="text/css" rel="stylesheet" />
				
		{$HOOK_HEADER}
		<link rel="stylesheet" href="http{if Tools::usingSecureMode()}s{/if}://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,700,600" type="text/css" media="all" />
		{if $page_name!='product' && $page_name!='order-confirmation'}
		<meta property="og:title" content="{$meta_title|escape:'htmlall':'UTF-8'}"/>
		<meta property="og:url" content="http://{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}"/>
		<meta property="og:site_name" content="http://www.upcyclepost.com"/>
		<meta property="og:type" content="website">
		<meta property="og:description" content="{$meta_description|escape:html:'UTF-8'}">
		<meta property="og:image" content="{$img_ps_dir}logo.jpg" />
		{else}
			<script type="text/javascript">var switchTo5x=true;</script>
			<script type="text/javascript" src="http{if Tools::usingSecureMode()}s{/if}://w{if Tools::usingSecureMode()}s{/if}.sharethis.com/button/buttons.js"></script>
			<script type="text/javascript">
				stLight.options(
				{
				publisher: '0919549b-9f77-444b-bd9a-4c8683b78c51',
				doNotHash: false,
				doNotCopy: false,
				hashAddressBar: false
				}
				);
			</script>
		{/if}
		<link href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/css/font-awesome.min.css" rel="stylesheet">
		<link href="{$css_dir}upcyclepost/css/upcyclepost.css" rel="stylesheet">
		<!-- mmenu -->
		<link href="{$css_dir}upcyclepost/libraries/mobile-slide-menu/jquery.mmenu.positioning.css" rel="stylesheet">

		<script type="text/javascript" src="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/js/libraries/tagmanager/tagmanager.js"></script>

        <script type="text/javascript" src="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/js/libraries/typeahead/bloodhound.min.js"></script>
        <script type="text/javascript" src="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/js/libraries/typeahead/typeahead.jquery.min.js"></script>
        <script type="text/javascript" src="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/up/config"></script>
		<script type="text/javascript" src="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/js/up.js"></script>
				
		{if $page_name=='product'}
		<script type="text/javascript" src="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/js/social/follow.js"></script>			
		{/if}
		<!--[if IE 8]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->

<!-- Start of upcyclepost Zendesk Widget script -->
<script>
{literal}
/*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(c){n=document.domain,r.src='javascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var o=this.createElement("script");n&&(this.domain=n),o.id="js-iframe-async",o.src=e,this.t=+new Date,this.zendeskHost=t,this.zEQueue=a,this.body.appendChild(o)},o.write('<body onload="document._l();">'),o.close()}("//assets.zendesk.com/embeddable_framework/main.js","upcyclepost.zendesk.com");/*]]>*/
{/literal}
</script>
<!-- End of upcyclepost Zendesk Widget script -->

{if $page_name=='order-confirmation'}
<!-- Facebook tracking pixel for order confirmation -->
<!-- Facebook Conversion Code for Registrations -->
<script>
(function() 
{
var _fbq = window._fbq || (window._fbq = []);
if (!_fbq.loaded) 
	{
	var fbds = document.createElement('script');
	fbds.async = true;
	fbds.src = '//connect.facebook.net/en_US/fbds.js';
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(fbds, s);
	_fbq.loaded = true;
	}
}
)();
window._fbq = window._fbq || [];
window._fbq.push(['track', '6027155380399', 
{
	'value':'{$total_to_pay}','currency':'{$currency_iso_code}'
}
]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6027155380399&amp;cd[value]={$total_to_pay}&amp;cd[currency]={$currency_iso_code}&amp;noscript=1" /></noscript>
<!-- End Facebook tracking pixel for order confirmation -->
<!-- Google Code for Purchase Confirmation Conversion Page --> 
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion_async.js" charset="utf-8"></script>
<script type="text/javascript">
/* <![CDATA[ */
	window.google_trackConversion({
		google_conversion_id : 1034553725,
		google_conversion_language : "en",
		google_conversion_format : "3",
		google_conversion_color : "ffffff",
		google_conversion_label : "eYXfCPiHjmAQ_ZKo7QM", 
		google_conversion_value : {$total_to_pay},
		google_conversion_currency : "{$currency_iso_code}", 
		google_remarketing_only : false
	});
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" 
src="//www.googleadservices.com/pagead/conversion/1034553725/?value={$total_to_pay}&amp;currency_code={$currency_iso_code}&amp;label=eYXfCPiHjmAQ_ZKo7QM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
{/if}

<!-- start Mixpanel snippet-->
{literal}
<script type="text/javascript">(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,e,d])};b.__SV=1.2;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f)}})(document,window.mixpanel||[]);
</script>
{/literal}
<!-- end Mixpanel snippet-->

<!-- start Mixpanel init-->
{if {$smarty.server.SERVER_NAME}=='www.upcyclepost.com' || {$smarty.server.SERVER_NAME}=='www.upmod.com'}
<script type="text/javascript">mixpanel.init("c0185653f28d7158fd08c11fd5eeca91");</script>
{else}
<script type="text/javascript">mixpanel.init("bdba27aa461b0f60b84e470697e19a0b");</script>
{/if}
<!-- end Mixpanel init-->

<script type="text/javascript">
{if $page_name=='order'}
{literal}
	mixpanel.track('Viewed Page', {'Page Name': $('#mixpanel_page_name').text()});
{/literal}
{else}
{literal}
	mixpanel.track('Viewed Page', {'Page Name': page_name});
{/literal}
{/if}
</script>		
	</head>
	<body{if isset($page_name)} id="{$page_name|escape:'html':'UTF-8'}"{/if} class="{if isset($page_name)}{$page_name|escape:'html':'UTF-8'}{/if}{if isset($body_classes) && $body_classes|@count} {implode value=$body_classes separator=' '}{/if}{if $hide_left_column} hide-left-column{/if}{if $hide_right_column} hide-right-column{/if}{if isset($content_only) && $content_only} content_only{/if} lang_{$lang_iso}">
	{if !isset($content_only) || !$content_only}
		{if isset($restricted_country_mode) && $restricted_country_mode}
			<div id="restricted-country">
				<p>{l s='You cannot place a new order from your country.'}{if isset($geolocation_country) && $geolocation_country} <span class="bold">{$geolocation_country|escape:'html':'UTF-8'}</span>{/if}</p>
			</div>
		{/if}
		<div id="page">
			<div id="header_setfooter" class="header-container">
				<header id="header" class="header-area">
					<nav class="mainmenu">
						<div class="container">
							<div class="row">
								<div class="col-xs-2 col-sm-3 col-md-2 col-lg-2" style="float:left;padding-left:0;padding-right:0;">
									<a class="logo" href="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}" title="{$shop_name|escape:'html':'UTF-8'}">
									</a>
								</div>		
								{if isset($HOOK_TOP)}{$HOOK_TOP}{/if}
								{hook h="displayNav"}
							</div>
						</div>
					</nav>
				</header>
				<div class="main-dd-menu">
				    <div class="container">
				        <div class="row">
							<div class="col-xs-12 search-container hidden-md hidden-lg" style="padding:5px 0">
								<form class="search-form form-inline" method="post" action="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/gallery" id="universal-search-form">
								<input type="search" name="term" class="form-control search" placeholder="Search the world's largest upcyle hand-crafted community" id="universal-search">
								</form>
							</div>

				            <div class="column clearfix">
				                <h3>Shop Categories</h3>
				                <ul class="categories">
				        				<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/art" title="Art">Art</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/automotive" title="Automotive">Automotive</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/construction" title="Construction">Construction</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/crafts" title="Crafts">Crafts</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/electronics" title="Electronics">Electronics</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/fashion" title="Fashion">Fashion</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/furniture" title="Furniture">Furniture</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/glass" title="Glass">Glass</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/hardware" title="Hardware">Hardware</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/holidays" title="Holidays">Holidays</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/home" title="Home">Home</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/jewelry" title="Jewelry">Jewelry</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/metal" title="Metal">Metal</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/musical" title="Musical">Musical</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/office" title="Office">Office</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/outdoors" title="Outdoors">Outdoors</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/paper" title="Paper">Paper</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/pets" title="Pets">Pets</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/plastic" title="Plastic">Plastic</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/sporting-goods" title="Sporting Goods">Sporting Goods</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/toys" title="Toys">Toys</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/vintage" title="Vintage">Vintage</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/wood" title="Wood">Wood</a></li>
										<li><a href="http://{$smarty.server.SERVER_NAME}/gallery/yard" title="Yard">Yard</a></li>							
				                </ul>
				            </div>
				            <div class="column clearfix">
				                <div class="left-menu">
				                    <h3>Share your ideas</h3>
				                    <ul class="links">
				                        <li><a href="http://{$smarty.server.SERVER_NAME}/post/idea" title="Upload your images">Upload your images</a></li>
				                        <li><a href="http://{$smarty.server.SERVER_NAME}/search/users" title="View member gallery">View member gallery</a></li>
				                        <li><a href="http://{$smarty.server.SERVER_NAME}/browse/ideas" title="Browse Ideas">Browse Ideas</a></li>
				                    </ul>
				                </div>
				                <div class="right-menu">
				                    <h3>Sell your products</h3>
				                    <ul class="links">
				                        <li><a href="http://{$smarty.server.SERVER_NAME}/shop/module/marketplace/sellerrequest" title="Create your shop">Create your shop</a></li>
				                        <li><a href="http://{$smarty.server.SERVER_NAME}/browse/shops" title="View shop gallery">View shop gallery</a></li>
				                    </ul>
				                </div>
				            </div>
				            <div class="column clearfix">
				                <h3>Upmod</h3>
				                <ul class="links">
				                    <li><a href="http://{$smarty.server.SERVER_NAME}/about" title="About us">About us</a></li>
				                    <li><a href="http://{$smarty.server.SERVER_NAME}/blog" title="Blog">Blog</a></li>
				                    <li><a href="http://{$smarty.server.SERVER_NAME}/contact" title="Contact us">Contact us</a></li>
				                </ul>
				                <div class="social-icons">
				                    <a href="https://www.facebook.com/upmodinc"><i class="fa fa-facebook-square"></i></a>
				                    <a href="https://www.twitter.com/upmodinc"><i class="fa fa-twitter-square"></i></a>	                    
				                    <a href="https://www.pinterest.com/upmodinc"><i class="fa fa-pinterest-square"></i></a>
									<a href="https://plus.google.com/+upmodinc"><i class="fa fa-google-plus-square"></i></a>										
				                </div>
				            </div>
				        </div>
				    </div>
				</div>
				{if $page_name=='product'}
				<nav class="submenu hidden-xs">
					<div class="submenu-bg">
                    <div class="container hidden-xs">
                        <div class="row">
                            <div class="left_menu">
                                <ul>
                                    <li>
                                        <a href="http://{$smarty.server.SERVER_NAME}/browse/products/art">Art</a>
                                    </li>

                                    <li>
                                        <a href="http://{$smarty.server.SERVER_NAME}/browse/products/fashion">Fashion</a>
                                    </li>

                                    <li>
                                        <a href="http://{$smarty.server.SERVER_NAME}/browse/products/furniture">Furniture</a>
                                    </li>

                                    <li>
                                        <a href="http://{$smarty.server.SERVER_NAME}/browse/products/home">Home</a>
                                    </li>

                                    <li>
                                        <a href="http://{$smarty.server.SERVER_NAME}/browse/products/jewelry">Jewelry</a>
                                    </li>

                                </ul>
                            </div>

                            <div class="right_menu">
                                <a class="menu-toggle">See all categories</a>
                            </div>
                        </div>
                    </div>
					</div>
                </nav>
				{/if}
				{if $page_name=="product"}
				<nav class="submenu hidden-md hidden-lg">
					<div class="submenu-bg">
                    <div class="container">
                        <div class="row">
								<div class="col-xs-12 search-container" style="padding:5px 0">
								<form class="search-form form-inline" method="post" action="http{if Tools::usingSecureMode()}s{/if}://{$smarty.server.SERVER_NAME}/gallery" id="universal-search-form">
								<input type="search" name="term" class="form-control search" placeholder="Search the world's largest upcyle hand-crafted community" id="universal-search">
								</form>
                            </div>
                        </div>
                    </div>
					</div>
                </nav>
				{/if}


			</div>
			<div id="body_setfooter" class="columns-container">
				<div id="columns" class="container">
					{if $page_name !='index' && $page_name !='pagenotfound'}
						{include file="$tpl_dir./breadcrumb.tpl"}
					{/if}
					<div id="slider_row" class="row">
						<div id="top_column" class="center_column col-xs-12 col-sm-12">{hook h="displayTopColumn"}</div>
					</div>
					<div class="row">
						{if isset($left_column_size) && !empty($left_column_size)}
						{if $page_name != 'module-marketplace-sellerrequest' && $page_name != 'history' && $page_name != 'order-confirmation' && page_name != 'module-bankwire-payment'} 
						<div id="left_column" class="column col-xs-12 col-sm-{$left_column_size|intval}">{$HOOK_LEFT_COLUMN}</div>
						{/if}
						{/if}
						{if isset($left_column_size) && isset($right_column_size)}{assign var='cols' value=(12 - $left_column_size - $right_column_size)}{else}{assign var='cols' value=12}{/if}
						<div id="center_column" class="center_column col-xs-12 col-sm-{$cols|intval}">
	{/if}
