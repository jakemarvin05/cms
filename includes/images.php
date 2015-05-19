<?php
/**
 * Created by PhpStorm.
 * User: dusia
 * Date: 06.05.15
 * Time: 09:40
 */

// Views

// Logic

/*
 * Params:
 * search - search term (name&path)
 * search_filed - field specified
 * order - the field name in db (table: images)
 * desc - true for desc order
 * limit
 * offset
 * */
$app->get('/images','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::IMAGE_ADMIN);
	if (is_numeric($auth)) {
		try {
			$query = Model::factory('Image');
			$allVars = $app->request->get();
			if (isset($allVars['order'])) {
				if ($allVars['desc']=='true') {
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
			if (isset($allVars['search']) && strlen($allVars['search'])>0) {
				if (isset($allVars['search_field'])) {
					$query->where_like($allVars['search_field'],'%'.$allVars['search'].'%');
				}
				else {
					$query->where_any_is(array(
						array('name' => '%'.$allVars['search'].'%'),
						array('path' => '%'.$allVars['search'].'%')
					), 'LIKE');
				}
			}
			if (isset($allVars['deleted'])) {
				$query->where_not_null('delete_date');
			}
			else {
				$query->where_null('delete_date');
			}
			$images = $query->find_many();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Images Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($images) {
			$data['success'] = true;
			foreach ($images as $image)
				$data['images'][] = $image->as_array();
			$data['query']=$query->get_last_query();
			echo json_encode($data);
		}
		else {
			$data['success'] = true;
			$data['images'] = 0;
		}
	}
	else echo json_encode($auth);
});

$app->get('/imagesCount','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),'IMAGE_ADMIN');
	if (is_numeric($auth)) {
		try {
			$query = Model::factory('Image');
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
			if (isset($allVars['search']) && strlen($allVars['search'])>0) {
				if (isset($allVars['search_field'])) {
					$query->where_like($allVars['search_field'],'%'.$allVars['search'].'%');
				}
				else {
					$query->where_any_is(array(
						array('name' => '%'.$allVars['search'].'%'),
						array('path' => '%'.$allVars['search'].'%')
					), 'LIKE');
				}
			}
			if (isset($allVars['deleted'])) {
				$query->where_not_null('delete_date');
			}
			else {
				$query->where_null('delete_date');
			}
			$images = $query->count();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Images Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($images) {
			$data['success'] = true;
			$data['images'] = $images;
			echo json_encode($data);
		}
		else {
			$data['success'] = true;
			$data['images'] = 0;
		}
	}
	else echo json_encode($auth);
});

$app->get('/images/:id', function($id) use ($app) {
	try {
		$image = Model::factory('Image')->find_one($id);
	}
	catch (PDOException $e){
		$errors['message'] = 'No Image Found: ' . $e->getMessage();
		$data['success'] = false;
		$data['errors']  = $errors;
		echo json_encode($data);
	}
	if ($image) {
		$data = $image->as_array();
		echo json_encode($data);
	}
});

$app->delete('/images/:id','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::IMAGE_ADMIN);
	if (is_numeric($auth)) {
		try {
			$image = Model::factory('Image')->find_one($id);
		}
		catch (PDOException $e){
			$errors['message'] = 'No Image Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($image) {
			$image->set_expr('delete_date','NOW()');
			$image->save();
			$data['success'] = true;
			$data['message'] = 'Image deleted';
			addLog('IMAGE_DELETED', $auth, $id);
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

/* variables:
image - the file input
name - title for the image?*/
$app->post('/image/add/','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::IMAGE_ADMIN);
	if (is_numeric($auth)) {
		$allVars = $app->request()->post();
		$imageup = $_FILES['file'];
		if (isset($imageup['name'])) {
			$path='content/upload/';
			if (!is_dir($path)) {
				mkdir($path);
				chmod($path,'777');
			}
			$types=array('image/gif','image/jpg','image/png','image/jpeg');

			if (in_array($imageup['type'],$types)) {
				$image = Model::factory('Image')->create();
				$image->name = $allVars['name'];
				$image->path = $path;
				$image->account_id = $auth;
				$image->set_expr('creation_date','NOW()');
				$image->save();
				$image = Model::factory('Image')->find_one($image->id);
				$unique_id = $image->id.'_'.time();
				$filetype = strrchr($imageup['name'], '.');
				$year=date('Y',strtotime($image->creation_date));
				$month=date('m',strtotime($image->creation_date));
				if (!is_dir($path.$year)) {
					mkdir($path.$year);
					chmod($path.$year,'777');
				}
				if (!is_dir($path.$year.'/'.$month)) {
					mkdir($path.$year.'/'.$month);
					chmod($path.$year.'/'.$month,'777');
				}
				$new_upload = $path.$year.'/'.$month.'/'.$unique_id.$filetype;
				move_uploaded_file($imageup['tmp_name'], $new_upload);

				$image->path = $new_upload;
				$image->save();

				$data['success'] = true;
				$data['message'] = 'Image added';
				addLog('IMAGE_ADDED', $auth, $image->id);
			}
			else {
				$errors['message'] = 'Wrong format, we allow only .jpg, .png, .gif';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		else {
			$errors['message'] = 'No image file uploaded';
			$data['success'] = false;
			$data['errors']  = $errors;
		}
		echo json_encode($data);
	}
	else echo json_encode($auth);
});
