$(document).ready(function() {
	function ListHandler() {};
	
	ListHandler.prototype.list = [];
	
	ListHandler.prototype.options = {
		page: 1,
		limit: 20,
		offset: 0,
		col: 'id',
		desc: false,
		query: '',
		count: 0,
		pageCount: 0
	};
	
	ListHandler.prototype.fetch = function() {
		var that = this;
		return new Promise(function(resolve, reject) {
			if (that.options.count == 0) {
				that.list = [];
				resolve();
			}
			
			var data = {
				search: that.options.query,
				order: that.options.col,
				desc: that.options.desc,
				limit: that.options.limit,
				offset: that.options.offset
			};
			
			loader.on();
			$.get(Config.BASE_URL + "/admin/l/accounts", data, function() {
				loader.off();
			}).done(function(resp) {
				var response = JSON.parse(resp);
				
				if (response.success) {
					that.list = response.accounts;
					that.printPagination();
					that.printList();
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
	
	ListHandler.prototype.count = function() {
		var that = this;
		return new Promise(function(resolve, reject) {
			var data = {
				search: that.options.query
			};
			
			loader.on();
			$.get(Config.BASE_URL + "/admin/l/countAccounts", data, function() {
				loader.off();
			}).done(function(resp) {
				var response = JSON.parse(resp);
				
				if (response.success) {
					that.options.count = response.count;
					that.options.pageCount = Math.ceil(that.options.count / that.options.limit);
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
	
	ListHandler.prototype.printPagination = function() {
		var code = paginationHelper.generatePagination(this.options.page, this.options.pageCount)
		$('#paginate').html(code);
	};
	
	ListHandler.prototype.printList = function() {
		var code = '';
		
		if (this.list.length == 0) {
			code += listHelper.emptyRow(6);
		} else {
			for (var i = 0; i < this.list.length; ++i) {
				code += listHelper.startRow();
				code += listHelper.cell(this.list[i].id);
				code += listHelper.linkCell(this.list[i].login, '/admin/accounts/view/', this.list[i].id);
				code += listHelper.cell(this.list[i].email);
				code += listHelper.cell(this.list[i].first_name + ' ' + this.list[i].last_name);
				code += listHelper.cell(this.list[i].update_date);
				code += listHelper.actionCell(this.list[i].id, true, '/admin/accounts/edit/');
				code += listHelper.endRow();
			}
		}
		
		$('table#list tbody').html(code);
	};
	
	ListHandler.prototype.setOffset = function() {
		this.options.offset = (this.options.page - 1) * this.options.limit;
	};
	
	ListHandler.prototype.setLimit = function(limit) {
		this.options.limit = limit;
		this.options.pageCount = Math.ceil(this.options.count / this.options.limit);
		this.options.page = 1;
		this.setOffset();
		
		this.fetch();
	};
	
	ListHandler.prototype.nextPage = function() {
		if (this.options.page < this.options.pageCount) {
			++this.options.page;
			this.setOffset();
			
			this.fetch();
		}
	};
	
	ListHandler.prototype.prevPage = function() {
		if (this.options.page > 1) {
			--this.options.page;
			this.setOffset();
			
			this.fetch();
		}
	};
	
	ListHandler.prototype.toPage = function(page) {
		if (page >= 1 && page <= this.options.pageCount) {
			this.options.page = page;
			this.setOffset();
			
			this.fetch();
		}
	};
	
	ListHandler.prototype.toFirstPage = function() {
		this.options.page = 1;
		this.setOffset();
		
		this.fetch();
	};
	
	ListHandler.prototype.toLastPage = function() {
		this.options.page = this.options.pageCount;
		this.setOffset();
		
		this.fetch();
	};
	
	ListHandler.prototype.sort = function(col, desc) {
		this.options.col = col;
		this.options.desc = desc;
		
		this.fetch();
	};
	
	ListHandler.prototype.search = function(query) {
		this.options.query = query;
		this.options.page = 1;
		
		var that = this;
		this.count().then(function(response) {
			that.fetch();
		}, function(reject) {
			return;
		});
	};
	
	// List
	if ($('.list-view').length > 0) {
		var listHandler = new ListHandler();
		
		listHandler.count().then(function(response) {
			listHandler.fetch();
		}, function(reject) {
			return;
		});
		
		$('form#sort').on('submit', function() {
			var data = {
				col: $('form#sort select[name=sort-col]').val(),
				order: $('form#sort select[name=sort-order]').val() 
			};
			
			switch (data.order) {
			case SortOrder.ASC:
				listHandler.sort(data.col, false);
				break;
			case SortOrder.DESC:
				listHandler.sort(data.col, true);
				break;
			}
			
			return false;
		});
		
		$('form#search').on('submit', function() {
			var data = {
				query: $('form#search input[name=search-search]').val(),
			};
			
			listHandler.search(data.query);
			
			return false;
		});
		
		$('form#page select[name=page-number]').on('change', function() {
			var data = {
				limit: $('form#page select[name=page-number]').val(),
			};
			
			listHandler.setLimit(data.limit);
			
			return false;
		});
		
		$('body').on('click', '#paginate a', function() {
			var a = this;
			
			if ($(a).hasClass('first')) {
				listHandler.toFirstPage();
			} else if ($(a).hasClass('prev')) {
				listHandler.prevPage();
			} else if ($(a).hasClass('next')) {
				listHandler.nextPage();
			} else if ($(a).hasClass('last')) {
				listHandler.toLastPage();
			} else {
				var data = {
					page: parseInt($(a).data('page'))
				};
				listHandler.toPage(data.page);
			}
			
			return false;
		});
		
		$('body').on('click', 'table#list tbody a.delete', function() {
			$('form#delete input[name=id]').val($(this).data('id'));
			$('#delete-modal .replace').html($(this).data('id'));
		});
		
		$('form#delete').on('submit', function() {
			var data = {
				id: $('form#delete input[name=id]').val(),
			};
			
			loader.on();
			$.ajax({
				url: '/admin/l/account/' + data.id,
				type: 'DELETE'
			}).done(function(resp) {
				var response = JSON.parse(resp);
				
				if (response.success) {
					$('#delete-modal').modal('hide');
					listHandler.count().then(function(response) {
						listHandler.fetch();
					}, function(reject) {
						return;
					});
				} else {
					$('#delete-modal').modal('hide');
					alertHandler.addGlobalAlerts(AlertType.ERROR, response.errors, true);
				}
			}).fail(function() {
				$('#delete-modal').modal('hide');
				alertHandler.removeGlobalAlerts();
				alertHandler.addGlobalAlert(AlertType.ERROR, CommonError.UNEXPECTED);
			}).always(function() {
				loader.off();
			});
			
			return false;
		});
	}
	
	// Add
	if ($('.add-view').length > 0) {
		var form = $('form#new-account');
		var rolesResponse;
		
		var getRoles = function() {
			return new Promise(function(resolve, reject) {
				loader.on();
				$.get(Config.BASE_URL + '/admin/l/accounts/roles', function() {
					loader.off();
				}).done(function(resp) {
					var response = JSON.parse(resp);
					
					if (response.success) {
						rolesResponse = response.roles;
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
		
		var addAccount = function() {
			return new Promise(function(resolve, reject) {
				var data = {
					 login: $('form#new-account input[name=login]').val(),
					 email: $('form#new-account input[name=email]').val(),
					 first_name: $('form#new-account input[name=first-name]').val(),
					 last_name: $('form#new-account input[name=last-name]').val(),
					 last_name: $('form#new-account input[name=last-name]').val(),
					 password: $('form#new-account input[name=password]').val(),
					 password_repeat: $('form#new-account input[name=password-repeat]').val()
				};
				
				if (!formHandler.validateForm(form)) {
					reject();
					return;
				} else {
					if (!formHandler.passwordMatch(data, form)) {
						reject();
						return;
					}
				}
				
				loader.on();
				$.post(Config.BASE_URL + '/admin/l/accounts/add', data, function() {
					loader.off();
				}).done(function(resp) {
					var response = JSON.parse(resp);
					
					if (response.success) {
						resolve(response.id);
					} else {
						alertHandler.addAlerts(form, AlertType.ERROR, response.errors, true);
						reject();
					}
				}).fail(function() {
					loader.off();
					alertHandler.removeAlerts(form);
					alertHandler.addAlert(AlertType.ERROR, CommonError.UNEXPECTED);
					reject();
				});
			});
		};
		
		var addRoles = function(accountId) {
			return new Promise(function(resolve, reject) {
				var rolesData = {
					account_id: accountId,
					roles: $('form#new-account select[name=roles]').val() != null ? $('form#new-account select[name=roles]').val() : [],
				};
				
				loader.on();
				$.post(Config.BASE_URL + '/admin/l/accounts/addRoles', rolesData, function() {
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
					alertHandler.addAlert(AlertType.ERROR, CommonError.UNEXPECTED);
					reject();
				});
			});
		};
		
		getRoles().then(function(resolve) {
			var select = $('form#new-account select[name=roles]');
			for (var i = 0; i < rolesResponse.length; ++i) {
				$(select).append('<option value="' + rolesResponse[i].id + '">' + rolesResponse[i].name + '</option>');
			}
		}, function(reject) {});
		
		$('form#new-account').on('submit', function() {
			addAccount().then(function(resolve) {
				addRoles(resolve).then(function(resolve) {
					alertHandler.removeAlerts(form);
					alertHandler.addAlert(form, AlertType.SUCCESS, 'Account has been added');
					setTimeout(function() {
						window.location.replace(Config.BASE_URL + '/admin/accounts');	
					}, 1000);
				}, function(reject) {});
			}, function(reject) {});
			
			return false;
		});
	}
	
	// Edit
	if ($('.edit-view').length > 0) {
		var form = $('form#edit-account');
		
		var userResponse;
		var userRolesResponse;
		var rolesResponse;
		
		var getUser = function() {
			return new Promise(function(resolve, reject) {
				loader.on();
				$.get(Config.BASE_URL + '/admin/l/account/' + serverResponse.id, function() {
					loader.off();
				}).done(function(resp) {
					var response = JSON.parse(resp);
					
					if (response.success) {
						userResponse = response;
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
		
		var getUserRoles = function() {
			return new Promise(function(resolve, reject) {
				loader.on();
				$.get(Config.BASE_URL + '/admin/l/account/getRoles/' + serverResponse.id, function() {
					loader.off();
				}).done(function(resp) {
					var response = JSON.parse(resp);
					
					if (response.success) {
						userRolesResponse = response.roles;
						resolve();
					} else {
						alertHandler.addAlerts(form, AlertType.ERROR, response.errors, true);
						reject();
					}
				}).fail(function() {
					loader.off();
					alertHandler.removAlerts();
					alertHandler.addAlert(form, AlertType.ERROR, CommonError.UNEXPECTED);
					reject();
				});
			});
		};
		
		var getRoles = function() {
			return new Promise(function(resolve, reject) {
				loader.on();
				
				$.get(Config.BASE_URL + '/admin/l/accounts/roles', function() {
					loader.off();
				}).done(function(resp) {
					var response = JSON.parse(resp);
					
					if (response.success) {
						rolesResponse = response.roles;
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
		
		var editUser = function() {
			return new Promise(function(resolve, reject) {
				loader.on();
				
				var data = {
					login: $('form#edit-account input[name=login]').val(),
					email: $('form#edit-account input[name=email]').val(),
					first_name: $('form#edit-account input[name=first-name]').val(),
					last_name: $('form#edit-account input[name=last-name]').val()
				};
				
				$.post(Config.BASE_URL + '/admin/l/accounts/edit/' + serverResponse.id, data, function() {
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
		
		var editRoles = function() {
			return new Promise(function(resolve, reject) {
				loader.on();
				
				var data = {
					account_id: serverResponse.id,
					roles: $('form#edit-account select[name=roles]').val() != null ? $('form#edit-account select[name=roles]').val() : []
				};
				
				$.post(Config.BASE_URL + '/admin/l/accounts/editRoles', data, function() {
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
		
		getUser().then(function(response) {
			getRoles().then(function(response) {
				getUserRoles().then(function(response) {
					$('form#edit-account input[name=login]').val(userResponse.login);
					$('form#edit-account input[name=email]').val(userResponse.email);
					$('form#edit-account input[name=first-name]').val(userResponse.first_name);
					$('form#edit-account input[name=last-name]').val(userResponse.last_name);
					
					var code = '';
					for (var i = 0; i < rolesResponse.length; ++i) {
						var selected = false;
						for (var j = 0; j < userRolesResponse.length; ++j) {
							if (rolesResponse[i].name == userRolesResponse[j]) {
								selected = true;
								break;
							}
						}
						code += '<option value="' + rolesResponse[i].id + '" ' + (selected ? 'selected' : '') + '>' + rolesResponse[i].name + '</option>';
					}
					
					$('form#edit-account select[name=roles]').append(code);
				}, function(reject) {});
			}, function(reject) {});
		}, function(reject) {});
		
		$('form#edit-account').on('submit', function() {
			if (!formHandler.validateForm(form)) {
				return false;
			}
			
			editUser().then(function(response) {
				editRoles().then(function(response) {
					alertHandler.removeAlerts(form);
					alertHandler.addAlert(form, AlertType.SUCCESS, 'Account has been edited');
					setTimeout(function() {
						window.location.replace(Config.BASE_URL + '/admin/accounts/view/' + serverResponse.id);	
					}, 1000);
				}, function(reject) {});
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
					password: $('form#change-password input[name=password]').val(),
					password_repeat: $('form#change-password input[name=password-repeat]').val()
				};

				if (!formHandler.validateForm(form)) {
					reject();
					return;
				} else {
					if (!formHandler.passwordMatch(data, form)) {
						reject();
						return;
					}
				}
				
				loader.on();
				$.post(Config.BASE_URL + '/admin/l/accounts/changePassword/' + serverResponse.id, data, function() {
					loader.off();
				}).done(function(resp) {
					var response = JSON.parse(resp);
					
					if (response.success) {
						userResponse = response;
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
				alertHandler.addAlert(form, AlertType.SUCCESS, 'Password has been changed');
				setTimeout(function() {
					window.location.replace(Config.BASE_URL + '/admin/accounts/view/' + serverResponse.id);	
				}, 1000);
			}, function(reject) {});
			
			return false;
		});
	}
	
	// View
	if ($('.view-view').length > 0) {
		var userResponse;
		var userRolesResponse;
		
		var getUser = function() {
			return new Promise(function(resolve, reject) {
				loader.on();
				$.get(Config.BASE_URL + '/admin/l/account/' + serverResponse.id, function() {
					loader.off();
				}).done(function(resp) {
					var response = JSON.parse(resp);
					
					if (response.success) {
						userResponse = response;
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
		
		var getUserRoles = function() {
			return new Promise(function(resolve, reject) {
				loader.on();
				$.get(Config.BASE_URL + '/admin/l/account/getRoles/' + serverResponse.id, function() {
					loader.off();
				}).done(function(resp) {
					var response = JSON.parse(resp);
					
					if (response.success) {
						userRolesResponse = response;
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
		
		getUser().then(function(response) {
			getUserRoles().then(function(response) {
				var code = '';
				code += viewHelper.addRow('Login', userResponse.login);
				code += viewHelper.addEmailRow(userResponse.email);
				code += viewHelper.addRow('First name', userResponse.first_name);
				code += viewHelper.addRow('Last name', userResponse.last_name);
				code += viewHelper.addRow('Creation date', userResponse.creation_date);
				code += viewHelper.addRow('Update date', userResponse.update_date);
				code += viewHelper.addMultiRow('Roles', userRolesResponse.roles, 'No roles to display');
				$('table#data').html(code);
			}, function(reject) {});
		}, function(reject) {});
		
		$('form#delete').on('submit', function() {
			loader.on();
			$.ajax({
				url: '/admin/l/account/' + serverResponse.id,
				type: 'DELETE'
			}).done(function(resp) {
				var response = JSON.parse(resp);
				
				if (response.success) {
					$('#delete-modal').modal('hide');
					alertHandler.removeGlobalAlerts();
					alertHandler.addGlobalAlert(AlertType.SUCCESS, 'Account has been deleted');
					setTimeout(function() {
						window.location.replace(Config.BASE_URL + '/admin/accounts');	
					}, 2000);
				} else {
					$('#delete-modal').modal('hide');
					alertHandler.addGlobalAlerts(AlertType.ERROR, response.errors, true);
				}
			}).fail(function() {
				$('#delete-modal').modal('hide');
				alertHandler.removeGlobalAlerts();
				alertHandler.addGlobalAlert(AlertType.ERROR, CommonError.UNEXPECTED);
			}).always(function() {
				loader.off();
			});
			
			return false;
		});
	}
});
