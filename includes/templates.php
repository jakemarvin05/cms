<?php
/**
 * Created by PhpStorm.
 * User: dusia
 * Date: 07.05.15
 * Time: 10:54
 */

// Views

// Logic

/*
 * Params:
 * search - search term
 * search_field - field in db to search in
 * deleted - true for getting deleted elements
 * order - the field name in db (table: accounts)
 * desc - true for desc order
 * limit
 * offset
 * */
$app->get('/templates/','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::TEMPLATE_ADMIN);
	//$auth=1;
	if (is_numeric($auth)) {
		try {
			$query = Model::factory('Template');
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
				if (isset($allVars['search_field'])) {
					$query->where_like('template.'.$allVars['search_field'],'%'.$allVars['search'].'%');
				}
			}
			if (isset($allVars['cat'])) {
				$query->where('category_id',$allVars['cat']);
				$query->join('template_has_category', array('template_has_category.template_id', '=', 'template.id'));
			}
			if (isset($allVars['tag'])) {
				$query->where('tag_id',$allVars['tag']);
				$query->join('template_has_tag', array('template_has_tag.template_id', '=', 'template.id'));
			}
			if (isset($allVars['deleted'])) {
				$query->where_not_null('template.delete_date');
			}
			else {
				$query->where_null('template.delete_date');
			}
			$templates = $query->find_many();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Templates Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($templates) {
			$data['success'] = true;
			foreach ($templates as $template)
				$data['templates'][] = $template->as_array();
			echo json_encode($data);
		}
		else {
			$data['success'] = true;
			$data['templates'] = 0;
		}
	}
	else echo json_encode($auth);
});

$app->get('/templatesCount/','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),'TEMPLATE_ADMIN');
	//$auth=1;
	if (is_numeric($auth)) {
		try {
			$query = Model::factory('Template');
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
				if (isset($allVars['search_field'])) {
					$query->where_like('template.'.$allVars['search_field'],'%'.$allVars['search'].'%');
				}
			}
			if (isset($allVars['cat'])) {
				$query->where('category_id',$allVars['cat']);
				$query->join('template_has_category', array('template_has_category.template_id', '=', 'template.id'));
			}
			if (isset($allVars['tag'])) {
				$query->where('tag_id',$allVars['tag']);
				$query->join('template_has_tag', array('template_has_tag.template_id', '=', 'template.id'));
			}
			if (isset($allVars['deleted'])) {
				$query->where_not_null('template.delete_date');
			}
			else {
				$query->where_null('template.delete_date');
			}
			$templates = $query->count();
		}
		catch (PDOException $e){
			$errors['message'] = 'No Templates Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($templates) {
			$data['success'] = true;
			$data['templates'] = $templates;
			echo json_encode($data);
		}
		else {
			$data['success'] = true;
			$data['templates'] = 0;
		}
	}
	else echo json_encode($auth);
});

$app->get('/template/(:id)', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::TEMPLATE_ADMIN);
	//$auth = 1;
	if (is_numeric($auth)) {
		try {
			$template = Model::factory('Template')->find_one($id);
		}
		catch (PDOException $e){
			$errors['message'] = 'No Template Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($template) {
			$data = $template->as_array();
			$tags = $template->tags()->find_many();
			foreach ($tags as $tag) $data['tags'][]=$tag->as_array();
			$cats = $template->cats()->find_many();
			foreach ($cats as $cat) $data['cats'][]=$cat->as_array();
			$hooks = $template->hooks()->find_many();
			foreach ($hooks as $hook) $data['hooks'][]=$hook->as_array();
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->delete('/template/(:id)','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::TEMPLATE_ADMIN);
	if (is_numeric($auth)) {
		try {
			$template = Model::factory('Template')->find_one($id);
		}
		catch (PDOException $e){
			$errors['message'] = 'No Template Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($template) {
			$pages = $template->pages()->find_many();
			if (!$pages) {
				$template->set_expr('delete_date','NOW()');
				$template->save();
				$data['success'] = true;
				$data['message'] = 'Template deleted';
				addLog('TEMPLATE_DELETED', $auth, $template->id);
			}
			else {
				$errors['message'] = 'this template has pages';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

/* post vars:
path - path to an existing tpl file
name
template_type_id

tags as tag_ids array
cats as category_ids array
hooks as hook elements array - each hook ahs fields as in hook db table
*/
$app->post('/template/add/','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::TEMPLATE_ADMIN);
	if (is_numeric($auth)) {
		$allVars=$app->request()->post();
		if (is_file($allVars['path'])) {
			$template = Model::factory('Template')->create();
			$template->name = $allVars['name'];
			$template->path = $allVars['path'];
			$template->template_type_id = $allVars['template_type_id'];
			$template->set_expr('creation_date', 'NOW()');
			$template->set_expr('update_date', 'NOW()');
			$template->account_id = $auth;
			$template->save();

			if ($allVars['tags']) foreach ($allVars['tags'] as $tagel) {
				$tag = Model::factory('Template_has_tag')->create();
				$tag->tag_id = $tagel;
				$tag->template_id = $template->id;
				$tag->account_id = $auth;
				unset($tag);
			}
			if ($allVars['cats']) foreach ($allVars['cats'] as $catel) {
				$cat = Model::factory('Template_has_category')->create();
				$cat->category_id = $catel;
				$cat->template_id = $template->id;
				$cat->account_id = $auth;
				unset($cat);
			}
			if ($allVars['hooks']) foreach ($allVars['hooks'] as $hookel) {
				$hook = Model::factory('Hook')->create();
				$hook->name = $hookel['name'];
				$hook->hook_type_id = $hookel['hook_type_id'];
				$hook->default_boolean_value = $hookel['default_boolean_value'];
				$hook->default_text_value = $hookel['default_text_value'];
				$hook->default_image_value_id = $hookel['default_image_value_id'];
				$hook->template_id = $template->id;
				$hook->set_expr('creation_date', 'NOW()');
				$hook->account_id = $auth;
				$hook->save();
				addLog('HOOK_ADDED', $auth, $hook->id);
				unset($hook);
			}

			$data['success'] = true;
			$data['message'] = 'Template added';
			addLog('TEMPLATE_ADDED', $auth, $template->id);
		}
		else {
			$errors['message'] = 'this template file doesn\'t exist';
			$data['success'] = false;
			$data['errors']  = $errors;
		}
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

/* variables:
template fields as normal
tags as array of tags
cats as array of categories
hooks as array of hooks - when edited with set id if added will be created
delhooks - hooks ids to be deleted*/
$app->post('/template/edit/(:id)','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::TEMPLATE_ADMIN);
	if (is_numeric($auth)) {
		try {
			$template = Model::factory('Template')->find_one($id);
		}
		catch (PDOException $e){
			$errors['message'] = 'No Template Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($template) {
			$allVars=$app->request()->post();
			if (!is_file($allVars['path'])) {
				$errors['message'] = 'this template file doesn\'t exist';
				$data['success'] = false;
				$data['errors']  = $errors;
				return json_encode($data);
			}
			$template->name = $allVars['name'];
			$template->path = $allVars['path'];
			$template->template_type_id = $allVars['template_type_id'];
			$template->set_expr('update_date', 'NOW()');
			$template->account_id = $auth;
			$template->save();

			if ($app->request()->post('tags')) {
				$tags = $template->tags()->find_many();
				foreach ($tags as $tel) {
					$id=$tel->id;
					if (!in_array($id,$allVars['tags'])) {
						$tel->delete();
					}
				}
				foreach ($allVars['tags'] as $tagel) {
					$tagdb = Model::factory('Template_has_tag')->where(array('tag_id' => $tagel, 'template_id' => $template->id))->find_one();
					if (!$tagdb) {
						$tag = Model::factory('Template_has_tag')->create();
						$tag->tag_id = $tagel;
						$tag->template_id = $template->id;
						$tag->set_expr('creation_date', 'NOW()');
						unset($tag);
					}
				}
			}
			if ($app->request()->post('cats')) {
				$cats = $template->cats()->find_many();
				foreach ($cats as $cel) {
					$id=$cel->id;
					if (!in_array($id,$allVars['cats'])) {
						$cel->delete();
					}
				}
				foreach ($allVars['cats'] as $catel) {
					$catdb = Model::factory('Template_has_category')->where(array('tag_id' => $catel, 'template_id' => $template->id))->find_one();
					if (!$catdb) {
						$cat = Model::factory('Template_has_tag')->create();
						$cat->tag_id = $catel;
						$cat->template_id = $template->id;
						$cat->set_expr('creation_date', 'NOW()');
						unset($cat);
					}
				}
			}
			if ($app->request()->post('hooks')) {
				foreach ($allVars['hooks'] as $hookel) {
					$hook = Model::factory('Hook')->find_one($hookel['id']);
					$code='HOOK_EDITED';
					if (!$hook) {
						$hook = Model::factory('Hook')->create();
						$code='HOOK_ADDED';
					}
					$hook->name = $hookel['name'];
					$hook->hook_type_id = $hookel['hook_type_id'];
					$hook->default_boolean_value = $hookel['default_boolean_value'];
					$hook->default_text_value = $hookel['default_text_value'];
					$hook->default_image_value_id = $hookel['default_image_value_id'];
					$hook->template_id = $template->id;
					$hook->set_expr('update_date', 'NOW()');
					$hook->account_id = $auth;
					$hook->save();
					addLog($code, $auth, $hook->id);
					unset($hook);
				}
			}
			if ($app->request()->post('delhooks')) {
				foreach ($allVars['delhooks'] as $hookel) {
					$hook = Model::factory('Hook')->find_one($hookel['id']);
					$hook->set_expr('delete_date', 'NOW()');
					$hook->save();
					addLog('HOOK_DELETED', $auth, $hook->id);
					unset($hook);
				}
			}

			$data['success'] = true;
			$data['message'] = 'Template added';
			addLog('TEMPLATE_ADDED', $auth, $template->id);
		}
		else {
			$errors['message'] = 'this template file doesn\'t exist';
			$data['success'] = false;
			$data['errors']  = $errors;
		}
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

$app->get('/template/get_pages/(:id)','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::TEMPLATE_ADMIN);
	if (is_numeric($auth)) {
		try {
			$template = Model::factory('Template')->find_one($id);
		}
		catch (PDOException $e){
			$errors['message'] = 'No Template Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			echo json_encode($data);
		}
		if ($template) {
			$pages = $template->pages()->find_many();
			if ($pages) {
				foreach ($pages as $page) {
					$data[]=$page->as_array();
				}
			}
		}
	}
});
