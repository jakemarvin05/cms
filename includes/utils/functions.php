<?php

function addLog($code, $account = false, $item = false) {
	$type = Model::factory('Log_type')->where('code', $code)->find_one();
	if ($type) {
		$log = Model::factory('Log')->create();
		$log->log_type_id = $type->id;
		$log->set_expr('creation_date', 'NOW()');
		$log->message = 'smth';
		if ($account) $log->account_id = $account;
		if ($item) $log->item_id = $item;
		$log->save();
		return true;
	}
	else return false;
}

function isAuthorised($login, $allowed) {
	$user = Model::factory('Account')->where('login', $login)->find_one();
	$roles = $user->roles()->find_many();
	if ($roles) {
		foreach ($roles as &$role) {
			$role_tmp = $role->role()->find_one();
			if ($role_tmp) {
				$all_roles[] = $role_tmp->name;
			}
		}
		if (is_array($allowed)) {
			foreach ($allowed as $allow)
				if (in_array($allow,$all_roles))
					return $user->id;
		}
		elseif (in_array($allowed, $all_roles))
			return $user->id;
		else return array('success' => false, 'errors' => ['message' => 'You are not authorised to access this page.']);
	}
	else return array('success' => true, 'errors' => ['message' => 'User doesn\'t have any roles']);
}

/**
 * @functionName
 * dismount
 * @description
 * A helper that will convert objects to array of strings if there is no getters set to the class
 * @params
 * $object - [objects], Array of objects that will be converted to array of strings
 **/
function dismount($object) {
	$reflectionClass = new ReflectionClass(get_class($object));
	$array = array();
	foreach ($reflectionClass->getProperties() as $property) {
		$property->setAccessible(true);
		$array[$property->getName()] = $property->getValue($object);
		$property->setAccessible(true);
	}
	return $array;
}

/**
 * @functionName
 * generateSessionId
 * @description
 * A function that will generate a SessionId in a hash from the user agent information, username and password
 * @params
 * $user, $password - [objects], Array of objects that will be converted to array of strings
 **/
function generateSessionId($user, $password) {
	$userAgent = $_SERVER["HTTP_USER_AGENT"];
	return hash("sha512", "{$user}{$password}{$userAgent}");
}

/**
 * @functionName
 * generateSalt
 * @description
 * A function that will generate a Salt in a hashing a random number
 **/
function generateSalt() {
	return hash("sha512", uniqid(mt_rand(1, mt_getrandmax()), true));
}

function generatePassword($pass, $salt) {
	$pass_hash = hash("sha512", $pass);
	return hash("sha512", $pass_hash.$salt);
}

/**
 * @functionName
 * validateUserKey
 * @description
 * A function that will validate the user from sessionID and username
 * @params
 * $sessionID, $username - [objects], Array of objects that will be converted to array of strings
 **/
function validateUserKey($sessionID, $username) {
	$user = Model::factory('Account')->where('login', $username)->find_one();

	if (empty($user)) {
		return false;
	} else {
		$app = \Slim\Slim::getInstance();
		if ($sessionID == generateSessionId($user->login, $user->password)) {
			$app->setEncryptedCookie('user', $username, '15 minutes');
			$app->setEncryptedCookie('sessionID', $sessionID, '15 minutes');
			return true;
		} else {
			$app->deleteCookie('user');
			$app->deleteCookie('sessionID');
			return false;
		}
	}
}

function isAuthenticated() {
	$app = \Slim\Slim::getInstance();
	$sessionID = $app->getEncryptedCookie('sessionID');
	$username = $app->getEncryptedCookie('user');
	return validateUserKey($sessionID, $username);
}

/**
 * @functionName
 * authentication
 * @description
 * A function that will validate the user from sessionID and username
 * @params
 * $route - [objects], Array of objects that will be converted to array of strings
 **/
function authenticate(\Slim\Route $route) {
	$app = \Slim\Slim::getInstance();
	if (!isAuthenticated()) {
		$app->response->redirect('/admin/login/', 303);
	}
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
