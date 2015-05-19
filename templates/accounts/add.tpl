{include file="../header.tpl" title='Accounts - Add' authorized=true current='accounts'}

<section class="add-view">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="action-box">
					<a href="{$smarty.const.BASE_URL}/admin/accounts" class="btn btn-primary pull-left">
						Back to list
					</a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="well">
					<form class="form-horizontal" id="new-account" novalidate>
						<fieldset>
							<legend>Add new account</legend>
							<div class="form-group">
								<label for="new-account-login" class="col-lg-4 control-label">
									Login
								</label>
								<div class="col-lg-8">
									<input class="form-control" id="new-account-login" name="login" placeholder="Login" type="text" required>
									<span class="error-message">Login is required</span>
								</div>
							</div>
							<div class="form-group">
								<label for="new-account-email" class="col-lg-4 control-label">
									E-mail
								</label>
								<div class="col-lg-8">
									<input class="form-control" id="new-account-email" name="email" placeholder="E-mail" type="email" required>
									<span class="error-message">Correct e-mail is required</span>
								</div>
							</div>
							<div class="form-group">
								<label for="new-account-first-name" class="col-lg-4 control-label">
									First name
								</label>
								<div class="col-lg-8">
									<input class="form-control" id="new-account-first-name" name="first-name" placeholder="First name" type="text" required>
									<span class="error-message">First name is required</span>
								</div>
							</div>
							<div class="form-group">
								<label for="new-account-last-name" class="col-lg-4 control-label">
									Last name
								</label>
								<div class="col-lg-8">
									<input class="form-control" id="new-account-last-name" name="last-name" placeholder="Last name" type="text" required>
									<span class="error-message">Last name is required</span>
								</div>
							</div>
							<div class="form-group">
								<label for="new-account-password" class="col-lg-4 control-label">
									Password
								</label>
								<div class="col-lg-8">
									{literal}
									<input class="form-control" id="new-account-password" name="password" placeholder="Password" type="password" pattern=".{8,}" required>
									{/literal}
									<span class="error-message">Password is empty or too short (at least 8 characters)</span>
								</div>
							</div>
							<div class="form-group">
								<label for="new-account-password-repeat" class="col-lg-4 control-label">
									Repeat password
								</label>
								<div class="col-lg-8">
									<input class="form-control" id="new-account-password-repeat" name="password-repeat" placeholder="Repeat password" type="password" required>
									<span class="error-message">Repeat password</span>
								</div>
							</div>
							<div class="form-group">
								<label for="new-account-roles" class="col-lg-4 control-label">
									Roles
								</label>
								<div class="col-lg-8">
									<select class="form-control" id="new-account-roles" name="roles" multiple>
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

<script src="{$smarty.const.BASE_URL}/assets/js/accounts.js"></script>

{include file="../footer.tpl"}
