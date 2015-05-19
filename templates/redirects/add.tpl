{include file="../header.tpl" title='Accounts - Add' authorized=true current='accounts'}

<section class="add-view">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="well">
					<form class="form-horizontal" id="new-redirect" novalidate>
						<fieldset>
							<legend>Add new category</legend>
							<div class="form-group">
								<label for="new-redirect-name" class="col-lg-4 control-label">
									Name
								</label>
								<div class="col-lg-8">
									<input class="form-control" id="new-redirect-name" name="name" placeholder="Name" type="text" required>
									<span class="error-message">Name</span>
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

<script src="{$smarty.const.BASE_URL}/assets/js/redirects.js"></script>

{include file="../footer.tpl"}
