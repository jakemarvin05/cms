$(document).ready(function() {
	// Edit
	if ($('.edit-view').length > 0) {
		var form = $('form#edit-my-account');
		
		var accountResponse;
		
		var getAccount = function() {
			return new Promise(function(resolve, reject) {
				loader.on();
				$.get(Config.BASE_URL + '/admin/l/myaccount', function() {
					loader.off();
				}).done(function(resp) {
					var response = JSON.parse(resp);
					
					if (response.success) {
						accountResponse = response;
						resolve();
					} else {
						alertHandler.addAlerts(form, AlertType.ERROR, response.errors, true);
						reject();
					}
				}).fail(function() {
					loader.off();
					alertHandler.removeAlerts(form);
					alertHandler.addAlert(form, AlertType.ERROR, CommonError.UNEXPECTED);
					reject();
				});
			});
		};
		
		var editAccount = function() {
			return new Promise(function(resolve, reject) {
				var data = {
					login: $('form#edit-my-account input[name=login]').val(),
					email: $('form#edit-my-account input[name=email]').val(),
					first_name: $('form#edit-my-account input[name=first-name]').val(),
					last_name: $('form#edit-my-account input[name=last-name]').val()
				};
				
				if (!formHandler.validateForm(form)) {
					reject();
					return;
				}
				
				loader.on();
				$.post(Config.BASE_URL + '/admin/l/myaccount/edit', data, function() {
					loader.off();
				}).done(function(resp) {
					var response = JSON.parse(resp);
					
					if (response.success) {
						resolve();
					} else {
						alertHandler.addAlerts(form, AlertType.ERROR, response.errors, true);
						reject();
					}
				}).fail(function() {
					loader.off();
					alertHandler.removeAlerts(form);
					alertHandler.addAlert(form, AlertType.ERROR, CommonError.UNEXPECTED);
					reject();
				});
			});
		};
		
		getAccount().then(function(response) {
			$('form#edit-my-account input[name=login]').val(accountResponse.login);
			$('form#edit-my-account input[name=email]').val(accountResponse.email);
			$('form#edit-my-account input[name=first-name]').val(accountResponse.first_name);
			$('form#edit-my-account input[name=last-name]').val(accountResponse.last_name);
		}, function(reject) {});
		
		$('form#edit-my-account').on('submit', function() {
			editAccount().then(function(response) {
				alertHandler.removeAlerts(form);
				alertHandler.addAlert(form, AlertType.SUCCESS, 'Your account has been edited');
				setTimeout(function() {
					window.location.replace(Config.BASE_URL + '/admin/my-account/view');	
				}, 1000);
			}, function(reject) {});
			
			return false;
		});
	}
	
	// Change
	if ($('.change-view').length > 0) {
		var form = $('form#change-password');
		
		var changePassword = function() {
			return new Promise(function(resolve, reject) {
				var data = {
					old_password: $('form#change-password input[name=old-password]').val(),
					new_password: $('form#change-password input[name=new-password]').val(),
					new_password_repeat: $('form#change-password input[name=new-password-repeat]').val()
				};
				
				if (!formHandler.validateForm(form)) {
					reject();
					return;
				} else {
					var matchData = {
						password: data.new_password,
						password_repeat: data.new_password_repeat
					};
					
					if (!formHandler.passwordMatch(matchData, form)) {
						reject();
						return;
					}
				}
				
				loader.on();
				$.post(Config.BASE_URL + '/admin/l/change-password', data, function() {
					loader.off();
				}).done(function(resp) {
					var response = JSON.parse(resp);
					
					if (response.success) {
						resolve();
					} else {
						alertHandler.addAlerts(form, AlertType.ERROR, response.errors, true);
						reject();
					}
				}).fail(function() {
					loader.off();
					alertHandler.removeAlerts(form);
					alertHandler.addAlert(form, AlertType.ERROR, CommonError.UNEXPECTED);
					reject();
				});
			});
		};
		
		$('form#change-password').on('submit', function() {
			changePassword().then(function(resolve) {
				alertHandler.removeAlerts(form);
				alertHandler.addAlert(form, AlertType.SUCCESS, 'Your password has been changed');
				setTimeout(function() {
					window.location.replace(Config.BASE_URL + '/admin/my-account/view');
				}, 1000);
			}, function(reject) {});
			
			return false;
		});
	}
	
	// View
	if ($('.view-view').length > 0) {
		var accountResponse;
		
		var getAccount = function() {
			return new Promise(function(resolve, reject) {
				loader.on();
				$.get(Config.BASE_URL + '/admin/l/myaccount', function() {
					loader.off();
				}).done(function(resp) {
					var response = JSON.parse(resp);
					
					if (response.success) {
						accountResponse = response;
						resolve();
					} else {
						alertHandler.addGlobalAlerts(AlertType.ERROR, response.errors, true);
						reject();
					}
				}).fail(function() {
					loader.off();
					alertHandler.removeGlobalAlerts();
					alertHandler.addGlobalAlert(AlertType.ERROR, CommonError.UNEXPECTED);
					reject();
				});
			});
		};
		
		getAccount().then(function(response) {
			var code = '';
			code += viewHelper.addRow('Login', accountResponse.login);
			code += viewHelper.addEmailRow(accountResponse.email);
			code += viewHelper.addRow('First name', accountResponse.first_name);
			code += viewHelper.addRow('Last name', accountResponse.last_name);
			code += viewHelper.addRow('Creation date', accountResponse.creation_date);
			code += viewHelper.addRow('Update date', accountResponse.update_date);
			$('table#data').html(code);
		}, function(reject) {});
	}
});
