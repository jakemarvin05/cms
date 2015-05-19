<?php
/**
 * Created by PhpStorm.
 * User: dusia
 * Date: 15.05.15
 * Time: 15:01
 */

// login

// FIXME: redirection based on user role
$app->get('/', function() use ($app) {
	if (isAuthenticated()) {
		$app->redirect('/admin/accounts/');
	} else {
		return $app->render('authorization/login.tpl');
	}
});

// FIXME: redirection based on user role
$app->get('/login/', function() use ($app) {
	if (isAuthenticated()) {
		$app->redirect('/admin/accounts/');
	} else {
		return $app->render('authorization/login.tpl');
	}
});

// FIXME: redirection based on user role
$app->get('/reset/', function() use ($app) {
	if (isAuthenticated()) {
		$app->redirect('/admin/accounts/');
	} else {
		return $app->render('authorization/reset.tpl');
	}
});

//404
//groups
$app->get('/404/', 'authenticate', function() use ($app) {
	return $app->render('404.tpl');
});

//accounts
$app->group('/accounts', function () use ($app) {
	$app->get('/', 'authenticate', function() use ($app) {
		return $app->render('accounts/list.tpl');
	});

	$app->get('/list/', 'authenticate', function() use ($app) {
		return $app->render('accounts/list.tpl');
	});

	$app->get('/view/(:id)', 'authenticate', function($id) use ($app) {
		return $app->render('accounts/view.tpl', ['id' => $id]);
	});

	$app->get('/add/', 'authenticate', function() use ($app) {
		return $app->render('accounts/add.tpl');
	});

	$app->get('/edit/(:id)', 'authenticate', function($id) use ($app) {
		return $app->render('accounts/edit.tpl', ['id' => $id]);
	});

	$app->get('/change/(:id)', 'authenticate', function($id) use ($app) {
		return $app->render('accounts/change.tpl', ['id' => $id]);
	});
});

$app->group('/my-account', function () use ($app) {
	$app->get('/', 'authenticate', function() use ($app) {
		return $app->render('my-account/view.tpl');
	});
	
	$app->get('/view/', 'authenticate', function() use ($app) {
		return $app->render('my-account/view.tpl');
	});
	
	$app->get('/edit/', 'authenticate', function() use ($app) {
		return $app->render('my-account/edit.tpl');
	});
	
	$app->get('/change/', 'authenticate', function() use ($app) {
		return $app->render('my-account/change.tpl');
	});
});

//categories

$app->get('/categories/', 'authenticate', function() use ($app) {
	return $app->render('categories/list.tpl');
});

$app->get('/categories/list/', 'authenticate', function() use ($app) {
	return $app->render('categories/list.tpl');
});

$app->get('/categories/view/(:id)', 'authenticate', function($id) use ($app) {
	return $app->render('categories/view.tpl', ['id' => $id]);
});

$app->get('/categories/add/', 'authenticate', function() use ($app) {
	return $app->render('categories/add.tpl');
});

$app->get('/categories/edit/(:id)', 'authenticate', function($id) use ($app) {
	return $app->render('categories/edit.tpl', ['id' => $id]);
});

//tags

$app->get('/tags/', 'authenticate', function() use ($app) {
	return $app->render('tags/list.tpl');
});

$app->get('/tags/list/', 'authenticate', function() use ($app) {
	return $app->render('tags/list.tpl');
});

$app->get('/tags/view/(:id)', 'authenticate', function($id) use ($app) {
	return $app->render('tags/view.tpl', ['id' => $id]);
});

$app->get('/tags/add/', 'authenticate', function() use ($app) {
	return $app->render('tags/add.tpl');
});

$app->get('/tags/edit/(:id)', 'authenticate', function($id) use ($app) {
	return $app->render('tags/edit.tpl', ['id' => $id]);
});

//redirects

$app->get('/redirects/list/', 'authenticate', function() use ($app) {
	return $app->render('redirects/list.tpl');
});

//groups
$app->get('/groups/', 'authenticate', function() use ($app) {
	return $app->render('groups/list.tpl');
});

//configuration
$app->get('/configuration/', 'authenticate', function() use ($app) {
	return $app->render('configuration/configuration.tpl');
});

$app->get('/configuration/list/', 'authenticate', function() use ($app) {
	return $app->render('configuration/list.tpl');
});

//categories

$app->get('/logs/', 'authenticate', function() use ($app) {
	return $app->render('logs/list.tpl');
});

$app->get('/logs/list/', 'authenticate', function() use ($app) {
	return $app->render('logs/list.tpl');
});

$app->get('/logs/view/(:id)', 'authenticate', function($id) use ($app) {
	return $app->render('logs/view.tpl', ['id' => $id]);
});

//accounts
$app->group('/images', function () use ($app) {
	$app->get('/', 'authenticate', function() use ($app) {
		return $app->render('images/list.tpl');
	});

	$app->get('/list/', 'authenticate', function() use ($app) {
		return $app->render('images/list.tpl');
	});

	$app->get('/view/(:id)', 'authenticate', function($id) use ($app) {
		return $app->render('images/view.tpl', ['id' => $id]);
	});

	$app->get('/add/', 'authenticate', function() use ($app) {
		return $app->render('images/add.tpl');
	});

	$app->get('/edit/(:id)', 'authenticate', function($id) use ($app) {
		return $app->render('images/edit.tpl', ['id' => $id]);
	});

	$app->get('/change/(:id)', 'authenticate', function($id) use ($app) {
		return $app->render('images/change.tpl', ['id' => $id]);
	});
});
