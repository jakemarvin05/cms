<?php


// admin page
$app->get('/admin/','authenticate', function() use ($app) {
	
	// die($_SERVER["HTTP_USER_AGENT"]);
	   return $app->render('admin.tpl');

});


//Login Page
$app->get('/login/', function() use ($app) {

 return $app->render('authorization/login.tpl');
});

//Displays PHP information
$app->get('/phpinfo/', function() use ($app) {
$info = phpinfo();
});


