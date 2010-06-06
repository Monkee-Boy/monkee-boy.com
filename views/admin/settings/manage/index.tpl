{include file="inc_header.tpl" page_title="Manage Settings" menu="settings"}
{assign var=subMenu value="Manage Settings"}
{head}
<script src="/scripts/dataTables/jquery.dataTables.min.js"></script>
<script src="/scripts/dataTables/plugins/paging-plugin.js"></script>
<script type="text/javascript">
	$(function(){ldelim}
		$('.dataTable').dataTable({ldelim}
			/* DON'T CHANGE */
			"sDom": 'rt<"dataTable-footer"flpi<"clear">',
			"sPaginationType": "scrolling",
			"bLengthChange": true,
			/* CAN CHANGE */
			"fnDrawCallback": function ( oSettings ) {ldelim}
				if ( oSettings.aiDisplay.length == 0 ) {ldelim}
					return;
				{rdelim}

				var nTrs = $('.dataTable tbody tr');
				var iColspan = nTrs[0].getElementsByTagName('td').length;
				var sLastGroup = "";
				for ( var i=0 ; i<nTrs.length ; i++ )
				{ldelim}
					var iDisplayIndex = oSettings._iDisplayStart + i;
					var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];
					if ( sGroup != sLastGroup )
					{ldelim}
						var nGroup = document.createElement( 'tr' );
						var nCell = document.createElement( 'td' );
						nCell.colSpan = iColspan;
						nCell.className = "group";
						nCell.innerHTML = sGroup;
						nGroup.appendChild( nCell );
						nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
						sLastGroup = sGroup;
					{rdelim}
				{rdelim}
			{rdelim}, //row grouping
			"bStateSave": true, //whether to save a cookie with the current table state
			"iDisplayLength": 10, //how many items to display on each page
			"aaSortingFixed": [[ 0, 'asc' ]],
			"aaSorting": [[4, "asc"]] //which column to sort by (0-X)
		{rdelim});
	{rdelim});
</script>
{/head}

	<a href="/admin/settings/manage/add/" title="Add Setting" class="button">Add Setting &raquo;</a>
	
	<ul class="pageTabs">
		{foreach from=$aAdminMenu item=aMenu key=k}
			{if $k == "settings"}
				{if $aMenu.menu|@count gt 1}
					{foreach from=$aMenu.menu item=aItem}
						<li><a{if $subMenu == $aItem.text} class="active"{/if} href="{$aItem.link}" title="{$aItem.text|clean_html}">{$aItem.text|clean_html}</a></li>
					{/foreach}
				{/if}
			{/if}
		{/foreach}
	</ul>
</header>

<table class="dataTable">
	<thead>
		<tr>
			<th class="hidden">Group</th>
			<th class="empty">&nbsp;</th>
			<th>Title</th>
			<th>Tag</th>
			<th class="hidden">Order</th>
			<th class="sorting_disabled center empty">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aSettings item=aSetting}
			<tr>
				<td class="hidden">{$aSetting.group|clean_html}</td>
				<td><img src="/images/admin/icons/bullet_green.png" alt="active"></td>
				<td>{$aSetting.title|clean_html}</td>
				<td>{$aSetting.tag|clean_html}</td>
				<td class="hidden">{$aSetting.sortorder}</td>
				<td class="center">
					<a href="/admin/settings/manage/edit/{$aSetting.id}/" title="Edit Setting">
						<img src="/images/admin/icons/pencil.png" alt="edit_icon">
					</a>
					<a href="/admin/settings/manage/delete/{$aSetting.id}/"
						onclick="return confirm_('Are you sure you would like to delete this setting?');" title="Delete Setting">
						<img src="/images/admin/icons/bin_closed.png" alt="delete_icon">
					</a>
				</td>
			</tr>
		{/foreach}
	</tbody>
</table>

<ul class="dataTable-legend">
	<li class="bullet-green">Active</li>
	<li class="bullet-red">Inactive</li>
</ul>
{include file="inc_footer.tpl"}