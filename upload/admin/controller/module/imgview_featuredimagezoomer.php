<?php
class ControllerModuleImgViewFeaturedImageZoomer extends Controller {
	private $error = array();
	public $MODULENAME = 'imgview_featuredimagezoomer';

	/* Default function to show setting page content */
	public function index() {
		$this->load->language("module/{$this->MODULENAME}");
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');

		$this->data['heading_title'] = $this->language->get('heading_title');

		/* Save posted data to database */
		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['fiz'])) {
			// Check and clean submited data
			$cleaned = $this->validate();
			if (is_array($cleaned)) {
				// We want to save serialized data to 1 row
				$data = array('fiz_config' => $cleaned);
				// Store to database
				$this->model_setting_setting->editSetting('featuredimagezoomer', $data);
				$this->session->data['success'] = $this->language->get('text_success');
				$this->redirect($this->url->link('extension/module',
												 'token=' . $this->session->data['token'],
												 'SSL'));
			}
		}

		$this->data['entry_zoomrange'] = $this->language->get('entry_zoomrange');
		$this->data['entry_magnifiersize'] = $this->language->get('entry_magnifiersize');
		$this->data['entry_curshade'] = $this->language->get('entry_curshade');
		$this->data['entry_cursorshadecolor'] =
			$this->language->get('entry_cursorshadecolor');
		$this->data['entry_cursorshadeopacity'] =
			$this->language->get('entry_cursorshadeopacity');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		/* Error messages */
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['zoomrange'])) {
			$this->data['error_zoomrange'] = $this->error['zoomrange'];
		} else {
			$this->data['error_zoomrange'] = '';
		}

		if (isset($this->error['magnifiersize'])) {
			$this->data['error_magnifiersize'] = $this->error['magnifiersize'];
		} else {
			$this->data['error_magnifiersize'] = '';
		}

		if (isset($this->error['curshadecolor'])) {
			$this->data['error_curshadecolor'] = $this->error['curshadecolor'];
		} else {
			$this->data['error_curshadecolor'] = '';
		}
		
		if (isset($this->error['curshadeopacity'])) {
			$this->data['error_curshadeopacity'] = $this->error['curshadeopacity'];
		} else {
			$this->data['error_curshadeopacity'] = '';
		}

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

		/* Target for form */
		$this->data['action'] = $this->url->link("module/{$this->MODULENAME}", 'token=' . $this->session->data['token'], 'SSL');

		/* Cancel button's link target */
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		/* Form's input data */
		if (isset($this->request->post['fiz'])) {
			// Get from REQUEST data...
			extract($this->request->post['fiz']);
		}
		else {
			// ... or from database
			$d = $this->model_setting_setting->getSetting('featuredimagezoomer');
			if (isset($d['fiz_config']) && is_array($d['fiz_config'])) {
				extract($d['fiz_config']);
			}
		}

		if (isset($range_low)) {
			$this->data['range_low'] = $range_low;
		}
		if (isset($range_high)) {
			$this->data['range_high'] = $range_high;
		}

		if (isset($size_low)) {
			$this->data['size_low'] = $size_low;
		}
		if (isset($size_high)) {
			$this->data['size_high'] = $size_high;
		}
		if (isset($curshade)) {
			$this->data['curshade'] = $curshade;
		}
		if (isset($curshadecolor)) {
			$this->data['curshadecolor'] = $curshadecolor;
		}
		if (isset($curshadeopacity)) {
			$this->data['curshadeopacity'] = $curshadeopacity;
		}

		$this->template = "module/{$this->MODULENAME}.tpl";
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	/* Check the submitted data. Return cleaned data if check is OK */
	private function validate() {
		if (!$this->user->hasPermission('modify', "module/{$this->MODULENAME}")) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		/* Extract */
		extract($this->request->post['fiz']);
		// To know what variables are, see template file (the form and inputs).

		if (!$this->is_zoomrange_ok($range_low, $range_high)) {
			$this->error['zoomrange'] = $this->language->get('error_zoomrange');
		}

		$size_low = intval($size_low);
		$size_high = intval($size_high);
		if ($size_low < 50 || $size_high < 50 || $size_low > 900 || $size_high > 900) {
			$this->error['magnifiersize'] = $this->language->get('error_magnifiersize');
		}

		if (!(preg_match('/^#[a-f0-9]{6}$/i', $curshadecolor) ||
			  preg_match('/^#[a-f0-9]{3}$/i', $curshadecolor))) {
			$this->error['curshadecolor'] = $this->language->get('error_curshadecolor');
		}

		$curshadeopacity = floatval($curshadeopacity);
		if ($curshadeopacity < 0.1 || $curshadeopacity > 1) {
			$this->error['curshadeopacity'] = $this->language->get('error_curshadeopacity');
		}

		if (!$this->error) {
			return compact('range_low', 'range_high', 'size_low', 'size_high',
						 'curshade', 'curshadecolor', 'curshadeopacity');
		} else {
			return FALSE;
		}
	}

	private function is_zoomrange_ok($low, $high) {
		if ($low == '' && $high == '') {
			return TRUE;
		}
		$low = intval($low);
		$high = intval($high);
		if ($low > 10 || $high > 10 || $low < 1 || $high < 1) {
			return FALSE;
		}
		return TRUE;
	}
}
?>
