<?php
class ControllerModuleAutoBanner extends Controller {
	private $error = array();
	public $MODULENAME = 'autobanner';

	function _make_static_data() {
		$this->load->language("module/{$this->MODULENAME}");
		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['heading_title'] = $this->language->get('heading_title');

		/* Breadcrumbs */
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link("module/{$this->MODULENAME}", 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		/* Error messages */
		$error_msgs = array();
		if (isset($this->session->data['error'])) {
			$error_msgs[] = $this->session->data['error'];
		}
		if (isset($this->error['warning'])) {
			$error_msgs[] = $this->error['warning'];
		}
		$this->data['error_warning'] = join('<br />', $error_msgs);

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		}
		else {
			$this->data['success'] = '';
		}
		$this->session->data['error'] = '';
		$this->session->data['success'] = '';

		$this->children = array(
			'common/header',
			'common/footer'
		);
	}

	function _get_banner_info($banner_id) {
		$banner_info = $this->model_design_banner->getBanner($banner_id);

		$token = $this->session->data['token'];
		$banner_info['edit_link'] = $this->url->link(
			"module/{$this->MODULENAME}/update",
			"token=$token&banner_id=$banner_id", 'SSL');

		return $banner_info;
	}

	public function index() {
		$this->load->model('module/autobanner');
		$this->load->model('design/banner');

		$this->_make_static_data();

		/* Button */
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['insert'] = $this->url->link("module/{$this->MODULENAME}/insert", 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link("module/{$this->MODULENAME}/delete", 'token=' . $this->session->data['token'], 'SSL');

		/* Get banners which were created by this module */
		$own_banners = $this->model_module_autobanner->getBannerIds();
		$this->data['own_banners'] = array_map(array($this, '_get_banner_info'),
												 $own_banners);

		$this->template = "module/{$this->MODULENAME}.tpl";
		$this->response->setOutput($this->render());
	}

	function _make_form_data($banner_id=0) {
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
 		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');	

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_link'] = $this->language->get('entry_link');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_status'] = $this->language->get('entry_status');


 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}

		if ($banner_id) {
			$this->load->model('design/banner');
			$banner_info = $this->model_design_banner->getBanner($banner_id);
			$this->data['banner_id'] = $banner_id;
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (!empty($banner_info)) {
			$this->data['name'] = $banner_info['name'];
		} else {
			$this->data['name'] = '';
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($banner_info)) {
			$this->data['status'] = $banner_info['status'];
		} else {
			$this->data['status'] = true;
		}
	}

	function _show_form($banner_id=0) {
		/* Button */
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		/* Target for form */
		$token = $this->session->data['token'];
		if ($banner_id) {
			$this->data['action'] = $this->url->link(
				"module/{$this->MODULENAME}/update",
				"token=$token&banner_id=$banner_id", 'SSL');
		}
		else {
			$this->data['action'] = $this->url->link(
				"module/{$this->MODULENAME}/insert",
				"token=$token", 'SSL');
		}
		
		/* Cancel button's link target */
		$this->data['cancel'] = $this->url->link("module/{$this->MODULENAME}",
		                                         "token=$token", 'SSL');

		$this->_make_form_data($banner_id);
		
		$this->document->addScript('view/javascript/jquery/ui/jquery.dataTables.min.js');
		$this->document->addStyle('view/stylesheet/autobanner.css');

		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		if (!$banner_id) {
			$criteria = array('filter_status' => TRUE);
			$allproducts = $this->model_catalog_product->getProducts($criteria);
			$ownproducts = array();
		}
		else {
			$prd_ids = $this->model_module_autobanner->getProductIds($banner_id);
			$allproducts = $this->model_module_autobanner->getProductsByIds($prd_ids,
			                                                                FALSE);
			$ownproducts = $this->model_module_autobanner->getProductsByIds($prd_ids,
			                                                                TRUE);
		}

		$allproducts = array_map(array($this, '_reduce_product'), $allproducts);
		$ownproducts = array_map(array($this, '_reduce_product'), $ownproducts);
		$this->data['allproducts'] = $allproducts;
		$this->data['ownproducts'] = $ownproducts;

		$this->template = "module/{$this->MODULENAME}_form.tpl";
		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->model('module/autobanner');
		$this->_make_static_data();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->_process_submitted_data();
		}

		$this->_show_form();
	}

	public function update() {
		$this->load->model('module/autobanner');
		$this->_make_static_data();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->_process_submitted_data();
		}

		$id = (int)$this->request->get['banner_id'];
		$this->_show_form($id);
	}

	function _reduce_product($prd) {
		$img = $prd['image'];
		if ($img && file_exists(DIR_IMAGE . $img)) {
			$image = $this->model_tool_image->resize($img, 40, 40);
		}
		else {
			$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
		}
		return array(
			'product_id' => $prd['product_id'],
			'name'       => $prd['name'],
			'model'      => $prd['model'],
			'image'      => $image
		);
	}

	function _desc_in_langs($desc, $languages) {
		$d = array();
		foreach ($languages as $l) {
			$lid = $l['language_id'];
			$d[$lid] = array('title' => $desc);
		}
		return $d;
	}
	function _process_submitted_data() {
		if (!$this->_validate()) {
			return;
		}

		/* Data OK */
		$banner = array(
			'name' => $this->request->post['name'],
			'status' => $this->request->post['status']
		);
		if (isset($this->request->get['banner_id'])) {
			$banner_id = (int)$this->request->get['banner_id'];
		}
		$this->load->model('catalog/product');
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		$products = $this->request->post['selected'];
		$banner_image = array();
		foreach ($products as $id) {
			$product_info = $this->model_catalog_product->getProduct($id);
			$banner_image_description = $this->_desc_in_langs($product_info['name'],
															  $languages);
			$image = $product_info['image'];
			$cat = $this->model_catalog_product->getProductCategories($id)[0];
			$link = $this->url->link('product/product', "path=$cat&product_id=$id");
			$banner_image[] = compact('banner_image_description', 'image', 'link');
		}
		$banner['banner_image'] = $banner_image;

		/* Save to database */
		$this->load->model('design/banner');
		if (isset($banner_id)) {
			$this->model_design_banner->editBanner($banner_id, $banner);
			/* Update the list of banners created by this module */
			$this->model_module_autobanner->editBanner($banner_id, $products);
		}
		else {
			$banner_id = $this->model_design_banner->addBanner($banner);
			/* Update the list of banners created by this module */
			$this->model_module_autobanner->addBanner($banner_id, $products);
		}
		$this->session->data['success'] = $this->language->get('text_success');

		/* Redirect to parent page */
		$this->redirect($this->url->link('module/autobanner', 'token=' . $this->session->data['token'], 'SSL'));
	}

	function _validate() {
		$this->request->post['name'] = trim($this->request->post['name']);
		if (!$this->user->hasPermission('modify', 'design/banner')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function install() {
		$this->load->language('extension/module');
		
		/* Though permission checking has been done in extesion/module already,
		 * we still have to check again because this function is public,
		 * one may call it via URL. */
		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->session->data['error'] = $this->language->get('error_permission');
		}
		else {
			/*Permisson is OK */
			$this->load->model('module/autobanner');
			$this->model_module_autobanner->install();
		}
	}

	public function uninstall() {
		$this->load->language('extension/module');

		/* Though permission checking has been done in extesion/module already,
		 * we still have to check again because this function is public,
		 * one may call it via URL. */
		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->session->data['error'] = $this->language->get('error_permission');
		}
		else {
			/*Permisson is OK */
			$this->load->model('module/autobanner');
			$this->model_module_autobanner->uninstall();
		}
	}
}
?>