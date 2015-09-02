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
                <link href="{$css_dir}custommaker.css" rel="stylesheet" type="text/css" media="screen" />
                <link href="{$css_dir}customuser.css" rel="stylesheet" type="text/css" media="screen" />
				<link href="{$css_dir}styles.min.css" type="text/css" rel="stylesheet" />
		{$HOOK_HEADER}
		<link rel="stylesheet" href="http{if Tools::usingSecureMode()}s{/if}://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,700,600" type="text/css" media="all" />
		{if $page_name!='product'}
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
<!-- Facebook Conversion Code for Registrations - UpcyclePost 1 -->
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
window._fbq.push(['track', '6025947566399', 
{
	'value':'{$total_to_pay}','currency':'{$currency_iso_code}'
}
]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6025947566399&amp;cd[value]={$total_to_pay}&amp;cd[currency]={$currency_iso_code}&amp;noscript=1" /></noscript>
<!-- End Facebook tracking pixel for order confirmation -->
{/if}		
	</head>
	<body{if isset($page_name)} id="{$page_name|escape:'html':'UTF-8'}"{/if} class="{if isset($page_name)}{$page_name|escape:'html':'UTF-8'}{/if}{if isset($body_classes) && $body_classes|@count} {implode value=$body_classes separator=' '}{/if}{if $hide_left_column} hide-left-column{/if}{if $hide_right_column} hide-right-column{/if}{if isset($content_only) && $content_only} content_only{/if} lang_{$lang_iso}">
	{if !isset($content_only) || !$content_only}
		{if isset($restricted_country_mode) && $restricted_country_mode}
			<div id="restricted-country">
				<p>{l s='You cannot place a new order from your country.'}{if isset($geolocation_country) && $geolocation_country} <span class="bold">{$geolocation_country|escape:'html':'UTF-8'}</span>{/if}</p>
			</div>
		{/if}
		<div id="page">
			<div class="header-container">
				<header id="header">
					<div>
						<div class="container">
							<div class="row">
								<div id="" style="float:left;">
									<a href="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}" title="{$shop_name|escape:'html':'UTF-8'}">
										<img class="logo img-responsive" src="{$logo_url}" alt="{$shop_name|escape:'html':'UTF-8'}"{if isset($logo_image_width) && $logo_image_width} width="{$logo_image_width}"{/if}{if isset($logo_image_height) && $logo_image_height} height="{$logo_image_height}"{/if}/>
									</a>
								</div>
								<a id="mobile-menu" class="mobile-menu fa fa-bars visible-lg visible-md pull-right"></a>
								{if isset($HOOK_TOP)}{$HOOK_TOP}{/if}
								{hook h="displayNav"}

							</div>
						</div>
					</div>
				</header>
				<!-- Nav -->
				<div class="container">
					<div class="row">
						<nav class="menu hidden-xs hidden-sm" id="main-menu">
							<div class="content-container">
								<div class="menu-container clearfix">
									<div class="main-menu menu-categories clearfix">
										<h4 class="blue">Browse Ideas</h4>
										<div class="col-sm-3">
											<ul>
												<li><a href="/gallery/art">Art</a></li>
												<li><a href="/gallery/automotive">Automotive</a></li>
												<li><a href="/gallery/construction">Construction</a></li>
												<li><a href="/gallery/crafts">Crafts</a></li>
												<li><a href="/gallery/electronics">Electronics</a></li>
												<li><a href="/gallery/fashion">Fashion</a></li>
											</ul></div><div class="col-sm-3"><ul>
												<li><a href="/gallery/furniture">Furniture</a></li>
												<li><a href="/gallery/glass">Glass</a></li>
												<li><a href="/gallery/hardware">Hardware</a></li>
												<li><a href="/gallery/holidays">Holidays</a></li>
												<li><a href="/gallery/home">Home</a></li>
												<li><a href="/gallery/jewelry">Jewelry</a></li>
											</ul></div><div class="col-sm-3"><ul>
												<li><a href="/gallery/metal">Metal</a></li>
												<li><a href="/gallery/musical">Musical</a></li>
												<li><a href="/gallery/office">Office</a></li>
												<li><a href="/gallery/outdoors">Outdoors</a></li>
												<li><a href="/gallery/paper">Paper</a></li>
												<li><a href="/gallery/pets">Pets</a></li>
											</ul></div><div class="col-sm-3"><ul>
												<li><a href="/gallery/plastic">Plastic</a></li>
												<li><a href="/gallery/sporting-goods">Sporting Goods</a></li>
												<li><a href="/gallery/toys">Toys</a></li>
												<li><a href="/gallery/vintage">Vintage</a></li>
												<li><a href="/gallery/wood">Wood</a></li>
												<li><a href="/gallery/yard">Yard</a></li>
											</ul>
										</div>
										<div class="col-sm-6" style="margin-top: 15px;">
											<ul>
												<li><a href="/search/users"><i class="fa fa-users"></i> Visit Profile Gallery</a></li>
											</ul>
										</div>
									</div>
									<div class="main-menu menu-child-menus clearfix">
										<div class="menu-col-1 clearfix">
											<h4 class="blue">Company</h4>
											<div class="col-xs-12">
												<ul>
													<li><a href="/about">About Us</a></li>
													<li><a target="_blank" href="http://www.facebook.com/upcyclepost">Facebook</a></li>
													<li><a target="_blank" href="http://www.twitter.com/upcyclepost">Twitter</a></li>
													<li><a target="_blank" href="http://www.linkedin.com/company/upcyclepost-com">LinkedIn</a></li>
													<li><a target="_blank" href="/blog">Blog</a></li>
													<li><a target="_blank" href="/contact">Contact Us</a></li>
												</ul>
											</div>
										</div>
										<div class="menu-col-2 clearfix">
											<h4 class="green">Do you have an idea?</h4>
											<div class="col-xs-12">
												<p>It doesn't matter if it's a work in progress, rough draft or a finished product.</p>
												<a class="btn btn-green" href="/post/idea"><i class="fa fa-camera"></i>Post Your Idea</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</nav>
					</div>
				</div>
				<!-- End Nav -->
			</div>
			<div class="columns-container">
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
