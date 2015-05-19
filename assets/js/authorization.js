$(document).ready(function() {
	$('form#login').submit(function() {
		var form = this;
		
		var data = {
			login: $('form#login input[name=login]').val(),
			password: $('form#login input[name=password]').val() 
		};
		
		if (!formHandler.validateForm(this)) {
			return false;
		}
		
		loader.on();
		$.post(Config.BASE_URL + "/admin/l/authorize", data, function() {
			loader.off();
		}).done(function(resp) {
			var response = JSON.parse(resp);
			
			if (response.success) {
				window.location.replace(Config.BASE_URL + "/admin/accounts");
			} else {
				alertHandler.addAlerts(form, AlertType.ERROR, response.errors, true);
			}
		}).fail(function() {
			loader.off();
			alertHandler.removeAlerts(form);
			alertHandler.addAlert(form, AlertType.ERROR, CommonError.UNEXPECTED);
		});
		
		return false;
	});
	
	$('form#reset').submit(function() {
		var form = this;
		
		var data = {
			login: $('form#reset input[name=login]').val(),
		};
		
		if (!formHandler.validateForm(this)) {
			return false;
		}
		
		loader.on();
		$.post(Config.BASE_URL + "/admin/l/reset-password", data, function() {
			loader.off();
		}).done(function(resp) {
			var response = JSON.parse(resp);
			
			if (response.success) {
				window.location.replace(Config.BASE_URL + "/admin/login");
			} else {
				alertHandler.addAlerts(form, AlertType.ERROR, response.errors, true);
			}
		}).fail(function() {
			loader.off();
			alertHandler.removeAlerts(form);
			alertHandler.addAlert(form, AlertType.ERROR, CommonError.UNEXPECTED);
		});
		
		return false;
	});
	
	$('a#logout').click(function() {
		loader.on();
		
		$.post(Config.BASE_URL + "/admin/l/logout", function() {
			loader.off();
		}).done(function(resp) {
			var response = JSON.parse(resp);
			
			if (response.success) {
				window.location.replace(Config.BASE_URL + "/admin/login");
			} else {
				alertHandler.addGlobalAlerts(AlertType.ERROR, response.errors);
			}
		}).fail(function() {
			loader.off();
			alertHandler.addGlobalAlert(AlertType.ERROR, CommonError.UNEXPECTED);
		});
		
		return false;
	});
});
