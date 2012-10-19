<?php
class ControllerModuleImgViewFeaturedImageZoomer extends Controller {
    private $error = array();
    public function index() {
        $this->load->language('module/imgview_featuredimagezoomer');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->template = 'module/imgview_featuredimagezoomer.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
        $this->response->setOutput($this->render());
    }
}
?>
