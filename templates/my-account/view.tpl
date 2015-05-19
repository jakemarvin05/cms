{include file="../header.tpl" title='My account - View' authorized=true current='my-account'}

<section class="view-view">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="action-box">
					<a href="{$smarty.const.BASE_URL}/admin/my-account/edit" class="btn btn-primary" title="Edit">
						<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
					</a>
					<a href="{$smarty.const.BASE_URL}/admin/my-account/change" class="btn btn-primary" title="Change password">
						<span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
					</a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<h3>
					My account
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

<script src="{$smarty.const.BASE_URL}/assets/js/my-account.js"></script>

{include file="../footer.tpl"}
