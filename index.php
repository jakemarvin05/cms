<?php

require 'includes/setup.php';
$app->group('/admin', function () use ($app) {
	require 'includes/views.php';
	// logic group
	$app->group('/l', function () use ($app) {
		require 'includes/authorization.php';
		require 'includes/account.php';
		require 'includes/groups.php';
		require 'includes/pages.php';
		require 'includes/templates.php';
		require 'includes/tags.php';
		require 'includes/categories.php';
		require 'includes/redirects.php';
		require 'includes/config.php';
		require 'includes/images.php';
	});
});

$app->run();
