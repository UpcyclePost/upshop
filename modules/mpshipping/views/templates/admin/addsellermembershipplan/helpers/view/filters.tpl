<div class="badges_filters">
{if $type == 'badges_feature' || $type == 'badges_achievement'}
	<div>{l s="Type:" mod='mpshipping'}
		<select id="group_select_{$type}" onchange="filterBadge('{$type}');">
				<option value="badge_all">{l s="All" mod='mpshipping'}</option>
			{foreach from=$groups.$type key=id_group item=group}
				<option value="group_{$id_group}">{$group}</option>
			{/foreach}
		</select>
	</div>
{/if}	
	<div>{l s="State:" mod='mpshipping'}
		<select id="status_select_{$type}" onchange="filterBadge('{$type}');">
			<option value="badge_all">{l s="All" mod='mpshipping'}</option>
			<option value="validated">{l s="Validated" mod='mpshipping'}</option>
			<option value="not_validated">{l s="Not Validated" mod='mpshipping'}</option>
		</select>
	</div>

{if $type == 'badges_feature' || $type == 'badges_achievement'}
	<div>{l s="Level:" mod='mpshipping'}
		<select id="level_select_{$type}" onchange="filterBadge('{$type}');">
				<option value="badge_all">{l s="All" mod='mpshipping'}</option>
			{foreach from=$levels key=id_level item=level}
				<option value="level_{$id_level}">{$level}</option>
			{/foreach}
		</select>
	</div>
{/if}
</div>
<div class="clear"></div>


