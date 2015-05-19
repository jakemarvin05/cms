<?php
// Logic

$app->get('/tags/','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::TAG_ADMIN);
	if (is_numeric($auth)) {
		try {
			$query = Model::factory('Tag');
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
			$tags = $query->find_many();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Tags Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($tags) {
			$data['success'] = true;
			foreach ($tags as $tag)
				$data['tags'][] = $tag->as_array();
			echo json_encode($data);
		}
		else {
			$data['success'] = true;
			$data['tags'] = 0;
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->get('/tagsCount/','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::TAG_ADMIN);
	if (is_numeric($auth)) {
		try {
			$query = Model::factory('Tag');
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
			$tags = $query->count();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Tags Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			return json_encode($data);
		}
		if ($tags) {
			$data['success'] = true;
			$data['tags'] = $tags;
			echo json_encode($data);
		}
		else {
			$data['success'] = true;
			$data['tags'] = 0;
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});



//add Tag
$app->post('/tag/add/','authenticate', function() use ($app) {
	$errors = [];
	$data = [];
	echo $app->getEncryptedCookie('user');
	if (empty($_POST['tag']) || $_POST['tag'] === '') $errors['tag'] = 'Tag is required.';
	if (empty($errors)) {
		$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::TAG_ADMIN);
		if (is_numeric($auth)) {

			try{
				$tag = Model::factory('Tag')->create();
				$user = Model::factory('Account')->where('login', $app->getEncryptedCookie('user'))->find_one();
				$userID = $user->id;
				//die($userID);
				$tag->name = $_POST['tag'];
				$tag->account_id = $userID;
				$tag->set_expr('creation_date','now()');
				$tag->save();
				$data['success'] = true;
				$data['message'] = 'Tag added';
				echo json_encode($data);

			} catch (PDOException $e) {
				$errors['message'] = $e->getMessage();;
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


//edit Tag
$app->post('/tag/edit/','authenticate', function() use ($app) {
	$errors = [];
	$data = [];
	if (empty($_POST['id']) || $_POST['id'] === '') $errors['id'] = 'ID is required.';
	if (empty($_POST['name']) || $_POST['name'] === '') $errors['name'] = 'New Tag name is required.';

	if (empty($errors)) {
		$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::TAG_ADMIN);
		if (is_numeric($auth)) {

			try{
				$tag = Model::factory('Tag')->find_one($_POST['id']);

				if($tag) {
					$user = Model::factory('Account')->where('login', $app->getEncryptedCookie('user'))->find_one();
					$userID = $user->id;
					$oldName = $tag->name;
					$tag->name = $_POST['name'];
					$tag->account_id = $userID;
					$tag->save();
					$data['success'] = true;
					$data['message'] = 'Tag '.$oldName.' Changed to '.$_POST['name'];
					echo json_encode($data);
				}
				else{
					$errors['message'] = 'ID should be Numeric';
					$data['success'] = false;
					$data['errors'] = $errors;
					echo json_encode($data);

				}

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
		$data['success'] = false;
		$data['errors'] = $errors;
		echo json_encode($data);
	}

});



//delete Tag
$app->post('/tag/delete/','authenticate', function() use ($app) {
	$errors = [];
	$data = [];
	if (empty($_POST['id']) || $_POST['id'] === '') $errors['id'] = 'ID is required.';

	if (empty($errors)) {
		$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::TAG_ADMIN);
		if (is_numeric($auth)) {

			try{
				$tag = Model::factory('Tag')->find_one($_POST['id']);

				if($tag) {
					$user = Model::factory('Account')->where('login', $app->getEncryptedCookie('user'))->find_one();
					$userID = $user->id;
					$tag->account_id = $userID;
					$tag->set_expr('delete_date','now()');
					$tag->save();
					$data['success'] = true;
					$data['message'] = 'Category '.$tag->name.' successfully deleted';
					echo json_encode($data);
				}
				else{
					$errors['message'] = 'ID should be Numeric';
					$data['success'] = false;
					$data['errors'] = $errors;
					echo json_encode($data);

				}

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
		$data['success'] = false;
		$data['errors'] = $errors;
		echo json_encode($data);
	}

});


//get Tag by ID
$app->get('/tag/:id','authenticate', function($id) use ($app) {
	$data = [];
	$errors = [];

	if (!empty($id)) {
		$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::CATEGORY_ADMIN);
		if (is_numeric($auth)) {

			try{
				$tag = Model::factory('Tag')->find_one($id);

				if($tag){
					if($tag->delete_date == null){
						$data['success'] = true;
						$data['category'][] = $tag->as_array();
						echo json_encode($data);
					}
					else{
						$errors['message'] = 'Tag does not exist anymore!';
						$data['success'] = false;
						$data['errors']= $errors;
						echo json_encode($data);

					}

				}
				else{
					$errors['message'] = 'No Tag found!';
					$data['success'] = false;
					$data['errors']= $errors;
					echo json_encode($data);
				}

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
		$errors['message'] = 'ID is required';
		$data['success'] = false;
		$data['errors'] = $errors;
		echo json_encode($data);
	}

});