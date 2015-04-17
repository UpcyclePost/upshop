<style type="text/css">
.new_range{
	float: left;
	width: 100%;
}
</style>
<div class="error wizard_error" {if $is_main_error==-1}style="display:none;"{/if}>
	{if $is_main_error==1}
		<div class="alert alert-danger">
			{l s='Shipping name must not have Invalid characters /^[^<>;=#{}]*$/u' mod='mpshipping'}
		</div>
	{else if $is_main_error==2}
		<div class="alert alert-danger">
			{l s='Transit time must not have Invalid characters /^[^<>={}]*$/u' mod='mpshipping'}
		</div>
	{else if $is_main_error==3}
		<div class="alert alert-danger">
			{l s='Only jpg,png,jpeg image allow and image size should not exceed 125*125' mod='mpshipping'}
			</div>
	{else if $is_main_error==4}
		<div class="alert alert-danger">
			{l s='The grade field is invalid.' mod='mpshipping'}
		</div>
	{else if $is_main_error==5}
		<div class="alert alert-danger">
			{l s='Invalid Tracking Url' mod='mpshipping'}
		</div>
	{else if $is_main_error==6}
		<div class="alert alert-danger">
			{l s='The max height field is invalid.' mod='mpshipping'}
			</div>
	{else if $is_main_error==7}
		<div class="alert alert-danger">
			{l s='The max width field is invalid.' mod='mpshipping'}
		</div>
	{else if $is_main_error==8}
		<div class="alert alert-danger">
			{l s='The max depth field is invalid.' mod='mpshipping'}
		</div>
	{else if $is_main_error==9}
		<div class="alert alert-danger">
			{l s='The max weight field is invalid.' mod='mpshipping'}
		</div>
	{/if}
</div>
<div class="shipping_list_container left">
	<div class="shipping_heading">
		<div class="left_heading">
			<h1>{l s='Update Basic Information' mod='mpshipping'}</h1>
		</div>
		<div class="right_links">
			<div class="home_link">
				<a href="{$dash_board_link}"><img alt="home" title="Home" src="{$modules_dir}mpshipping/img/home.gif"></a>
				<a class="btn btn-default button button-small" id="add_new_shipping" href="{$sellershippinglist_link}">
					<span>{l s='Shipping list' mod='mpshipping'}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="shipping_add left swMain"  id="carrier_wizard">
		<ul class="nbr_steps_4 anchor">
			<li {if $step_count==1}class="selected"{else}class="done"{/if} style="width:33%;" id="selectedstep1">
				<a {if $step_count==1}class="selected"{else}class="done"{/if} href="#step-1" isdone="1" rel="1">
					<label class="stepNumber">1</label>
					<span class="stepDesc">
						{l s='General settings' mod='mpshipping'}
						<br>
					</span>
				</a>
			</li>
			<li  id="selectedstep2" style="width:33%;" {if $step_count==2}class="selected"{else if $step_count>2}class="done"{else}class="disabled"{/if}>
				<a {if $step_count==2}class="selected"{else if $step_count>2}class="done"{else}class="disabled"{/if} href="#step-2" {if $step_count>=2}isdone="1"{else}isdone="0"{/if} rel="2">
					<label class="stepNumber">2</label>
					<span class="stepDesc">
						{l s='Shipping locations and costs' mod='mpshipping'}
						<br />
					</span>
				</a>
			</li>
			<li  id="selectedstep3" style="width:33%;" {if $step_count==3}class="selected"{else if $step_count>3}class="done"{else}class="disabled"{/if}>
				<a {if $step_count==3}class="selected"{else if $step_count>3}class="done"{else}class="disabled"{/if} href="#step-3" {if $step_count>=3}isdone="1"{else}isdone="0"{/if} rel="3">
					<label class="stepNumber">3</label>
					<span class="stepDesc">
						{l s='Size, weight, and group access' mod='mpshipping'}
						<br />
					</span>
				</a>
			</li>
		</ul>
		<div class="stepContainer left">
			<form id="step_carrier_general" class="defaultForm form-horizontal" enctype="multipart/form-data" method="post" action="{$mpshippingprocess_link_update}">
				<div id="step-1" {if $step_count!=1}style="display:none;"{/if}>
					{include file="$self/../../views/templates/front/basicupdatestep1.tpl"}
				</div>
				<div id="step-2" {if $step_count!=2}style="display:none;"{/if}>
					{include file="$self/../../views/templates/front/basicupdatestep2.tpl"}
				</div>
				<div id="step-3" {if $step_count!=3}style="display:none;"{/if}>
					{include file="$self/../../views/templates/front/basicupdatestep3.tpl"}
				</div>
		</div>
		<div class="actionBar">
			<div class="msgBox">
				<div class="content"></div>
					<a class="close" href="#">X</a>
				</div>
			<div class="loader">{l s='Loading' mod='mpshipping'}</div>
			{if $step_count==3}
				<a class="buttonFinish" href="javascript:void(0)">{l s='Finish' mod='mpshipping'}</a>
				<a class="buttonNext buttonDisabled" href="javascript:void(0)">{l s='Next' mod='mpshipping'}</a>
			{else}
				<!--<a class="buttonFinish buttonDisabled" href="javascript:void(0)">Finish</a>-->
				<input type="submit" class="buttonFinish buttonDisabled" value="Finish">
				<a class="buttonNext" href="javascript:void(0)">{l s='Next' mod='mpshipping'}</a>
			{/if}
			{if $step_count==1}
				<a class="buttonPrevious buttonDisabled" href="javascript:void(0)">{l s='Previous' mod='mpshipping'}</a>
			{else}
				<a class="buttonPrevious" href="javascript:void(0)">{l s='Previous' mod='mpshipping'}</a>
			{/if}
		</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	var string_price = '{l s='Will be applied when the price is' js=1 mod='mpshipping'}';
	var string_weight = '{l s='Will be applied when the weight is' js=1 mod='mpshipping'}';
	var invalid_range = '{l s='This range is not valid' js=1 mod='mpshipping'}';
	var need_to_validate = '{l s='Please validate the last range before create a new one.' js=1 mod='mpshipping'}';
	var delete_range_confirm = '{l s='Are you sure to delete this range ?' js=1 mod='mpshipping'}';
	var currency_sign = '{$currency_sign}';
	var PS_WEIGHT_UNIT = '{$PS_WEIGHT_UNIT}';
	var	labelDelete = '{l s='Delete' js=1 mod='mpshipping'}';
	var	labelValidate = '{l s='Validate' js=1 mod='mpshipping'}';
	var range_is_overlapping = '{l s='Ranges are overlapping' js=1 mod='mpshipping'}';
	var select_country = '{l s='Select country' js=1 mod='mpshipping'}';
	var select_state = '{l s='All' js=1 mod='mpshipping'}';
	var zone_error = '{l s='Select Zone' js=1 mod='mpshipping'}';
	var zone_error = '{l s='Select Country' js=1 mod='mpshipping'}';
	var ranges_info = '{l s='Ranges' js=1 mod='mpshipping'}';
	var shipping_method = {$shipping_method};
	var finish_error = '{l s='You need to go through all step' js=1 mod='mpshipping'}';
</script>