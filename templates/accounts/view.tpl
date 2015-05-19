{include file="../header.tpl" title='Accounts - View' authorized=true current='accounts'}

<section class="view-view">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="action-box">
					<a href="{$smarty.const.BASE_URL}/admin/accounts/" class="btn btn-primary pull-left">
						Back to list
					</a>
					<a href="#" class="btn btn-default" title="Delete" data-target="#delete-modal" data-toggle="modal">
						<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
					</a>
					<a href="{$smarty.const.BASE_URL}/admin/accounts/edit/{$id}" class="btn btn-primary" title="Edit">
						<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
					</a>
					<a href="{$smarty.const.BASE_URL}/admin/accounts/change/{$id}" class="btn btn-primary" title="Change password">
						<span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
					</a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<h3>
					Account no {$id}
				</h3>
				<table class="table" id="data">
					<tr>
						<td>
							No data to display
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</section>

<div class="modal fade" role="dialog" id="delete-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Confirm action</h4>
			</div>
			<div class="modal-body">
				<p>
					Are you sure you want to remove account no <strong>{$id}</strong>?
				</p>
			</div>
			<div class="modal-footer">
				<form id="delete">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Delete</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	var serverResponse = {
		id: {$id}
	}
</script>
<script src="{$smarty.const.BASE_URL}/assets/js/accounts.js"></script>

{include file="../footer.tpl"}
