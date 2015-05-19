{include file="../header.tpl" title='Login' authorized=false}

<section>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="well">
					<form class="form-horizontal" id="login" novalidate>
						<fieldset>
							<legend>Login</legend>
							<div class="form-group">
								<label for="login-login" class="col-lg-2 control-label">
									Login
								</label>
								<div class="col-lg-10">
									<input class="form-control" id="login-login" name="login" placeholder="Login" type="text" required>
									<span class="error-message">Login is required</span>
								</div>
							</div>
							<div class="form-group">
								<label for="login-password" class="col-lg-2 control-label">
									Password
								</label>
								<div class="col-lg-10">
									<input class="form-control" id="login-password" name="password" placeholder="Password" type="password" required>
									<span class="error-message">Password is required</span>
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-6 col-lg-offset-2">
									<p>
										<a href="/admin/reset/">I don't remember my password</a>
									</p>
								</div>
								<div class="col-lg-4">
									<button type="submit" class="btn btn-primary btn-block">Login</button>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

{include file="../footer.tpl"}
