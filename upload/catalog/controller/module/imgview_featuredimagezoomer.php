<?php
class ControllerModuleImgViewFeaturedImageZoomer extends Controller {
	protected function index($setting) {
		static $module = 0;

		$cssfile = 'catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/featuredimagezoomer.css';

		$this->document->addScript('catalog/view/javascript/jquery/featuredimagezoomer/featuredimagezoomer.js');

		if (!file_exists($cssfile)) {
			$cssfile = 'catalog/view/theme/default/stylesheet/featuredimagezoomer.css';
		}
		$this->document->addStyle($cssfile);

		// Load data from database
		$this->load->model('setting/setting');
		$d = $this->model_setting_setting->getSetting('featuredimagezoomer');
		if (isset($d['fiz_config']) && is_array($d['fiz_config'])) {
			$this->data = array_merge($this->data, $d['fiz_config']);
		}

		if (isset($this->data['range_low']) && $this->data['range_low'] == '') {
			unset($this->data['range_low']);
			unset($this->data['range_high']);
		}

		$this->template = $this->config->get('config_template') . '/template/module/imgview_featuredimagezoomer.tpl';
		if (!file_exists(DIR_TEMPLATE . $this->template)) {
			$this->template = 'default/template/module/imgview_featuredimagezoomer.tpl';
		}

		$this->render();
	}
}
?>
