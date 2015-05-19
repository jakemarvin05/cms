{include file="../header.tpl" title='Accounts - Edit' authorized=true current='accounts'}

<section class="edit-view">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="action-box">
					<a href="{$smarty.const.BASE_URL}/admin/accounts/view/{$id}" class="btn btn-primary pull-left">
						Cancel
					</a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="well">
					<form class="form-horizontal" id="edit-account" novalidate>
						<fieldset>
							<legend>Edit account no <strong>{$id}</strong></legend>
							<div class="form-group">
								<label for="edit-account-login" class="col-lg-4 control-label">
									Login
								</label>
								<div class="col-lg-8">
									<input class="form-control" id="edit-account-login" name="login" placeholder="Login" type="text" required>
									<span class="error-message">Login is required</span>
								</div>
							</div>
							<div class="form-group">
								<label for="edit-account-email" class="col-lg-4 control-label">
									E-mail
								</label>
								<div class="col-lg-8">
									<input class="form-control" id="edit-account-email" name="email" placeholder="E-mail" type="email" required>
									<span class="error-message">Correct e-mail is required</span>
								</div>
							</div>
							<div class="form-group">
								<label for="edit-account-first-name" class="col-lg-4 control-label">
									First name
								</label>
								<div class="col-lg-8">
									<input class="form-control" id="edit-account-first-name" name="first-name" placeholder="First name" type="text" required>
									<span class="error-message">First name is required</span>
								</div>
							</div>
							<div class="form-group">
								<label for="edit-account-last-name" class="col-lg-4 control-label">
									Last name
								</label>
								<div class="col-lg-8">
									<input class="form-control" id="edit-account-last-name" name="last-name" placeholder="Last name" type="text" required>
									<span class="error-message">Last name is required</span>
								</div>
							</div>
							<div class="form-group">
								<label for="edit-account-roles" class="col-lg-4 control-label">
									Roles
								</label>
								<div class="col-lg-8">
									<select class="form-control" id="edit-account-roles" name="roles" multiple>
									</select>
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
	var serverResponse = {
		id: {$id}
	}
</script>
<script src="{$smarty.const.BASE_URL}/assets/js/accounts.js"></script>

{include file="../footer.tpl"}
