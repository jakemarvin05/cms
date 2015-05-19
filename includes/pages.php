<?php
/**
 * Created by PhpStorm.
 * User: dusia
 * Date: 08.05.15
 * Time: 14:10
 */

// Views

// Logic

/*
 * Params:
 * search - search term
 * search_field - field in db to search in
 * group - group_id
 * deleteed - true for getting deleted elements
 * order - the field name in db (table: accounts)
 * desc - true for desc order
 * limit
 * offset
 * */
$app->get('/pages/','authenticate', function() use ($app) {
//$app->get('/pages/', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::PAGE_ADMIN);
	//$auth=1;
	if (is_numeric($auth)) {
		try {
			$query = Model::factory('Page');
			$query->join('page_has_template', array('page_has_template.page_id', '=', 'page.id'));
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
					$query->where_like($allVars['search_field'],'%'.$allVars['search'].'%');
				}
				else {
					$query->where_any_is(array(
						array('page.name' => '%'.$allVars['search'].'%'),
						array('page.uri' => '%'.$allVars['search'].'%')
					), 'LIKE');
					$cats=Model::factory('Cat')->where_like('name','%'.$allVars['search'].'%')->find_many();
					if ($cats) {
						foreach ($cats as $cat) {
							$catIds[] = $cat->id;
						}
						if (isset($catIds)) {
							$query->where_in('category_id',$catIds);
							$query->join('template_has_category', array('template_has_category.template_id', '=', 'page_has_template.template_id'));
						}
					}
					$tags=Model::factory('Tag')->where_like('name','%'.$allVars['search'].'%')->find_many();
					if ($tags) {
						foreach ($tags as $tag) {
							$tagIds[] = $tag->id;
						}
						if (isset($tagIds)) {
							$query->where_in('tag_id',$tagIds);
							$query->join('template_has_tag', array('template_has_tag.template_id', '=', 'page_has_template.template_id'));
						}
					}
				}
			}
			if (isset($allVars['group'])) {
				$query->where('group_id',$allVars['group']);
			}
			if (isset($allVars['deleted'])) {
				$query->where_not_null('page.delete_date');
			}
			else {
				$query->where_null('page.delete_date');
			}
			$pages = $query->find_many();
			var_dump(ORM::get_last_query());
		}
		catch (PDOException $e){
			$errors['message'] = 'No Pages Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			return json_encode($data);
		}
		if ($pages) {
			$data['success'] = true;
			foreach ($pages as $page)
				$data['pages'][] = $page->as_array();
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

/*$app->get('/page/(:id)', function($id) use ($app) {
	$auth = isAuthorised('dusia', RoleName::CATEGORY_ADMIN);
	var_dump($auth);
});*/


$app->get('/page/(:id)','authenticate', function($id) use ($app) {
//$app->get('/page/(:id)', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::PAGE_ADMIN);
	//$auth=1;
	if (is_numeric($auth)) {
		try {
			$page = Model::factory('Page')->find_one($id);
		}
		catch (PDOException $e){
			$errors['message'] = 'No Page Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			return json_encode($data);
		}
		if ($page) {
			$data['success'] = true;
			$data['page'] = $page->as_array();
			$data['meta'] = $page->meta()->find_one();
			$templates = $page->templates()->order_by_asc('page_has_template.position')->find_many();
			foreach ($templates as &$template) {
				$temp =  Model::factory('Template')->find_one($template->template_id);
				$hooks = $temp->hooks()->find_many();
				$template = $template->as_array();
				$template['template'] = $temp->as_array();
				foreach ($hooks as &$hook) {
					$hook = $hook->as_array();
					$hook_val = Model::factory('Hook_value')->where('page_id',$page->id)->where('hook_id',$hook['id'])->find_one();
					if ($hook_val) $hook['value'] = $hook_val;
					$template['hooks'][] = $hook;
					unset($hook_val);
				}
			}
			$data['templates'] = $templates;
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->get('/duplicatePage/(:id)','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::PAGE_ADMIN);
	if (is_numeric($auth)) {
		try {
			$page = Model::factory('Page')->find_one($id);
		}
		catch (PDOException $e){
			$errors['message'] = 'No Page Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			return json_encode($data);
		}
		if ($page) {
			$new_page = Model::factory('Page')->create();
			$new_page = $page;
			$new_page->set_expr('id','NULL');
			$new_page->set_expr('creation_date','NOW()');
			$new_page->save();
			$new_page_meta = Model::factory('Page_meta')->create();
			$page_meta = $page->meta()->find_one();
			$new_page_meta = $page_meta;
			$new_page_meta->page_id = $new_page->id;
			$new_page_meta->set_expr('id','NULL');
			$new_page_meta->save();

			$page_templates = $page->templates()->find_many();
			foreach ($page_templates as $template) {
				$new_page_has_template = Model::factory('Page_has_template')->create();
				$new_page_has_template = $template;
				$new_page_has_template->page_id = $new_page->id;
				$new_page_has_template->set_expr('id','NULL');
				$new_page_has_template->set_expr('creation_date','NOW()');
				$new_page_has_template->save();
				unset($new_page_has_template);
			}
			$page_hook_values = $page->hook_values()->find_many();
			foreach ($page_hook_values as $hook_value) {
				$new_page_hook_value = Model::factory('Hook_value')->create();
				$new_page_hook_value = $hook_value;
				$new_page_hook_value->page_id = $new_page->id;
				$new_page_hook_value->set_expr('id','NULL');
				$new_page_hook_value->set_expr('creation_date','NOW()');
				$new_page_hook_value->save();
				unset($new_page_hook_value);
			}
			$data['success'] = true;
			$data['id'] = $new_page->id();
			addLog('PAGE_DUPLICATED', $auth, $new_page->id);
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->post('/addPage','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'), RoleName::PAGE_ADMIN);
	if (is_numeric($auth)) {
		$allVars = $app->request()->post();
		$page = Model::factory('Page')->create();
		$page->name = $allVars['name'];
		$page->uri = $allVars['uri'];
		$page->group_id = $allVars['group_id'];
		if (isset($allVars['scripts'])) $page->scripts = $allVars['scripts'];
		$page->account_id = $auth;
		$page->set_expr('creation_date','NOW()');
		$page->save();
		$data['success'] = true;
		$data['id'] = $page->id;
		addLog('PAGE_ADDED', $auth, $page->id);
		echo json_encode($data);
	}
	else echo json_encode($auth);
});


$app->post('/editPage/:id','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),'PAGE_ADMIN');
	if (is_numeric($auth)) {
		$allVars = $app->request()->post();
		$page = Model::factory('Page')->find_one($id);
		$page->name = $allVars['name'];
		$page->uri = $allVars['uri'];
		$page->group_id = $allVars['group_id'];
		if (isset($allVars['scripts'])) $page->scripts = $allVars['scripts'];
		$page->set_expr('update_date','NOW()');
		$page->save();
		$data['success'] = true;
		$data['id'] = $page->id;
		addLog('PAGE_EDITED', $auth, $page->id);
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

$app->post('/pageMeta/:id','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),'PAGE_ADMIN');
	if (is_numeric($auth)) {
		$allVars = $app->request()->post();
		$page_meta = Model::factory('Page_meta')->where('page_id',$id)->find_one();
		$code='PAGE_META_EDITED';
		if (!$page_meta) {
			$page_meta = Model::factory('Page_meta')->create();
			$page_meta->page_id = $id;
			$code='PAGE_META_ADDED';
		}
		if (isset($allVars['title'])) $page_meta->title = $allVars['title'];
		if (isset($allVars['meta_author'])) $page_meta->meta_author = $allVars['meta_author'];
		if (isset($allVars['meta_description'])) $page_meta->meta_description = $allVars['meta_description'];
		if (isset($allVars['meta_keywords'])) $page_meta->meta_keywords = $allVars['meta_keywords'];
		if (isset($allVars['og_type'])) $page_meta->og_type = $allVars['og_type'];
		if (isset($allVars['og_url'])) $page_meta->og_url = $allVars['og_url'];
		if (isset($allVars['og_image_path'])) $page_meta->og_image_path = $allVars['og_image_path'];
		if (isset($allVars['favicon_id'])) $page_meta->favicon_id = $allVars['favicon_id'];
		$page_meta->save();
		$data['success'] = true;
		$data['id'] = $page_meta->id;
		addLog($code, $auth, $page_meta->id);
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

$app->post('/addPageTemplate','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),'PAGE_ADMIN');
	if (is_numeric($auth)) {
		$allVars = $app->request()->post();
		$page_tpl = Model::factory('Page_has_template')->create();
		$page_tpl->page_id = $allVars['page_id'];
		$page_tpl->template_id = $allVars['template_id'];
		$page_tpl->position = $allVars['position'];
		$page_tpl->set_expr('creation_date','NOW()');
		$page_tpl->account_id = $auth;
		$page_tpl->save();
		$data['success'] = true;
		$data['id'] = $page_tpl->id;
		addLog('PAGE_TEMPLATE_ADDED', $auth, $page_tpl->id);
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

$app->post('/editPageTemplate/:id','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),'PAGE_ADMIN');
	if (is_numeric($auth)) {
		$allVars = $app->request()->post();
		$page_tpl = Model::factory('Page_has_template')->find_one($id);
		$page_tpl->page_id = $allVars['page_id'];
		$page_tpl->template_id = $allVars['template_id'];
		$page_tpl->position = $allVars['position'];
		$page_tpl->set_expr('update_date','NOW()');
		$page_tpl->account_id = $auth;
		$page_tpl->save();
		$data['success'] = true;
		$data['id'] = $page_tpl->id;
		addLog('PAGE_TEMPLATE_EDITED', $auth, $page_tpl->id);
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

$app->post('/addHookValue','authenticate', function() use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),'PAGE_ADMIN');
	if (is_numeric($auth)) {
		$allVars = $app->request()->post();
		$hook_val = Model::factory('Hook_value')->create();
		$hook_val->page_id = $allVars['page_id'];
		$hook_val->hook_id = $allVars['hook_id'];
		$hook_val->boolean_value = $allVars['boolean_value'];
		$hook_val->text_value = $allVars['text_value'];
		$hook_val->image_value_id = $allVars['image_value_id'];
		$hook_val->set_expr('creation_date','NOW()');
		$hook_val->account_id = $auth;
		$hook_val->save();
		$data['success'] = true;
		$data['id'] = $hook_val->id;
		addLog('HOOK_VALUE_ADDED', $auth, $hook_val->id);
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

$app->post('/editHookValue/:id','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),'PAGE_ADMIN');
	if (is_numeric($auth)) {
		$allVars = $app->request()->post();
		$hook_val = Model::factory('Hook_value')->find_one($id);
		$hook_val->boolean_value = $allVars['boolean_value'];
		$hook_val->text_value = $allVars['text_value'];
		$hook_val->image_value_id = $allVars['image_value_id'];
		$hook_val->set_expr('update_date','NOW()');
		$hook_val->account_id = $auth;
		$hook_val->save();
		$data['success'] = true;
		$data['id'] = $hook_val->id;
		addLog('HOOK_VALUE_EDITED', $auth, $hook_val->id);
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

$app->delete('/delHookValue/:id','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),'PAGE_ADMIN');
	if (is_numeric($auth)) {
		$allVars = $app->request()->post();
		$hook_val = Model::factory('Hook_value')->find_one($id);
		$hook_val->set_expr('delete_date','NOW()');
		$hook_val->save();
		$data['success'] = true;
		addLog('HOOK_VALUE_DELETED', $auth, $hook_val->id);
		echo json_encode($data);
	}
	else echo json_encode($auth);
});

$app->get('/pageCats/(:id)','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),'PAGE_ADMIN');
	if (is_numeric($auth)) {
		try {
			$query=Model::factory('Category');
			$query->select_expr('category.*');
			$query->where('page_has_template.page_id',$id);
			$query->join('template_has_category', 'template_has_category.category_id = category.id');
			$query->join('page_has_template', 'page_has_template.template_id = template_has_category.template_id');
			$cats=$query->find_many();
			//var_dump(ORM::get_last_query());
		}
		catch (PDOException $e){
			$errors['message'] = 'No Categories Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			return json_encode($data);
		}
		if ($cats) {
			$data['success'] = true;
			foreach ($cats as $cat) {
				$data['cats'][] = $cat->as_array();
			}
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->get('/pageTags/(:id)','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),'PAGE_ADMIN');
	if (is_numeric($auth)) {
		try {
			$query=Model::factory('Tag');
			$query->select_expr('tag.*');
			$query->where('page_has_template.page_id',$id);
			$query->join('template_has_tag', 'template_has_tag.tag_id = tag.id');
			$query->join('page_has_template', 'page_has_template.template_id = template_has_tag.template_id');
			$tags=$query->find_many();
			//var_dump(ORM::get_last_query());
		}
		catch (PDOException $e){
			$errors['message'] = 'No Categories Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			return json_encode($data);
		}
		if ($tags) {
			$data['success'] = true;
			foreach ($tags as $tag) {
				$data['tags'][] = $tag->as_array();
			}
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->get('/groupMeta/(:id)','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),'PAGE_ADMIN');
	if (is_numeric($auth)) {
		try {
			$query=Model::factory('Page');
			$query->select_expr('group_meta.*');
			$query->where('page.id',$id);
			$query->join('group', 'group.id = page.group_id');
			$query->join('group_meta', 'group_meta.group_id = group.id');
			$meta=$query->find_many();
			//var_dump(ORM::get_last_query());
		}
		catch (PDOException $e){
			$errors['message'] = 'No Categories Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			return json_encode($data);
		}
		if ($meta) {
			$data['success'] = true;
			foreach ($meta as $metael) {
				$data['meta'][] = $metael->as_array();
			}
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->get('/pageGroup/(:id)','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),'PAGE_ADMIN');
	if (is_numeric($auth)) {
		try {
			$query=Model::factory('Page');
			$query->select('group.*');
			$query->where('page.id',$id);
			$query->join('group', 'group.id = page.group_id');
			$group=$query->find_one();
			//var_dump(ORM::get_last_query());
		}
		catch (PDOException $e){
			$errors['message'] = 'No Categories Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			return json_encode($data);
		}
		if ($group) {
			$data['success'] = true;
			$data['group'] = $group->as_array();
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});

$app->delete('/page/(:id)','authenticate', function($id) use ($app) {
	$auth = isAuthorised($app->getEncryptedCookie('user'),'PAGE_ADMIN');
	if (is_numeric($auth)) {
		try {
			$page = Model::factory('Page')->find_one($id);
		}
		catch (PDOException $e){
			$errors['message'] = 'No Page Found: ' . $e->getMessage();
			$data['success'] = false;
			$data['errors']  = $errors;
			return json_encode($data);
		}
		if ($page) {
			$page->set_expr('delete_date','NOW()');
			$page->save();
			$data['success'] = true;
			$data['message'] = 'Page deleted';
			addLog('PAGE_DELETED', $auth, $page->id);
			echo json_encode($data);
		}
	}
	else echo json_encode($auth);
});