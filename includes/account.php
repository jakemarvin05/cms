<?php
/**
 * Created by PhpStorm.
 * User: dusia
 * Date: 04.05.15
 * Time: 09:40
 */

$app->allowed = 'admin';

/*
 * Params:
 * order - the field name in db (table: accounts)
 * desc - true for desc order
 * limit
 * offset
 * */
$app->get('/accounts/','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),$app->allowed);
	if (is_numeric($auth)) {
		try {
			$query = Model::factory('Account');
			$allVars = $app->request->get();
			if (isset($allVars['order'])) {
				if (isset($allVars['desc'])) {
					$query->order_by_desc($allVars['order']);
				}
				else $query->order_by_asc($allVars['order']);
			}
			if (isset($allVars['limit'])) {
				if (isset($allVars['offset'])) {
					$query->offset($allVars['offset']);
				}
				$query->limit($allVars['limit']);
			}
			$accounts = $query->find_many();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Accounts Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($accounts) {
			foreach ($accounts as $account)
				$data[] = $account->as_array();
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->get('/account/(:id)', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),$app->allowed);
	if (is_numeric($auth)) {
		try {
			if (is_numeric($id)) $account = Model::factory('Account')->find_one($id);
			else $account = Model::factory('Account')->where('login', $id)->find_one();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Account Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($account) {
			$data = $account->as_array();
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->get('/account/get_roles/(:id)','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),$app->allowed);
	if (is_numeric($auth)) {
		try {
			$account = Model::factory('Account')->find_one($id);
		}
		catch (PDOException $e){
			$errors['message'] = 'No Account Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($account) {
			$roles = $account->roles()->find_many();
			foreach ($roles as &$role) {
				$role_tmp = $role->role()->find_one();
				if ($role_tmp) {
					$all_roles[] = $role_tmp->name;
				}
			}
			echo json_encode($all_roles);
		}
	}
	else echo json_encode($auth);
});

$app->post('/accounts/add_roles/','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),$app->allowed);
	if (is_numeric($auth)) {
		$account_has_roles = Model::factory('Account_has_role')->create();
		$account_has_roles->account_id = $app->request()->post('account_id');
		$account_has_roles->role_id = $app->request()->post('role_id');
		$account_has_roles->save();

		$data['success'] = true;
		$data['message'] = 'Roles added';

		addLog('ROLE_ADDED', $auth, $app->request()->post('account_id'));
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

$app->delete('/accounts/delete_role/(:id)','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),$app->allowed);
	if (is_numeric($auth)) {
		$account_has_roles = Model::factory('Account_has_role')->find_one($id);
		$account_has_roles->delete();

		$data['success'] = true;
		$data['message'] = 'Roles deleted';

		addLog('ROLE_DELETED', $auth, $id);
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

$app->delete('/account/(:id)','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),$app->allowed);
	if (is_numeric($auth)) {
		try {
			if (is_numeric($id)) $account = Model::factory('Account')->find_one($id);
			else $account = Model::factory('Account')->where('login', $id)->find_one();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Account Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($account) {
			$account->delete_date = 'CURRENT_TIMESTAMP';
			$account->save();
			$data['success'] = true;
			$data['message'] = 'Account deleted';
			addLog('ACCOUNT_DELETED', $auth, $id);
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->post('/accounts/add/','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),$app->allowed);
	if (is_numeric($auth)) {
		$account = Model::factory('Account')->create();
		$account->login = $app->request()->post('login');
		$account->email = $app->request()->post('email');
		$account->first_name = $app->request()->post('first_name');
		$account->last_name = $app->request()->post('last_name');
		$account->set_expr('creation_date', 'NOW()');
		$account->set_expr('update_date', 'NOW()');
		$account->password = hash("sha512",$app->request()->post('password'));
		$account->salt = generateSalt();
		$account->save();


		$data['success'] = true;
		$data['message'] = 'Account added';
		addLog('ACCOUNT_ADDED', $auth, $account->id);
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

$app->post('/accounts/edit/(:id)','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),$app->allowed);
	if (is_numeric($auth)) {
		$account = Model::factory('Account')->find_one($id);
		$account->login = $app->request()->post('login');
		$account->email = $app->request()->post('email');
		$account->first_name = $app->request()->post('first_name');
		$account->last_name = $app->request()->post('last_name');
		$account->set_expr('update_date', 'NOW()');
		$account->password = hash("sha512",$app->request()->post('password'));
		$account->save();

		$data['success'] = true;
		$data['message'] = 'Account saved';
		addLog('ACCOUNT_EDITED', $auth, $account->id);
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

$app->get('/myaccount/','authenticate', function() use ($app) {
	try {
		$account = Model::factory('Account')->where('login', $app->getEncryptedCookie('user'))->find_one();
	}
	catch (PDOException $e){
		$errors['message'] = 'No Account Found: ' . $e->getMessage();
		$data['success'] = false;
		$data['errors']  = $errors;
		echo json_encode($data);
	}
	if ($account) {
		$data = $account->as_array();
		echo json_encode($data);
	}
});

$app->post('/myaccount/edit/','authenticate', function() use ($app) {
	try {
		$account = Model::factory('Account')->where('login', $app->getEncryptedCookie('user'))->find_one();
	}
	catch (PDOException $e){
		$errors['message'] = 'No Account Found: ' . $e->getMessage();
		$data['success'] = false;
		$data['errors']  = $errors;
		echo json_encode($data);
	}
	if ($account) {
		$account->login = $app->request()->post('login');
		$account->email = $app->request()->post('email');
		$account->first_name = $app->request()->post('first_name');
		$account->last_name = $app->request()->post('last_name');
		$account->set_expr('update_date', 'NOW()');
		$account->password = hash("sha512",$app->request()->post('password'));
		$account->save();

		$data['success'] = true;
		$data['message'] = 'Account saved';
		addLog('ACCOUNT_ADDED', $account->id, $account->id);
		echo json_encode($data);
	}
});

$app->get('/myaccount/get_roles/','authenticate', function($id) use ($app) {
	try {
		$account = Model::factory('Account')->where('login', $app->getEncryptedCookie('user'))->find_one();
	}
	catch (PDOException $e){
		$errors['message'] = 'No Account Found: ' . $e->getMessage();
		$data['success'] = false;
		$data['errors']  = $errors;
		echo json_encode($data);
	}
	if ($account) {
		$roles = $account->roles()->find_many();
		foreach ($roles as &$role) {
			$role_tmp = $role->role()->find_one();
			if ($role_tmp) {
				$all_roles[] = $role_tmp->name;
			}
		}
		echo json_encode($all_roles);
	}
});