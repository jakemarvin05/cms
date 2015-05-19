<?php

// Logic

//$app->get('/groups','authenticate', function() use ($app) {
$app->get('/groups', function() use ($app) {
	//$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::GROUP_ADMIN);
	$auth = isAuthorised('dusia', RoleName::GROUP_ADMIN);
	if (is_numeric($auth)) {
		try {
			$query = Model::factory('Group');
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
			if (isset($allVars['deleted'])) {
				$query->where_not_null('delete_date');
			}
			else {
				$query->where_null('delete_date');
			}
			$groups = $query->find_many();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Groups Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($groups) {
			$data['success'] = true;
			foreach ($groups as $group)
				$data['groups'][] = $group->as_array();
			echo json_encode($data);
		}
		else {
			$data['success'] = true;
			$data['groups'] = 0;
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->get('/groupsCount/','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::GROUP_ADMIN);
	if (is_numeric($auth)) {
		try {
			$query = Model::factory('Group');
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
			if (isset($allVars['deleted'])) {
				$query->where_not_null('delete_date');
			}
			else {
				$query->where_null('delete_date');
			}
			$groups = $query->count();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Groups Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			return json_encode($data);
		}
		if ($groups) {
			$data['success'] = true;
			$data['groups'] = $groups;
			echo json_encode($data);
		}
		else {
			$data['success'] = true;
			$data['groups'] = 0;
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->get('/group/:id','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::GROUP_ADMIN);
	if (is_numeric($auth)) {
		try {
			$group = Model::factory('Group')->find_one($id);
		}
		catch (PDOException $e){
			$errors['message'] = 'No Group Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			return json_encode($data);
		}
		if ($group) {
			$data['success'] = true;
			$data['group'] = $group->as_array();
			echo json_encode($data);
		}
		else {
			$data['success'] = true;
			$data['group'] = 0;
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->post('/addGroup/','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::GROUP_ADMIN);
	if (is_numeric($auth)) {
		$allVars = $app->request->post();
		if ($allVars) {
			$group = Model::factory('Group')->create();
			$group->name = $allVars['name'];
			$group->set_expr('creation_date','NOW()');
			$group->footer_id = $allVars['footer_id'];
			$group->header_id = $allVars['header_id'];
			$group->scripts = $allVars['scripts'];
			$group->accoint_id = $auth;
			$group->save();
			$group_meta = Model::factory('Group_meta')->create();
			$group_meta->group_id = $group->id;
			$group_meta->title = $allVars['title'];
			if (isset($allVars['meta_author'])) $group_meta->meta_author = $allVars['meta_author'];
			if (isset($allVars['meta_description'])) $group_meta->meta_description = $allVars['meta_description'];
			if (isset($allVars['meta_keywords'])) $group_meta->meta_keywords = $allVars['meta_keywords'];
			if (isset($allVars['og_type'])) $group_meta->og_type = $allVars['og_type'];
			if (isset($allVars['og_url'])) $group_meta->og_url = $allVars['og_url'];
			$group_meta->og_image_id = $allVars['og_image_id'];
			$group_meta->favicon_id = $allVars['favicon_id'];
			$group_meta->save();
			$data['success'] = true;
			$data['group'] = $group->id;
			addLog('GROUP_ADDED', $auth, $group->id);
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->post('/editGroup/:id','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::GROUP_ADMIN);
	if (is_numeric($auth)) {
		$allVars = $app->request->post();
		if ($allVars) {
			$group = Model::factory('Group')->find_one($id);
			$group->name = $allVars['name'];
			$group->footer_id = $allVars['footer_id'];
			$group->header_id = $allVars['header_id'];
			$group->scripts = $allVars['scripts'];
			$group->accoint_id = $auth;
			$group->save();
			$data['success'] = true;
			$data['group'] = $group->id;
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->delete('/group/:id','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::GROUP_ADMIN);
	if (is_numeric($auth)) {
		$allVars = $app->request->post();
		if ($allVars) {
			$group = Model::factory('Group')->find_one($id);
			$group->set_expr('delete_date','NOW()');
			$group->save();
			$data['success'] = true;
			$data['group'] = $group->id;
			addLog('GROUP_DELETED', $auth, $group->id);
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});
