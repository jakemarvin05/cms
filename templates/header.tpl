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
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{$smarty.const.BASE_URL}/admin/">{$smarty.const.PROJECT_NAME}</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a href="#">Groups</a>
					</li>
					<li>
						<a href="#">Images</a>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Associations <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#">Categories</a></li>
							<li><a href="#">Tags</a></li>
						</ul>
					</li>
					<li>
						<a href="#">Accounts</a>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Other <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#">Redirects</a></li>
							<li><a href="#">Configuration</a></li>
							<li class="divider"></li>
							<li><a href="#">Logs</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
							<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
							Username <span class="caret"></span>
						</a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#">My account</a></li>
							<li><a href="#">Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>
