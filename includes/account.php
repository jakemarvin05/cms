<?php
/**
 * Created by PhpStorm.
 * User: dusia
 * Date: 04.05.15
 * Time: 09:40
 */

// Logic

/*
 * Params:
 * search - search term
 * search_field - field in db to search in
 * deleteed - true for getting deleted elements
 * order - the field name in db (table: accounts)
 * desc - true for desc order
 * limit
 * offset
 * */
$app->get('/accounts','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::ACCOUNT_ADMIN);
	if (is_numeric($auth)) {
		try {
			$query = Model::factory('Account');
			$allVars = $app->request->get();
			if (isset($allVars['order'])) {
				if (isset($allVars['desc']) && $allVars['desc'] == 'true') {
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
			if (isset($allVars['search'])) {
				if (isset($allVars['search_field'])) {
					$query->where_like($allVars['search_field'],'%'.$allVars['search'].'%');
				}
				else {
					$query->where_any_is(array(
						array('email' => '%'.$allVars['search'].'%'),
						array('first_name' => '%'.$allVars['search'].'%'),
						array('last_name' => '%'.$allVars['search'].'%'),
						array('login' => '%'.$allVars['search'].'%')
					), 'LIKE');
				}
			}
			if (isset($allVars['deleted'])) {
				$query->where_not_null('delete_date');
			}
			else {
				$query->where_null('delete_date');
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
			$data['success'] = true;
			foreach ($accounts as $account)
				$data['accounts'][] = $account->as_array();
			echo json_encode($data);
		} else {
			$data['success'] = true;
			$data['accounts'] = [];
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

/* arguments as accounts */
$app->get('/countAccounts','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),RoleName::ACCOUNT_ADMIN);
	if (is_numeric($auth)) {
		try {
			$query = Model::factory('Account');
			$allVars = $app->request->get();
			if (isset($allVars['limit'])) {
				if (isset($allVars['offset'])) {
					$query->offset($allVars['offset']);
				}
				$query->limit($allVars['limit']);
			}
			if (isset($allVars['search'])) {
				if (isset($allVars['search_field'])) {
					$query->where_like($allVars['search_field'],'%'.$allVars['search'].'%');
				}
				else {
					$query->where_any_is(array(
						array('email' => '%'.$allVars['search'].'%'),
						array('first_name' => '%'.$allVars['search'].'%'),
						array('last_name' => '%'.$allVars['search'].'%'),
						array('login' => '%'.$allVars['search'].'%')
					), 'LIKE');
				}
			}
			if (isset($allVars['deleted'])) {
				$query->where_not_null('delete_date');
			}
			else {
				$query->where_null('delete_date');
			}
			$accounts = $query->count();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Accounts Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			return json_encode($data);
		}
		if ($accounts) {
			$data['success'] = true;
			$data['count'] = $accounts;
			echo json_encode($data);
		} else {
			$data['success'] = true;
			$data['count'] = 0;
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->get('/accounts/roles','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),RoleName::ACCOUNT_ADMIN);
	if (is_numeric($auth)) {
		try {
			$roles = Model::factory('Role')->find_many();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Roles Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($roles) {
			foreach ($roles as $role) {
				$all_roles[] = $role->as_array();
			}
			$data['success'] = true;
			$data['roles'] = $all_roles;
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->get('/account/(:id)', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),RoleName::ACCOUNT_ADMIN);
	//$auth=1;
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
			$data['success'] = true;
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->get('/account/getRoles/(:id)','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),RoleName::ACCOUNT_ADMIN);
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
			$all_roles = [];
			$roles = $account->roles()->find_many();
			foreach ($roles as &$role) {
				$role_tmp = $role->role()->find_one();
				if ($role_tmp) {
					$all_roles[] = $role_tmp->name;
				}
			}
			$data['success'] = true;
			$data['roles'] = $all_roles;
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

/*
account_id
roles = array
*/
$app->post('/accounts/addRoles','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),RoleName::ACCOUNT_ADMIN);
	if (is_numeric($auth)) {
		$allVars=$app->request()->post();
		foreach ($allVars['roles'] as $role) {
			$account_has_roles = Model::factory('Account_has_role')->create();
			$account_has_roles->account_id = $allVars['account_id'];
			$account_has_roles->role_id = $role;
			$account_has_roles->save();
			unset($account_has_roles);
		}
		$data['success'] = true;
		$data['message'] = 'Roles added';

		addLog('ROLE_ADDED', $auth, $allVars['account_id']);
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

/* params:
account_id
roles - array of roles_ids
*/
$app->post('/accounts/editRoles','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),RoleName::ACCOUNT_ADMIN);
	if (is_numeric($auth)) {
		$allVars=$app->request()->post();
		$existingRoles = Model::factory('Account_has_role')->where('account_id',$allVars['account_id'])->find_many();
		
		$activeRole = [];
		if ($existingRoles) foreach ($existingRoles as $er) {
			$activeRole[] = $er->role_id;
		}
		if (isset($allVars['roles']) && count($allVars['roles']>0)) {
			foreach ($allVars['roles'] as $role) {
				if (in_array($role,$activeRole)) {
					$saveId[]=$role;
				}
				else {
					$account_has_roles = Model::factory('Account_has_role')->create();
					$account_has_roles->account_id = $allVars['account_id'];
					$account_has_roles->role_id = $role;
					$account_has_roles->save();
					unset($account_has_roles);

					addLog('ROLE_ADDED', $auth, $allVars['account_id']);
				}
			}
			foreach ($existingRoles as $er) {
				if (!in_array($er->role_id,$allVars['roles'])) {
					$er->delete();
					addLog('ROLE_DELETED', $auth, $allVars['account_id']);
				}
			}
		}
		else {
			if ($existingRoles) foreach ($existingRoles as $er) {
				$er->delete();
				addLog('ROLE_DELETED', $auth, $allVars['account_id']);
			}
		}
		$data['success'] = true;
		$data['message'] = 'Roles done';

		echo json_encode($data);
	}
	else echo json_encode($auth);
});

$app->delete('/account/role/(:id)','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),RoleName::ACCOUNT_ADMIN);
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
	$auth = isAuthorised($app->getEncryptedCookie('user'),RoleName::ACCOUNT_ADMIN);
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
			if ($app->getEncryptedCookie('user')!=$account->login) {
				$account->delete_date = 'CURRENT_TIMESTAMP';
				$account->save();
				$data['success'] = true;
				$data['message'] = 'Account deleted';
				addLog('ACCOUNT_DELETED', $auth, $id);
			}
			else {
				$errors['message'] = 'You are trying to delete yourself!';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->post('/accounts/add','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),RoleName::ACCOUNT_ADMIN);
	if (is_numeric($auth)) {
		$allVars=$app->request()->post();
		$account = Model::factory('Account')->where('login',$allVars['login'])->find_one();
		if (!$account) {
			$account = Model::factory('Account')->create();
			$account->login = $allVars['login'];
			$account->email = $allVars['email'];
			$account->first_name = $allVars['first_name'];
			$account->last_name = $allVars['last_name'];
			$account->set_expr('creation_date', 'NOW()');
			$account->set_expr('update_date', 'NOW()');
			$salt = generateSalt();
			$account->password = generatePassword($allVars['password'],$salt);
			$account->salt = $salt;
			$account->save();

			$data['success'] = true;
			$data['message'] = 'Account added';
			$data['id'] = $account->id;
			addLog('ACCOUNT_ADDED', $auth, $account->id);
		}
		else {
			$errors['message'] = 'Login taken';
			$data['success'] = false;
			$data['errors']  = $errors;
		}
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

$app->post('/accounts/edit/(:id)','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),RoleName::ACCOUNT_ADMIN);
	if (is_numeric($auth)) {
		$allVars=$app->request()->post();
		$account = Model::factory('Account')->find_one($id);
		$account2 = Model::factory('Account')->where('login',$allVars['login'])->find_one();
		if ($account2->id == ($id)) {
			$account->login = $allVars['login'];
			$account->email = $allVars['email'];
			$account->first_name = $allVars['first_name'];
			$account->last_name = $allVars['last_name'];
			$account->set_expr('update_date', 'NOW()');
			$account->save();

			$data['success'] = true;
			$data['message'] = 'Account saved';
			addLog('ACCOUNT_EDITED', $auth, $account->id);
		}
		else {
			$errors['message'] = 'Login taken';
			$data['success'] = false;
			$data['errors']  = $errors;
		}
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

$app->post('/accounts/changePassword/(:id)','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),RoleName::ACCOUNT_ADMIN);
	if (is_numeric($auth)) {
		$allVars=$app->request()->post();
		$account = Model::factory('Account')->find_one($id);
		$account->set_expr('update_date', 'NOW()');
		$account->password = generatePassword($allVars['password'],$account->salt);
		$account->save();

		$data['success'] = true;
		$data['message'] = 'Account saved';
		addLog('ACCOUNT_EDITED', $auth, $account->id);
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

$app->get('/myaccount','authenticate', function() use ($app) {
	try {
		$account = Model::factory('Account')->where('login', $app->getEncryptedCookie('user'))->find_one();
	}
	catch (PDOException $e){
		$errors['message'] = 'No Account Found: ' . $e->getMessage();
		$data['success'] = false;
		$data['errors']  = $errors;
		return json_encode($data);
	}
	if ($account) {
		$data = $account->as_array();
		$data['success'] = true;
		echo json_encode($data);
	}
});

$app->post('/myaccount/edit','authenticate', function() use ($app) {
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
		$allVars=$app->request()->post();
		$account2 = Model::factory('Account')->where('login',$allVars['login'])->find_one();
		if ($account2->id == $account->id) {
			$account->login = $allVars['login'];
			$account->email = $allVars['email'];
			$account->first_name = $allVars['first_name'];
			$account->last_name = $allVars['last_name'];
			$account->set_expr('update_date', 'NOW()');
			$account->save();

			$sessionID = generateSessionId($account->login, $account->password);
			$app->setEncryptedCookie('user', $account->login, '15 minutes');
			$app->setEncryptedCookie('sessionID', $sessionID, '15 minutes');

			$data['success'] = true;
			$data['message'] = 'Account saved';
			addLog('ACCOUNT_ADDED', $account->id, $account->id);
		}
		else {
			$errors['message'] = 'Login taken';
			$data['success'] = false;
			$data['errors']  = $errors;
		}
		echo json_encode($data);
	}
});

$app->get('/myaccount/getRoles','authenticate', function($id) use ($app) {
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
