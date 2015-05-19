	// Handlers

function Loader() {};

Loader.prototype.on = function() {
	$('#progress').addClass('visible');
};

Loader.prototype.off = function() {
	$('#progress').removeClass('visible');
};

var loader = new Loader();

function FormHandler() {};

FormHandler.prototype.validateForm = function(form) {
	var res = true;
	$('input, select, textarea', form).each(function() {
		if (this.checkValidity()) {
			$(this).closest('.form-group').removeClass('has-error');
		} else {
			$(this).closest('.form-group').addClass('has-error');
			res = false;
		}
	});
	
	return res;
};

FormHandler.prototype.passwordMatch = function(data, form) {
	if (data.password != data.password_repeat) {
		alertHandler.removeAlerts(form);
		alertHandler.addAlert(form, AlertType.ERROR, CommonMessage.PASSWORD_NOT_MATCHING);
		return false;
	} else {
		return true;
	}
};

var formHandler = new FormHandler();

var AlertType = {
	ERROR: 'alert-danger',
	SUCCESS: 'alert-success',
	INFO: 'alert-info',
	WARNING: 'alert-warning'
};

function AlertHandler() {}; 

AlertHandler.prototype.removeAlerts = function(el) {
	$('.alert', el).remove();
};

AlertHandler.prototype.removeGlobalAlerts = function() {
	$('.alert', $('#global-alert')).remove();
};

AlertHandler.prototype.createAlertCode = function(type, alert) {
	var code = '<div class="alert alert-dismissible ' + type + '">';
	code += '<button type="button" class="close" data-dismiss="alert">×</button>';
	code += alert;
	code += '</div>';
	
	return code;
};

AlertHandler.prototype.addAlert = function(form, type, alert) {
	var code = this.createAlertCode(type, alert);
	$(form).prepend(code);
};

AlertHandler.prototype.addAlerts = function(form, type, alerts, removeCurrent) {
	if (removeCurrent) {
		this.removeAlerts(form);
	}
	
	for (var alert in alerts) {
		if (alerts.hasOwnProperty(alert)) {
			this.addAlert(form, type, alerts[alert]);
		}
	}
};

AlertHandler.prototype.addGlobalAlert = function(type, alert) {
	var code = this.createAlertCode(type, alert);
	$('#global-alert').prepend(code);
};

AlertHandler.prototype.addGlobalAlerts = function(type, alerts, removeCurrent) {
	if (removeCurrent) {
		this.removeAlerts($('#global-alert'));
	}
	
	for (var alert in alerts) {
		if (alerts.hasOwnProperty(alert)) {
			this.addGlobalAlert(type, alerts[alert]);
		}
	}
};

var alertHandler = new AlertHandler();

// Enums

var SortOrder = {
	ASC: 'asc',
	DESC: 'desc'
};

var CommonError = {
	UNEXPECTED: 'Unexpected error occured'
};

var CommonMessage = {
	NO_RESULTS: 'No results to display',
	PASSWORD_NOT_MATCHING: 'Passwords don\'t match'
};

// Helpers

function ViewHelper() {};

ViewHelper.prototype.addRow = function(label, value) {
	var code = '<tr>';
	code += '<td>' + label + '</td>';
	code += '<td><strong>' + value + '</strong></td>';
	code += '</tr>';
	return code;
};

ViewHelper.prototype.addMultiRow = function(label, array, emptyMessage) {
	var code = '<tr>';
	code += '<td>' + label + '</td>';
	code += '<td>';
	
	if (array.length == 0) {
		code += '<strong>' + emptyMessage + '</strong>';
	} else {
		for (var i = 0; i < array.length; ++i) {
			code += '<strong>' + array[i] + '</strong><br>';
		}
	}

	code += '</td>';
	code += '</tr>';
	return code;
};

ViewHelper.prototype.addEmailRow = function(email) {
	var code = '<tr>';
	code += '<td>E-mail</td>';
	code += '<td><strong><a href="mailto:' + email + '">' + email + '</a></strong></td>';
	code += '</tr>';
	return code;
};

var viewHelper = new ViewHelper();

function ListHelper() {};

ListHelper.prototype.startRow = function() {
	return '<tr>';
};

ListHelper.prototype.endRow = function() {
	return '</tr>';
};

ListHelper.prototype.emptyRow = function(colspan) {
	return '<tr><td colspan="' + colspan + '">' + CommonMessage.NO_RESULTS + '</td></tr>';
};

ListHelper.prototype.cell = function(value) {
	return '<td>' + value + '</td>';
};

ListHelper.prototype.linkCell = function(value, uri, id) {
	return '<td><a href="' + Config.BASE_URL + uri + id + '">' + value + '</a></td>';
};

ListHelper.prototype.actionCell = function(id, del, editUri) {
	var code = '';
	code += '<td class="actions">';
	
	if (del) {
		code += '<a href="#" class="btn btn-default btn-sm delete" title="Delete" data-toggle="modal" data-target="#delete-modal" data-id="' + id + '">';
		code += '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
		code += '</a>';
	}
	
	if (editUri != null) {
		code += '<a href="' + Config.BASE_URL + editUri + id + '" class="btn btn-primary btn-sm" title="Edit">';
		code += '<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>';
		code += '</a>';
	}
	
	code += '</td>';
	return code;
};

var listHelper = new ListHelper();

function PaginationHelper() {};

PaginationHelper.prototype.generateLimits = function(page, pageCount) {
	var from, to;
	
	if (page - Config.PAGINATION_PAGES <= 0) {
		from = 1;
	} else {
		from = page - Config.PAGINATION_PAGES;
	}
	
	if (page + Config.PAGINATION_PAGES > pageCount) {
		to = pageCount;
	} else {
		to = page + Config.PAGINATION_PAGES;
	}
	
	return {
		from: from,
		to: to
	}
};

PaginationHelper.prototype.generatePagination = function(page, pageCount) {
	var limits = this.generateLimits(page, pageCount);
	
	var code = '';
	code += '<ul class="pagination pagination-sm">';
	code += '<li class="' + (page == 1 ? 'disabled' : '') + '"><a href="#" class="first">«</a></li>';
	code += '<li class="' + (page == 1 ? 'disabled' : '') + '"><a href="#" class="prev">‹</a></li>';
	
	for (var i = limits.from; i <= limits.to; ++i) {
		code += '<li class="' + (page == i ? 'active' : '') + '"><a href="#" data-page="' + i + '">' + i + '</a></li>';
	}
	
	code += '<li class="' + (page == pageCount ? 'disabled' : '') + '"><a href="#" class="next">›</a></li>';
	code += '<li class="' + (page == pageCount ? 'disabled' : '') + '"><a href="#" class="last">»</a></li>';
	code += '</ul>';
	
	return code;
};

var paginationHelper = new PaginationHelper();
