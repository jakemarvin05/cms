<?php

// Logic

$app->get('/categories','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::CATEGORY_ADMIN);
	if (is_numeric($auth)) {
		try {
			$query = Model::factory('Category');
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
			$categories = $query->find_many();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Groups Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($categories) {
			$data['success'] = true;
			foreach ($categories as $category)
				$data['categories'][] = $category->as_array();
			echo json_encode($data);
		}
		else {
			$data['success'] = true;
			$data['categories'] = 0;
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->get('/categoriesCount','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::CATEGORY_ADMIN);
	if (is_numeric($auth)) {
		try {
			$query = Model::factory('Category');
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
			$categories = $query->count();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Groups Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			return json_encode($data);
		}
		if ($categories) {
			$data['success'] = true;
			$data['categories'] = $categories;
			echo json_encode($data);
		}
		else {
			$data['success'] = true;
			$data['categories'] = 0;
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});


//add Category
$app->post('/category/add/','authenticate', function() use ($app) {
	$errors = [];
	$data = [];
	echo $app->getEncryptedCookie('user');
	if (empty($_POST['category']) || $_POST['category'] === '') $errors['category'] = 'Category is required.';
	if (empty($errors)) {
		$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::CATEGORY_ADMIN);
		if (is_numeric($auth)) {

			try{
				$category = Model::factory('category')->create();
				$user = Model::factory('Account')->where('login', $app->getEncryptedCookie('user'))->find_one();
				$userID = $user->id;
				//die($userID);
				$category->name = $_POST['category'];
				$category->account_id = $userID;
				$category->set_expr('creation_date','now()');
				$category->save();
				$data['success'] = true;
				$data['message'] = 'Category added';
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

//edit Category
$app->post('/category/edit/','authenticate', function() use ($app) {
	$errors = [];
	$data = [];
	if (empty($_POST['id']) || $_POST['id'] === '') $errors['id'] = 'ID is required.';
	if (empty($_POST['name']) || $_POST['name'] === '') $errors['name'] = 'New category name is required.';

	if (empty($errors)) {
		$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::CATEGORY_ADMIN);
		if (is_numeric($auth)) {

			try{
				$category = Model::factory('category')->find_one($_POST['id']);

				if($category) {
					$user = Model::factory('Account')->where('login', $app->getEncryptedCookie('user'))->find_one();
					$userID = $user->id;
					$oldName = $category->name;
					$category->name = $_POST['name'];
					$category->account_id = $userID;
					$category->save();
					$data['success'] = true;
					$data['message'] = 'Category '.$oldName.' Changed to '.$_POST['name'];
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



//delete Category
$app->post('/category/delete/','authenticate', function() use ($app) {
	$errors = [];
	$data = [];
	if (empty($_POST['id']) || $_POST['id'] === '') $errors['id'] = 'ID is required.';

	if (empty($errors)) {
		$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::CATEGORY_ADMIN);
		if (is_numeric($auth)) {

			try{
				$category = Model::factory('category')->find_one($_POST['id']);

				if($category) {
					$user = Model::factory('Account')->where('login', $app->getEncryptedCookie('user'))->find_one();
					$userID = $user->id;
					$category->account_id = $userID;
					$category->set_expr('delete_date','now()');
					$category->save();
					$data['success'] = true;
					$data['message'] = 'Category '.$category->name.' successfully deleted';
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



//get Category by ID
$app->get('/category/:id','authenticate', function($id) use ($app) {
	$data = [];
	$errors = [];
	
	if (!empty($id)) {
		$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::CATEGORY_ADMIN);
		if (is_numeric($auth)) {

			try{
				$category = Model::factory('category')->find_one($id);

				if($category){
					if($category->delete_date == null){
						$data['success'] = true;
						$data['category'][] = $category->as_array();
						echo json_encode($data);
					}
					else{
						$errors['message'] = 'Category does not exist anymore!';
						$data['success'] = false;
						$data['errors']= $errors;
						echo json_encode($data);

					}

				}
				else{
					$errors['message'] = 'No Category found!';
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