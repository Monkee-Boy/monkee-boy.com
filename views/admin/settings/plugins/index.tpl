{include file="inc_header.tpl" page_title="Manage Plugins" menu="settings"}
{head}
<script language="JavaScript" type="text/javascript" src="/scripts/jTPS/jTPS.js"></script>
<link rel="stylesheet" type="text/css" href="/scripts/jTPS/jTPS.css">
<script type="text/javascript">
	$(function(){ldelim}
		$('.dataTable').jTPS({ldelim}
			perPages:[10,15,20],
			scrollStep: 1
		{rdelim});
	{rdelim});
</script>
{/head}
<table class="dataTable">
	<thead>
		<tr>
			<th sort="title">Plugin</th>
			<th sort="version">Version</th>
			<th sort="version">Author</th>
			<th sort="status">Status</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aPlugins item=aPlugin}
			<tr>
				<td>{$aPlugin.name|clean_html}</td>
				<td>{$aPlugin.version|clean_html}</td>
				<td>{$aPlugin.author|clean_html}</td>
				<td>
					{if $aPlugin.status == 1}
						Active
					{else}
						In-Active
					{/if}
				</td>
				<td class="small center border-end">
					{if $aPlugin.status == 0}
						<a href="/admin/settings/manage/edit/{$aSetting.id}/" title="Edit Setting">
							<img src="/images/admin/icons/pencil.png">
						</a>
					{else}
						<a href="/admin/settings/manage/delete/{$aSetting.id}/"
							onclick="return confirm_('Are you sure you would like to delete this setting?');" title="Delete Setting">
							<img src="/images/admin/icons/bin_closed.png">
						</a>
					{/if}
				</td>
			</tr>
		{/foreach}
	</tbody>
	<tfoot class="nav">
		<tr>
			<td colspan="5">
				<div class="pagination"></div>
				<div class="paginationTitle">Page</div>
				<div class="selectPerPage"></div>
				<div class="status"></div>
			</td>
		</tr>
	</tfoot>
</table>
{include file="inc_footer.tpl"}