<?php
/**
 * Created by PhpStorm.
 * User: dusia
 * Date: 06.05.15
 * Time: 11:40
 */

// Views

// Logic

/*
 * Params:
 * search - search by name
 * order - the field name in db (table: accounts)
 * desc - true for desc order
 * limit
 * offset
 * */
$app->get('/config/','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::CONFIG_ADMIN);
	if (is_numeric($auth)) {
		try {
			$query = Model::factory('Config');
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
			if (isset($allVars['search'])) {
				$query->where_like('name','%'.$allVars['search'].'%');
			}
			$configs = $query->find_many();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Config Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($configs) {
			foreach ($configs as $config)
				$data[] = $config->as_array();
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->post('/config/edit/:id','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::CONFIG_ADMIN);
	if (is_numeric($auth)) {
		$config = Model::factory('Config')->find_one($id);
		$config->name = $app->request()->post('name');
		$config->value = $app->request()->post('value');
		$config->save();

		$data['success'] = true;
		$data['message'] = 'Config saved';
		addLog('CONFIG_EDITED', $auth, $config->id);
		echo json_encode($data);
	}
	else echo json_encode($auth);
});
