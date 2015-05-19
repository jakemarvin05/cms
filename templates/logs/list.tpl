{include file="../header.tpl" title='Accounts - List' authorized=true current='accounts'}

<section class="list-view">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<nav class="navbar navbar-inverse">
					<div class="container-fluid">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#list-navbar-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
				
						<div class="collapse navbar-collapse" id="list-navbar-collapse">
							<form id="sort" class="navbar-form navbar-left">
								<div class="form-group">
									<select id="sort-field" name="sort-col" class="form-control">
										<option value="id">Sort by id</option>
										<option value="create_date">Sort by Create date</option>
										<option value="update_date">Log type</option>
										<option value="author">First 50 chars of message</option>
										<option value="author">User</option>
									</select>
								</div>
								<div class="form-group">
									<select id="sort-order" name="sort-order" class="form-control">
										<option value="asc">Ascending</option>
										<option value="desc">Descending</option>
									</select>
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-primary">OK</button>
								</div>
							</form>
							<form id="search" class="navbar-form navbar-right" role="search">
								<div class="form-group">
									<input class="form-control" placeholder="Search..." type="text" name="search-search">
								</div>
								<div class="form-group">
									<a href="{$smarty.const.BASE_URL}/admin/logs/add/" class="btn btn-primary" title="Add new account">
										<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
									</a>
								</div>
							</form>
						</div>
					</div>
				</nav>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<table class="table table-striped table-hover" id="list">
					<thead>
						<tr>
							<th>Id</th>
							<th>Create date</th>
							<th>Log type</th>
							<th>First 50 chars of message</th>
							<th>Author</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="6">
								No results to display
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row table-footer">
			<div class="col-lg-6">
				<form id="page" class="page">
					<div class="form-group">
						<label for="page-number" class="control-label">
							Per page
						</label>
						<select id="page-number" name="page-number" class="form-control input-sm">
							<option value="20">20</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="200">200</option>
						</select>
					</div>
				</form>
			</div>
			<div class="col-lg-6 text-right">
				<ul class="pagination pagination-sm" id="paginate">
					<li class="disabled"><a href="#" class="first">«</a></li>
					<li class="disabled"><a href="#" class="prev">‹</a></li>
					<li class="active"><a href="#" data-page="1">1</a></li>
					<li class="disabled"><a href="#" class="next">›</a></li>
					<li class="disabled"><a href="#" class="last">»</a></li>
				</ul>
			</div>
		</div>
	</div>
</section>

<script src="{$smarty.const.BASE_URL}/assets/js/logs.js"></script>

{include file="../footer.tpl"}
