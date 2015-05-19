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
 * order - the field name in db (table: redirects)
 * desc - true for desc order
 * limit
 * offset
 * */
$app->get('/redirects/','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::REDIRECT_ADMIN);
	if (is_numeric($auth)) {
		try {
			$query = Model::factory('Redirect');
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
			$redirects = $query->find_many();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Redirects Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($redirects) {
			$data['success'] = true;
			foreach ($redirects as $redirect)
				$data['redirects'][] = $redirect->as_array();
			echo json_encode($data);
		}
		else {
			$data['success'] = true;
			$data['redirects'] = 0;
		}
	}
	else echo json_encode($auth);
});

$app->get('/redirectsCount/','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),'REDIRECT_ADMIN');
	if (is_numeric($auth)) {
		try {
			$query = Model::factory('Redirect');
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
			$redirects = $query->count();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Redirects Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($redirects) {
			$data['success'] = true;
			$data['count'] = $redirects;
			echo json_encode($data);
		}
		else {
			$data['success'] = true;
			$data['count'] = 0;
		}
	}
	else echo json_encode($auth);
});

$app->get('/redirect/:id', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::REDIRECT_ADMIN);
	if (is_numeric($auth)) {
		try {
			$redirect = Model::factory('Redirect')->find_one($id);
		}
		catch (PDOException $e){
			$errors['message'] = 'No Redirect Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($redirect) {
			$data['success'] = true;
			$data['redirect'] = $redirect->as_array();
			echo json_encode($data);
		}
		else {
			$data['success'] = true;
			$data['redirect'] = 0;
		}
	}
	else echo json_encode($auth);
});

$app->delete('/redirect/:id','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::REDIRECT_ADMIN);
	if (is_numeric($auth)) {
		try {
			$redirect = Model::factory('Redirect')->find_one($id);
		}
		catch (PDOException $e){
			$errors['message'] = 'No Redirect Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($redirect) {
			$redirect->delete();
			$data['success'] = true;
			$data['message'] = 'Redirect deleted';
			addLog('REDIRECT_DELETED', $auth, $id);
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->post('/redirect/add/','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::REDIRECT_ADMIN);
	if (is_numeric($auth)) {
		$page = Model::factory('Page')->find_one($app->request()->post('page_id'));
		if ($page) {
			$url = Model::factory('Redirect')->where('url',$app->request()->post('url'))->find_one();
			if ($url) {
				$redirect = Model::factory('Redirect')->create();
				$redirect->url = $app->request()->post('url');
				$redirect->page_id = $page->id;
				$redirect->save();

				$data['success'] = true;
				$data['message'] = 'Redirect added';
				addLog('REDIRECT_ADDED', $auth, $redirect->id);
			}
			else {
				$errors['message'] = 'Redirect with this url already exists';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		else {
			$errors['message'] = 'No Page Found';
			$data['success'] = false;
			$data['errors']  = $errors;
		}
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

$app->post('/redirect/edit/:id','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::REDIRECT_ADMIN);
	if (is_numeric($auth)) {
		$redirect = Model::factory('Redirect')->find_one($id);
		$redirect->url = $app->request()->post('url');
		$redirect->page_id = $app->request()->post('page_id');
		$redirect->save();

		$data['success'] = true;
		$data['message'] = 'Redirect saved';
		addLog('REDIRECT_EDITED', $auth, $redirect->id);
		echo json_encode($data);
	}
	else echo json_encode($auth);
});
