<?php

// TODO: check if user is authorized, if so redirect to groups page
$app->get('/admin/', function() use ($app) {
	//return $app->render('authorization/login.tpl');
	$app->response->redirect('/admin/groups', 300);
});

$app->get('/admin/login/', function() use ($app) {
	//return $app->render('authorization/login.tpl');
	$app->response->redirect('/admin/groups', 300);
});



//Authenticate user
$app->post('/admin/authorize/', function () use ($app) {

	// array to hold validation errors
	$errors = array();

	// validate the variables
	if (empty($_POST['user']) || $_POST['user'] === '') $errors['user'] = 'Username is required.';
	if (empty($_POST['password']) || $_POST['password'] === '') $errors['password'] = 'Password is required.';

	// response if there are errors
	if (empty($errors)) {

		try {
			$user = Model::factory('Account')->where('login', $_POST['user']
			)->find_one();

			if (empty($user)) {
				$errors['message'] = 'Invalid User';
				$data['success'] = false;
				$data['errors'] = $errors;
				echo json_encode($data);
			} else {
				if ($user->password != generatePass($_POST['password'],$user->salt)) {
					$errors['message'] = 'Invalid Password';
					$data['success'] = false;
					$data['errors'] = $errors;
					echo json_encode($data);
				} else {
					$app->setEncryptedCookie('user', $_POST['user']);
					$app->setEncryptedCookie('sessionID', generateSessionId($_POST['user'], hash("sha512", $_POST['password'])));

					$data['success'] = true;
					echo json_encode($data);
				}
			}
		} catch (PDOException $e) {
			$errors['message'] = ' up all the Fields';
			$data['success'] = false;
			$data['errors'] = $errors;
			echo json_encode($data);
		}
	} else {

		$data['success'] = false;
		$data['errors'] = $errors;
		echo json_encode($data);
	}
});


//Logout User
$app->post('/admin/logout/', function () use ($app) {
	try {

		$app->deleteCookie('user');
		$app->deleteCookie('sessionID');

		$data['success'] = true;
		echo json_encode($data);
	} catch (PDOException $e) {
		$errors['message'] = 'Unexpected Error Occur. Cannot proceed to logout';
		$data['success'] = false;
		$data['errors'] = $errors;
		echo json_encode($data);
	}
});



//Change password
$app->post('/admin/change-password/', function () use ($app) {

	// array to hold validation errors
	$errors = array();

	// validate the variables
	if (empty($_POST['password']) || $_POST['password'] === '') $errors['password'] = 'Password is required.';
	if (empty($_POST['new_password']) || $_POST['new_password'] === '') $errors['new_password'] = 'New Password is required.';

	// response if there are errors
	if (empty($errors)) {

		try {
			$user = Model::factory('Account')->where('login',$app->getEncryptedCookie('user')
			)->find_one();

			if (empty($user)) {
				$errors['message'] = 'Invalid User';
				$data['success'] = false;
				$data['errors'] = $errors;
				echo json_encode($data);
			} else {
				if ($user->password != generatePassword($_POST['password'], $user->salt)) {
					$errors['message'] = 'Invalid Password';
					$data['success'] = false;
					$data['errors'] = $errors;
					echo json_encode($data);
				} else {
					$salt = generateSalt();
					$user->password = generatePassword($_POST['new_password'], $salt);
					$user->salt = $salt;
					$user->save();
					
					$data['success'] = true;
					echo json_encode($data);
				}
			}
		} catch (PDOException $e) {
			$errors['message'] = 'Sorry, an Unexpected Error Occur. Password change is not complete.';
			$data['success'] = false;
			$data['errors'] = $errors;
			echo json_encode($data);
		}
	} else {

		$data['success'] = false;
		$data['errors'] = $errors;
		echo json_encode($data);
	}
});


//Reset password
$app->post('/admin/reset-password/', function () use ($app) {

	// array to hold validation errors
	$errors = array();

	// validate the variables
	if (empty($_POST['email']) || $_POST['email'] === '') $errors['email'] = 'Email is required.';

	// response if there are errors
	if (empty($errors)) {

		try {
			$user = Model::factory('Account')->where('login',$_POST['email']
			)->find_one();

			if (empty($user)) {
				$errors['message'] = 'Invalid User';
				$data['success'] = false;
				$data['errors'] = $errors;
				echo json_encode($data);
			} else {
					$salt = generateSalt();
					$newPassword = generateRandomString();
					$user->password = generatePassword($newPassword, $salt);
					$user->salt = $salt;
					$user->save();
					
					\Helper\Mailer::$RESET_PASSWORD_MESSAGE="Thank you! your password has been reset. You can change your password later. Use this details to login. <br><br> Username: ".$_POST['email']."<br>Password: ".$newPassword."<br><br>";
					$email = new \Helper\Mailer($_POST['email']);    
					$email->sendEmail('reset-password');
				
					$data['success'] = true;
					echo json_encode($data);
				
			}
		} catch (PDOException $e) {
			$errors['message'] = 'Sorry, an Unexpected Error Occur. Password Reset is not complete.';
			$data['success'] = false;
			$data['errors'] = $errors;
			echo json_encode($data);
		}
	} else {

		$data['success'] = false;
		$data['errors'] = $errors;
		echo json_encode($data);
	}
});

// Allow admin to manage tables and do actions
$app->allowed = 'admin';


//add Category
$app->post('/admin/category/add/','authenticate', function() use ($app) {
	$errors = [];
	$data = [];
	if (empty($_POST['category']) || $_POST['category'] === '') $errors['category'] = 'Category is required.';
	if (empty($errors)) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),$app->allowed);
	if (is_numeric($auth)) {
		
		try{
		$category = Model::factory('category')->create();
		$user = Model::factory('Account')->where('login', $app->getEncryptedCookie('user'))->find_one();
		$userID = $user->id;
		//die($userID)
		$category->name = $_POST['category'];
		$category->account_id = $userID;
		$category->save();
		$data['success'] = true;
		$data['message'] = 'Category added';
		echo json_encode($data);
	
		} catch (PDOException $e) {
			$errors['message'] = $e->getMessage();
			$data['success'] = false;
			$data['errors'] = $errors;
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
	}
	else{
			$data['error'] = $errors;
			$data['success'] = false;
			$data['errors'] = $errors;
			echo json_encode($data);
	}
		
});