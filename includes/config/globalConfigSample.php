<?php

define('PROJECT_NAME', 'Less Living Live CMS');
define('BASE_URL', 'http://cms.triathlonresearch.dev');

define('ROOT_DIR', $_SERVER ['DOCUMENT_ROOT']);
define('SLIM', str_replace("\\", "/", ROOT_DIR . '/lib/Slim/Slim.php'));

define('INCLUDES_DIR', ROOT_DIR . '/includes/');
define('SMARTY_DIR', str_replace("\\", "/", ROOT_DIR . '/lib/Smarty/libs/'));

define('TEMPLATE_DIR', ROOT_DIR . '/templates/');
define('TEMPLATE_CO_DIR', ROOT_DIR . '/templates_c/compiled');
define('TEMPLATE_CA_DIR', ROOT_DIR . '/templates_c/cached');

define('STRIPE_API_KEY', 'sk_test_4aAoNATjOCJYwIcD3UmNX29s ');
