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
			$.get(Config.BASE_URL + "/admin/l/images", data, function() {
				loader.off();
			}).done(function(resp) {
				var response = JSON.parse(resp);
				
				if (response.success) {
					that.list = response.images;
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
			$.get(Config.BASE_URL + "/admin/l/imagesCount", data, function() {
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
				code += listHelper.linkCell(this.list[i].name, '/admin/images/view/', this.list[i].id);
				code += listHelper.cell(this.list[i].path);
				code += listHelper.actionCell(this.list[i].id, true, '/admin/images/edit/');
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
				url: '/admin/l/image/' + data.id,
				type: 'DELETE'
			}).done(function(resp) {
				var response = JSON.parse(resp);
				
				if (response.success) {
					listHandler.count().then(function(response) {
						listHandler.fetch();
					}, function(reject) {
						return;
					});
					$('#delete-modal').modal('hide');
				} else {
					alertHandler.addGlobalAlerts(AlertType.ERROR, response.errors, true);
				}
			}).fail(function() {
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
		loader.on();
		
		$('form#new-image').on('submit', function(e) {
            e.preventDefault();
			var form = this;
            var data = new FormData(this);
			/*var data = {
				 name: $('form#new-image input[name=name]').val(),
				 file: $('form#new-image input[name=file]').val()
			};*/
            //console.log(this);
			if (!formHandler.validateForm(form)) {
				return false;
			}
			
			loader.on();
			/*$.post(Config.BASE_URL + '/admin/l/image/add', data,  function() {
				loader.off();
			})*/
            $.ajax({
                url: Config.BASE_URL + '/admin/l/image/add',
                type: "POST",
                data: data,
                processData: false,  // tell jQuery not to process the data
                contentType: false   // tell jQuery not to set contentType
            }).done(function(resp) {
				var response = JSON.parse(resp);
                console.log(response);
                if (response.success) {
                    alertHandler.removeAlerts(form);
                    alertHandler.addAlert(form, AlertType.SUCCESS, 'Image has been added');
                    setTimeout(function() {
                        window.location.replace(Config.BASE_URL + '/admin/images');
                    }, 1000);
                } else {
                    alertHandler.addAlerts(form, AlertType.ERROR, response.errors, true);
                }
            });
			return false;
		});
	}
	
	// Edit
	if ($('.edit-view').length > 0) {
		// TODO: get image
	}
	
	// View
	if ($('.view-view').length > 0) {
        var that = this;
        return new Promise(function(resolve, reject) {

            loader.on();
            $.get(Config.BASE_URL + "/admin/l/image/".response.id, function() {
                loader.off();
            }).done(function(resp) {
                var response = JSON.parse(resp);

                if (response.success) {
                    that.options.image = response.image;
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
	}
});
