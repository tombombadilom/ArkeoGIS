{extends tplextends('arkeogis/layout')}

{block name='webpage_head' append}
	{js file="/mod/cssjs/js/ckeditor/ckeditor.js"}
	{js file="/mod/cssjs/js/captainhook.js"}
	{js file="/mod/cssjs/js/chwysiwyg.js"}
	{js file="/mod/cssjs/js/chmypaginate.js"}
	{js file="/mod/cssjs/js/chfilter.js"}
	{js file="/mod/page/js/page.js"}
	{css file="/mod/page/css/bootstrap-responsive.css"}
	{css file="/mod/page/css/extra.css"}
	{css file="/mod/page/css/icon.css"}
	{css file="/mod/page/css/page.css"}
{/block}

{block name='arkeogis_content'}
<div class="page-list">
	{block name='paginator'}
	{* Pagination *}
	<div id="pagination" class="pagination">
  		<ul>
    			<li  class="prev"><a id='paginator_prev' href="#">&larr; Previous</a></li>
    			<li class="active"><a id="paginator_nums" href="#"></a></li>
    			<li class="next"><a id="paginator_next" href="#">Next &rarr;</a></li>
  		</ul>
	</div>
	{/block}
	{block name='page_list'}
	<table id="page_list" class="table zebra-striped condensed-table bordered-table table-list" summary="Page List" border="0" cellspacing="0" cellpadding="0">
		<caption class="list">Pages list</caption>
		<thead>
			<tr>
			<th><div >Name </div></th>
			<th><div>Created by</div></th>
			<th><div>Published</div></th>
			<th><div>Created</div></th>
			<th><div>Updated</div></th>
			<th>Action</th>
			</tr>
		</thead>
		<tbody>
			{section name=p loop=$list}
			<tr>
				<td><a href="/page/{$list[p].sysname}">{$list[p].name}</a></td>
				<td>{$list[p].login}</td>
				<td >{if $list[p].published eq 1}yes{else}no{/if}</td>
				<td >{$list[p].created|date_format: '%d %b %Y'}</td>
				<td >{$list[p].updated|date_format: '%d %b %Y'}</td>
				<td class="action">
	 				<a class="btn" href="/page/edit/{$list[p].pid}"><i class="icon-edit"></i>  Edit</div></a>
	 				
					<a class="ajaxLink btn" onclick="mypage.delPage({$list[p].pid});" href="#"><i class="icon-remove"></i>  Del</a>
				</td>
			</tr>
			{/section}
		</tbody>
	</table>
	{/block}
</div>
<script>
	
	var mypage = new Page();
	window.addEvent('domready', function() {
		$$('a.ajaxLink').addEvent('click', function(event){
				event.preventDefault();
		});
		var paginate= new CHMyPaginate({
			paginateElement: 'pagination',
			tableElement: 'page_list',
			path:'/page/list/',
			conf: true,
			sort: '{$sort}',
			sortable: ['name', 'login', 'published', 'created', 'updated'],
			filters: [[['label', 'Title'],
				   ['name', 'name'],
				   ['type', 'text'],
				   ['returnMethod', 'func'],
				   ['returnFunc', 'nameFilter']],
				  [['label', 'Created by'],
                                   ['name', 'login'],
                                   ['type', 'select'],
                                   ['returnMethod', 'func'],
                                   ['returnFunc', 'authorList']],
				  [['label', 'Published'],
                                   ['name', 'published'],
                                   ['type', 'select'],
                                   ['returnMethod', 'bool'],
                                   ['returnFunc', 'no|yes']], 
				 ],
			filter: '{$filter}',		
			maxrow: {$maxrow},
			offset: {$offset},
			quant: {$quant}
		});
	});

</script>
{/block}
