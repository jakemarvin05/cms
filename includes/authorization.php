<?php

// Logic

$app->post('/authorize', function() use ($app) {
	$errors = array();


	$allVars = $app->request->post();
	if (empty($allVars['login']) || $allVars['login'] === '') $errors['login'] = 'Username is required.';
	if (empty($allVars['password']) || $allVars['password'] === '') $errors['password'] = 'Password is required.';

	// response if there are errors
	if (empty($errors)) {
		try {
			$user = Model::factory('Account')->where('login', $allVars['login'])->find_one();

			if (empty($user)) {
				$errors['message'] = 'Invalid User';
				$data['success'] = false;
				$data['errors'] = $errors;
				echo json_encode($data);
			} else {
				if ($user->password != generatePassword($allVars['password'], $user->salt)) {
					$errors['message'] = 'Invalid Password';
					$data['success'] = false;
					$data['errors'] = $errors;
					echo json_encode($data);
				} else {
					$roles = $user->roles()->find_many();
					if ($roles) {
						foreach ($roles as $role) $all_roles[] = $role->as_array();
						$data['success'] = true;
						$i = 0;
						foreach ($all_roles as $role){

							$data['roles'][$i]['id']=$role['id'];
							$data['roles'][$i]['role_id']=$role['role_id'];
							$Role = Model::factory('Role')->where('id',$role['role_id'] )->find_one()->name;
							$data['roles'][$i]['name']=$Role;
							$i++;

						}
					}


					$app->setEncryptedCookie('user', $allVars['login']);
					$app->setEncryptedCookie('sessionID', generateSessionId($allVars['login'], $user->password));

					$data['success'] = true;
					echo json_encode($data);
				}
			}
		} catch (PDOException $e) {
			$errors['message'] = $e->getMessage();
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

$app->post('/logout', function () use ($app) {
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

///Change password
$app->post('/change-password', function () use ($app) {

	// array to hold validation errors
	$errors = array();
	$allVars = $app->request->post();
	// validate the variables
	if (empty($allVars['old_password']) || $allVars['old_password'] === '') $errors['old_password'] = 'Password is required.';
	if (empty($allVars['new_password']) || $allVars['new_password'] === '') $errors['new_password'] = 'Password is required.';

	// response if there are errors
	if (empty($errors)) {

		try {
			$user = Model::factory('Account')->where('login', $app->getEncryptedCookie('user'))->find_one();

			if (empty($user)) {
				$errors['message'] = 'Invalid User';
				$data['success'] = false;
				$data['errors'] = $errors;
				echo json_encode($data);
			} else {
				if ($user->password != generatePassword($allVars['old_password'], $user->salt)) {
					$errors['message'] = 'Invalid old password';
					$data['success'] = false;
					$data['errors'] = $errors;
					echo json_encode($data);
				} else {
					$salt = generateSalt();
					$user->password = generatePassword($allVars['new_password'], $salt);
					$user->salt = $salt;
					$user->save();
					$data['message']= 'Password successfully changed!';
					$data['success'] = true;
					echo json_encode($data);
				}
			}
		} catch (PDOException $e) {
			$errors['message'] = $e->getMessage();
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

$app->post('/reset-password', function () use ($app) {

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
					
					// FIXME: e-mail messages should be kept as enums
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