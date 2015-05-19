<!doctype html>
<html>
<head>
	<title>{$smarty.const.PROJECT_NAME} - {$title}</title>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Prism Ninjas">
	
	<link rel="icon" href="{$smarty.const.BASE_URL}/assets/images/favicon.png">
	
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.4/united/bootstrap.min.css">
	<link rel="stylesheet" href="{$smarty.const.BASE_URL}/assets/css/main.css">
	
	<script>
		var Config = {
			BASE_URL: '{$smarty.const.BASE_URL}',
			PAGINATION_PAGES: 3
		};
	</script>
	
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<script src="{$smarty.const.BASE_URL}/assets/js/main.js"></script>
	<script src="{$smarty.const.BASE_URL}/assets/js/authorization.js"></script>
	
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
</head>

<body>
	<!-- TODO: sections, displaying elements according to auth or user roles, selecting current page -->
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				{if $authorized}
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				{/if}
				<a class="navbar-brand" href="{$smarty.const.BASE_URL}/admin/">{$smarty.const.PROJECT_NAME}</a>
			</div>
			{if $authorized}
			<div class="navbar-collapse collapse" id="navbar-collapse">
				<ul class="nav navbar-nav navbar-right">
					<li class="{if $current == 'groups'}active{/if}">
						<a href="{$smarty.const.BASE_URL}/admin/groups/list/">Groups</a>
					</li>
					<li class="{if $current == 'templates'}active{/if}">
						<a href="{$smarty.const.BASE_URL}/admin/templates/list/">Templates</a>
					</li>
					<li class="{if $current == 'images'}active{/if}">
						<a href="{$smarty.const.BASE_URL}/admin/images/list/">Images</a>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Associations <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li class="{if $current == 'categories'}active{/if}">
								<a href="{$smarty.const.BASE_URL}/admin/categories/list/">Categories</a>
							</li>
							<li class="{if $current == 'tags'}active{/if}">
								<a href="{$smarty.const.BASE_URL}/admin/tags/list/">Tags</a>
							</li>
						</ul>
					</li>
					<li class="{if $current == 'accounts'}active{/if}">
						<a href="{$smarty.const.BASE_URL}/admin/accounts/list/">Accounts</a>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Other <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li class="{if $current == 'redirects'}active{/if}">
								<a href="{$smarty.const.BASE_URL}/admin/redirects/list/">Redirects</a>
							</li>
							<li class="{if $current == 'configuration'}active{/if}">
								<a href="{$smarty.const.BASE_URL}/admin/configuration/list/">Configuration</a>
							</li>
							<li class="divider"></li>
							<li class="{if $current == 'logs'}active{/if}">
								<a href="{$smarty.const.BASE_URL}/admin/logs/list/">Logs</a>
							</li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
							<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
							Username <span class="caret"></span>
						</a>
						<ul class="dropdown-menu" role="menu">
							<li class="{if $current == 'my-account'}active{/if}">
								<a href="{$smarty.const.BASE_URL}/admin/my-account/">My account</a>
							</li>
							<li>
								<a href="#" id="logout">Logout</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
			{/if}
		</div>
		<div id="progress" class="progress progress-striped active">
			<div class="progress-bar"></div>
		</div>
	</nav>

	<section class="global-alert">
		<div class="container">
			<div class="row">
				<div class="col-lg-12" id="global-alert">
				</div>
			</div>
		</div>
	</section>
	