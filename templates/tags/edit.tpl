{include file="../header.tpl" title='Accounts - Edit' authorized=true current='accounts'}

<section class="edit-view">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="action-box">
					<a href="{$smarty.const.BASE_URL}/admin/tags/view/{$id}" class="btn btn-primary pull-left">
						Cancel
					</a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="well">
					<form class="form-horizontal" id="edit-tags" novalidate>
						<fieldset>
							<legend>Edit tag no <strong>{$id}</strong></legend>
							<div class="form-group">
								<label for="edit-tags-name" class="col-lg-4 control-label">
									Name
								</label>
								<div class="col-lg-8">
									<input class="form-control" id="edit-tags-name" name="name" placeholder="Name" type="text" required>
									<span class="error-message">Name is required</span>
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-8 col-lg-offset-4">
									<button type="submit" class="btn btn-primary">Submit</button>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
	var response = {
		id: {$id}
	}
</script>
<script src="{$smarty.const.BASE_URL}/assets/js/categories.js"></script>

{include file="../footer.tpl"}
