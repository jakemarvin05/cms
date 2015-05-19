{include file="../header.tpl" title='Accounts - Change password' authorized=true current='accounts'}

<section class="change-view">
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
					<form class="form-horizontal" id="change-password" novalidate>
						<fieldset>
							<legend>Change password for account no <strong>{$id}</strong></legend>
							<div class="form-group">
								<label for="change-password-password" class="col-lg-5 control-label">
									New password
								</label>
								<div class="col-lg-7">
									{literal}
									<input class="form-control" id="change-password-password" name="password" placeholder="New password" type="password" pattern=".{8,}" required>
									{/literal}
									<span class="error-message">New password is empty or too short (at least 8 characters)</span>
								</div>
							</div>
							<div class="form-group">
								<label for="change-password-password-repeat" class="col-lg-5 control-label">
									Repeat new password
								</label>
								<div class="col-lg-7">
									<input class="form-control" id="change-password-password-repeat" name="password-repeat" placeholder="Repeat new password" type="password" required>
									<span class="error-message">Repeat new password</span>
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-7 col-lg-offset-5">
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
