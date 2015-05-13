{include file="../header.tpl" title='Login'}

<section>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="well">
					<form class="form-horizontal" id="login">
						<fieldset>
							<legend>Login</legend>
							<div class="form-group">
								<label for="login-login" class="col-lg-2 control-label">
									Login
								</label>
								<div class="col-lg-10">
									<input class="form-control" id="login-login" name="login-login" placeholder="Login" type="text">
								</div>
							</div>
							<div class="form-group">
								<label for="login-password" class="col-lg-2 control-label">
									Password
								</label>
								<div class="col-lg-10">
									<input class="form-control" id="login-password" name="login-password" placeholder="Password" type="password">
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-6 col-lg-offset-2">
									<p>
										<a href="#">I don't remember my password.</a>
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
