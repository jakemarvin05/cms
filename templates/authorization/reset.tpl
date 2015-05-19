{include file="../header.tpl" title='Reset password' authorized=false}

<section>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="well">
					<form class="form-horizontal" id="reset" novalidate>
						<fieldset>
							<legend>Reset your password</legend>
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
								<div class="col-lg-4 col-lg-offset-8">
									<button type="submit" class="btn btn-primary btn-block">Reset</button>
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
