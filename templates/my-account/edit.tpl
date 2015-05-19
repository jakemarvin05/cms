{include file="../header.tpl" title='My account - Edit' authorized=true current='my-account'}

<section class="edit-view">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="action-box">
					<a href="{$smarty.const.BASE_URL}/admin/my-account/view" class="btn btn-primary pull-left">
						Cancel
					</a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="well">
					<form class="form-horizontal" id="edit-my-account" novalidate>
						<fieldset>
							<legend>Edit my account</legend>
							<div class="form-group">
								<label for="edit-my-account-login" class="col-lg-4 control-label">
									Login
								</label>
								<div class="col-lg-8">
									<input class="form-control" id="edit-my-account-login" name="login" placeholder="Login" type="text" required>
									<span class="error-message">Login is required</span>
								</div>
							</div>
							<div class="form-group">
								<label for="edit-my-account-email" class="col-lg-4 control-label">
									E-mail
								</label>
								<div class="col-lg-8">
									<input class="form-control" id="edit-my-account-email" name="email" placeholder="E-mail" type="email" required>
									<span class="error-message">Correct e-mail is required</span>
								</div>
							</div>
							<div class="form-group">
								<label for="edit-my-account-first-name" class="col-lg-4 control-label">
									First name
								</label>
								<div class="col-lg-8">
									<input class="form-control" id="edit-my-account-first-name" name="first-name" placeholder="First name" type="text" required>
									<span class="error-message">First name is required</span>
								</div>
							</div>
							<div class="form-group">
								<label for="edit-my-account-last-name" class="col-lg-4 control-label">
									Last name
								</label>
								<div class="col-lg-8">
									<input class="form-control" id="edit-my-account-last-name" name="last-name" placeholder="Last name" type="text" required>
									<span class="error-message">Last name is required</span>
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

<script src="{$smarty.const.BASE_URL}/assets/js/my-account.js"></script>

{include file="../footer.tpl"}
