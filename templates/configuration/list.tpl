{include file="../header.tpl" title='Accounts - List' authorized=true current='accounts'}

<section class="list-view">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<table class="table table-striped table-hover" id="list">
					<thead>
						<tr>
							<th>Key</th>
							<th>Name</th>
							<th>Value</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="6">
								No results to display
							</td>
						</tr>
					</tbody>
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
				<h4 class="modal-title">Type new value</h4>
			</div>
			<div class="modal-body">
				<form role="form">
					<div class="form-group">
						<label for="value">Choose value</label>
						<input type="text" class="form-control" id="value" placeholder="old value">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<form id="delete">
					<input type="hidden" name="value" value="">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Save</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script src="{$smarty.const.BASE_URL}/assets/js/configuration.js"></script>

{include file="../footer.tpl"}
