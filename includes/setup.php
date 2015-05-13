<?php

require 'includes/config/globalConfig.php';

// Slim
require SLIM;
Slim\Slim::registerAutoloader();
require 'lib/Views/Smarty.php';
 
// Paris and Idiorm
require 'lib/Paris/idiorm.php';
require 'lib/Paris/paris.php';

// Model
require 'models/Account.php';
require 'models/Log.php';
require 'models/Article.php';
require 'models/Contacts.php';
require 'helper/init.php';

// Stripe
require 'plugins/stripe/Stripe.php';

// Database and ORM
require 'includes/config/modelConfig.php';

// FIXME: we can make it better :)

// Invoking Slim
$app = new Slim\Slim(array(
    'view' => new Slim\Extras\Views\Smarty,
	'templates.path' => TEMPLATE_DIR,
	'cookies.encrypt' => true,
    'cookies.secret_key' => 'samprism123', // FIXME: generate SHA-512 code
    'cookies.cipher' => MCRYPT_RIJNDAEL_256,
    'cookies.cipher_mode' => MCRYPT_MODE_CBC
));

$view = $app->view();
$view->parserDirectory = SMARTY_DIR;
$view->parserCompileDirectory = TEMPLATE_CO_DIR;
$view->parserCacheDirectory = TEMPLATE_CA_DIR;
$view->parserExtensions = array(
	'Views/SmartyPlugins'
);


